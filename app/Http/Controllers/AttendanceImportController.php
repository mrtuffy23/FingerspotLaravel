<?php

namespace App\Http\Controllers;

use App\Models\AttendanceEvent;
use Illuminate\Http\Request;
use League\Csv\Reader;

class AttendanceImportController extends Controller
{
    public function index()
    {
        return view('admin.absen.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        try {
            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();

            $count = 0;
            foreach ($records as $record) {
                AttendanceEvent::create([
                    'employee_pin' => $record['pin'] ?? $record['PIN'] ?? null,
                    'event_time' => $record['datetime'] ?? $record['timestamp'] ?? null,
                    'device_id' => $record['device'] ?? null,
                    'raw_data' => json_encode($record)
                ]);
                $count++;
            }

            return redirect()->route('absen.import')
                ->with('success', "Successfully imported $count attendance records");
        } catch (\Exception $e) {
            return redirect()->route('absen.import')
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
