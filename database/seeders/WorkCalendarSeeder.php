<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkCalendar;
use Carbon\Carbon;

class WorkCalendarSeeder extends Seeder
{
    public function run()
    {
        $holidays = [
            // 2025 Indonesian Public Holidays
            ['date' => '2025-01-01', 'description' => 'Tahun Baru 2025', 'type' => 'national_holiday'],
            ['date' => '2025-01-29', 'description' => 'Tahun Baru Imlek 2025', 'type' => 'national_holiday'],
            ['date' => '2025-03-29', 'description' => 'Wafat Isa Almasih', 'type' => 'national_holiday'],
            ['date' => '2025-03-31', 'description' => 'Hari Raya Nyepi', 'type' => 'national_holiday'],
            ['date' => '2025-04-01', 'description' => 'Hari Raya Nyepi (Cuti Bersama)', 'type' => 'collective_leave'],
            ['date' => '2025-04-18', 'description' => 'Jumat Agung', 'type' => 'national_holiday'],
            ['date' => '2025-04-20', 'description' => 'Hari Raya Idul Fitri 1446 H', 'type' => 'national_holiday'],
            ['date' => '2025-04-21', 'description' => 'Hari Raya Idul Fitri 1446 H (Cuti Bersama)', 'type' => 'collective_leave'],
            ['date' => '2025-04-22', 'description' => 'Hari Raya Idul Fitri 1446 H (Cuti Bersama)', 'type' => 'collective_leave'],
            ['date' => '2025-05-01', 'description' => 'Hari Buruh Internasional', 'type' => 'national_holiday'],
            ['date' => '2025-05-12', 'description' => 'Hari Raya Waisak 2569', 'type' => 'national_holiday'],
            ['date' => '2025-05-29', 'description' => 'Kenaikan Isa Almasih', 'type' => 'national_holiday'],
            ['date' => '2025-06-01', 'description' => 'Hari Lahir Pancasila', 'type' => 'national_holiday'],
            ['date' => '2025-06-06', 'description' => 'Hari Raya Idul Adha 1446 H', 'type' => 'national_holiday'],
            ['date' => '2025-07-07', 'description' => 'Tahun Baru Islam 1447 H', 'type' => 'national_holiday'],
            ['date' => '2025-08-17', 'description' => 'Hari Kemerdekaan RI', 'type' => 'national_holiday'],
            ['date' => '2025-08-28', 'description' => 'Hari Raya Natal', 'type' => 'national_holiday'],
            ['date' => '2025-09-05', 'description' => 'Hari Raya Natal (Cuti Bersama)', 'type' => 'collective_leave'],
            ['date' => '2025-12-25', 'description' => 'Hari Raya Natal', 'type' => 'national_holiday'],
            // Add more years if needed
        ];

        foreach ($holidays as $holiday) {
            WorkCalendar::updateOrCreate(
                ['date' => $holiday['date']],
                [
                    'description' => $holiday['description'],
                    'type' => $holiday['type']
                ]
            );
        }
    }
}