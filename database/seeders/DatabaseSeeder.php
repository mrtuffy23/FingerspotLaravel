<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default departments
        $deptIT = Department::create(['name' => 'Information Technology']);
        $deptHR = Department::create(['name' => 'Human Resources']);
        $deptAcc = Department::create(['name' => 'Accounting']);

        // Create default positions
        $posManager = Position::create(['name' => 'Manager', 'allowance' => 150000]);
        $posSenior = Position::create(['name' => 'Senior Staff', 'allowance' => 100000]);
        $posStaff = Position::create(['name' => 'Staff', 'allowance' => 0]);
        $posJunior = Position::create(['name' => 'Junior Staff', 'allowance' => 0]);

        // Create default employees
        Employee::create([
            'pin' => '001',
            'nik' => '3213210001',
            'name' => 'Ahmad Wijaya',
            'birth_place' => 'Jakarta',
            'birth_date' => '1990-01-15',
            'status' => 'aktif',
            'position_id' => $posManager->id,
            'department_id' => $deptIT->id,
            'join_year' => 2020,
            'umk' => 3500000,
            'employment_type' => 'monthly',
        ]);

        Employee::create([
            'pin' => '002',
            'nik' => '3213210002',
            'name' => 'Siti Nurhaliza',
            'birth_place' => 'Bandung',
            'birth_date' => '1992-03-20',
            'status' => 'aktif',
            'position_id' => $posSenior->id,
            'department_id' => $deptIT->id,
            'join_year' => 2021,
            'umk' => 3500000,
            'employment_type' => 'daily',
        ]);

        Employee::create([
            'pin' => '003',
            'nik' => '3213210003',
            'name' => 'Budi Santoso',
            'birth_place' => 'Surabaya',
            'birth_date' => '1995-06-10',
            'status' => 'aktif',
            'position_id' => $posStaff->id,
            'department_id' => $deptIT->id,
            'join_year' => 2022,
            'umk' => 3500000,
            'employment_type' => 'daily',
        ]);

        Employee::create([
            'pin' => '004',
            'nik' => '3213210004',
            'name' => 'Rini Kusuma',
            'birth_place' => 'Jakarta',
            'birth_date' => '1988-08-05',
            'status' => 'aktif',
            'position_id' => $posManager->id,
            'department_id' => $deptHR->id,
            'join_year' => 2019,
            'umk' => 3500000,
            'employment_type' => 'monthly',
        ]);

        Employee::create([
            'pin' => '005',
            'nik' => '3213210005',
            'name' => 'Hendra Gunawan',
            'birth_place' => 'Medan',
            'birth_date' => '1991-11-12',
            'status' => 'aktif',
            'position_id' => $posSenior->id,
            'department_id' => $deptAcc->id,
            'join_year' => 2020,
            'umk' => 3500000,
            'employment_type' => 'monthly',
        ]);

        Employee::create([
            'pin' => '006',
            'nik' => '3213210006',
            'name' => 'Dewi Lestari',
            'birth_place' => 'Yogyakarta',
            'birth_date' => '1998-01-08',
            'status' => 'aktif',
            'position_id' => $posStaff->id,
            'department_id' => $deptAcc->id,
            'join_year' => 2023,
            'umk' => 3500000,
            'employment_type' => 'daily',
        ]);

        // Seed shifts and assign to employees
        $this->call(ShiftSeeder::class);
        $this->call(ShiftAssignmentSeeder::class);

        // Seed work calendar holidays
        $this->call(WorkCalendarSeeder::class);

        // Seed comprehensive attendance data with various statuses
        $this->call(ComprehensiveAttendanceSeeder::class);

        // Seed payroll periods and generate payrolls
        $this->call(PayrollPeriodSeeder::class);
        $this->call(PayrollSeeder::class);
    }
}