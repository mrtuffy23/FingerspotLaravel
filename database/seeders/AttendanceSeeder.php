<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $startDate = Carbon::create(2025, 11, 1);
        $endDate = Carbon::create(2025, 12, 10);

        $statuses = ['present', 'late', 'early_leave', 'absent', 'sick', 'permission'];

        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            // Lewati hari Minggu (weekend)
            if ($date->isSunday()) {
                continue;
            }

            foreach ($employees as $employee) {
                // Random chance untuk status kehadiran
                $randomStatus = rand(1, 100);
                
                if ($randomStatus <= 70) {
                    // 70% Present (Hadir) - Jam masuk 07:30-08:30, pulang 16:00-17:00
                    $status = 'present';
                    $firstIn = $date->copy()->setTime(rand(7, 8), rand(0, 59));
                    $lastOut = $date->copy()->setTime(rand(16, 17), rand(0, 59));
                } elseif ($randomStatus <= 80) {
                    // 10% Late (Terlambat) - Jam masuk 08:30-09:30, pulang 16:30-17:30
                    $status = 'late';
                    $firstIn = $date->copy()->setTime(8, rand(30, 59))->addMinutes(rand(0, 60));
                    $lastOut = $date->copy()->setTime(rand(16, 17), rand(0, 59));
                } elseif ($randomStatus <= 88) {
                    // 8% Early Leave (Pulang Cepat) - Jam masuk 08:00, pulang 14:00-15:00
                    $status = 'early_leave';
                    $firstIn = $date->copy()->setTime(rand(7, 8), rand(30, 59));
                    $lastOut = $date->copy()->setTime(rand(14, 15), rand(0, 59));
                } elseif ($randomStatus <= 93) {
                    // 5% Sick (Sakit)
                    $status = 'sick';
                    $firstIn = null;
                    $lastOut = null;
                } elseif ($randomStatus <= 98) {
                    // 5% Permission (Izin)
                    $status = 'permission';
                    $firstIn = null;
                    $lastOut = null;
                } else {
                    // 2% Absent (Tidak Hadir)
                    $status = 'absent';
                    $firstIn = null;
                    $lastOut = null;
                }

                // Hitung work_hours otomatis dari first_in dan last_out (minus 1 jam istirahat)
                $workHours = 0;
                if ($firstIn && $lastOut) {
                    $diffHours = $lastOut->diffInMinutes($firstIn) / 60;
                    $workHours = max(0, $diffHours - 1); // Kurangi 1 jam istirahat
                }

                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->format('Y-m-d'),
                    'first_in' => $firstIn,
                    'last_out' => $lastOut,
                    'work_hours' => round($workHours, 2),
                    'compensated_hours' => 0,
                    'status' => $status,
                    'point_delta' => 0,
                    'note' => null,
                ]);
            }
        }

        $this->command->info('✅ Attendance data (Nov 1 - Dec 10, 2025) seeded successfully!');
    }
}