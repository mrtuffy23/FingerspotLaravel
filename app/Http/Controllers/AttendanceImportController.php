<?php

namespace App\Http\Controllers;

use App\Models\AttendanceEvent;
use Illuminate\Http\Request;
use League\Csv\Reader;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\Log;

class AttendanceImportController extends Controller
{
    public function index()
    {
        return view('admin.absen.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx|max:5120',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        try {
            $records = [];

            // Check file type and read accordingly
            if ($extension === 'xlsx') {
                $records = $this->readExcelXlsxFile($path);
            } else {
                // Read CSV/TXT file with custom parser to handle duplicates
                $records = $this->readCsvFile($path);
            }

            if (empty($records)) {
                return redirect()->route('absen.import')
                    ->with('error', 'File is empty or no valid records found');
            }

            // Get first record to check available columns
            $firstRecord = reset($records);
            $availableColumns = array_keys($firstRecord);

            // Process records - generate attendance events for each scan time
            $mappedRecords = [];
            foreach ($records as $record) {
                $scans = $this->extractScans($record, $availableColumns);
                
                foreach ($scans as $scan) {
                    if ($scan['employee_pin'] && $scan['event_time']) {
                        $mappedRecords[] = $scan;
                    }
                }
            }

            if (empty($mappedRecords)) {
                $columnList = implode(', ', $availableColumns);
                return redirect()->route('absen.import')
                    ->with('error', "No valid records found. Available columns in file: $columnList. Expected: 'PIN', 'Tanggal', 'Scan 1/2/3'");
            }

            $count = 0;
            foreach ($mappedRecords as $mapped) {
                try {
                    AttendanceEvent::create([
                        'employee_pin' => $mapped['employee_pin'],
                        'event_time' => $mapped['event_time'],
                        'device_id' => $mapped['device_id'] ?? null,
                        'raw_data' => json_encode($mapped)
                    ]);
                    $count++;
                } catch (\Exception $e) {
                    // Log individual record errors but continue
                    Log::warning('Failed to import record: ' . json_encode($mapped) . ' Error: ' . $e->getMessage());
                }
            }

            if ($count === 0) {
                return redirect()->route('absen.import')
                    ->with('error', 'No records were successfully imported. Please check the file format and data.');
            }

            return redirect()->route('absen.import')
                ->with('success', "Successfully imported $count attendance records");
        } catch (\Exception $e) {
            return redirect()->route('absen.import')
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    /**
     * Extract multiple scan records from a single row
     * Generate one record per scan (Scan 1, Scan 2, Scan 3)
     */
    private function extractScans($record, $availableColumns)
    {
        $scans = [];
        
        // Get PIN
        $pin = null;
        foreach ($record as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            if (in_array($normalizedKey, ['pin', 'employee_pin', 'employee id', 'id', 'userid', 'user_id'])) {
                $pin = trim($value);
                break;
            }
        }
        
        // Get date
        $date = null;
        foreach ($record as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            if (in_array($normalizedKey, ['date', 'tanggal', 'tgl'])) {
                $date = trim($value);
                break;
            }
        }
        
        // Get all scan times (Scan 1, Scan 2, Scan 3, etc)
        $scanTimes = [];
        foreach ($record as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            $value = trim($value);
            
            // Match pattern like "Scan 1", "Scan 2", "Scan 3", or "scan 1", etc
            if (preg_match('/scan\s*(\d+)/i', $normalizedKey) && !empty($value)) {
                $scanTimes[] = $value;
            }
        }
        
        // Create one attendance record per scan time
        if ($pin && $date) {
            foreach ($scanTimes as $time) {
                $eventTime = $this->parseDateTime($date . ' ' . $time);
                if ($eventTime) {
                    $scans[] = [
                        'employee_pin' => $pin,
                        'event_time' => $eventTime,
                        'device_id' => null,
                    ];
                }
            }
        }
        
        return $scans;
    }

    /**
    private function mapRecord($record, $availableColumns)
    {
        $mapped = [
            'employee_pin' => null,
            'event_time' => null,
            'device_id' => null,
        ];

        // Normalize available columns (lowercase, trimmed)
        $normalizedMap = [];
        foreach ($availableColumns as $col) {
            $normalized = strtolower(trim($col));
            $normalizedMap[$normalized] = $col;
        }

        // Map employee_pin
        foreach ($record as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            
            if (in_array($normalizedKey, ['pin', 'employee_pin', 'employee id', 'id', 'userid', 'user_id'])) {
                $mapped['employee_pin'] = trim($value);
                break;
            }
        }

        // Map event_time (combine date + time if needed)
        $dateValue = null;
        $timeValue = null;

        // First, look for combined datetime
        foreach ($record as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            
            if (in_array($normalizedKey, ['datetime', 'event_time', 'timestamp'])) {
                $mapped['event_time'] = $this->parseDateTime($value);
                if ($mapped['event_time']) {
                    return $mapped; // Return early if found combined datetime
                }
                break;
            }
        }

        // If not found, look for separate date and time columns
        foreach ($record as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            
            if (in_array($normalizedKey, ['date', 'tanggal', 'tgl'])) {
                $dateValue = trim($value);
            }
            
            // Look for first scan time (prefer Scan 1)
            if (in_array($normalizedKey, ['scan 1', 'scan1', 'time', 'waktu', 'jam', 'jam_masuk', 'check_in'])) {
                if (!$timeValue) { // Only set if not already set
                    $timeValue = trim($value);
                }
            }
        }

        // Combine date and time
        if ($dateValue && $timeValue) {
            $combined = $dateValue . ' ' . $timeValue;
            $mapped['event_time'] = $this->parseDateTime($combined);
        }

        // Map device_id (optional)
        foreach ($record as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            
            if (in_array($normalizedKey, ['device', 'device_id', 'device_name', 'mesin', 'terminal', 'kantor'])) {
                $mapped['device_id'] = trim($value);
                break;
            }
        }

        return $mapped;
    }

    /**
     * Parse various datetime formats
     */
    private function parseDateTime($value)
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);

        // Try to parse with common formats
        $formats = [
            'Y-m-d H:i:s',      // 2026-01-21 08:30:00
            'Y-m-d h:i:s A',    // 2026-01-21 08:30:00 AM
            'd-m-Y H:i:s',      // 21-01-2026 08:30:00
            'd/m/Y H:i:s',      // 21/01/2026 08:30:00
            'd-m-Y H:i',        // 21-01-2026 08:30
            'd/m/Y H:i',        // 21/01/2026 08:30
            'Y/m/d H:i:s',      // 2026/01/21 08:30:00
            'Y-m-d',            // 2026-01-21
            'd-m-Y',            // 21-01-2026
            'd/m/Y',            // 21/01/2026
            'H:i:s',            // 08:30:00
            'H:i',              // 08:30
        ];

        foreach ($formats as $format) {
            try {
                $parsed = \DateTime::createFromFormat($format, $value);
                if ($parsed !== false) {
                    return $parsed->format('Y-m-d H:i:s');
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // If all else fails, try strtotime
        try {
            $timestamp = strtotime($value);
            if ($timestamp !== false) {
                return date('Y-m-d H:i:s', $timestamp);
            }
        } catch (\Exception $e) {
            // Do nothing
        }

        return null;
    }

    /**
     * Read Excel XLSX file using Spout library v3
     */
    private function readExcelXlsxFile($filePath)
    {
        $records = [];
        
        try {
            $reader = ReaderEntityFactory::createXLSXReader();
            $reader->open($filePath);
            
            $headerRow = null;
            $rowIndex = 0;
            
            // Iterate through sheets (usually just need first sheet)
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    // In Spout v3, row returns Row object, convert to array
                    $rowData = $row->toArray();
                    
                    // Skip empty rows
                    if (!array_filter($rowData)) {
                        continue;
                    }
                    
                    // Skip first row if it contains "Pegawai", "Data scanlog", or title-like content
                    if ($rowIndex === 0) {
                        $firstRowContent = implode('', $rowData);
                        if (preg_match('/(Pegawai|Data scanlog|Header|Judul)/i', $firstRowContent)) {
                            $rowIndex++;
                            continue;
                        }
                    }
                    
                    // First actual header row
                    if ($headerRow === null) {
                        $headerRow = $this->sanitizeHeaders($rowData);
                        $rowIndex++;
                        continue;
                    }
                    
                    // Convert row to associative array using header
                    if ($headerRow) {
                        $record = [];
                        foreach ($headerRow as $key => $header) {
                            $record[$header] = $rowData[$key] ?? '';
                        }
                        $records[] = $record;
                    }
                    
                    $rowIndex++;
                }
                // Only read first sheet
                break;
            }
            
            $reader->close();
            
        } catch (\Exception $e) {
            throw new \Exception('Error reading Excel XLSX file: ' . $e->getMessage());
        }
        
        return $records;
    }

    /**
     * Read CSV/TXT file with custom header handling
     */
    private function readCsvFile($filePath)
    {
        $records = [];
        $handle = fopen($filePath, 'r');
        
        if (!$handle) {
            throw new \Exception('Cannot open file');
        }
        
        $headerRow = null;
        $rowIndex = 0;
        
        while (($row = fgetcsv($handle)) !== false) {
            // Skip completely empty rows
            if (!array_filter($row)) {
                continue;
            }
            
            // Skip first row if it contains "Pegawai", "Data scanlog", or title-like content
            if ($rowIndex === 0) {
                $firstRowContent = implode('', $row);
                if (preg_match('/(Pegawai|Data scanlog|Header|Judul)/i', $firstRowContent)) {
                    $rowIndex++;
                    continue;
                }
            }
            
            // First actual header row
            if ($headerRow === null) {
                $headerRow = $this->sanitizeHeaders($row);
                $rowIndex++;
                continue;
            }
            
            // Create associative array from row
            if ($headerRow) {
                $record = [];
                foreach ($headerRow as $key => $header) {
                    $record[$header] = $row[$key] ?? '';
                }
                $records[] = $record;
            }
            
            $rowIndex++;
        }
        
        fclose($handle);
        return $records;
    }

    /**
     * Sanitize header row to handle duplicates and empty columns
     */
    private function sanitizeHeaders($headerRow)
    {
        $sanitized = [];
        $counts = [];
        
        foreach ($headerRow as $header) {
            $header = trim($header);
            
            // Skip completely empty headers
            if (empty($header)) {
                $header = 'empty';
            }
            
            // Track duplicate headers and make them unique
            if (!isset($counts[$header])) {
                $counts[$header] = 0;
                $sanitized[] = $header;
            } else {
                $counts[$header]++;
                $sanitized[] = $header . '_' . $counts[$header];
            }
        }
        
        return $sanitized;
    }
}
