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
            UserSeeder::class,
            ClassificationSeeder::class,
            AllowanceSeeder::class,
            ShiftSeeder::class,
            WorkCalendarSeeder::class,
            ShiftAssignmentSeeder::class,
            DivisionSeeder::class,
            DepartmentSeeder::class,
            PositionSeeder::class,
            SubDivisionSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}