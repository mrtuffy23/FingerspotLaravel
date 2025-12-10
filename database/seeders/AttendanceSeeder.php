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
        $endDate = Carbon::create(2025, 11, 30);

        $statuses = ['present', 'late', 'early_leave', 'absent'];

        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            // Lewati hari Minggu (weekend)
            if ($date->isSunday()) {
                continue;
            }

            foreach ($employees as $employee) {
                // Random chance untuk status kehadiran
                $randomStatus = rand(1, 100);
                
                if ($randomStatus <= 70) {
                    // 70% Present (Hadir)
                    $status = 'present';
                    $firstIn = $date->copy()->setTime(rand(7, 8), rand(0, 59));
                    $lastOut = $date->copy()->setTime(rand(16, 17), rand(0, 59));
                    $workHours = 8.5 + (rand(-30, 30) / 60); // 8-9 jam kerja
                } elseif ($randomStatus <= 85) {
                    // 15% Late (Terlambat)
                    $status = 'late';
                    $firstIn = $date->copy()->setTime(rand(8, 9), rand(0, 59));
                    $lastOut = $date->copy()->setTime(rand(16, 17), rand(0, 59));
                    $workHours = 7.5 + (rand(-30, 30) / 60); // 7-8 jam kerja
                } elseif ($randomStatus <= 95) {
                    // 10% Early Leave (Pulang Cepat)
                    $status = 'early_leave';
                    $firstIn = $date->copy()->setTime(rand(7, 8), rand(0, 59));
                    $lastOut = $date->copy()->setTime(rand(14, 15), rand(0, 59));
                    $workHours = 6.5 + (rand(-30, 30) / 60); // 6-7 jam kerja
                } else {
                    // 5% Absent (Tidak Hadir)
                    $status = 'absent';
                    $firstIn = null;
                    $lastOut = null;
                    $workHours = 0;
                }

                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->format('Y-m-d'),
                    'first_in' => $firstIn,
                    'last_out' => $lastOut,
                    'work_hours' => $workHours,
                    'compensated_hours' => 0,
                    'status' => $status,
                    'point_delta' => 0,
                    'note' => null,
                ]);
            }
        }

        $this->command->info('Attendance data for November 2025 seeded successfully!');
    }
}