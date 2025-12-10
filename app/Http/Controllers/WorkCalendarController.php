<?php
namespace App\Http\Controllers;

use App\Models\WorkCalendar;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class WorkCalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year',  now()->year);

        $date = Carbon::create($year, $month, 1);

        $startDate = $date->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $endDate   = $date->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $days = [];
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $day) {
            $days[] = [
                'date'       => $day,
                'in_month'   => $day->month == $month,
                'is_weekend' => $day->isWeekend(),
            ];
        }

        $records = WorkCalendar::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy(fn($r) => $r->date->format('Y-m-d'));

        $monthName = $date->format('F');
        $prevMonth = $date->copy()->subMonth()->month;
        $prevYear  = $date->copy()->subMonth()->year;
        $nextMonth = $date->copy()->addMonth()->month;
        $nextYear  = $date->copy()->addMonth()->year;
        $startWeekday = $startDate->dayOfWeek;
        $daysInMonth = $date->daysInMonth;

        $data = [
            'date'         => $date,
            'monthName'    => $monthName,
            'year'         => $year,
            'month'        => $month,
            'prevMonth'    => $prevMonth,
            'prevYear'     => $prevYear,
            'nextMonth'    => $nextMonth,
            'nextYear'     => $nextYear,
            'days'         => $days,
            'records'      => $records,
            'startWeekday' => $startWeekday,
            'daysInMonth'  => $daysInMonth,
            'holidays'     => $records,
        ];

        // Jika request AJAX, return partial view
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('work_calendars._calendar', $data)->render();
        }

        // Full page view
        return view('work_calendars.index', $data);
    }

    public function create(Request $request)
    {
        $date = $request->get('date');
        return view('work_calendars.form', [
            'record' => null,
            'date'   => $date
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'        => 'required|date|unique:work_calendars,date',
            'type'        => 'required|in:workday,national_holiday,collective_leave,weekend',
        ]);

        WorkCalendar::create([
            'date'        => $request->date,
            'type'        => $request->type,
            'description' => $request->type,
        ]);

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true]);
        }

        return redirect()->route('work-calendars.index')
            ->with('success', 'Holiday saved successfully');
    }

    public function edit(WorkCalendar $workCalendar)
    {
        return view('work_calendars.form', [
            'record' => $workCalendar,
            'date'   => $workCalendar->date->format('Y-m-d')
        ]);
    }

    public function update(Request $request, WorkCalendar $workCalendar)
    {
        $request->validate([
            'type' => 'required|in:workday,national_holiday,collective_leave,weekend',
        ]);

        $workCalendar->update([
            'type'        => $request->type,
            'description' => $request->type,
        ]);

        return redirect()->route('work-calendars.index')
            ->with('success', 'Holiday updated successfully');
    }

    public function destroy(WorkCalendar $workCalendar)
    {
        $workCalendar->delete();

        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true]);
        }

        return redirect()->route('work-calendars.index')
            ->with('success', 'Holiday deleted successfully');
    }
}