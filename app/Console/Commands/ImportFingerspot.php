<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\AttendanceEvent;
use League\Csv\Reader;
use App\Jobs\ProcessAttendanceBatch;
class ImportFingerspot extends Command {
    protected $signature = 'fingerspot:import {path}';
    protected $description = 'Import CSV from Fingerspot device';
    public function handle() {
        $path = $this->argument('path');
        if (!file_exists($path)) return $this->error('File not found');
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();
        $count = 0;
        foreach ($records as $r) {
            AttendanceEvent::create([
                'employee_pin' => $r['pin'] ?? $r['PIN'] ?? null,
                'event_time' => $r['datetime'] ?? $r['timestamp'] ?? null,
                'device_id' => $r['device'] ?? null,
                'raw_data' => json_encode($r)
            ]);
            $count++;
        }
        ProcessAttendanceBatch::dispatch();
        $this->info("Imported {$count} records and dispatched processing job");
    }
}
