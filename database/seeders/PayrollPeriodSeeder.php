<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PayrollPeriod;

class PayrollPeriodSeeder extends Seeder
{
    public function run(): void
    {
        // November 2025
        PayrollPeriod::updateOrCreate(
            ['start_date' => '2025-11-01', 'end_date' => '2025-11-30'],
            ['name' => 'November 2025', 'starting_points' => 100]
        );

        // December 2025 (partial based on seeded attendance)
        PayrollPeriod::updateOrCreate(
            ['start_date' => '2025-12-01', 'end_date' => '2025-12-10'],
            ['name' => 'December 2025 (partial)', 'starting_points' => 100]
        );

        $this->command->info('✅ Payroll periods created for Nov and Dec 2025');
    }
}
