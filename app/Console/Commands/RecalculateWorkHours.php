<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Command;

class RecalculateWorkHours extends Command
{
    protected $signature = 'attendance:recalculate {--date= : Tanggal spesifik (YYYY-MM-DD)} {--employee= : ID Karyawan} {--period-start= : Mulai periode} {--period-end= : Akhir periode}';
    protected $description = 'Recalculate work hours berdasarkan check-in/out dan overtime permit terbaru';

    public function handle()
    {
        $query = Attendance::where('work_hours', '>', 0);

        // Filter by specific date
        if ($this->option('date')) {
            $query->where('date', $this->option('date'));
            $this->info("Recalculating for date: {$this->option('date')}");
        }

        // Filter by employee
        if ($this->option('employee')) {
            $query->where('employee_id', $this->option('employee'));
            $this->info("Recalculating for employee: {$this->option('employee')}");
        }

        // Filter by period
        if ($this->option('period-start') && $this->option('period-end')) {
            $query->whereBetween('date', [$this->option('period-start'), $this->option('period-end')]);
            $this->info("Recalculating for period: {$this->option('period-start')} to {$this->option('period-end')}");
        }

        $attendances = $query->get();
        $recalculatedCount = 0;
        $updatedCount = 0;

        $bar = $this->output->createProgressBar(count($attendances));
        $bar->start();

        foreach ($attendances as $attendance) {
            $oldWorkHours = $attendance->work_hours;

            // Recalculate based on employment type
            if ($attendance->employee->employment_type === 'monthly') {
                $newWorkHours = $attendance->calculateMonthlyWorkHours();
            } else {
                $newWorkHours = $attendance->calculateShiftWorkHours();
            }

            // Update if different
            if ($oldWorkHours != $newWorkHours) {
                $attendance->update(['work_hours' => $newWorkHours]);
                $updatedCount++;
                $this->line("\n  ✓ {$attendance->employee->name} ({$attendance->date}): {$oldWorkHours} → {$newWorkHours} jam");
            }

            $recalculatedCount++;
            $bar->advance();
        }

        $bar->finish();

        $this->info("\n\n✅ Recalculation complete!");
        $this->info("Total processed: {$recalculatedCount}");
        $this->info("Total updated: {$updatedCount}");
    }
}
