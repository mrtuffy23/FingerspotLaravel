<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClassificationSeeder::class,
            AllowanceSeeder::class,
            DeductionSeeder::class,
            ShiftSeeder::class,
            WorkCalendarSeeder::class,
            PayrollPeriodSeeder::class,
            ShiftAssignmentSeeder::class,
            AttendanceSeeder::class,
            PayrollSeeder::class,
        ]);
    }
}