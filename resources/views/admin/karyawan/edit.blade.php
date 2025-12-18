@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Title -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="fw-bold">
            <i class="bi bi-person-lines-fill me-2"></i> Edit Employee
        </h1>

        <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Back
        </a>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <h5 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Validation Errors</h5>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Card -->
    <form action="{{ route('karyawan.update', $employee) }}" method="POST" 
          class="card border-0 shadow-lg p-4 rounded-4">

        @csrf
        @method('PUT')

        <h4 class="fw-bold mb-3 text-primary">
            <i class="bi bi-info-circle me-1"></i> Basic Information
        </h4>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="pin" class="form-label fw-semibold">PIN</label>
                <input type="text" class="form-control form-control-lg @error('pin') is-invalid @enderror"
                       id="pin" name="pin" value="{{ old('pin', $employee->pin) }}" placeholder="Employee PIN" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="nik" class="form-label fw-semibold">NIK</label>
                <input type="text" class="form-control form-control-lg @error('nik') is-invalid @enderror"
                       id="nik" name="nik" value="{{ old('nik', $employee->nik) }}" placeholder="National ID" required>
            </div>
        </div>

        <div class="mb-4">
            <label for="name" class="form-label fw-semibold">Full Name</label>
            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name', $employee->name) }}" placeholder="Employee Name" required>
        </div>

        <hr class="my-4">

        <h4 class="fw-bold mb-3 text-primary">
            <i class="bi bi-calendar-date me-1"></i> Birth Information
        </h4>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="birth_place" class="form-label fw-semibold">Birth Place</label>
                <input type="text" class="form-control form-control-lg @error('birth_place') is-invalid @enderror"
                       id="birth_place" name="birth_place" 
                       value="{{ old('birth_place', $employee->birth_place) }}" placeholder="City of Birth">
            </div>

            <div class="col-md-6 mb-3">
                <label for="birth_date" class="form-label fw-semibold">Birth Date</label>
                <input type="date" class="form-control form-control-lg @error('birth_date') is-invalid @enderror"
                       id="birth_date" name="birth_date" 
                       value="{{ old('birth_date', is_string($employee->birth_date) ? $employee->birth_date : ($employee->birth_date ? $employee->birth_date->format('Y-m-d') : '')) }}">
            </div>
        </div>

        <hr class="my-4">

        <h4 class="fw-bold mb-3 text-primary">
            <i class="bi bi-briefcase me-1"></i> Job & Status
        </h4>

        <!-- Employment Type -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Jenis Karyawan</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="employment_type" 
                       id="monthly" value="monthly" 
                       {{ old('employment_type', $employee->employment_type) === 'monthly' ? 'checked' : '' }}>
                <label class="form-check-label" for="monthly">
                    <strong>Karyawan Bulanan</strong> - Jam kerja 08:00 - 16:00
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="employment_type" 
                       id="daily" value="daily" 
                       {{ old('employment_type', $employee->employment_type) === 'daily' ? 'checked' : '' }}>
                <label class="form-check-label" for="daily">
                    <strong>Karyawan Harian</strong> - Tiga shift (07:00-15:00, 15:00-23:00, 23:00-07:00)
                </label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="position_id" class="form-label fw-semibold">Posisi</label>
                <select class="form-select form-select-lg @error('position_id') is-invalid @enderror"
                        id="position_id" name="position_id" required>
                    <option value="">Pilih Posisi</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}"
                            {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                            {{ $position->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="department_id" class="form-label fw-semibold">Departemen</label>
                <select class="form-select form-select-lg @error('department_id') is-invalid @enderror"
                        id="department_id" name="department_id" required>
                    <option value="">Pilih Departemen</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label fw-semibold">Status</label>
                <select class="form-select form-select-lg @error('status') is-invalid @enderror"
                        id="status" name="status" required>
                    <option value="aktif" {{ old('status', $employee->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $employee->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="kontrak" {{ old('status', $employee->status) === 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                    <option value="resign" {{ old('status', $employee->status) === 'resign' ? 'selected' : '' }}>Resign</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="join_year" class="form-label fw-semibold">Join Year</label>
                <input type="number" class="form-control form-control-lg @error('join_year') is-invalid @enderror"
                       id="join_year" name="join_year" value="{{ old('join_year', $employee->join_year) }}">
            </div>
        </div>

        <div class="mb-4">
            <label for="umk" class="form-label fw-semibold">UMK (Monthly Salary)</label>
            <input type="number" step="0.01" class="form-control form-control-lg @error('umk') is-invalid @enderror"
                   id="umk" name="umk" value="{{ old('umk', $employee->umk) }}" placeholder="Gaji Bulanan">
        </div>

        <div class="mb-4">
            <label for="photo" class="form-label fw-semibold">Foto Profil</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="file" class="form-control form-control-lg @error('photo') is-invalid @enderror"
                           id="photo" name="photo" accept="image/*" placeholder="Upload foto profil">
                    <small class="text-muted">Format: JPG, PNG, GIF (Max 2MB)</small>
                </div>
                <div class="col-md-6">
                    @if($employee->photo)
                        <div class="alert alert-info">
                            <strong>Foto Saat Ini:</strong><br>
                            <img src="{{ asset('storage/' . $employee->photo) }}" alt="Photo" class="img-thumbnail" width="100">
                        </div>
                    @else
                        <div class="alert alert-warning">Belum ada foto profil</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-4">
                <i class="bi bi-save me-1"></i> Update Employee
            </button>
            <a href="{{ route('karyawan.index') }}" class="btn btn-light btn-lg px-4 border">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
