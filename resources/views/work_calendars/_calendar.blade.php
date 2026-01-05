<!-- Day headers -->
<div class="calendar">
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
                <form action="{{ route('work-calendars.destroy', $holiday->id) }}" method="POST" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger btn-set-holiday" onclick="return confirm('Hapus hari libur ini?')">Hapus</button>
                </form>
            @else
                <form action="{{ route('work-calendars.store') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="date" value="{{ $currentDate }}">
                    <input type="hidden" name="type" value="national_holiday">
                    <input type="hidden" name="description" value="Hari Libur">
                    <button type="submit" class="btn btn-sm btn-primary btn-set-holiday">Set Libur</button>
                </form>
            @endif
        </div>
    @endfor
</div>