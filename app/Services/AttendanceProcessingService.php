<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceEvent;
use App\Models\Employee;
use App\Models\ShiftAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceProcessingService
{
    private $pointService;

    public function __construct()
    {
        $this->pointService = new PointTransactionService();
    }

    /**
     * Process attendance events into attendance records
     * Groups scans by employee and date, calculates work hours
     * Also processes employees with NO SCANS as ALPHA
     */
    public function processAttendanceEvents($startDate = null, $endDate = null)
    {
        // Default: process today
        if (!$startDate) {
            $startDate = now()->format('Y-m-d');
        }
        if (!$endDate) {
            $endDate = now()->format('Y-m-d');
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate);

        // Get all events in date range
        $events = AttendanceEvent::whereBetween('event_time', [
            $startDate->startOfDay(),
            $endDate->endOfDay()
        ])->orderBy('event_time')->get();

        // Group events by employee PIN and date
        $groupedEvents = $events->isEmpty() ? [] : $events->groupBy(function ($event) {
            $date = Carbon::parse($event->event_time)->format('Y-m-d');
            return $event->employee_pin . '|' . $date;
        });

        $processed = 0;
        $failed = 0;
        $processedDates = []; // Track which employee-date combinations were processed

        // Step 1: Process employees WITH scans
        foreach ($groupedEvents as $key => $dayEvents) {
            try {
                [$pin, $date] = explode('|', $key);
                $this->processEmployeeDayAttendance($pin, $date, $dayEvents);
                $processed++;
                $processedDates[] = $pin . '|' . $date;
            } catch (\Exception $e) {
                Log::error("Failed to process attendance for PIN: $pin, Date: $date - " . $e->getMessage());
                $failed++;
            }
        }

        // Step 2: Process all active employees with NO scans in date range as ALPHA
        $activeEmployees = Employee::where('status', 'aktif')->get();
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            
            foreach ($activeEmployees as $employee) {
                $checkKey = $employee->pin . '|' . $dateStr;
                
                // Skip if already processed (has scans)
                if (in_array($checkKey, $processedDates)) {
                    continue;
                }
                
                // Check if already has attendance record for this date
                $existingAttendance = Attendance::where('employee_id', $employee->id)
                    ->where('date', $dateStr)
                    ->first();

                if (!$existingAttendance) {
                    // No attendance record and no scans = ALPHA
                    try {
                        $this->processAlphaAttendance($employee, $dateStr);
                        $processed++;
                    } catch (\Exception $e) {
                        Log::error("Failed to process ALPHA attendance for PIN: {$employee->pin}, Date: $dateStr - " . $e->getMessage());
                        $failed++;
                    }
                }
            }
            
            $currentDate->addDay();
        }

        return [
            'success' => true,
            'message' => "Processed $processed employee-dates, Failed: $failed",
            'processed' => $processed,
            'failed' => $failed
        ];
    }

    /**
     * Process ALPHA attendance (employee with no scans)
     */
    private function processAlphaAttendance(Employee $employee, $date)
    {
        // Create attendance record with ALPHA status
        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'date' => $date,
            'first_in' => null,
            'last_out' => null,
            'work_hours' => 0,
            'status' => 'alpha',
            'point_delta' => config('discipline.rules.alpha.point', -40),
            'notes' => 'Auto-marked as ALPHA (no scan records found)'
        ]);

        // Process points automatically
        try {
            $this->pointService->processAttendancePoints($attendance);
        } catch (\Exception $e) {
            Log::warning("Failed to process points for attendance", [
                'attendance_id' => $attendance->id,
                'error' => $e->getMessage()
            ]);
        }

        return $attendance;
    }

    /**
     * Process attendance for one employee on one day
     */
    private function processEmployeeDayAttendance($pin, $date, $events)
    {
        // Find employee by PIN
        $employee = Employee::where('pin', $pin)->first();

        if (!$employee) {
            throw new \Exception("Employee with PIN $pin not found");
        }

        // Get first and last event times
        $sortedEvents = $events->sortBy('event_time');
        $firstIn = $sortedEvents->first()->event_time;
        $lastOut = $sortedEvents->last()->event_time;

        // Calculate work hours
        $firstInCarbon = Carbon::parse($firstIn);
        $lastOutCarbon = Carbon::parse($lastOut);
        $workHours = $lastOutCarbon->diffInSeconds($firstInCarbon) / 3600; // Convert to hours

        // Get shift info for this employee on this date
        $shiftAssignment = ShiftAssignment::where('employee_id', $employee->id)
            ->where('start_date', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->where('end_date', '>=', $date)
                      ->orWhereNull('end_date');
            })
            ->first();

        $shift = $shiftAssignment?->shift;
        $status = $this->determineStatus($employee, $date, $firstInCarbon, $workHours, $shift);

        // Create or update attendance record
        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'date' => $date
            ],
            [
                'first_in' => $firstIn,
                'last_out' => $lastOut,
                'work_hours' => round($workHours, 2),
                'status' => $status,
                'point_delta' => $this->pointService->getPointsByStatus($status),
                'note' => "Auto-processed from {$events->count()} scan events"
            ]
        );

        // Process points automatically
        try {
            $this->pointService->processAttendancePoints($attendance);
        } catch (\Exception $e) {
            Log::warning("Failed to process points for attendance", [
                'attendance_id' => $attendance->id,
                'error' => $e->getMessage()
            ]);
        }

        return $attendance;
    }

    /**
     * Determine attendance status based on various rules
     */
    private function determineStatus($employee, $date, $checkInTime, $workHours, $shift)
    {
        $dateCarbon = Carbon::createFromFormat('Y-m-d', $date);

        // Check if employee has leave approved for this date
        $leave = \App\Models\Leave::where('employee_id', $employee->id)
            ->where('start_date', '<=', $dateCarbon)
            ->where('end_date', '>=', $dateCarbon)
            ->whereNotNull('approved_at')
            ->first();

        if ($leave) {
            return $leave->type;
        }

        // Default status based on work hours
        if ($workHours <= 0) {
            // No work hours - could be early leave or absent
            return 'early_leave';
        }

        // Check if late (for monthly employees, compare with 08:00; for shift employees, compare with shift start)
        if ($employee->employment_type === 'monthly') {
            // Monthly employee late check
            $standardStart = $checkInTime->clone()->setTimeFromTimeString('08:00:00');
            if ($checkInTime->greaterThan($standardStart)) {
                return 'late';
            }
        } else if ($shift && $shift->start_time) {
            // Shift employee late check with grace period
            $checkInDate = $checkInTime->format('Y-m-d');
            $shiftStartFull = Carbon::createFromFormat('Y-m-d H:i:s', "$checkInDate {$shift->start_time}");
            $toleranceMinutes = config('discipline.tolerance_minutes', 5);
            
            // If checked in after shift start + tolerance, it's late
            if ($checkInTime->diffInMinutes($shiftStartFull, false) > $toleranceMinutes) {
                return 'late';
            }
        }

        return 'present';
    }
}
