<!-- Day headers -->
<div class="calendar-day-header">Sun</div>
<div class="calendar-day-header">Mon</div>
<div class="calendar-day-header">Tue</div>
<div class="calendar-day-header">Wed</div>
<div class="calendar-day-header">Thu</div>
<div class="calendar-day-header">Fri</div>
<div class="calendar-day-header">Sat</div>

<!-- Empty cells before month starts -->
@for ($i = 0; $i < $startWeekday; $i++)
    <div class="calendar-day empty"></div>
@endfor

<!-- Days of month -->
@for ($d = 1; $d <= $daysInMonth; $d++)
    @php
        $currentDate = sprintf("%04d-%02d-%02d", $year, $month, $d);
        $holiday = $holidays->firstWhere('date', $currentDate);
    @endphp

    <div class="calendar-day">
        <div class="date-number">{{ $d }}</div>

        @if ($holiday)
            <span class="holiday-badge">{{ ucfirst(str_replace('_', ' ', $holiday->type)) }}</span>
            <button type="button" class="btn-delete-holiday" data-date="{{ $holiday->id }}">Delete</button>
        @else
            <form action="{{ route('work-calendars.store') }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $currentDate }}">
                <input type="hidden" name="type" value="national_holiday">
                <button type="button" class="btn btn-sm btn-primary btn-set-holiday">Set Holiday</button>
            </form>
        @endif
    </div>
@endfor