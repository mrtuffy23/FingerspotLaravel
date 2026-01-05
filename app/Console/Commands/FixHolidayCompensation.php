<?php

namespace App\Console\Commands;

use App\Services\HolidayCompensationService;
use Illuminate\Console\Command;

class FixHolidayCompensation extends Command
{
    protected $signature = 'compensation:fix {--start= : Start date (YYYY-MM-DD)} {--end= : End date (YYYY-MM-DD)}';

    protected $description = 'Fix holiday compensation data from old formula (50%) to new formula (fixed bonus)';

    public function handle()
    {
        $startDate = $this->option('start');
        $endDate = $this->option('end');

        $this->info('Fixing holiday compensation data...');
        $this->info("Period: {$startDate} to {$endDate}");

        $result = HolidayCompensationService::fixOldCompensationData($startDate, $endDate);

        $this->info("✅ Fixed {$result['total_fixed']} attendance records.");
    }
}
