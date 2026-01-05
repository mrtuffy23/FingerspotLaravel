@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Konfigurasi Tunjangan</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-dark mb-0">
                    <i class="bi bi-wallet2 text-primary me-2"></i>Konfigurasi Tunjangan
                </h1>
                <p class="text-muted mt-1 mb-0">Kelola tunjangan tetap dan tidak tetap per golongan</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#guideModal">
                    <i class="bi bi-question-circle me-2"></i>Panduan
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkUpdateModal">
                    <i class="bi bi-pencil-square me-2"></i>Update Massal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- INFO CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="info-icon bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="bi bi-grid-3x3-gap-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small">Total Golongan</p>
                        <h4 class="fw-bold mb-0">{{ $classifications->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="info-icon bg-success bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="bi bi-cash-coin text-success fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small">Tunjangan Tetap</p>
                        <h4 class="fw-bold mb-0">{{ $classifications->sum(fn($c) => $c->fixedAllowances->count()) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="info-icon bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="bi bi-calendar-check text-warning fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small">Tunjangan Tidak Tetap</p>
                        <h4 class="fw-bold mb-0">{{ $classifications->sum(fn($c) => $c->variableAllowances->count()) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CLASSIFICATION CARDS -->
<div class="row g-4">
    @forelse ($classifications as $classification)
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 classification-card">
                
                <!-- Card Header -->
                <div class="card-header bg-gradient-primary text-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-bookmark-fill me-2"></i>{{ $classification->name }}
                            </h5>
                            <small class="opacity-75">Kode: {{ $classification->code }}</small>
                        </div>
                        <div>
                            <span class="badge bg-white bg-opacity-25 px-3 py-2">
                                <i class="bi bi-people-fill me-1"></i>
                                {{ $classification->employees->count() ?? 0 }} Karyawan
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    <!-- FIXED ALLOWANCES -->
                    <div class="allowance-section mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-cash-stack text-success me-2"></i>Tunjangan Tetap
                            </h6>
                            <span class="badge bg-success bg-opacity-10 text-success">Per Bulan</span>
                        </div>

                        <div class="allowance-list">
                            @forelse ($classification->fixedAllowances as $allowance)
                                <div class="allowance-item">
                                    <form action="{{ route('allowance.fixed.update', $allowance) }}" 
                                          method="POST" 
                                          class="allowance-form">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row align-items-center g-3">
                                            <div class="col-md-6">
                                                <div class="allowance-info">
                                                    <div class="fw-semibold text-dark mb-1">{{ $allowance->name }}</div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-tag me-1"></i>{{ $allowance->code }}
                                                    </small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="bi bi-currency-dollar"></i>
                                                    </span>
                                                    <input type="number" 
                                                           name="amount" 
                                                           class="form-control fw-semibold" 
                                                           value="{{ $allowance->amount }}" 
                                                           step="1000"
                                                           placeholder="0"
                                                           required>
                                                    <button type="submit" 
                                                            class="btn btn-success" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Simpan Perubahan">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @empty
                                <div class="empty-state-sm">
                                    <i class="bi bi-inbox text-muted"></i>
                                    <p class="text-muted mb-0 small">Belum ada tunjangan tetap</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- VARIABLE ALLOWANCES -->
                    <div class="allowance-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-calendar-range text-warning me-2"></i>Tunjangan Tidak Tetap
                            </h6>
                            <span class="badge bg-warning bg-opacity-10 text-warning">Per Hari</span>
                        </div>

                        <div class="allowance-list">
                            @forelse ($classification->variableAllowances as $allowance)
                                <div class="allowance-item">
                                    <form action="{{ route('allowance.variable.update', $allowance) }}" 
                                          method="POST" 
                                          class="allowance-form">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row align-items-center g-3">
                                            <div class="col-md-6">
                                                <div class="allowance-info">
                                                    <div class="fw-semibold text-dark mb-1">{{ $allowance->name }}</div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-tag me-1"></i>{{ $allowance->code }}
                                                    </small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="bi bi-currency-dollar"></i>
                                                    </span>
                                                    <input type="number" 
                                                           name="amount_per_day" 
                                                           class="form-control fw-semibold" 
                                                           value="{{ $allowance->amount_per_day }}" 
                                                           step="1000"
                                                           placeholder="0"
                                                           required>
                                                    <button type="submit" 
                                                            class="btn btn-warning" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Simpan Perubahan">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @empty
                                <div class="empty-state-sm">
                                    <i class="bi bi-inbox text-muted"></i>
                                    <p class="text-muted mb-0 small">Belum ada tunjangan tidak tetap</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Total: {{ $classification->fixedAllowances->count() + $classification->variableAllowances->count() }} Tunjangan
                        </small>
                        <a href="{{ route('allowance.edit', $classification) }}" 
                           class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil-square me-1"></i>Edit Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted mb-2">Belum Ada Data Golongan</h5>
                    <p class="text-muted mb-0">Silakan tambahkan golongan terlebih dahulu</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Guide Modal -->
<div class="modal fade" id="guideModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-book text-primary me-2"></i>Panduan Konfigurasi Tunjangan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="guide-section mb-4">
                    <h6 class="fw-bold text-success mb-3">
                        <i class="bi bi-cash-stack me-2"></i>Tunjangan Tetap
                    </h6>
                    <p class="text-muted mb-2">
                        Tunjangan yang diberikan dengan nilai tetap setiap bulan kepada karyawan, 
                        tidak terpengaruh oleh jumlah hari kerja.
                    </p>
                    <div class="alert alert-success bg-success bg-opacity-10 border-0">
                        <strong>Contoh:</strong> Tunjangan Jabatan Rp 500.000/bulan
                    </div>
                </div>

                <div class="guide-section">
                    <h6 class="fw-bold text-warning mb-3">
                        <i class="bi bi-calendar-range me-2"></i>Tunjangan Tidak Tetap
                    </h6>
                    <p class="text-muted mb-2">
                        Tunjangan yang dihitung berdasarkan hari kerja aktual. 
                        Nilai yang dikonfigurasi adalah nilai per hari kerja.
                    </p>
                    <div class="alert alert-warning bg-warning bg-opacity-10 border-0">
                        <strong>Contoh:</strong> Tunjangan Makan Rp 50.000/hari × 22 hari kerja = Rp 1.100.000
                    </div>
                </div>

                <hr class="my-4">

                <div class="alert alert-info bg-info bg-opacity-10 border-0">
                    <i class="bi bi-lightbulb-fill me-2"></i>
                    <strong>Tips:</strong> Klik tombol hijau/kuning untuk menyimpan perubahan setiap tunjangan
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Update Massal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Golongan</label>
                        <select class="form-select" required>
                            <option value="">-- Pilih Golongan --</option>
                            @foreach($classifications as $class)
                                <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Tunjangan</label>
                        <select class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="fixed">Tunjangan Tetap</option>
                            <option value="variable">Tunjangan Tidak Tetap</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Persentase Perubahan</label>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button">-</button>
                            <input type="number" class="form-control text-center fw-bold" value="0" step="5">
                            <span class="input-group-text">%</span>
                            <button class="btn btn-outline-secondary" type="button">+</button>
                        </div>
                        <small class="text-muted">Gunakan nilai negatif untuk menurunkan</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Terapkan Perubahan
                </button>
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

.info-card {
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

.info-icon {
    transition: all 0.3s ease;
}

.info-card:hover .info-icon {
    transform: scale(1.1);
}

.classification-card {
    transition: all 0.3s ease;
    border-radius: 16px;
    overflow: hidden;
}

.classification-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.15) !important;
}

.allowance-section {
    position: relative;
}

.allowance-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.allowance-item {
    padding: 16px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.2s ease;
}

.allowance-item:hover {
    background: #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.allowance-form input[type="number"] {
    text-align: right;
}

.allowance-form input[type="number"]:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1);
}

.allowance-form .btn {
    min-width: 45px;
}

.empty-state-sm {
    text-align: center;
    padding: 32px 16px;
    background: #f8f9fa;
    border-radius: 12px;
}

.empty-state-sm i {
    font-size: 2rem;
    opacity: 0.3;
    display: block;
    margin-bottom: 8px;
}

.guide-section {
    padding-left: 8px;
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

.card {
    border-radius: 16px;
}

.input-group-text {
    border-right: 0;
}

.form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1);
}

.modal-content {
    border-radius: 16px;
    border: none;
}
</style>
@endpush

@push('scripts')
<script>
// Initialize Tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

// Auto-submit prevention (optional - can add confirmation)
document.querySelectorAll('.allowance-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const input = this.querySelector('input[type="number"]');
        if (input.value <= 0) {
            e.preventDefault();
            alert('Nilai tunjangan harus lebih dari 0');
            return false;
        }
    });
});

// Format currency input on focus out
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value) {
            // You can add currency formatting here if needed
            console.log('Value:', this.value);
        }
    });
});
</script>
@endpush