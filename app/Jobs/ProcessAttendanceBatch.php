<?php
namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\AttendanceEvent;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave as LeaveModel;
use App\Models\PointTransaction;
use Carbon\Carbon;
class ProcessAttendanceBatch implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function handle() {
        $dates = AttendanceEvent::selectRaw('DATE(event_time) as date')
            ->where('event_time','>=',now()->subDays(14))
            ->distinct()->pluck('date');
        foreach ($dates as $date) {
            $events = AttendanceEvent::whereDate('event_time', $date)->get()->groupBy('employee_pin');
            foreach ($events as $pin => $rows) {
                $employee = Employee::where('pin', $pin)->first();
                if (!$employee) continue;
                $first = $rows->min('event_time');
                $last = $rows->max('event_time');
                $firstDt = Carbon::parse($first);
                $lastDt = Carbon::parse($last);
                $diffHours = max(0, $lastDt->floatDiffInHours($firstDt));
                $break = 1.0;
                $workHours = max(0, round($diffHours - $break,2));
                $attendance = Attendance::updateOrCreate([
                    'employee_id' => $employee->id,
                    'date' => $date
                ],[
                    'first_in' => $first,
                    'last_out' => $last,
                    'work_hours' => $workHours,
                    'processed_at' => now()
                ]);
                $leave = LeaveModel::where('employee_id',$employee->id)
                    ->where('start_date','<=',$date)->where('end_date','>=',$date)
                    ->whereNotNull('approved_at')->first();
                if ($leave) {
                    $rules = config('discipline.rules');
                    $type = $leave->type;
                    $rule = $rules[$type] ?? null;
                    if ($rule) {
                        $attendance->status = $type;
                        $attendance->compensated_hours = $rule['compensated_hours'];
                        $attendance->point_delta = $rule['point'];
                        $attendance->save();
                        
                        PointTransaction::create([
                            'employee_id' => $employee->id,
                            'date' => $date,
                            'delta' => $rule['point'],
                            'reason' => "leave: {$type}",
                            'source_id' => $leave->id
                        ]);
                        // Update employee's current points
                        $employee->increment('current_points', $rule['point']);
                    }
                } else {
                    // No leave approved - check if employee has any scan
                    // If no scan at all ($first is null), mark as ALPHA/ABSENT
                    if (is_null($first)) {
                        $rule = config('discipline.rules')['alpha'] ?? [];
                        $attendance->status = 'alpha';
                        $attendance->point_delta = $rule['point'] ?? -5;
                        $attendance->work_hours = 0;
                        $attendance->save();
                        
                        // Record point transaction
                        if ($rule) {
                            PointTransaction::create([
                                'employee_id' => $employee->id,
                                'date' => $date,
                                'delta' => $rule['point'],
                                'reason' => 'attendance_alpha',
                                'source_id' => $attendance->id
                            ]);
                            // Update employee's current points
                            $employee->increment('current_points', $rule['point']);
                        }
                    } else {
                        // Employee has scan - determine status based on work hours
                        // This will be "present", "late", or "early_leave" depending on hours
                        $rule = config('discipline.rules')['present'] ?? [];
                        
                        // If work hours is 0 after deducting break, mark as early leave
                        if ($workHours <= 0) {
                            $attendance->status = 'early_leave';
                            $rule = config('discipline.rules')['early_leave'] ?? [];
                        } else {
                            $attendance->status = 'present';
                        }
                        
                        $attendance->point_delta = $rule['point'] ?? 0;
                        $attendance->save();
                        
                        // Record point transaction if there's a point delta
                        if (isset($rule['point']) && $rule['point'] != 0) {
                            PointTransaction::create([
                                'employee_id' => $employee->id,
                                'date' => $date,
                                'delta' => $rule['point'],
                                'reason' => 'attendance_' . $attendance->status,
                                'source_id' => $attendance->id
                            ]);
                            // Update employee's current points
                            $employee->increment('current_points', $rule['point']);
                        }
                    }
                }
            }
        }
    }
}
