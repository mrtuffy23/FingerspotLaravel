@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ $record ? 'Edit Hari Libur' : 'Tambah Hari Libur' }}
    </div>
    <div class="card-body">

        <form method="POST" action="{{ $record ? route('work-calendars.update', $record) : route('work-calendars.store') }}">
            @csrf
            @if($record)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" name="date" value="{{ $date }}" {{ $record ? 'readonly' : '' }}>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <input type="text" class="form-control" name="description" value="{{ $record->description ?? '' }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Tipe Hari Libur</label>
                <select class="form-control" name="type">
                    <option value="workday" {{ (optional($record)->type=='workday')?'selected':'' }}>Hari Kerja Normal</option>
                    <option value="national_holiday" {{ (optional($record)->type=='national_holiday')?'selected':'' }}>Hari Nasional</option>
                    <option value="collective_leave" {{ (optional($record)->type=='collective_leave')?'selected':'' }}>Cuti Bersama</option>
                    <option value="weekend" {{ (optional($record)->type=='weekend')?'selected':'' }}>Hari Minggu</option>
                </select>
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>

    </div>
</div>
@endsection
