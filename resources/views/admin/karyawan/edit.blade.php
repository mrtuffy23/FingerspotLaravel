@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('karyawan.index') }}" class="text-decoration-none">Karyawan</a></li>
                            <li class="breadcrumb-item active">Edit Karyawan</li>
                        </ol>
                    </nav>
                    <h1 class="h3 fw-bold text-dark mb-0">
                        <i class="bi bi-pencil-square text-primary me-2"></i>Edit Data Karyawan
                    </h1>
                    <p class="text-muted mt-1 mb-0">Perbarui informasi karyawan: <strong>{{ $employee->name }}</strong></p>
                </div>
                <div>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div class="flex-grow-1">
                    <h6 class="alert-heading fw-bold mb-2">Terdapat Kesalahan Input!</h6>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Form -->
    <form action="{{ route('karyawan.update', $employee) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                
                <!-- Section 1: Informasi Dasar -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                <i class="bi bi-person-circle text-primary fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Informasi Dasar</h5>
                                <small class="text-muted">Data identitas karyawan</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">PIN <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-shield-lock"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 @error('pin') is-invalid @enderror"
                                           name="pin" value="{{ old('pin', $employee->pin) }}" placeholder="Masukkan PIN" required>
                                </div>
                                <small class="form-text text-muted">PIN untuk sistem absensi</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-card-text"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 @error('nik') is-invalid @enderror"
                                           name="nik" value="{{ old('nik', $employee->nik) }}" placeholder="Nomor Induk Karyawan" required>
                                </div>
                                <small class="form-text text-muted">Nomor identitas unik karyawan</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror"
                                           name="name" value="{{ old('name', $employee->name) }}" placeholder="Nama lengkap sesuai KTP" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Informasi Lahir -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-3 p-2 me-3">
                                <i class="bi bi-calendar-event text-success fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Informasi Kelahiran</h5>
                                <small class="text-muted">Data tempat dan tanggal lahir</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tempat Lahir</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-geo-alt"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 @error('birth_place') is-invalid @enderror"
                                           name="birth_place" value="{{ old('birth_place', $employee->birth_place) }}" placeholder="Contoh: Yogyakarta">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Lahir</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                    <input type="date" class="form-control border-start-0 @error('birth_date') is-invalid @enderror"
                                           name="birth_date" value="{{ old('birth_date', is_string($employee->birth_date) ? $employee->birth_date : ($employee->birth_date ? $employee->birth_date->format('Y-m-d') : '')) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Informasi Pekerjaan -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-3 p-2 me-3">
                                <i class="bi bi-briefcase-fill text-info fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Informasi Pekerjaan</h5>
                                <small class="text-muted">Detail posisi dan departemen</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Employment Type -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">Jenis Karyawan <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-check-custom border rounded-3 p-3 h-100 {{ old('employment_type', $employee->employment_type) === 'monthly' ? 'border-primary bg-primary bg-opacity-10' : '' }}">
                                        <input class="form-check-input" type="radio" name="employment_type" 
                                               id="monthly" value="monthly" 
                                               {{ old('employment_type', $employee->employment_type) === 'monthly' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100 cursor-pointer" for="monthly">
                                            <div class="d-flex align-items-start">
                                                <i class="bi bi-calendar-month text-primary fs-4 me-2"></i>
                                                <div>
                                                    <strong class="d-block">Karyawan Bulanan</strong>
                                                    <small class="text-muted">Jam kerja reguler 08:00 - 16:00</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-custom border rounded-3 p-3 h-100 {{ old('employment_type', $employee->employment_type) === 'daily' ? 'border-warning bg-warning bg-opacity-10' : '' }}">
                                        <input class="form-check-input" type="radio" name="employment_type" 
                                               id="daily" value="daily" 
                                               {{ old('employment_type', $employee->employment_type) === 'daily' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100 cursor-pointer" for="daily">
                                            <div class="d-flex align-items-start">
                                                <i class="bi bi-clock-history text-warning fs-4 me-2"></i>
                                                <div>
                                                    <strong class="d-block">Karyawan Harian</strong>
                                                    <small class="text-muted">Sistem shift (07:00-15:00, 15:00-23:00, 23:00-07:00)</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Posisi <span class="text-danger">*</span></label>
                                <select class="form-select @error('position_id') is-invalid @enderror"
                                        name="position_id" required>
                                    <option value="">Pilih Posisi</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->id }}" 
                                            {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                            {{ $position->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Departemen <span class="text-danger">*</span></label>
                                <select class="form-select @error('department_id') is-invalid @enderror"
                                        name="department_id" required>
                                    <option value="">Pilih Departemen</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" 
                                            {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Golongan <span class="text-danger">*</span></label>
                                <select class="form-select @error('classification_id') is-invalid @enderror"
                                        name="classification_id" required>
                                    <option value="">Pilih Golongan</option>
                                    @foreach($classifications as $classification)
                                        <option value="{{ $classification->id }}"
                                            {{ old('classification_id', $employee->classification_id) == $classification->id ? 'selected' : '' }}>
                                            {{ $classification->name }} ({{ $classification->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Status Kepegawaian -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-2 me-3">
                                <i class="bi bi-bookmark-check-fill text-warning fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Status Kepegawaian</h5>
                                <small class="text-muted">Status dan tahun bergabung</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="aktif" {{ old('status', $employee->status) === 'aktif' ? 'selected' : '' }}>
                                        🟢 Aktif
                                    </option>
                                    <option value="kontrak" {{ old('status', $employee->status) === 'kontrak' ? 'selected' : '' }}>
                                        🟡 Kontrak
                                    </option>
                                    <option value="nonaktif" {{ old('status', $employee->status) === 'nonaktif' ? 'selected' : '' }}>
                                        ⚪ Nonaktif
                                    </option>
                                    <option value="resign" {{ old('status', $employee->status) === 'resign' ? 'selected' : '' }}>
                                        🔴 Resign
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tahun Bergabung</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-calendar-check"></i>
                                    </span>
                                    <input type="number" class="form-control border-start-0 @error('join_year') is-invalid @enderror"
                                           name="join_year" value="{{ old('join_year', $employee->join_year) }}" 
                                           placeholder="Contoh: 2024" min="1990" max="{{ date('Y') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Penghasilan -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger bg-opacity-10 rounded-3 p-2 me-3">
                                <i class="bi bi-cash-stack text-danger fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Informasi Penghasilan</h5>
                                <small class="text-muted">Upah minimum karyawan</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label fw-semibold">UMK (Upah Minimum Kabupaten/Kota)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" step="0.01" class="form-control @error('umk') is-invalid @enderror"
                                   name="umk" value="{{ old('umk', $employee->umk) }}" placeholder="0">
                        </div>
                        <small class="form-text text-muted">Masukkan nilai UMK dalam Rupiah</small>
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                
                <!-- Section 6: Foto Profil -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary bg-opacity-10 rounded-3 p-2 me-3">
                                <i class="bi bi-camera-fill text-secondary fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Foto Profil</h5>
                                <small class="text-muted">Update foto karyawan</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <div class="photo-preview mx-auto mb-3" style="width: 200px; height: 200px;">
                                @if($employee->photo)
                                    <img id="preview" src="{{ asset($employee->photo) }}" 
                                         class="img-fluid rounded-circle border border-3 border-light shadow" 
                                         alt="Current Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img id="preview" src="https://via.placeholder.com/200x200?text=No+Photo" 
                                         class="img-fluid rounded-circle border border-3 border-light shadow" 
                                         alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                        </div>
                        
                        @if($employee->photo)
                            <div class="alert alert-info py-2 mb-3">
                                <small><i class="bi bi-info-circle me-1"></i> Foto saat ini tersedia</small>
                            </div>
                        @else
                            <div class="alert alert-warning py-2 mb-3">
                                <small><i class="bi bi-exclamation-triangle me-1"></i> Belum ada foto</small>
                            </div>
                        @endif
                        
                        <label for="photoInput" class="btn btn-outline-primary w-100 mb-2">
                            <i class="bi bi-cloud-upload me-2"></i>Pilih Foto Baru
                        </label>
                        <input type="file" class="form-control d-none @error('photo') is-invalid @enderror"
                               id="photoInput" name="photo" accept="image/*" onchange="previewImage(event)">
                        <small class="form-text text-muted d-block mt-2">
                            <i class="bi bi-info-circle"></i> Format: JPG, PNG, GIF (Max 2MB)
                        </small>
                    </div>
                </div>

                <!-- Change Log Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-clock-history text-muted me-2"></i>Informasi Perubahan
                        </h6>
                        <div class="small text-muted">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Dibuat:</span>
                                <strong>{{ $employee->created_at ? $employee->created_at->format('d M Y H:i') : '-' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Terakhir Diubah:</span>
                                <strong>{{ $employee->updated_at ? $employee->updated_at->format('d M Y H:i') : '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save-fill me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle me-2"></i> Batal
                            </a>
                        </div>
                        <hr class="my-3">
                        <small class="text-muted d-block text-center">
                            <i class="bi bi-shield-check"></i> Perubahan akan tersimpan dengan aman
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </form>

</div>

<style>
.form-check-custom {
    transition: all 0.3s ease;
    cursor: pointer;
}

.form-check-custom:hover {
    background-color: #f8f9fa;
    border-color: #0d6efd !important;
}

.form-check-custom input:checked ~ label {
    color: #0d6efd;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.cursor-pointer {
    cursor: pointer;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
}

.input-group-text {
    border-right: 0;
}

.form-control:focus ~ .input-group-text {
    border-color: #86b7fe;
}

.photo-preview {
    position: relative;
    overflow: hidden;
}

.photo-preview img {
    transition: transform 0.3s ease;
}

.photo-preview:hover img {
    transform: scale(1.05);
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
}

.breadcrumb-item a {
    color: #6c757d;
}

.breadcrumb-item a:hover {
    color: #0d6efd;
}
</style>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview');
    
    if (input.files && input.files[0]) {
        // Validate file size (2MB max)
        if (input.files[0].size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            input.value = '';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(input.files[0].type)) {
            alert('Format file tidak valid! Gunakan JPG, PNG, atau GIF');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            // Add animation
            preview.style.opacity = '0';
            setTimeout(() => {
                preview.style.transition = 'opacity 0.3s ease';
                preview.style.opacity = '1';
            }, 50);
        }
        
        reader.onerror = function() {
            alert('Gagal membaca file!');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Add active state for radio buttons
document.querySelectorAll('.form-check-custom input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Remove active class from all
        document.querySelectorAll('.form-check-custom').forEach(div => {
            div.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10', 'border-warning', 'bg-warning');
        });
        
        // Add active class to selected
        const parent = this.closest('.form-check-custom');
        if (this.value === 'monthly') {
            parent.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
        } else if (this.value === 'daily') {
            parent.classList.add('border-warning', 'bg-warning', 'bg-opacity-10');
        }
    });
});
</script>
@endsection