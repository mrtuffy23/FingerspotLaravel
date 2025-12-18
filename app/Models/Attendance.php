<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\HolidayCompensationService;
use App\Services\LeaveCompensationService;
use Carbon\Carbon;
use App\Models\OvertimePermit;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'date', 'first_in', 'last_out', 
        'work_hours', 'compensated_hours', 'status', 'point_delta', 'notes', 'approved_by', 'approved_at'
    ];

    protected $attributes = [
        'compensated_hours' => 0,
        'work_hours' => 0,
        'point_delta' => 0,
    ];

    protected $casts = [
        'date' => 'date',
        'first_in' => 'datetime',
        'last_out' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Cek apakah attendance adalah hari libur dengan kerja
     */
    public function isHolidayWork(): bool
    {
        return HolidayCompensationService::isHoliday(Carbon::parse($this->date)) && 
               $this->work_hours > 0;
    }

    /**
     * Dapatkan informasi hari libur jika ada
     */
    public function getHolidayInfo(): ?array
    {
        return HolidayCompensationService::getHolidayInfo(Carbon::parse($this->date));
    }

    /**
     * Accessor untuk mendapatkan hari libur description
     */
    public function getHolidayDescriptionAttribute(): ?string
    {
        $info = $this->getHolidayInfo();
        return $info ? $info['description'] : null;
    }

    /**
     * Hitung dan update kompensasi otomatis
     */
    public function calculateCompensation(): void
    {
        if ($this->isHolidayWork()) {
            $compensatedHours = HolidayCompensationService::calculateCompensatedHours(
                $this->work_hours,
                Carbon::parse($this->date)
            );
            
            $this->update(['compensated_hours' => $compensatedHours]);
        }
    }

    /**
     * Hitung jam kerja berdasarkan aturan karyawan bulanan
     * 
     * Aturan:
     * - Jam masuk: 08:00. Jika > 08:00 = telat, jika < 08:00 jam kerja mulai dari 08:00
     * - Jam pulang: 16:00. Jika < 16:00 = pulang cepat, jika > 16:00 terhitung 16:00 (kecuali ada izin lembur)
     * - Istirahat: 1 jam (dikurangi dari total jam kerja)
     */
    public function calculateMonthlyWorkHours(): float
    {
        if (!$this->first_in || !$this->last_out || $this->employee->employment_type !== 'monthly') {
            return 0;
        }

        $firstIn = Carbon::parse($this->first_in);
        $lastOut = Carbon::parse($this->last_out);
        $attendanceDate = Carbon::parse($this->date);

        // Standard working times for monthly employees
        $standardStartTime = Carbon::createFromFormat('H:i:s', '08:00:00', $attendanceDate->timezone);
        $standardStartTime->setDate($attendanceDate->year, $attendanceDate->month, $attendanceDate->day);

        $standardEndTime = Carbon::createFromFormat('H:i:s', '16:00:00', $attendanceDate->timezone);
        $standardEndTime->setDate($attendanceDate->year, $attendanceDate->month, $attendanceDate->day);

        // Determine actual check-in time for calculation
        // Jika masuk sebelum 08:00, jam kerja mulai dari 08:00
        $actualCheckIn = $firstIn->greaterThan($standardStartTime) ? $firstIn : $standardStartTime;

        // Determine actual check-out time for calculation
        // Jika pulang sebelum 16:00 = pulang cepat
        // Jika pulang setelah 16:00, hanya terhitung 16:00 (kecuali ada izin lembur)
        $actualCheckOut = $standardEndTime;

        if ($lastOut->greaterThan($standardEndTime)) {
            // Ada kemungkinan overtime
            $overtimePermit = $this->getOvertimePermit();
            if ($overtimePermit && $overtimePermit->isApproved()) {
                // Gunakan waktu lembur yang diizinkan
                $overtimeEndTime = Carbon::createFromFormat('H:i:s', $overtimePermit->overtime_end_time, $attendanceDate->timezone);
                $overtimeEndTime->setDate($attendanceDate->year, $attendanceDate->month, $attendanceDate->day);
                $actualCheckOut = $lastOut->greaterThan($overtimeEndTime) ? $overtimeEndTime : $lastOut;
            }
            // else: tetap gunakan standardEndTime (16:00)
        } elseif ($lastOut->lessThan($standardEndTime)) {
            // Pulang sebelum jam 16:00
            $actualCheckOut = $lastOut;
        }

        // Hitung total jam kerja
        $workHours = $actualCheckIn->diffInMinutes($actualCheckOut) / 60;

        // Kurangi 1 jam untuk istirahat
        $workHours = $workHours - 1;

        return $workHours > 0 ? $workHours : 0;
    }

    /**
     * Hitung jam kerja berdasarkan shift untuk karyawan harian
     * 
     * Aturan:
     * - Jam masuk & pulang sesuai dengan shift assignment
     * - Jika > jam mulai shift = telat
     * - Jika < jam akhir shift = pulang cepat
     * - Istirahat: 1 jam (dikurangi dari total jam kerja)
     * - Support overtime dengan izin lembur
     */
    public function calculateShiftWorkHours(): float
    {
        if (!$this->first_in || !$this->last_out || $this->employee->employment_type === 'monthly') {
            return 0;
        }

        $firstIn = Carbon::parse($this->first_in);
        $lastOut = Carbon::parse($this->last_out);
        $attendanceDate = Carbon::parse($this->date);

        // Get shift schedule for this employee on this date
        $shiftAssignment = ShiftAssignment::where('employee_id', $this->employee_id)
            ->where('start_date', '<=', $attendanceDate)
            ->where(function ($query) use ($attendanceDate) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $attendanceDate);
            })
            ->with('shift')
            ->first();

        if (!$shiftAssignment || !$shiftAssignment->shift) {
            return 0;
        }

        // Get shift start and end times
        $shiftStartTime = Carbon::createFromFormat('H:i:s', $shiftAssignment->shift->start_time, $attendanceDate->timezone);
        $shiftStartTime->setDate($attendanceDate->year, $attendanceDate->month, $attendanceDate->day);

        $shiftEndTime = Carbon::createFromFormat('H:i:s', $shiftAssignment->shift->end_time, $attendanceDate->timezone);
        $shiftEndTime->setDate($attendanceDate->year, $attendanceDate->month, $attendanceDate->day);

        // Determine actual check-in time
        // Jika masuk sebelum jam mulai shift, jam kerja mulai dari jam mulai shift
        $actualCheckIn = $firstIn->greaterThan($shiftStartTime) ? $firstIn : $shiftStartTime;

        // Determine actual check-out time
        // Jika pulang sebelum jam akhir shift = pulang cepat
        // Jika pulang setelah jam akhir shift, hanya terhitung jam akhir shift (kecuali ada izin lembur)
        $actualCheckOut = $shiftEndTime;

        if ($lastOut->greaterThan($shiftEndTime)) {
            // Ada kemungkinan overtime
            $overtimePermit = $this->getOvertimePermit();
            if ($overtimePermit && $overtimePermit->isApproved()) {
                // Gunakan waktu lembur yang diizinkan
                $overtimeEndTime = Carbon::createFromFormat('H:i:s', $overtimePermit->overtime_end_time, $attendanceDate->timezone);
                $overtimeEndTime->setDate($attendanceDate->year, $attendanceDate->month, $attendanceDate->day);
                $actualCheckOut = $lastOut->greaterThan($overtimeEndTime) ? $overtimeEndTime : $lastOut;
            }
            // else: tetap gunakan shiftEndTime
        } elseif ($lastOut->lessThan($shiftEndTime)) {
            // Pulang sebelum jam akhir shift
            $actualCheckOut = $lastOut;
        }

        // Hitung total jam kerja
        $workHours = $actualCheckIn->diffInMinutes($actualCheckOut) / 60;

        // Kurangi 1 jam untuk istirahat
        $workHours = $workHours - 1;

        return $workHours > 0 ? $workHours : 0;
    }

    /**
     * Check if employee is late (masuk lebih dari 08:00)
     * Hanya untuk monthly employee
     */
    public function isLateMonthly(): bool
    {
        if (!$this->first_in || !$this->employee || $this->employee->employment_type !== 'monthly') {
            return false;
        }

        $checkInTime = Carbon::parse($this->first_in);
        $standardStartTime = Carbon::createFromFormat('H:i:s', '08:00:00');
        
        return $checkInTime->greaterThan(
            $checkInTime->clone()->setTimeFromTimeString('08:00:00')
        );
    }

    /**
     * Check if employee is early leave (pulang sebelum 16:00)
     * Hanya untuk monthly employee
     */
    public function isEarlyLeaveMonthly(): bool
    {
        if (!$this->last_out || !$this->employee || $this->employee->employment_type !== 'monthly') {
            return false;
        }

        $checkOutTime = Carbon::parse($this->last_out);
        $standardEndTime = $checkOutTime->clone()->setTimeFromTimeString('16:00:00');
        
        return $checkOutTime->lessThan($standardEndTime);
    }

    /**
     * Check if shift employee is late (masuk lebih dari jam mulai shift)
     * Hanya untuk shift/daily employee
     */
    public function isLateShift(): bool
    {
        if (!$this->first_in || !$this->employee || $this->employee->employment_type === 'monthly') {
            return false;
        }

        $checkInTime = Carbon::parse($this->first_in);
        $attendanceDate = Carbon::parse($this->date);

        // Get shift schedule
        $shiftAssignment = ShiftAssignment::where('employee_id', $this->employee_id)
            ->where('start_date', '<=', $attendanceDate)
            ->where(function ($query) use ($attendanceDate) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $attendanceDate);
            })
            ->with('shift')
            ->first();

        if (!$shiftAssignment || !$shiftAssignment->shift) {
            return false;
        }

        $shiftStartTime = Carbon::createFromFormat('H:i:s', $shiftAssignment->shift->start_time);
        $shiftStartTime->setDate($attendanceDate->year, $attendanceDate->month, $attendanceDate->day);

        return $checkInTime->greaterThan($shiftStartTime);
    }

    /**
     * Check if shift employee is early leave (pulang sebelum jam akhir shift)
     * Hanya untuk shift/daily employee
     */
    public function isEarlyLeaveShift(): bool
    {
        if (!$this->last_out || !$this->employee || $this->employee->employment_type === 'monthly') {
            return false;
        }

        $checkOutTime = Carbon::parse($this->last_out);
        $attendanceDate = Carbon::parse($this->date);

        // Get shift schedule
        $shiftAssignment = ShiftAssignment::where('employee_id', $this->employee_id)
            ->where('start_date', '<=', $attendanceDate)
            ->where(function ($query) use ($attendanceDate) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $attendanceDate);
            })
            ->with('shift')
            ->first();

        if (!$shiftAssignment || !$shiftAssignment->shift) {
            return false;
        }

        $shiftEndTime = Carbon::createFromFormat('H:i:s', $shiftAssignment->shift->end_time);
        $shiftEndTime->setDate($attendanceDate->year, $attendanceDate->month, $attendanceDate->day);

        return $checkOutTime->lessThan($shiftEndTime);
    }

    /**
     * Get overtime permit for this attendance date
     */
    public function getOvertimePermit(): ?OvertimePermit
    {
        return OvertimePermit::where('employee_id', $this->employee_id)
            ->where('date', $this->date)
            ->first();
    }

    /**
     * Check if employee is late based on their schedule
     */
    public function isLate(): bool
    {
        if (!$this->first_in || !$this->employee) {
            return false;
        }

        $checkInTime = Carbon::parse($this->first_in);
        $checkInHour = $checkInTime->format('H:i:00');
        
        // Get employee's schedule time
        $scheduleTime = $this->getScheduleStartTime();
        
        if (!$scheduleTime) {
            return false;
        }

        // Compare: if check-in time is after schedule start time, employee is late
        return $checkInTime->greaterThan(Carbon::createFromFormat('H:i:s', $scheduleTime));
    }

    /**
     * Get the scheduled start time for the employee on this date
     */
    public function getScheduleStartTime(): ?string
    {
        $employee = $this->employee;
        $attendanceDate = Carbon::parse($this->date);

        if ($employee->employment_type === 'monthly') {
            // Monthly employee: fixed 08:00 schedule
            return '08:00:00';
        } else {
            // Daily employee: get from shift assignment
            $shiftAssignment = ShiftAssignment::where('employee_id', $employee->id)
                ->where('start_date', '<=', $attendanceDate)
                ->where(function ($query) use ($attendanceDate) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', $attendanceDate);
                })
                ->with('shift')
                ->first();

            return $shiftAssignment?->shift?->start_time;
        }
    }

    /**
     * Calculate total compensation (holiday + leave compensation)
     */
    public function calculateTotalCompensation(): float
    {
        $totalCompensation = $this->compensated_hours ?? 0;

        // Add leave-based compensation if applicable
        $leave = LeaveCompensationService::getLeaveForDate($this->employee_id, Carbon::parse($this->date));
        if ($leave) {
            $leaveCompensation = LeaveCompensationService::calculateLeaveCompensation($leave->type, Carbon::parse($this->date));
            $totalCompensation += $leaveCompensation;
        }

        return $totalCompensation;
    }

    /**
     * Get leave compensation for this date if applicable
     */
    public function getLeaveCompensation(): float
    {
        $leave = LeaveCompensationService::getLeaveForDate($this->employee_id, Carbon::parse($this->date));
        if ($leave) {
            return LeaveCompensationService::calculateLeaveCompensation($leave->type, Carbon::parse($this->date));
        }
        return 0;
    }

    /**
     * Get leave info if employee is on leave for this date
     */
    public function getLeaveInfo(): ?array
    {
        $leave = LeaveCompensationService::getLeaveForDate($this->employee_id, Carbon::parse($this->date));
        if ($leave) {
            return [
                'type' => $leave->type,
                'reason' => $leave->reason,
                'compensation' => LeaveCompensationService::calculateLeaveCompensation($leave->type, Carbon::parse($this->date)),
                'description' => LeaveCompensationService::getCompensationDescription($leave->type, Carbon::parse($this->date)),
            ];
        }
        return null;
    }

    /**
     * Scope untuk filter attendance hari kerja saja
     */
    public function scopeWorkdaysOnly($query)
    {
        $workCalendarDates = WorkCalendar::whereIn('type', ['national_holiday', 'collective_leave'])
            ->pluck('date')
            ->toArray();

        return $query->whereNotIn('date', $workCalendarDates)
                    ->where(function ($q) {
                        $q->whereRaw('DAYOFWEEK(date) NOT IN (1)');
                    });
    }

    /**
     * Scope untuk filter attendance hari libur dengan kerja
     */
    public function scopeHolidayWork($query)
    {
        $workCalendarDates = WorkCalendar::whereIn('type', ['national_holiday', 'collective_leave'])
            ->pluck('date')
            ->toArray();

        return $query->where('work_hours', '>', 0)
                    ->where(function ($q) use ($workCalendarDates) {
                        $q->whereIn('date', $workCalendarDates)
                          ->orWhereRaw('DAYOFWEEK(date) = 1'); // 1 = Sunday
                    });
    }
}

