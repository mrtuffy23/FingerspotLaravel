@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Title -->
    <div class="d-flex align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0">Tambah Karyawan</h1>
            <span class="text-muted">Lengkapi data berikut untuk menambahkan karyawan baru</span>
        </div>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <h5 class="fw-bold"><i class="bi bi-exclamation-triangle"></i> Ada kesalahan input</h5>
            <ul class="mb-0 ms-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Card Form -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="bi bi-person-plus"></i> Formulir Karyawan Baru</h5>
        </div>

        <form action="{{ route('karyawan.store') }}" method="POST" class="p-4">
            @csrf

            <!-- Basic Info -->
            <h5 class="fw-bold mb-3 text-primary">1. Informasi Dasar</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">PIN</label>
                    <input type="text" class="form-control @error('pin') is-invalid @enderror"
                           name="pin" value="{{ old('pin') }}" placeholder="Masukkan PIN" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIK</label>
                    <input type="text" class="form-control @error('nik') is-invalid @enderror"
                           name="nik" value="{{ old('nik') }}" placeholder="Nomor Induk Karyawan" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" placeholder="Nama lengkap..." required>
            </div>

            <!-- Birth Info -->
            <h5 class="fw-bold text-primary mt-4 mb-3">2. Informasi Lahir</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control @error('birth_place') is-invalid @enderror"
                           name="birth_place" value="{{ old('birth_place') }}" placeholder="Contoh: Surabaya">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                           name="birth_date" value="{{ old('birth_date') }}">
                </div>
            </div>

            <!-- Job Info -->
            <h5 class="fw-bold text-primary mt-4 mb-3">3. Informasi Pekerjaan</h5>
            
            <!-- Employment Type -->
            <div class="mb-3">
                <label class="form-label">Jenis Karyawan</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="employment_type" 
                           id="monthly" value="monthly" 
                           {{ old('employment_type', 'monthly') === 'monthly' ? 'checked' : '' }}>
                    <label class="form-check-label" for="monthly">
                        <strong>Karyawan Bulanan</strong> - Jam kerja 08:00 - 16:00
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="employment_type" 
                           id="daily" value="daily" 
                           {{ old('employment_type') === 'daily' ? 'checked' : '' }}>
                    <label class="form-check-label" for="daily">
                        <strong>Karyawan Harian</strong> - Tiga shift (07:00-15:00, 15:00-23:00, 23:00-07:00)
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Posisi</label>
                    <select class="form-select @error('position_id') is-invalid @enderror"
                            name="position_id" required>
                        <option value="">Pilih Posisi</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" 
                                {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                {{ $position->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Departemen</label>
                    <select class="form-select @error('department_id') is-invalid @enderror"
                            name="department_id" required>
                        <option value="">Pilih Departemen</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" 
                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Employment Status -->
            <h5 class="fw-bold text-primary mt-4 mb-3">4. Status Kepegawaian</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="kontrak" {{ old('status') === 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="resign" {{ old('status') === 'resign' ? 'selected' : '' }}>Resign</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun Bergabung</label>
                    <input type="number" class="form-control @error('join_year') is-invalid @enderror"
                           name="join_year" value="{{ old('join_year') }}" placeholder="Contoh: 2024">
                </div>
            </div>

            <!-- Salary -->
            <h5 class="fw-bold text-primary mt-4 mb-3">5. Penghasilan</h5>
            <div class="mb-3">
                <label class="form-label">UMK</label>
                <input type="number" step="0.01" class="form-control @error('umk') is-invalid @enderror"
                       name="umk" value="{{ old('umk') }}" placeholder="Masukkan UMK">
            </div>

            <!-- Photo -->
            <h5 class="fw-bold text-primary mt-4 mb-3">6. Foto Profil</h5>
            <div class="mb-3">
                <label class="form-label">Foto Karyawan</label>
                <input type="file" class="form-control @error('photo') is-invalid @enderror"
                       name="photo" accept="image/*" placeholder="Upload foto profil">
                <small class="text-muted">Format: JPG, PNG, GIF (Max 2MB)</small>
            </div>

            <!-- Buttons -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle"></i> Simpan
                </button>
                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
