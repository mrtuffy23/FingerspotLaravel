<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;

class LeaveCompensationService
{
    /**
     * Mapping leave types to compensation hours (supports EN + ID keys)
     */
    private static array $leaveCompensationMap = [
        // Sakit (weekday)
        'sick_leave' => 7,
        'sakit' => 7,
        // Sakit Sabtu
        'sick_leave_saturday' => 5,
        'sakit_sabtu' => 5,
        // Kecelakaan kerja
        'work_accident' => 7,
        'kecelakaan' => 7,
        // Cuti tahunan
        'vacation' => 7,
        'cuti' => 7,
        // Izin (no compensation)
        'permission' => 0,
        'izin' => 0,
        'permission_half_day' => 0,
    ];

    /**
     * Cek apakah ada leave untuk tanggal dan employee tertentu
     */
    public static function getLeaveForDate(int $employeeId, Carbon $date): ?Leave
    {
        return Leave::where('employee_id', $employeeId)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('approved_at', '!=', null)
            ->first();
    }

    /**
     * Hitung kompensasi jam berdasarkan tipe cuti
     */
    public static function calculateLeaveCompensation($leaveType, Carbon $date): float
    {
        $leaveType = (string) $leaveType;

        // Cek jika sakit di hari Sabtu
        if (in_array($leaveType, ['sick_leave', 'sakit'], true) && $date->isSaturday()) {
            return self::$leaveCompensationMap['sick_leave_saturday'] ?? 5;
        }

        // Cek jika kecelakaan kerja
        if (str_contains($leaveType, 'accident') || str_contains($leaveType, 'kecelakaan')) {
            return self::$leaveCompensationMap['work_accident'] ?? 7;
        }

        // Return default hours untuk leave type tersebut
        return self::$leaveCompensationMap[$leaveType] ?? 0;
    }

    /**
     * Update attendance dengan leave compensation
     * Jika employee sedang cuti/izin sakit/kecelakaan, berikan kompensasi hours
     */
    public static function updateAttendanceWithLeaveCompensation(Attendance $attendance): Attendance
    {
        $leave = self::getLeaveForDate($attendance->employee_id, Carbon::parse($attendance->date));

        if ($leave) {
            $compensatedHours = self::calculateLeaveCompensation($leave->type, Carbon::parse($attendance->date));
            $attendance->compensated_hours = $compensatedHours;
            $attendance->save();
        }

        return $attendance;
    }

    /**
     * Dapatkan deskripsi kompensasi untuk ditampilkan
     */
    public static function getCompensationDescription($leaveType, Carbon $date): ?string
    {
        $compensationHours = self::calculateLeaveCompensation($leaveType, $date);

        if ($compensationHours <= 0) {
            return null;
        }

        $descriptions = [
            'sick_leave' => "Izin sakit: +{$compensationHours} jam kerja",
            'sakit' => "Izin sakit: +{$compensationHours} jam kerja",
            'sick_leave_saturday' => "Izin sakit (Sabtu): +{$compensationHours} jam kerja",
            'sakit_sabtu' => "Izin sakit (Sabtu): +{$compensationHours} jam kerja",
            'work_accident' => "Kecelakaan kerja: +{$compensationHours} jam kerja",
            'kecelakaan' => "Kecelakaan kerja: +{$compensationHours} jam kerja",
            'vacation' => "Cuti: +{$compensationHours} jam kerja",
            'cuti' => "Cuti: +{$compensationHours} jam kerja",
        ];

        foreach ($descriptions as $key => $description) {
            if (str_contains($leaveType, $key) || str_contains($key, $leaveType)) {
                return $description;
            }
        }

        return null;
    }

    /**
     * Get all leave types with their compensation values
     */
    public static function getLeaveCompensationMap(): array
    {
        return self::$leaveCompensationMap;
    }
}
