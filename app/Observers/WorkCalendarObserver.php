<?php

namespace App\Observers;

use App\Models\WorkCalendar;
use App\Models\Attendance;
use Carbon\Carbon;

class WorkCalendarObserver
{
    /**
     * Handle the WorkCalendar "created" event.
     * Saat hari libur BARU dibuat, update attendance yang sudah ada
     */
    public function created(WorkCalendar $calendar): void
    {
        $this->syncAttendanceForDate($calendar);
    }

    /**
     * Handle the WorkCalendar "updated" event.
     * Saat hari libur diubah, update attendance yang sudah ada
     */
    public function updated(WorkCalendar $calendar): void
    {
        $this->syncAttendanceForDate($calendar);
    }

    /**
     * Handle the WorkCalendar "deleted" event.
     * Saat hari libur dihapus, reset attendance kembali ke normal
     */
    public function deleted(WorkCalendar $calendar): void
    {
        // Find semua attendance di tanggal yang dihapus
        $attendances = Attendance::where('date', $calendar->date)
                                 ->where('work_hours', '>', 0)
                                 ->get();
        
        foreach ($attendances as $att) {
            // Reset compensated_hours jika sebelumnya ada kompensasi
            if ($att->compensated_hours > $att->work_hours) {
                $att->update([
                    'compensated_hours' => $att->work_hours,
                    'note' => ($att->note ? $att->note . ' | ' : '') . 
                             "Holiday removed: compensated_hours reset"
                ]);
            }
        }
    }

    /**
     * Sync attendance untuk tanggal kalender tertentu
     */
    private function syncAttendanceForDate(WorkCalendar $calendar): void
    {
        // Hanya sync jika adalah hari libur (bukan workday)
        if ($calendar->type === 'workday') {
            return;
        }

        // Find semua attendance di tanggal tersebut dengan work_hours > 0
        $attendances = Attendance::where('date', $calendar->date)
                                 ->where('work_hours', '>', 0)
                                 ->get();

        foreach ($attendances as $att) {
            // Jika adalah hari libur nasional atau collective leave
            if (in_array($calendar->type, ['national_holiday', 'collective_leave'])) {
                $compensatedHours = round($att->work_hours * 1.5, 2);
                
                $att->update([
                    'compensated_hours' => $compensatedHours,
                    'note' => ($att->note ? $att->note . ' | ' : '') . 
                             "🔄 Auto-synced: {$calendar->type} - {$calendar->description}",
                ]);
            }
        }
    }
}
