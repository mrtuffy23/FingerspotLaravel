<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;

class ComprehensiveAttendanceSeeder extends Seeder
{
    /**
     * Seed comprehensive attendance data from November 1 to December 10, 2025
     * including various statuses: present, late, sick, leave, permit
     */
    public function run(): void
    {
        $employees = Employee::all();
        $startDate = Carbon::create(2025, 11, 1);
        $endDate = Carbon::create(2025, 12, 10);

        // Define leave periods for various employees
        $leaveSchedules = [
            1 => [ // Ahmad Wijaya - Cuti di awal Desember
                ['type' => 'cuti', 'start' => '2025-12-01', 'end' => '2025-12-03', 'reason' => 'Cuti tahunan']
            ],
            2 => [ // Siti Nurhaliza - Sakit di tengah November
                ['type' => 'sakit', 'start' => '2025-11-10', 'end' => '2025-11-11', 'reason' => 'Sakit flu'],
                ['type' => 'izin', 'start' => '2025-11-24', 'end' => '2025-11-24', 'reason' => 'Keperluan keluarga']
            ],
            3 => [ // Budi Santoso - Sakit panjang
                ['type' => 'sakit', 'start' => '2025-11-17', 'end' => '2025-11-19', 'reason' => 'Sakit demam']
            ],
            4 => [ // Rini Kusuma - Cuti di akhir November
                ['type' => 'cuti', 'start' => '2025-11-28', 'end' => '2025-11-30', 'reason' => 'Cuti liburan'],
                ['type' => 'sakit', 'start' => '2025-12-08', 'end' => '2025-12-09', 'reason' => 'Sakit migrain']
            ],
            5 => [ // Hendra Gunawan - Izin dan sakit
                ['type' => 'izin', 'start' => '2025-11-20', 'end' => '2025-11-20', 'reason' => 'Mengurus dokumen'],
                ['type' => 'sakit', 'start' => '2025-12-02', 'end' => '2025-12-02', 'reason' => 'Sakit perut']
            ],
            6 => [ // Dewi Lestari - Cuti dan izin
                ['type' => 'cuti', 'start' => '2025-11-25', 'end' => '2025-11-27', 'reason' => 'Cuti liburan'],
                ['type' => 'izin', 'start' => '2025-12-05', 'end' => '2025-12-05', 'reason' => 'Keperluan mendesak']
            ]
        ];

        // Create leave records
        foreach ($leaveSchedules as $employeeId => $leaves) {
            foreach ($leaves as $leave) {
                $leaveRecord = Leave::create([
                    'employee_id' => $employeeId,
                    'type' => $leave['type'],
                    'start_date' => $leave['start'],
                    'end_date' => $leave['end'],
                    'reason' => $leave['reason'],
                    'approved_by' => 4, // Rini Kusuma as HR
                    'approved_at' => Carbon::now(),
                    'duration' => Carbon::parse($leave['end'])->diffInDays(Carbon::parse($leave['start'])) + 1
                ]);
                
                // AUTO-CREATE ATTENDANCE RECORDS FOR LEAVE DAYS
                // This simulates the LeaveController::approve() behavior
                $statusMap = [
                    'izin' => 'permission',
                    'sakit' => 'sick',
                    'sakit_sabtu' => 'sick',
                    'kecelakaan' => 'accident',
                    'cuti' => 'on_leave',
                    'izin_keluar' => 'out_permission',
                    'libur' => 'on_leave',
                ];
                
                $attendanceStatus = $statusMap[$leave['type']] ?? 'on_leave';
                $startDate = Carbon::parse($leave['start']);
                $endDate = Carbon::parse($leave['end']);
                
                // Create attendance record for each day of leave
                for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                    // Calculate leave compensation based on type
                    $leaveCompensation = 0;
                    if ($leave['type'] === 'sakit' && $date->isSaturday()) {
                        $leaveCompensation = 5; // Sakit di Sabtu = 5 jam
                    } elseif ($leave['type'] === 'sakit' || $leave['type'] === 'cuti' || $leave['type'] === 'kecelakaan') {
                        $leaveCompensation = 7; // Sakit/Cuti/Kecelakaan = 7 jam
                    }
                    // izin dan izin_keluar tidak dapat compensation
                    
                    Attendance::updateOrCreate(
                        [
                            'employee_id' => $employeeId,
                            'date' => $date->toDateString(),
                        ],
                        [
                            'status' => $attendanceStatus,
                            'work_hours' => 0,
                            'compensated_hours' => $leaveCompensation,
                            'note' => "✅ Approved {$leave['type']}: {$leave['reason']}",
                            'approved_by' => 4, // HR approve
                            'approved_at' => Carbon::now(),
                        ]
                    );
                }
            }
        }

        // Create attendance records
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Skip Sundays (weekends)
            if ($date->isSunday()) {
                continue;
            }

            foreach ($employees as $employee) {
                // Check if this date falls within a leave period
                $leaveRecord = Leave::where('employee_id', $employee->id)
                    ->where('start_date', '<=', $date->format('Y-m-d'))
                    ->where('end_date', '>=', $date->format('Y-m-d'))
                    ->first();

                if ($leaveRecord) {
                    // Skip attendance record for leave days - it will be handled by leave data
                    continue;
                }

                // Determine attendance status with realistic distribution
                $randomValue = rand(1, 100);
                $firstIn = null;
                $lastOut = null;
                $workHours = 0;
                $status = 'present';
                $note = null;

                if ($randomValue <= 75) {
                    // 75% - Present (Hadir tepat waktu)
                    $status = 'present';
                    $firstIn = $date->copy()->setTime(rand(7, 8), rand(0, 59), 0);
                    $lastOut = $date->copy()->setTime(rand(16, 17), rand(0, 59), 0);
                    $workHours = 8.5 + (rand(-20, 20) / 60);
                    $note = 'Hadir';
                } elseif ($randomValue <= 90) {
                    // 15% - Late (Telat masuk)
                    $status = 'late';
                    $lateMinutes = rand(15, 120);
                    $firstIn = $date->copy()->setTime(8, 0, 0)->addMinutes($lateMinutes);
                    $lastOut = $date->copy()->setTime(rand(16, 17), rand(0, 59), 0);
                    $workHours = max(0, 8.5 - ($lateMinutes / 60));
                    $note = "Telat masuk {$lateMinutes} menit";
                } elseif ($randomValue <= 98) {
                    // 8% - Early leave (Pulang cepat)
                    $status = 'early_leave';
                    $firstIn = $date->copy()->setTime(rand(7, 8), rand(0, 59), 0);
                    $earlyMinutes = rand(30, 120);
                    $lastOut = $date->copy()->setTime(16, 0, 0)->subMinutes($earlyMinutes);
                    $workHours = max(0, 8.5 - ($earlyMinutes / 60));
                    $note = "Pulang {$earlyMinutes} menit lebih awal";
                } else {
                    // 2% - Absent (Tidak hadir tanpa keterangan)
                    $status = 'absent';
                    $firstIn = null;
                    $lastOut = null;
                    $workHours = 0;
                    $note = 'Alpa';
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
                    'note' => $note,
                ]);
            }
        }

        $this->command->info('✓ Comprehensive attendance data from Nov 1 - Dec 10, 2025 seeded successfully!');
        $this->command->info('✓ Includes: Present, Late arrivals, Early leaves, Sick leaves, Permits, and Vacation');
    }
}
