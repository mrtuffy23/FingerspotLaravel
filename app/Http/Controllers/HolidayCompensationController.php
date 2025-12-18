<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\WorkCalendar;
use App\Services\HolidayCompensationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HolidayCompensationController extends Controller
{
    /**
     * Tampilkan laporan karyawan yang masuk pada hari libur
     */
    public function holidayAttendanceReport(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Get report
        $report = HolidayCompensationService::getHolidayAttendanceReport($month, $year);

        // Calculate totals
        $totalEmployees = count($report);
        $totalHolidayDays = array_sum(array_column($report, 'holiday_count'));
        $totalWorkHours = array_sum(array_column($report, 'total_work_hours'));
        $totalCompensation = array_sum(array_column($report, 'total_compensated_hours'));

        return view('admin.holiday-compensation.report', [
            'report' => $report,
            'month' => $month,
            'year' => $year,
            'totals' => [
                'employees' => $totalEmployees,
                'holiday_days' => $totalHolidayDays,
                'work_hours' => $totalWorkHours,
                'compensated_hours' => $totalCompensation,
            ]
        ]);
    }

    /**
     * Tampilkan daftar hari libur
     */
    public function holidayList(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->clone()->endOfMonth();

        $holidays = WorkCalendar::whereBetween('date', [
            $startDate->toDateString(),
            $endDate->toDateString()
        ])
        ->where('type', '!=', 'workday')
        ->orderBy('date')
        ->get();

        // Add weekend days
        for ($date = $startDate->clone(); $date <= $endDate; $date->addDay()) {
            if ($date->isSunday()) {
                $exists = $holidays->where('date', $date->toDateString())->first();
                if (!$exists) {
                    $holidays->push((object)[
                        'date' => $date->toDateString(),
                        'description' => 'Hari Minggu',
                        'type' => 'weekend',
                    ]);
                }
            }
        }

        return view('admin.holiday-compensation.holiday-list', [
            'holidays' => $holidays->sortBy('date'),
            'month' => $month,
            'year' => $year,
        ]);
    }

    /**
     * Proses kompensasi untuk periode tertentu
     */
    public function processCompensation(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $result = HolidayCompensationService::processHolidayCompensationForPeriod(
            $validated['start_date'],
            $validated['end_date']
        );

        return redirect()->back()->with('success', 
            "Proses kompensasi selesai. Total {$result['total_processed']} attendance diproses."
        );
    }

    /**
     * Tampilkan detail kompensasi untuk satu karyawan
     */
    public function employeeCompensationDetail($employeeId, Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('work_hours', '>', 0)
            ->with('employee')
            ->orderBy('date')
            ->get();

        $holidays = $attendances->filter(function ($attendance) {
            return HolidayCompensationService::isHoliday(Carbon::parse($attendance->date));
        })->map(function ($attendance) {
            $date = Carbon::parse($attendance->date);
            $holidayInfo = HolidayCompensationService::getHolidayInfo($date);

            return [
                'date' => $attendance->date,
                'day_name' => $date->translatedFormat('l'),
                'holiday_description' => $holidayInfo['description'] ?? 'Hari Minggu',
                'work_hours' => $attendance->work_hours,
                'compensated_hours' => $attendance->compensated_hours ?? 0,
                'compensation_ratio' => $attendance->compensated_hours ? 
                    round($attendance->compensated_hours / $attendance->work_hours, 2) : 0,
                'status' => $attendance->status,
                'note' => $attendance->note,
            ];
        });

        $totalWorkHours = $holidays->sum('work_hours');
        $totalCompensation = $holidays->sum('compensated_hours');

        return view('admin.holiday-compensation.employee-detail', [
            'employee' => $attendances->first()->employee ?? null,
            'month' => $month,
            'year' => $year,
            'holidays' => $holidays,
            'totals' => [
                'holiday_days' => count($holidays),
                'work_hours' => round($totalWorkHours, 2),
                'compensated_hours' => round($totalCompensation, 2),
            ]
        ]);
    }

    /**
     * Export laporan kompensasi hari libur ke Excel/CSV
     */
    public function exportReport(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $report = HolidayCompensationService::getHolidayAttendanceReport($month, $year);

        // Create CSV content
        $csv = "Laporan Kompensasi Hari Libur - {$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "\n\n";
        $csv .= "Karyawan,Departemen,Jumlah Hari Libur,Total Jam Kerja,Total Kompensasi\n";

        foreach ($report as $row) {
            $csv .= "{$row['employee_name']},{$row['department']},{$row['holiday_count']},";
            $csv .= "{$row['total_work_hours']},{$row['total_compensated_hours']}\n";
        }

        return response()->streamDownload(
            fn() => print($csv),
            "holiday-compensation-{$year}-{$month}.csv"
        );
    }
}
