<?php

namespace App\Console\Commands;

use App\Models\WorkCalendar;
use App\Models\Attendance;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SyncAttendanceCalendarCommand extends Command
{
    protected $signature = 'sync:attendance-calendar {--date= : Sync untuk tanggal tertentu (YYYY-MM-DD)} {--month= : Sync untuk bulan tertentu (YYYY-MM)} {--all : Sync semua data}';
    protected $description = 'Sync data Attendance dengan WorkCalendar untuk menghitung kompensasi hari libur';

    public function handle()
    {
        $this->info('╔════════════════════════════════════════════╗');
        $this->info('║   SYNC ATTENDANCE & CALENDAR               ║');
        $this->info('╚════════════════════════════════════════════╝');
        
        $date = $this->option('date');
        $month = $this->option('month');
        $all = $this->option('all');

        if ($date) {
            $this->syncByDate($date);
        } elseif ($month) {
            $this->syncByMonth($month);
        } elseif ($all) {
            $this->syncAll();
        } else {
            // Default: sync bulan ini
            $this->syncByMonth(now()->format('Y-m'));
        }

        $this->info('✅ Sync selesai!');
    }

    /**
     * Sync untuk tanggal tertentu
     */
    private function syncByDate($date)
    {
        $this->info("\n📅 Syncing tanggal: {$date}");
        
        $calendar = WorkCalendar::where('date', $date)->first();
        $attendances = Attendance::where('date', $date)
                                ->where('work_hours', '>', 0)
                                ->get();

        if ($attendances->isEmpty()) {
            $this->info('   ℹ️  Tidak ada attendance untuk tanggal ini.');
            return;
        }

        $synced = 0;
        foreach ($attendances as $att) {
            if ($calendar && in_array($calendar->type, ['national_holiday', 'collective_leave'])) {
                $compensatedHours = round($att->work_hours * 1.5, 2);
                $att->update(['compensated_hours' => $compensatedHours]);
                $synced++;
            }
        }

        $this->info("   ✅ Synced: {$synced} record(s)");
    }

    /**
     * Sync untuk bulan tertentu
     */
    private function syncByMonth($month)
    {
        $this->info("\n📅 Syncing bulan: {$month}");
        
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $attendances = Attendance::whereBetween('date', [$start, $end])
                                ->where('work_hours', '>', 0)
                                ->get();

        if ($attendances->isEmpty()) {
            $this->info('   ℹ️  Tidak ada attendance untuk bulan ini.');
            return;
        }

        $synced = 0;
        $bar = $this->output->createProgressBar($attendances->count());
        
        foreach ($attendances as $att) {
            $calendar = WorkCalendar::where('date', $att->date)->first();
            
            if ($calendar && in_array($calendar->type, ['national_holiday', 'collective_leave'])) {
                $compensatedHours = round($att->work_hours * 1.5, 2);
                $att->update(['compensated_hours' => $compensatedHours]);
                $synced++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->info("\n   ✅ Synced: {$synced} dari {$attendances->count()} record(s)");
    }

    /**
     * Sync semua data (hati-hati!)
     */
    private function syncAll()
    {
        if (!$this->confirm('⚠️  Ini akan sync SEMUA data attendance. Lanjutkan?')) {
            $this->info('Dibatalkan.');
            return;
        }

        $this->info("\n📅 Syncing SEMUA data...");
        
        $attendances = Attendance::where('work_hours', '>', 0)->get();

        if ($attendances->isEmpty()) {
            $this->info('   ℹ️  Tidak ada attendance untuk di-sync.');
            return;
        }

        $synced = 0;
        $bar = $this->output->createProgressBar($attendances->count());
        
        foreach ($attendances as $att) {
            $calendar = WorkCalendar::where('date', $att->date)->first();
            
            if ($calendar && in_array($calendar->type, ['national_holiday', 'collective_leave'])) {
                $compensatedHours = round($att->work_hours * 1.5, 2);
                $att->update(['compensated_hours' => $compensatedHours]);
                $synced++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->info("\n   ✅ Synced: {$synced} dari {$attendances->count()} record(s)");
    }
}
