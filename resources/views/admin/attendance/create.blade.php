@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h1 class="mb-4 fw-bold">
        <i class="bi bi-calendar-plus me-2"></i> Tambah Absensi
    </h1>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded-3">
            <strong>Terjadi Kesalahan:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('attendance.store') }}" method="POST" 
          class="card p-4 shadow-lg border-0 rounded-4">

        @csrf

        <h5 class="fw-bold mb-3">
            <i class="bi bi-person-badge me-2"></i> Informasi Karyawan
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-person-circle me-1"></i> Karyawan
                </label>
                <select class="form-select @error('employee_id') is-invalid @enderror" 
                        name="employee_id" required>
                    <option value="">Pilih Karyawan</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-calendar-event me-1"></i> Tanggal
                </label>
                <input type="date" 
                       class="form-control @error('date') is-invalid @enderror" 
                       name="date" value="{{ old('date') }}" required>
            </div>
        </div>

        <hr>

        <h5 class="fw-bold mb-3">
            <i class="bi bi-alarm me-2"></i> Waktu Kehadiran
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-clock me-1"></i> Waktu Masuk
                </label>
                <input type="datetime-local" 
                       class="form-control @error('check_in') is-invalid @enderror" 
                       name="check_in" value="{{ old('check_in') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-clock-history me-1"></i> Waktu Keluar
                </label>
                <input type="datetime-local" 
                       class="form-control @error('check_out') is-invalid @enderror" 
                       name="check_out" value="{{ old('check_out') }}">
            </div>
        </div>

        <hr>

        <h5 class="fw-bold mb-3">
            <i class="bi bi-hourglass-split me-2"></i> Jam Kerja
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-hourglass me-1"></i> Jam Kerja
                </label>
                <input type="number" step="0.5" 
                       class="form-control @error('work_hours') is-invalid @enderror" 
                       name="work_hours" value="{{ old('work_hours') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-plus-circle-dotted me-1"></i> Jam Kompensasi
                </label>
                <input type="number" step="0.5" 
                       class="form-control @error('compensated_hours') is-invalid @enderror" 
                       name="compensated_hours" value="{{ old('compensated_hours') }}">
            </div>
        </div>

        <hr>

        <h5 class="fw-bold mb-3">
            <i class="bi bi-flag-fill me-2"></i> Status Kehadiran
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-clipboard-check me-1"></i> Status
                </label>
                <select class="form-select @error('status') is-invalid @enderror" 
                        name="status" required>
                    <option value="">Pilih Status</option>
                    <option value="present" {{ old('status') === 'present' ? 'selected' : '' }}>Hadir</option>
                    <option value="absent" {{ old('status') === 'absent' ? 'selected' : '' }}>Absen</option>
                    <option value="late"   {{ old('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                    <option value="sick"   {{ old('status') === 'sick' ? 'selected' : '' }}>Sakit</option>
                    <option value="leave"  {{ old('status') === 'leave' ? 'selected' : '' }}>Cuti</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">
                <i class="bi bi-journal-text me-1"></i> Catatan
            </label>
            <textarea class="form-control" name="notes" rows="3">{{ old('notes') }}</textarea>
        </div>

        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i> Simpan
            </button>
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary px-4">
                <i class="bi bi-x-circle me-1"></i> Batal
            </a>
        </div>

    </form>
</div>
@endsection
