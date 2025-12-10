@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">
            <i class="bi bi-pencil-square me-2"></i> Edit Kehadiran
        </h1>
        <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- ERROR ALERT -->
    @if ($errors->any())
    <div class="alert alert-danger shadow-sm">
        <strong>Terjadi Kesalahan:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- FORM CARD -->
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Form Edit Kehadiran</h5>
        </div>

        <form action="{{ route('attendance.update', $attendance) }}" method="POST" class="p-4">
            @csrf
            @method('PUT')

            <!-- ROW 1 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Karyawan</label>
                    <select class="form-select @error('employee_id') is-invalid @enderror" 
                            name="employee_id" required>
                        <option value="">Pilih Karyawan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" 
                                {{ old('employee_id', $attendance->employee_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" 
                        class="form-control @error('date') is-invalid @enderror"
                        name="date"
                        value="{{ old('date', $attendance->date?->format('Y-m-d')) }}" required>
                </div>
            </div>

            <!-- ROW 2 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Check In</label>
                    <input type="datetime-local"
                        class="form-control @error('check_in') is-invalid @enderror"
                        name="check_in"
                        value="{{ old('check_in', $attendance->check_in?->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Check Out</label>
                    <input type="datetime-local"
                        class="form-control @error('check_out') is-invalid @enderror"
                        name="check_out"
                        value="{{ old('check_out', $attendance->check_out?->format('Y-m-d\TH:i')) }}">
                </div>
            </div>

            <!-- ROW 3 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jam Kerja</label>
                    <input type="number" step="0.5"
                        class="form-control @error('work_hours') is-invalid @enderror"
                        name="work_hours"
                        value="{{ old('work_hours', $attendance->work_hours) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jam Kompensasi</label>
                    <input type="number" step="0.5"
                        class="form-control @error('compensated_hours') is-invalid @enderror"
                        name="compensated_hours"
                        value="{{ old('compensated_hours', $attendance->compensated_hours) }}">
                </div>
            </div>

            <!-- ROW 4 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                        <option value="present" {{ old('status', $attendance->status) === 'present' ? 'selected' : '' }}>Hadir</option>
                        <option value="absent" {{ old('status', $attendance->status) === 'absent' ? 'selected' : '' }}>Absen</option>
                        <option value="late" {{ old('status', $attendance->status) === 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="sick" {{ old('status', $attendance->status) === 'sick' ? 'selected' : '' }}>Sakit</option>
                        <option value="leave" {{ old('status', $attendance->status) === 'leave' ? 'selected' : '' }}>Cuti</option>
                    </select>
                </div>
            </div>

            <!-- NOTES -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea class="form-control" name="notes" rows="3">{{ old('notes', $attendance->notes) }}</textarea>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Update Kehadiran
                </button>
                <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary px-4">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
