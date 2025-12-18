@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- TITLE -->
    <h2 class="fw-bold mb-4">
        <i class="bi bi-plus-circle me-2"></i> Tambah Izin Lembur
    </h2>

    <!-- FORM CARD -->
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h5 class="mb-0">Form Izin Lembur</h5>
        </div>

        <div class="card-body px-4 py-4">
            <form action="{{ route('overtime-permit.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- EMPLOYEE -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="bi bi-person-circle me-2"></i> Karyawan
                        </label>
                        <select name="employee_id" class="form-select form-select-lg @error('employee_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach ($employees as $employee)
                                @if($employee->employment_type === 'monthly')
                                <option value="{{ $employee->id }}" 
                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }} ({{ $employee->position->name ?? '-' }})
                                </option>
                                @endif
                            @endforeach
                        </select>
                        @error('employee_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle me-1"></i> Hanya karyawan bulanan yang bisa mengajukan izin lembur
                        </small>
                    </div>

                    <!-- DATE -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-event me-2"></i> Tanggal
                        </label>
                        <input type="date" name="date" class="form-control form-control-lg @error('date') is-invalid @enderror" 
                            value="{{ old('date') }}" required>
                        @error('date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- OVERTIME END TIME -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="bi bi-clock-history me-2"></i> Waktu Lembur Berakhir
                        </label>
                        <input type="time" name="overtime_end_time" class="form-control form-control-lg @error('overtime_end_time') is-invalid @enderror" 
                            value="{{ old('overtime_end_time', '18:00') }}" required>
                        @error('overtime_end_time')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle me-1"></i> Standar jam kerja: 16:00. Masukkan waktu setelah 16:00
                        </small>
                    </div>

                    <!-- REASON -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="bi bi-chat-left-text me-2"></i> Alasan (Opsional)
                        </label>
                        <input type="text" name="reason" class="form-control form-control-lg @error('reason') is-invalid @enderror" 
                            placeholder="Masukkan alasan lembur" value="{{ old('reason') }}">
                        @error('reason')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- BUTTON -->
                    <div class="col-12 mt-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="bi bi-check-circle me-2"></i> Simpan Izin Lembur
                            </button>
                            <a href="{{ route('overtime-permit.index') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
