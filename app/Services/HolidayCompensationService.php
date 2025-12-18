<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\WorkCalendar;
use Carbon\Carbon;

class HolidayCompensationService
{
    /**
     * Cek apakah tanggal adalah hari libur atau tanggal merah
     */
    public static function isHoliday(Carbon $date): bool
    {
        $workCalendar = WorkCalendar::where('date', $date->toDateString())->first();

        if ($workCalendar) {
            return in_array($workCalendar->type, ['national_holiday', 'collective_leave']);
        }

        // Hanya hari besar nasional/hari raya dari database yang dianggap libur
        // Minggu adalah hari kerja biasa
        return false;
    }

    /**
     * Cek apakah tanggal adalah hari kerja normal
     */
    public static function isWorkday(Carbon $date): bool
    {
        return !self::isHoliday($date);
    }

    /**
     * Dapatkan informasi hari libur
     */
    public static function getHolidayInfo(Carbon $date): ?array
    {
        $workCalendar = WorkCalendar::where('date', $date->toDateString())->first();

        if (!$workCalendar) {
            // Tidak ada di database = hari kerja biasa (termasuk Minggu)
            return null;
        }

        return [
            'date' => $workCalendar->date->toDateString(),
            'description' => $workCalendar->description,
            'type' => $workCalendar->type,
            'is_holiday' => in_array($workCalendar->type, ['national_holiday', 'collective_leave']),
        ];
    }

    /**
     * Hitung kompensasi jam kerja untuk hari libur
     * Logika baru:
     * - Jika jam_kerja == 5 jam, maka ditambah 3 (total 8 jam)
     * - Jika jam_kerja == 6 jam, maka ditambah 4 (total 10 jam)
     * - Jika jam_kerja >= 7 jam, maka ditambah 5 (total 12+ jam)
     * - Selain itu 0
     */
    public static function calculateCompensatedHours($workHours, Carbon $date): float
    {
        if (!self::isHoliday($date) || $workHours <= 0) {
            return 0;
        }

        // Round to nearest integer for comparison
        $roundedHours = round($workHours);

        // Tentukan bonus kompensasi berdasarkan jam kerja
        $compensationBonus = 0;
        
        if ($roundedHours == 5) {
            $compensationBonus = 3;
        } elseif ($roundedHours == 6) {
            $compensationBonus = 4;
        } elseif ($roundedHours >= 7) {
            $compensationBonus = 5;
        }

        return round($compensationBonus, 2);
    }

    /**
     * Update attendance dengan informasi hari libur dan kompensasi
     */
    public static function updateAttendanceWithHolidayInfo(Attendance $attendance): Attendance
    {
        $date = Carbon::parse($attendance->date);
        $holidayInfo = self::getHolidayInfo($date);

        // Jika adalah hari libur
        if ($holidayInfo && $holidayInfo['is_holiday']) {
            // Hitung kompensasi jam
            $compensatedHours = self::calculateCompensatedHours(
                $attendance->work_hours,
                $date
            );

            // Update attendance
            $attendance->update([
                'compensated_hours' => $compensatedHours,
                'note' => ($attendance->note ? $attendance->note . ' | ' : '') . 
                         "Hari Libur: {$holidayInfo['description']}",
            ]);
        }

        return $attendance;
    }

    /**
     * Cek dan update semua attendance untuk periode tertentu
     */
    public static function processHolidayCompensationForPeriod($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $attendances = Attendance::whereBetween('date', [
            $startDate->toDateString(),
            $endDate->toDateString()
        ])->get();

        $processedCount = 0;

        foreach ($attendances as $attendance) {
            if ($attendance->work_hours > 0) {
                self::updateAttendanceWithHolidayInfo($attendance);
                $processedCount++;
            }
        }

        return [
            'total_processed' => $processedCount,
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
            ]
        ];
    }

    /**
     * Dapatkan laporan karyawan yang masuk pada hari libur
     */
    public static function getHolidayAttendanceReport($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $attendances = Attendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('work_hours', '>', 0)
            ->with('employee')
            ->get()
            ->filter(function ($attendance) {
                return self::isHoliday(Carbon::parse($attendance->date));
            })
            ->groupBy('employee_id');

        $report = [];

        foreach ($attendances as $employeeId => $records) {
            $totalWorkHours = 0;
            $totalCompensatedHours = 0;
            $holidays = [];

            foreach ($records as $attendance) {
                $date = Carbon::parse($attendance->date);
                $holidayInfo = self::getHolidayInfo($date);

                $totalWorkHours += $attendance->work_hours;
                $totalCompensatedHours += $attendance->compensated_hours ?? 0;

                $holidays[] = [
                    'date' => $attendance->date,
                    'day_name' => $date->translatedFormat('l'),
                    'holiday_description' => $holidayInfo['description'] ?? 'Hari Minggu',
                    'work_hours' => $attendance->work_hours,
                    'compensated_hours' => $attendance->compensated_hours ?? 0,
                    'status' => $attendance->status,
                    'note' => $attendance->note,
                ];
            }

            if (count($records) > 0) {
                $firstRecord = $records->first();
                $report[] = [
                    'employee_id' => $employeeId,
                    'employee_name' => $firstRecord->employee->name,
                    'department' => $firstRecord->employee->department->name ?? '-',
                    'holiday_count' => count($records),
                    'total_work_hours' => round($totalWorkHours, 2),
                    'total_compensated_hours' => round($totalCompensatedHours, 2),
                    'holidays' => $holidays,
                ];
            }
        }

        return $report;
    }

    /**
     * Cek apakah attendance perlu kompensasi (masuk pada hari libur)
     */
    public static function needsCompensation(Attendance $attendance): bool
    {
        return self::isHoliday(Carbon::parse($attendance->date)) && 
               $attendance->work_hours > 0;
    }
}
