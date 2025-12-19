<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PayrollPeriod;
use App\Services\PayrollService;

class PayrollSeeder extends Seeder
{
    public function run(): void
    {
        $service = new PayrollService();

        $periods = PayrollPeriod::whereIn('start_date', ['2025-11-01', '2025-12-01'])
            ->orderBy('start_date')
            ->get();

        foreach ($periods as $period) {
            $service->generateForPeriod($period);
            $this->command->info("✅ Payroll generated for period: {$period->name} ({$period->start_date} - {$period->end_date})");
        }
    }
}
