@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}" class="text-decoration-none">Penggajian</a></li>
                <li class="breadcrumb-item active">Buat Periode Baru</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="h3 fw-bold text-dark mb-0">
                    <i class="bi bi-calendar-plus text-primary me-2"></i>Buat Periode Penggajian
                </h1>
                <p class="text-muted mt-1 mb-0">Tetapkan rentang tanggal periode gaji yang akan diproses</p>
            </div>
            <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary">
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
                <li>Pastikan periode tidak tumpang tindih dengan periode yang sudah ada</li>
                <li>Sistem akan menghitung gaji berdasarkan data kehadiran dalam periode ini</li>
                <li>Periode yang sudah dibuat dapat di-finalisasi setelah semua data terverifikasi</li>
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
                    <i class="bi bi-calendar-range text-primary me-2"></i>Informasi Periode
                </h5>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('payroll.store') }}" method="POST" id="payrollForm">
                    @csrf

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
                            <small class="text-muted">Hari pertama periode penggajian</small>
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
                            <small class="text-muted">Hari terakhir periode penggajian</small>
                        </div>
                    </div>

                    <!-- Period Info Display -->
                    <div class="alert alert-light border mb-4" id="periodInfo" style="display: none;">
                        <div class="row g-3 text-center">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <i class="bi bi-calendar3 text-primary fs-4 d-block mb-2"></i>
                                    <small class="text-muted d-block">Durasi Periode</small>
                                    <strong class="h5 mb-0" id="periodDays">0 Hari</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <i class="bi bi-calendar-week text-success fs-4 d-block mb-2"></i>
                                    <small class="text-muted d-block">Hari Kerja (Est.)</small>
                                    <strong class="h5 mb-0" id="workDays">0 Hari</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <i class="bi bi-calendar-x text-danger fs-4 d-block mb-2"></i>
                                    <small class="text-muted d-block">Akhir Pekan</small>
                                    <strong class="h5 mb-0" id="weekends">0 Hari</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Select Buttons -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-lightning-charge me-1"></i>Quick Select Periode
                        </label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setThisMonth()">
                                <i class="bi bi-calendar-month me-1"></i>Bulan Ini
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setLastMonth()">
                                <i class="bi bi-calendar-minus me-1"></i>Bulan Lalu
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setNextMonth()">
                                <i class="bi bi-calendar-plus me-1"></i>Bulan Depan
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="set15Days()">
                                <i class="bi bi-calendar2-range me-1"></i>15 Hari Terakhir
                            </button>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label fw-semibold">
                            <i class="bi bi-pencil-square me-1"></i>Catatan
                        </label>
                        <textarea 
                            class="form-control" 
                            id="notes" 
                            name="notes" 
                            rows="4"
                            placeholder="Tambahkan catatan khusus untuk periode ini (opsional)...">{{ old('notes') }}</textarea>
                        <small class="text-muted">Catatan akan terlihat pada detail periode penggajian</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="bi bi-check-circle me-2"></i>Buat Periode
                        </button>
                        <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary btn-lg px-4">
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
                    <i class="bi bi-eye me-2"></i>Preview Periode
                </h6>
            </div>
            <div class="card-body">
                <div class="preview-item mb-3">
                    <small class="text-muted d-block mb-1">Periode</small>
                    <div class="fw-bold" id="previewPeriod">Belum dipilih</div>
                </div>
                <div class="preview-item mb-3">
                    <small class="text-muted d-block mb-1">Durasi</small>
                    <div class="fw-bold" id="previewDuration">-</div>
                </div>
                <div class="preview-item">
                    <small class="text-muted d-block mb-1">Status</small>
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">
                        <i class="bi bi-clock-history me-1"></i>Draft
                    </span>
                </div>
            </div>
        </div>

        <!-- Guidelines Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-list-check text-success me-2"></i>Checklist
                </h6>
            </div>
            <div class="card-body">
                <div class="checklist-item">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <span>Pilih tanggal mulai dan selesai</span>
                </div>
                <div class="checklist-item">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <span>Pastikan tidak ada overlap periode</span>
                </div>
                <div class="checklist-item">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <span>Verifikasi data kehadiran lengkap</span>
                </div>
                <div class="checklist-item">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <span>Tambahkan catatan jika perlu</span>
                </div>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-lightbulb text-warning me-2"></i>Tips
                </h6>
                <ul class="mb-0 small text-muted">
                    <li class="mb-2">Gunakan tombol "Quick Select" untuk memilih periode umum dengan cepat</li>
                    <li class="mb-2">Periode biasanya dimulai tanggal 1 dan berakhir di akhir bulan</li>
                    <li>Sistem akan otomatis menghitung tunjangan dan potongan</li>
                </ul>
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

.info-box {
    padding: 12px;
    background: white;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.info-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.preview-item {
    padding-bottom: 12px;
    border-bottom: 1px solid #e9ecef;
}

.preview-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.checklist-item {
    padding: 8px 0;
    font-size: 14px;
}

.checklist-item:not(:last-child) {
    border-bottom: 1px solid #e9ecef;
}

.form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1);
}

.form-control-lg {
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

.btn-outline-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(79, 70, 229, 0.2);
}
</style>
@endpush

@push('scripts')
<script>
// Date inputs
const startDateInput = document.getElementById('start_date');
const endDateInput = document.getElementById('end_date');

// Calculate period info
function calculatePeriodInfo() {
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);
    
    if (startDateInput.value && endDateInput.value && startDate <= endDate) {
        // Calculate days
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
        document.getElementById('periodDays').textContent = diffDays + ' Hari';
        document.getElementById('workDays').textContent = workDays + ' Hari';
        document.getElementById('weekends').textContent = weekends + ' Hari';
        document.getElementById('periodInfo').style.display = 'block';
        
        // Update preview
        updatePreview();
    } else {
        document.getElementById('periodInfo').style.display = 'none';
    }
}

// Update preview
function updatePreview() {
    const startDate = startDateInput.value;
    const endDate = endDateInput.value;
    
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
startDateInput.addEventListener('change', calculatePeriodInfo);
endDateInput.addEventListener('change', calculatePeriodInfo);

// Quick select functions
function setThisMonth() {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    
    startDateInput.value = firstDay.toISOString().split('T')[0];
    endDateInput.value = lastDay.toISOString().split('T')[0];
    calculatePeriodInfo();
}

function setLastMonth() {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth() - 1, 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth(), 0);
    
    startDateInput.value = firstDay.toISOString().split('T')[0];
    endDateInput.value = lastDay.toISOString().split('T')[0];
    calculatePeriodInfo();
}

function setNextMonth() {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth() + 1, 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 2, 0);
    
    startDateInput.value = firstDay.toISOString().split('T')[0];
    endDateInput.value = lastDay.toISOString().split('T')[0];
    calculatePeriodInfo();
}

function set15Days() {
    const now = new Date();
    const fifteenDaysAgo = new Date(now.getTime() - (15 * 24 * 60 * 60 * 1000));
    
    startDateInput.value = fifteenDaysAgo.toISOString().split('T')[0];
    endDateInput.value = now.toISOString().split('T')[0];
    calculatePeriodInfo();
}

// Form validation
document.getElementById('payrollForm').addEventListener('submit', function(e) {
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);
    
    if (startDate > endDate) {
        e.preventDefault();
        alert('Tanggal selesai harus setelah atau sama dengan tanggal mulai!');
        return false;
    }
    
    // Confirmation
    if (!confirm('Apakah Anda yakin ingin membuat periode penggajian ini?')) {
        e.preventDefault();
        return false;
    }
});

// Set min date for end_date based on start_date
startDateInput.addEventListener('change', function() {
    endDateInput.min = this.value;
});
</script>
@endpush