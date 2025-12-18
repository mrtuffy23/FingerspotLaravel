@extends('layouts.admin')

@section('content')

<div class="calendar-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="margin: 0;">{{ $monthName }} {{ $year }}</h2>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('work-calendars.index', ['month' => $prevMonth, 'year' => $prevYear]) }}" class="btn btn-sm btn-secondary">
                ← Bulan Lalu
            </a>
            <a href="{{ route('work-calendars.index', ['month' => $nextMonth, 'year' => $nextYear]) }}" class="btn btn-sm btn-secondary">
                Bulan Depan →
            </a>
            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
                + Tambah Hari Libur
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="calendar">
        <!-- Day headers -->
        <div class="calendar-day-header">Minggu</div>
        <div class="calendar-day-header">Senin</div>
        <div class="calendar-day-header">Selasa</div>
        <div class="calendar-day-header">Rabu</div>
        <div class="calendar-day-header">Kamis</div>
        <div class="calendar-day-header">Jumat</div>
        <div class="calendar-day-header">Sabtu</div>

        <!-- Days of month -->
        @foreach($days as $day)
            @php
                $dateStr = $day['date']->format('Y-m-d');
                $holiday = $records->get($dateStr);
                $dayNum = $day['date']->day;
            @endphp

            <div class="calendar-day {{ !$day['in_month'] ? 'empty' : '' }}">
                @if($day['in_month'])
                    <div class="date-number">{{ $dayNum }}</div>

                    @if($holiday)
                        @php
                            $typeLabel = [
                                'national_holiday' => 'Hari Nasional',
                                'collective_leave' => 'Cuti Bersama',
                                'weekend' => 'Hari Minggu',
                                'workday' => 'Hari Kerja'
                            ][$holiday->type] ?? $holiday->type;
                        @endphp
                        <span class="holiday-badge">
                            {{ $typeLabel }}
                        </span>
                        <div style="font-size: 11px; color: #666; margin: 4px 0; margin-top: auto;">
                            {{ $holiday->description }}
                        </div>
                        <form action="{{ route('work-calendars.destroy', $holiday->id) }}" method="POST" style="margin-top: 8px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-set-holiday" onclick="return confirm('Hapus hari libur ini?')">
                                Hapus
                            </button>
                        </form>
                    @else
                        <form action="{{ route('work-calendars.store') }}" method="POST" style="margin-top: auto;">
                            @csrf
                            <input type="hidden" name="date" value="{{ $dateStr }}">
                            <input type="hidden" name="type" value="national_holiday">
                            <input type="hidden" name="description" value="Hari Libur">
                            <button type="submit" class="btn btn-sm btn-primary btn-set-holiday">
                                Set Libur
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        @endforeach
    </div>

    <!-- Legend -->
    <div style="display: flex; gap: 20px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <div style="width: 20px; height: 20px; background: #dc3545; border-radius: 3px;"></div>
            <span>Hari Libur</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <div style="width: 20px; height: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 3px;"></div>
            <span>Bulan Lain</span>
        </div>
    </div>
</div>

<!-- Modal Tambah Hari Libur -->
<div class="modal fade" id="addHolidayModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Hari Libur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('work-calendars.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="date">Tanggal</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" id="date" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="description">Keterangan</label>
                        <input type="text" class="form-control" name="description" id="description" placeholder="Misal: Hari Raya Idul Fitri" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="type">Tipe Hari Libur</label>
                        <select class="form-control" name="type" id="type" required>
                            <option value="national_holiday">Libur Nasional</option>
                            <option value="collective_leave">Cuti Bersama</option>
                            <option value="workday">Hari Kerja Normal</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection