<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\PointTransaction;
use App\Models\PayrollPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointTransactionService
{
    /**
     * Hitung poin berdasarkan status absensi
     */
    public function getPointsByStatus($status): int
    {
        $rules = config('discipline.rules');
        return $rules[$status]['point'] ?? 0;
    }

    /**
     * Record point transaction dan update saldo karyawan
     */
    public function recordTransaction(Employee $employee, $pointDelta, $reason, $sourceId = null, $date = null, $payrollPeriodId = null)
    {
        try {
            return DB::transaction(function () use ($employee, $pointDelta, $reason, $sourceId, $date, $payrollPeriodId) {
                // Gunakan tanggal saat ini jika tidak ada
                $date = $date ?? now()->format('Y-m-d');

                // Tentukan payroll period jika tidak ada
                if (!$payrollPeriodId) {
                    $payrollPeriod = PayrollPeriod::where('start_date', '<=', $date)
                        ->where('end_date', '>=', $date)
                        ->first();
                    $payrollPeriodId = $payrollPeriod?->id;
                }

                // Record transaction
                $transaction = PointTransaction::create([
                    'employee_id' => $employee->id,
                    'payroll_period_id' => $payrollPeriodId,
                    'date' => $date,
                    'delta' => $pointDelta,
                    'reason' => $reason,
                    'source_id' => $sourceId,
                ]);

                // Update current points karyawan
                $employee->increment('current_points', $pointDelta);

                Log::info("Point transaction recorded", [
                    'employee_id' => $employee->id,
                    'delta' => $pointDelta,
                    'reason' => $reason,
                    'new_balance' => $employee->fresh()->current_points
                ]);

                return $transaction;
            });
        } catch (\Exception $e) {
            Log::error("Failed to record point transaction", [
                'employee_id' => $employee->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Process points untuk attendance record
     */
    public function processAttendancePoints($attendance)
    {
        \Illuminate\Support\Facades\Log::info("processAttendancePoints called", [
            'attendance_id' => $attendance->id,
            'employee_id' => $attendance->employee_id,
            'status' => $attendance->status,
        ]);

        $employee = $attendance->employee;
        $status = $attendance->status;
        $pointDelta = $this->getPointsByStatus($status);

        \Illuminate\Support\Facades\Log::info("Point delta calculated", [
            'status' => $status,
            'pointDelta' => $pointDelta,
        ]);

        if ($pointDelta == 0) {
            \Illuminate\Support\Facades\Log::info("Point delta is 0, skipping");
            return null; // Tidak ada perubahan poin
        }

        // Check if already recorded
        $existingTransaction = PointTransaction::where('employee_id', $employee->id)
            ->where('source_id', $attendance->id)
            ->where('reason', 'attendance_' . $status)
            ->first();

        if ($existingTransaction) {
            \Illuminate\Support\Facades\Log::info("Transaction already exists");
            return $existingTransaction; // Sudah tercatat
        }

        // Record transaction
        \Illuminate\Support\Facades\Log::info("Recording new transaction", [
            'employee_id' => $employee->id,
            'pointDelta' => $pointDelta,
            'date' => $attendance->date,
        ]);

        return $this->recordTransaction(
            employee: $employee,
            pointDelta: $pointDelta,
            reason: 'attendance_' . $status,
            sourceId: $attendance->id,
            date: $attendance->date,
            payrollPeriodId: null
        );
    }

    /**
     * Inisialisasi poin karyawan di awal periode payroll
     */
    public function initializePointsForPeriod(PayrollPeriod $payrollPeriod)
    {
        $initialPoints = config('discipline.initial_points', 100);
        $employees = Employee::where('status', 'aktif')->get();
        $initialized = 0;

        foreach ($employees as $employee) {
            try {
                DB::transaction(function () use ($employee, $payrollPeriod, $initialPoints) {
                    // Reset points ke initial
                    $employee->update([
                        'current_points' => $initialPoints,
                        'initial_points' => $initialPoints,
                        'current_payroll_period_id' => $payrollPeriod->id,
                    ]);

                    Log::info("Points initialized for payroll period", [
                        'employee_id' => $employee->id,
                        'payroll_period_id' => $payrollPeriod->id,
                        'points' => $initialPoints
                    ]);
                });
                $initialized++;
            } catch (\Exception $e) {
                Log::error("Failed to initialize points for employee", [
                    'employee_id' => $employee->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'success' => true,
            'message' => "Initialized points for {$initialized} employees",
            'count' => $initialized
        ];
    }

    /**
     * Dapatkan saldo poin karyawan
     */
    public function getEmployeePointBalance(Employee $employee): int
    {
        return $employee->current_points;
    }

    /**
     * Dapatkan riwayat transaksi poin
     */
    public function getPointHistory(Employee $employee, $limit = 50)
    {
        return PointTransaction::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Dapatkan summary poin dalam periode
     */
    public function getPointSummary(Employee $employee, PayrollPeriod $payrollPeriod = null)
    {
        $query = PointTransaction::where('employee_id', $employee->id);

        if ($payrollPeriod) {
            $query->where('payroll_period_id', $payrollPeriod->id);
        }

        $transactions = $query->get();

        return [
            'current_balance' => $employee->current_points,
            'total_transactions' => $transactions->count(),
            'total_deductions' => $transactions->where('delta', '<', 0)->sum('delta'),
            'total_additions' => $transactions->where('delta', '>', 0)->sum('delta'),
            'by_reason' => $transactions->groupBy('reason')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('delta'),
                    'average' => round($group->sum('delta') / $group->count(), 2),
                ];
            }),
        ];
    }

    /**
     * Manual adjustment poin (untuk kasus khusus)
     */
    public function adjustPoints(Employee $employee, $pointDelta, $reason, $adminId = null)
    {
        if ($pointDelta == 0) {
            throw new \InvalidArgumentException("Point delta must not be zero");
        }

        return $this->recordTransaction(
            employee: $employee,
            pointDelta: $pointDelta,
            reason: 'manual_adjustment: ' . $reason,
            sourceId: $adminId,
            date: now()->format('Y-m-d'),
            payrollPeriodId: null
        );
    }

    /**
     * Dapatkan karyawan dengan poin terendah
     */
    public function getLowestPointEmployees($limit = 10)
    {
        return Employee::where('status', 'aktif')
            ->orderBy('current_points', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Validate points tidak go below minimum
     */
    public function validateMinimumPoints(Employee $employee, $minimumPoints = 0): bool
    {
        return $employee->current_points >= $minimumPoints;
    }
}
