@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Permintaan Cuti Baru</h2>
            <p class="text-muted mb-0">Isi form berikut untuk mengajukan permintaan cuti karyawan.</p>
        </div>
        <a href="{{ route('leave.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Error -->
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('leave.store') }}" method="POST" class="card shadow-sm p-4 rounded-3 border-0">
        @csrf

        <div class="row g-3">

            <!-- Karyawan -->
            <div class="col-md-6">
                <label for="employee_id" class="form-label fw-semibold">Karyawan</label>
                <select class="form-select @error('employee_id') is-invalid @enderror"
                        id="employee_id" name="employee_id" required>
                    <option value="">— Pilih Karyawan —</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Jenis Cuti -->
            <div class="col-md-6">
                <label for="type" class="form-label fw-semibold">Jenis Cuti</label>
                <select class="form-select @error('type') is-invalid @enderror"
                        id="type" name="type" required>
                    <option value="">— Pilih Jenis Cuti —</option>
                    <option value="izin" {{ old('type') === 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ old('type') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="sakit_sabtu" {{ old('type') === 'sakit_sabtu' ? 'selected' : '' }}>Sakit Sabtu</option>
                    <option value="kecelakaan" {{ old('type') === 'kecelakaan' ? 'selected' : '' }}>Kecelakaan</option>
                    <option value="cuti" {{ old('type') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                    <option value="izin_keluar" {{ old('type') === 'izin_keluar' ? 'selected' : '' }}>Izin Keluar</option>
                    <option value="libur" {{ old('type') === 'libur' ? 'selected' : '' }}>Libur</option>
                </select>
            </div>

            <!-- Tanggal Mulai -->
            <div class="col-md-6">
                <label for="start_date" class="form-label fw-semibold">Tanggal Mulai</label>
                <input type="date" 
                       class="form-control @error('start_date') is-invalid @enderror"
                       id="start_date" name="start_date" value="{{ old('start_date') }}" required>
            </div>

            <!-- Tanggal Selesai -->
            <div class="col-md-6">
                <label for="end_date" class="form-label fw-semibold">Tanggal Selesai</label>
                <input type="date" 
                       class="form-control @error('end_date') is-invalid @enderror"
                       id="end_date" name="end_date" value="{{ old('end_date') }}" required>
            </div>

            <!-- Alasan -->
            <div class="col-12">
                <label for="reason" class="form-label fw-semibold">Alasan</label>
                <textarea class="form-control" id="reason" name="reason" rows="3" 
                          placeholder="Contoh: Keperluan keluarga mendesak, izin sakit, atau lainnya...">
                    {{ old('reason') }}
                </textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-circle"></i> Kirim Permintaan
            </button>
            <a href="{{ route('leave.index') }}" class="btn btn-light border px-4">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection
