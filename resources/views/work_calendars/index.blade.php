@extends('layouts.admin')

@push('styles')
<style>
    .calendar-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-top: 20px;
    }

    .calendar-header {
        display: flex !important;
        gap: 15px !important;
        margin-bottom: 20px !important;
    }

    .calendar-header a {
        padding: 8px 16px !important;
        background: #007bff !important;
        color: white !important;
        text-decoration: none !important;
        border-radius: 4px !important;
        display: inline-block !important;
        cursor: pointer;
    }

    .calendar-header a:hover {
        background: #0056b3 !important;
    }

    .calendar {
        display: grid !important;
        grid-template-columns: repeat(7, 1fr) !important;
        gap: 10px !important;
        width: 100% !important;
    }

    .calendar-day-header {
        text-align: center !important;
        font-weight: bold !important;
        padding: 12px !important;
        background: #f0f0f0 !important;
        border-radius: 4px !important;
        border: 1px solid #ddd !important;
    }

    .calendar-day {
        border: 1px solid #ddd !important;
        padding: 10px !important;
        border-radius: 6px !important;
        background: white !important;
        min-height: 110px !important;
        display: flex !important;
        flex-direction: column !important;
        transition: all 0.2s;
    }

    .calendar-day:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .calendar-day.empty {
        background: #f9f9f9 !important;
        border-color: #e0e0e0 !important;
    }

    .calendar-day .date-number {
        font-weight: bold !important;
        font-size: 16px !important;
        margin-bottom: 10px !important;
    }

    .holiday-badge {
        background: #dc3545 !important;
        color: white !important;
        padding: 4px 8px !important;
        font-size: 11px !important;
        border-radius: 3px !important;
        display: inline-block !important;
        text-transform: uppercase !important;
        margin-top: auto !important;
        margin-bottom: 5px !important;
    }

    .btn-set-holiday {
        margin-top: auto !important;
        padding: 6px 10px !important;
        font-size: 12px !important;
        width: 100% !important;
        border: none !important;
        cursor: pointer !important;
    }

    .btn-delete-holiday {
        background: #dc3545 !important;
        color: white !important;
        padding: 4px 8px !important;
        font-size: 11px !important;
        border: none !important;
        border-radius: 3px !important;
        cursor: pointer !important;
        width: 100% !important;
    }

    .btn-delete-holiday:hover {
        background: #c82333 !important;
    }

    h2 {
        margin-bottom: 10px !important;
    }

    .alert {
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')

<div class="calendar-container">
    <h2>{{ $monthName }} {{ $year }}</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="calendar-header">
        <a href="{{ route('work-calendars.index', ['month' => $prevMonth, 'year' => $prevYear]) }}">« Previous</a>
        <a href="{{ route('work-calendars.index', ['month' => $nextMonth, 'year' => $nextYear]) }}">Next »</a>
    </div>

    <div class="calendar">
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
                    <form action="{{ route('work-calendars.destroy', $holiday->id) }}" method="POST" style="width: 100%;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete-holiday" onclick="return confirm('Delete this holiday?')">Delete</button>
                    </form>
                @else
                    <form action="{{ route('work-calendars.store') }}" method="POST" style="width: 100%; margin-top: auto;">
                        @csrf
                        <input type="hidden" name="date" value="{{ $currentDate }}">
                        <input type="hidden" name="type" value="national_holiday">
                        <button type="submit" class="btn btn-sm btn-primary btn-set-holiday">Set Holiday</button>
                    </form>
                @endif
            </div>
        @endfor
    </div>
</div>

@endsection