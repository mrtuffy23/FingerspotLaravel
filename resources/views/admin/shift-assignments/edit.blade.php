@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 fw-bold mb-0">✏️ Edit Penugasan Shift</h1>
        <a href="{{ route('shift-assignments.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>⚠ Ada kesalahan:</strong>
            <ul class="mt-2 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('shift-assignments.update', $assignment->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="employee_id" class="form-label">Karyawan *</label>
                    <select name="employee_id" id="employee_id" class="form-select" required>
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id', $assignment->employee_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} ({{ $emp->pin }}) - {{ $emp->employment_type == 'monthly' ? 'Bulanan' : 'Harian' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="shift_id" class="form-label">Shift *</label>
                    <select name="shift_id" id="shift_id" class="form-select" required>
                        <option value="">-- Pilih Shift --</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('shift_id', $assignment->shift_id) == $shift->id ? 'selected' : '' }}>
                                {{ $shift->code }} - {{ $shift->start_time }} s/d {{ $shift->end_time }} 
                                (Istirahat: {{ $shift->break_minutes }} menit)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Tanggal Mulai *</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $assignment->start_date) }}" required>
                </div>

                <div class="mb-3">
                    <label for="end_date" class="form-label">Tanggal Akhir (Opsional)</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $assignment->end_date) }}">
                    <small class="text-muted d-block mt-1">Kosongkan jika penugasan berlaku terus menerus</small>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                    <a href="{{ route('shift-assignments.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
