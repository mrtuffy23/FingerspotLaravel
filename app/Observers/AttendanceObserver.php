<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\Log;

class AttendanceObserver
{
    /**
     * Handle the Attendance "created" event.
     */
    public function created(Attendance $attendance): void
    {
        Log::info("AttendanceObserver.created triggered", [
            'attendance_id' => $attendance->id,
            'status' => $attendance->status,
            'point_delta' => $attendance->point_delta
        ]);
        $this->processPointTransaction($attendance, 'create');
    }

    /**
     * Handle the Attendance "updated" event.
     */
    public function updated(Attendance $attendance): void
    {
        // Process if status, point_delta, or relevant fields changed
        if ($attendance->wasChanged(['status', 'point_delta', 'employee_id', 'date'])) {
            Log::info("AttendanceObserver.updated triggered", [
                'attendance_id' => $attendance->id,
                'changed' => $attendance->getChanges(),
            ]);
            $this->processPointTransaction($attendance, 'update');
        }
    }

    /**
     * Handle the Attendance "restored" event.
     */
    public function restored(Attendance $attendance): void
    {
        //
    }

    /**
     * Handle the Attendance "force deleted" event.
     */
    public function forceDeleted(Attendance $attendance): void
    {
        // Reverse point transaction if it exists
        $this->reversePointTransaction($attendance);
    }

    /**
     * Process point transaction for attendance
     */
    private function processPointTransaction(Attendance $attendance, $action)
    {
        try {
            // Skip if no status or status is not set
            if (!$attendance->status) {
                return;
            }

            Log::info("Processing point transaction", [
                'action' => $action,
                'attendance_id' => $attendance->id,
                'employee_id' => $attendance->employee_id,
                'status' => $attendance->status,
                'point_delta' => $attendance->point_delta,
            ]);

            // For update action, always remove old transaction first
            if ($action === 'update') {
                $oldTransactions = PointTransaction::where('employee_id', $attendance->employee_id)
                    ->where('source_id', $attendance->id)
                    ->get();

                Log::info("Found old transactions", ['count' => count($oldTransactions)]);

                foreach ($oldTransactions as $oldTx) {
                    // Reverse the point
                    $reverseDelta = -$oldTx->delta;
                    $attendance->employee->increment('current_points', $reverseDelta);
                    
                    Log::info("Reversed old point transaction", [
                        'transaction_id' => $oldTx->id,
                        'reversed_delta' => $reverseDelta,
                    ]);
                    
                    // Delete old transaction
                    $oldTx->delete();
                }
            }

            // Get point delta from attendance record
            $pointDelta = $attendance->point_delta ?? 0;

            Log::info("Point delta to process", [
                'pointDelta' => $pointDelta,
            ]);

            if ($pointDelta == 0) {
                Log::info("Point delta is 0, skipping new transaction creation");
                return; // No point change
            }

            // Create new point transaction
            $transaction = PointTransaction::create([
                'employee_id' => $attendance->employee_id,
                'date' => $attendance->date,
                'delta' => $pointDelta,
                'reason' => 'attendance_' . $attendance->status,
                'source_id' => $attendance->id,
            ]);

            // Update employee's current points
            $attendance->employee->increment('current_points', $pointDelta);

            Log::info("Point transaction created", [
                'attendance_id' => $attendance->id,
                'employee_id' => $attendance->employee_id,
                'status' => $attendance->status,
                'delta' => $pointDelta,
                'new_balance' => $attendance->employee->fresh()->current_points,
                'action' => $action,
                'transaction_id' => $transaction->id,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to process point transaction for attendance", [
                'attendance_id' => $attendance->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Reverse point transaction when attendance is deleted
     */
    private function reversePointTransaction(Attendance $attendance)
    {
        try {
            $transaction = PointTransaction::where('employee_id', $attendance->employee_id)
                ->where('source_id', $attendance->id)
                ->where('reason', 'attendance_' . $attendance->status)
                ->first();

            if ($transaction) {
                // Reverse the point (negate it)
                $reverseDelta = -$transaction->delta;
                
                $attendance->employee->increment('current_points', $reverseDelta);
                
                // Delete transaction record
                $transaction->delete();

                Log::info("Point transaction reversed for deleted attendance", [
                    'attendance_id' => $attendance->id,
                    'employee_id' => $attendance->employee_id,
                    'reversed_delta' => $reverseDelta,
                    'new_balance' => $attendance->employee->fresh()->current_points
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Failed to reverse point transaction", [
                'attendance_id' => $attendance->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
