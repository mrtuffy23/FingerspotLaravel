<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Models\PointTransaction;
use App\Services\PointTransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PointController extends Controller
{
    private $pointService;

    public function __construct(PointTransactionService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * Dashboard poin - overview semua karyawan
     */
    public function dashboard()
    {
        $employees = Employee::where('status', 'aktif')
            ->orderBy('current_points', 'asc')
            ->with('department', 'position')
            ->paginate(15);

        $lowestPointEmployees = $this->pointService->getLowestPointEmployees(10);

        $statistics = [
            'total_active_employees' => Employee::where('status', 'aktif')->count(),
            'average_points' => Employee::where('status', 'aktif')->avg('current_points'),
            'below_50_points' => Employee::where('status', 'aktif')->where('current_points', '<', 50)->count(),
            'critical_below_20' => Employee::where('status', 'aktif')->where('current_points', '<', 20)->count(),
        ];

        return view('points.dashboard', [
            'employees' => $employees,
            'lowestPointEmployees' => $lowestPointEmployees,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Detail poin satu karyawan
     */
    public function employeePointDetail($employeeId)
    {
        $employee = Employee::with('department', 'position', 'currentPayrollPeriod')->findOrFail($employeeId);
        $transactions = $this->pointService->getPointHistory($employee, 100);
        $summary = $this->pointService->getPointSummary($employee, $employee->currentPayrollPeriod);

        return view('points.employee-detail', [
            'employee' => $employee,
            'transactions' => $transactions,
            'summary' => $summary,
        ]);
    }

    /**
     * Riwayat transaksi poin (untuk semua karyawan atau filter)
     */
    public function history(Request $request)
    {
        $query = PointTransaction::query();

        // Filter by employee
        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by reason
        if ($request->has('reason') && $request->reason) {
            $query->where('reason', 'like', '%' . $request->reason . '%');
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        $transactions = $query->with('employee.department', 'employee.position')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $employees = Employee::where('status', 'aktif')->orderBy('name')->get();

        return view('points.history', [
            'transactions' => $transactions,
            'employees' => $employees,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Manual adjustment poin (admin only)
     */
    public function adjustmentForm()
    {
        $employees = Employee::where('status', 'aktif')->orderBy('name')->get();
        return view('points.adjustment-form', ['employees' => $employees]);
    }

    /**
     * Submit manual adjustment
     */
    public function submitAdjustment(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'point_delta' => 'required|integer|not_in:0',
            'reason' => 'required|string|max:255',
        ]);

        try {
            $employee = Employee::findOrFail($validated['employee_id']);
            $transaction = $this->pointService->adjustPoints(
                employee: $employee,
                pointDelta: $validated['point_delta'],
                reason: $validated['reason'],
                adminId: auth()->id()
            );

            return redirect()->route('points.dashboard')
                ->with('success', "Poin berhasil diubah. ID Transaksi: {$transaction->id}");
        } catch (\Exception $e) {
            Log::error("Failed to adjust points", ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengubah poin: ' . $e->getMessage());
        }
    }

    /**
     * Initialize points untuk payroll period baru
     */
    public function initializePeriod(Request $request)
    {
        // Verify admin role
        if (!auth()->user()?->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'payroll_period_id' => 'required|exists:payroll_periods,id',
        ]);

        try {
            $payrollPeriod = PayrollPeriod::findOrFail($validated['payroll_period_id']);
            $result = $this->pointService->initializePointsForPeriod($payrollPeriod);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'count' => $result['count'],
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to initialize points", ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Export poin report
     */
    public function exportReport(Request $request)
    {
        $employees = Employee::where('status', 'aktif')
            ->with('department', 'position')
            ->orderBy('name')
            ->get();

        $fileName = 'point-report-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($employees) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['PIN', 'Nama', 'Departemen', 'Posisi', 'Poin Saat Ini', 'Status']);

            // Data
            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->pin,
                    $employee->name,
                    $employee->department?->name ?? '-',
                    $employee->position?->name ?? '-',
                    $employee->current_points,
                    $employee->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * API endpoint untuk get poin karyawan
     */
    public function getEmployeePoints($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        return response()->json([
            'employee_id' => $employee->id,
            'name' => $employee->name,
            'current_points' => $employee->current_points,
            'initial_points' => $employee->initial_points,
            'payroll_period_id' => $employee->current_payroll_period_id,
        ]);
    }

    /**
     * API endpoint untuk get riwayat transaksi
     */
    public function getPointTransactions($employeeId, Request $request)
    {
        $limit = $request->get('limit', 50);
        $employee = Employee::findOrFail($employeeId);
        $transactions = $this->pointService->getPointHistory($employee, $limit);

        return response()->json($transactions);
    }
}
