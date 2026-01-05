@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('leave.index') }}" class="text-decoration-none">Manajemen Cuti</a></li>
                <li class="breadcrumb-item active">Ajukan Cuti</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="h3 fw-bold text-dark mb-0">
                    <i class="bi bi-calendar-plus text-primary me-2"></i>Ajukan Permintaan Cuti
                </h1>
                <p class="text-muted mt-1 mb-0">Lengkapi formulir pengajuan cuti karyawan</p>
            </div>
            <a href="{{ route('leave.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- INFO ALERT -->
<div class="alert alert-info bg-info bg-opacity-10 border-0 shadow-sm mb-4">
    <div class="d-flex align-items-start">
        <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
        <div>
            <h6 class="alert-heading fw-bold mb-2">Informasi Penting</h6>
            <ul class="mb-0 small">
                <li>Pastikan tanggal cuti tidak bertumpang tindih dengan jadwal penting</li>
                <li>Pengajuan akan diproses setelah mendapat persetujuan atasan</li>
                <li>Sertakan alasan yang jelas untuk mempercepat approval</li>
            </ul>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <!-- MAIN FORM -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Form Pengajuan Cuti
                </h5>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('leave.store') }}" method="POST" id="leaveForm">
                    @csrf

                    <!-- Employee Selection -->
                    <div class="mb-4">
                        <label for="employee_id" class="form-label fw-semibold">
                            <i class="bi bi-person-circle me-1"></i>Pilih Karyawan <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg @error('employee_id') is-invalid @enderror"
                                id="employee_id" 
                                name="employee_id" 
                                required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" 
                                        data-position="{{ $emp->position->name ?? '-' }}"
                                        data-department="{{ $emp->department->name ?? '-' }}"
                                        {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }} ({{ $emp->nik }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pilih karyawan yang akan mengajukan cuti</small>
                        
                        <!-- Employee Info Display -->
                        <div id="employeeInfo" class="mt-3" style="display: none;">
                            <div class="alert alert-light border mb-0">
                                <div class="row g-2 small">
                                    <div class="col-md-6">
                                        <strong>Posisi:</strong> <span id="empPosition">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Departemen:</strong> <span id="empDepartment">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Type -->
                    <div class="mb-4">
                        <label for="type" class="form-label fw-semibold">
                            <i class="bi bi-tag me-1"></i>Jenis Cuti <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg @error('type') is-invalid @enderror"
                                id="type" 
                                name="type" 
                                required>
                            <option value="">-- Pilih Jenis Cuti --</option>
                            <option value="cuti" {{ old('type') === 'cuti' ? 'selected' : '' }}>
                                🏖️ Cuti Tahunan
                            </option>
                            <option value="sakit" {{ old('type') === 'sakit' ? 'selected' : '' }}>
                                🏥 Sakit
                            </option>
                            <option value="izin" {{ old('type') === 'izin' ? 'selected' : '' }}>
                                📝 Izin
                            </option>
                            <option value="kecelakaan" {{ old('type') === 'kecelakaan' ? 'selected' : '' }}>
                                🚑 Kecelakaan Kerja
                            </option>
                            <option value="izin_keluar" {{ old('type') === 'izin_keluar' ? 'selected' : '' }}>
                                🚪 Izin Keluar
                            </option>
                            <option value="sakit_sabtu" {{ old('type') === 'sakit_sabtu' ? 'selected' : '' }}>
                                🏥 Sakit (Sabtu)
                            </option>
                            <option value="libur" {{ old('type') === 'libur' ? 'selected' : '' }}>
                                🎉 Libur Khusus
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pilih jenis cuti sesuai keperluan</small>
                    </div>

                    <!-- Date Range -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-1"></i>Tanggal Mulai <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg @error('start_date') is-invalid @enderror"
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}" 
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Hari pertama cuti</small>
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-1"></i>Tanggal Selesai <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg @error('end_date') is-invalid @enderror"
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date') }}" 
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Hari terakhir cuti</small>
                        </div>
                    </div>

                    <!-- Duration Display -->
                    <div class="alert alert-light border mb-4" id="durationInfo" style="display: none;">
                        <div class="row g-3 text-center">
                            <div class="col-md-4">
                                <i class="bi bi-calendar3 text-primary fs-3 d-block mb-2"></i>
                                <small class="text-muted d-block">Total Hari</small>
                                <strong class="h4" id="totalDays">0</strong>
                            </div>
                            <div class="col-md-4">
                                <i class="bi bi-calendar-week text-success fs-3 d-block mb-2"></i>
                                <small class="text-muted d-block">Hari Kerja</small>
                                <strong class="h4 text-success" id="workDays">0</strong>
                            </div>
                            <div class="col-md-4">
                                <i class="bi bi-calendar-x text-danger fs-3 d-block mb-2"></i>
                                <small class="text-muted d-block">Akhir Pekan</small>
                                <strong class="h4 text-danger" id="weekends">0</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="mb-4">
                        <label for="reason" class="form-label fw-semibold">
                            <i class="bi bi-chat-left-text me-1"></i>Alasan Cuti
                        </label>
                        <textarea class="form-control @error('reason') is-invalid @enderror" 
                                  id="reason" 
                                  name="reason" 
                                  rows="4"
                                  placeholder="Jelaskan alasan pengajuan cuti (opsional, namun disarankan untuk mempercepat persetujuan)">{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Berikan penjelasan singkat tentang alasan cuti</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="bi bi-send-fill me-2"></i>Kirim Pengajuan
                        </button>
                        <a href="{{ route('leave.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- SIDEBAR INFO -->
    <div class="col-lg-4">
        
        <!-- Preview Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-eye me-2"></i>Preview Pengajuan
                </h6>
            </div>
            <div class="card-body">
                <div class="preview-item mb-3">
                    <small class="text-muted d-block mb-1">Karyawan</small>
                    <div class="fw-bold" id="previewEmployee">Belum dipilih</div>
                </div>
                <div class="preview-item mb-3">
                    <small class="text-muted d-block mb-1">Jenis Cuti</small>
                    <div class="fw-bold" id="previewType">Belum dipilih</div>
                </div>
                <div class="preview-item mb-3">
                    <small class="text-muted d-block mb-1">Periode</small>
                    <div class="fw-bold" id="previewPeriod">-</div>
                </div>
                <div class="preview-item">
                    <small class="text-muted d-block mb-1">Durasi</small>
                    <div class="fw-bold" id="previewDuration">-</div>
                </div>
            </div>
        </div>

        <!-- Guidelines Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-list-check text-success me-2"></i>Panduan Pengajuan
                </h6>
            </div>
            <div class="card-body">
                <div class="guide-item">
                    <div class="guide-number">1</div>
                    <div class="guide-text">Pilih karyawan yang akan mengajukan cuti</div>
                </div>
                <div class="guide-item">
                    <div class="guide-number">2</div>
                    <div class="guide-text">Tentukan jenis cuti sesuai keperluan</div>
                </div>
                <div class="guide-item">
                    <div class="guide-number">3</div>
                    <div class="guide-text">Atur tanggal mulai dan selesai cuti</div>
                </div>
                <div class="guide-item">
                    <div class="guide-number">4</div>
                    <div class="guide-text">Berikan alasan yang jelas (opsional)</div>
                </div>
                <div class="guide-item">
                    <div class="guide-number">5</div>
                    <div class="guide-text">Review dan kirim pengajuan</div>
                </div>
            </div>
        </div>

        <!-- Leave Types Info -->
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>Jenis Cuti
                </h6>
                <div class="leave-type-list small">
                    <div class="leave-type-item">
                        <strong>Cuti Tahunan:</strong> Cuti resmi sesuai hak tahunan
                    </div>
                    <div class="leave-type-item">
                        <strong>Sakit:</strong> Cuti karena kondisi kesehatan
                    </div>
                    <div class="leave-type-item">
                        <strong>Izin:</strong> Izin keperluan mendadak
                    </div>
                    <div class="leave-type-item">
                        <strong>Kecelakaan:</strong> Cuti akibat kecelakaan kerja
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
}

.preview-item {
    padding-bottom: 12px;
    border-bottom: 1px solid #e9ecef;
}

.preview-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.guide-item {
    display: flex;
    gap: 12px;
    align-items: start;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.guide-item:last-child {
    border-bottom: none;
}

.guide-number {
    width: 28px;
    height: 28px;
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 13px;
    flex-shrink: 0;
}

.guide-text {
    font-size: 14px;
    color: #475569;
    line-height: 1.5;
}

.leave-type-item {
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
}

.leave-type-item:last-child {
    border-bottom: none;
}

.form-control:focus,
.form-select:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1);
}

.form-control-lg,
.form-select-lg {
    font-weight: 600;
}

.card {
    border-radius: 16px;
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
@endpush

@push('scripts')
<script>
// Employee selection handler
document.getElementById('employee_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const employeeInfo = document.getElementById('employeeInfo');
    
    if (this.value) {
        document.getElementById('empPosition').textContent = selectedOption.dataset.position;
        document.getElementById('empDepartment').textContent = selectedOption.dataset.department;
        document.getElementById('previewEmployee').textContent = selectedOption.text;
        employeeInfo.style.display = 'block';
    } else {
        employeeInfo.style.display = 'none';
        document.getElementById('previewEmployee').textContent = 'Belum dipilih';
    }
});

// Leave type selection
document.getElementById('type').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('previewType').textContent = selectedOption.value ? selectedOption.text : 'Belum dipilih';
});

// Calculate duration
function calculateDuration() {
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(document.getElementById('end_date').value);
    
    if (startDate && endDate && startDate <= endDate) {
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        
        // Calculate weekends
        let weekends = 0;
        let workDays = 0;
        let currentDate = new Date(startDate);
        
        while (currentDate <= endDate) {
            const dayOfWeek = currentDate.getDay();
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                weekends++;
            } else {
                workDays++;
            }
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        // Update display
        document.getElementById('totalDays').textContent = diffDays;
        document.getElementById('workDays').textContent = workDays;
        document.getElementById('weekends').textContent = weekends;
        document.getElementById('durationInfo').style.display = 'block';
        
        // Update preview
        updatePreview();
    } else {
        document.getElementById('durationInfo').style.display = 'none';
    }
}

// Update preview
function updatePreview() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        const startFormatted = start.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const endFormatted = end.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        
        document.getElementById('previewPeriod').textContent = startFormatted + ' - ' + endFormatted;
        
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        document.getElementById('previewDuration').textContent = diffDays + ' hari';
    }
}

// Event listeners
document.getElementById('start_date').addEventListener('change', calculateDuration);
document.getElementById('end_date').addEventListener('change', calculateDuration);

// Form validation
document.getElementById('leaveForm').addEventListener('submit', function(e) {
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(document.getElementById('end_date').value);
    
    if (startDate > endDate) {
        e.preventDefault();
        alert('Tanggal selesai harus setelah atau sama dengan tanggal mulai!');
        return false;
    }
    
    // Confirmation
    if (!confirm('Apakah Anda yakin ingin mengajukan cuti ini?')) {
        e.preventDefault();
        return false;
    }
});

// Set min date for end_date
document.getElementById('start_date').addEventListener('change', function() {
    document.getElementById('end_date').min = this.value;
});
</script>
@endpush