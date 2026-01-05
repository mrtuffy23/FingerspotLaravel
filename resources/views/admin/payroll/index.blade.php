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
                        <li class="breadcrumb-item active">Penggajian</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-dark mb-0">
                    <i class="bi bi-cash-stack text-primary me-2"></i>Manajemen Penggajian
                </h1>
                <p class="text-muted mt-1 mb-0">Kelola periode penggajian dan proses payroll karyawan</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <a href="{{ route('payroll.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>Buat Periode Baru
                </a>
            </div>
        </div>
    </div>
</div>

<!-- STATISTICS CARDS -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Total Periode</p>
                        <h3 class="fw-bold mb-0">{{ $periods->total() }}</h3>
                        <small class="text-muted">Semua periode</small>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-calendar-range-fill text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Draft</p>
                        <h3 class="fw-bold mb-0">{{ $periods->where('status', 'draft')->count() }}</h3>
                        <small class="text-warning"><i class="bi bi-hourglass-split"></i> Belum finalisasi</small>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-file-earmark-text text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Finalized</p>
                        <h3 class="fw-bold mb-0">{{ $periods->where('status', 'finalized')->count() }}</h3>
                        <small class="text-success"><i class="bi bi-check-circle"></i> Sudah final</small>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Total Payroll</p>
                        <h3 class="fw-bold mb-0">{{ $periods->sum('payrolls_count') }}</h3>
                        <small class="text-info"><i class="bi bi-people"></i> Karyawan diproses</small>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-wallet2 text-info fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MAIN TABLE CARD -->
<div class="card border-0 shadow-sm">
    
    <!-- Card Header -->
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-table text-primary me-2"></i>Daftar Periode Penggajian
                </h5>
                <small class="text-muted">Menampilkan {{ $periods->count() }} dari {{ $periods->total() }} periode</small>
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary active">
                    <i class="bi bi-list"></i> List
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-grid-3x3-gap"></i> Grid
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 ps-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th class="border-0">Periode</th>
                        <th class="border-0 text-center">Tanggal Mulai</th>
                        <th class="border-0 text-center">Tanggal Selesai</th>
                        <th class="border-0 text-center">Durasi</th>
                        <th class="border-0 text-center">Total Payroll</th>
                        <th class="border-0 text-center">Status</th>
                        <th class="border-0 text-center pe-4">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($periods as $period)
                        <tr class="payroll-row">
                            <td class="ps-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $period->id }}">
                                </div>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="period-icon bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="bi bi-calendar-range-fill text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Periode #{{ $period->id }}</div>
                                        <small class="text-muted">{{ $period->created_at->format('d M Y') }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $period->start_date->format('d M Y') }}</span>
                                    <small class="text-muted">{{ $period->start_date->isoFormat('dddd') }}</small>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $period->end_date->format('d M Y') }}</span>
                                    <small class="text-muted">{{ $period->end_date->isoFormat('dddd') }}</small>
                                </div>
                            </td>

                            <td class="text-center">
                                @php
                                    $duration = $period->start_date->diffInDays($period->end_date) + 1;
                                @endphp
                                <div class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-2">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $duration }} Hari
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="badge bg-primary px-3 py-2 mb-1">
                                        <i class="bi bi-people-fill me-1"></i>{{ $period->payrolls_count }}
                                    </span>
                                    <small class="text-muted">Karyawan</small>
                                </div>
                            </td>

                            <td class="text-center">
                                @if($period->status === 'finalized')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Finalized
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2">
                                        <i class="bi bi-hourglass-split me-1"></i>Draft
                                    </span>
                                @endif
                            </td>

                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('payroll.show', $period) }}" 
                                       class="btn btn-sm btn-light"
                                       data-bs-toggle="tooltip"
                                       title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if($period->status !== 'finalized')
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-success"
                                                data-bs-toggle="tooltip"
                                                title="Finalisasi"
                                                onclick="confirmFinalize({{ $period->id }})">
                                            <i class="bi bi-check2-circle"></i>
                                        </button>

                                        <button type="button" 
                                                class="btn btn-sm btn-light text-danger"
                                                data-bs-toggle="tooltip"
                                                title="Hapus"
                                                onclick="confirmDelete({{ $period->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @else
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-primary"
                                                data-bs-toggle="tooltip"
                                                title="Download">
                                            <i class="bi bi-download"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Hidden Forms -->
                                @if($period->status !== 'finalized')
                                    <form id="finalize-form-{{ $period->id }}" 
                                          action="{{ route('payroll.finalize', $period) }}" 
                                          method="POST" 
                                          class="d-none">
                                        @csrf
                                    </form>

                                    <form id="delete-form-{{ $period->id }}" 
                                          action="{{ route('payroll.destroy', $period) }}" 
                                          method="POST" 
                                          class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                    <h5 class="text-muted mb-2">Belum Ada Periode Penggajian</h5>
                                    <p class="text-muted mb-3">Mulai dengan membuat periode penggajian baru</p>
                                    <a href="{{ route('payroll.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>Buat Periode Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Footer -->
    @if($periods->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Menampilkan {{ $periods->firstItem() ?? 0 }} - {{ $periods->lastItem() ?? 0 }} dari {{ $periods->total() }} periode
            </div>
            <div>
                {{ $periods->links() }}
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-funnel text-primary me-2"></i>Filter Periode
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select">
                            <option value="">Semua Status</option>
                            <option value="draft">Draft</option>
                            <option value="finalized">Finalized</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rentang Tanggal</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" class="form-control" placeholder="Dari">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control" placeholder="Sampai">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Payroll</label>
                        <select class="form-select">
                            <option value="">Semua</option>
                            <option value="1-50">1 - 50</option>
                            <option value="51-100">51 - 100</option>
                            <option value="100+">100+</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Reset</button>
                <button type="button" class="btn btn-primary">Terapkan Filter</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

.stat-icon {
    transition: all 0.3s ease;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1);
}

.payroll-row {
    transition: all 0.2s ease;
}

.payroll-row:hover {
    background-color: #f8f9fa;
}

.period-icon {
    transition: all 0.2s ease;
}

.payroll-row:hover .period-icon {
    transform: scale(1.1);
}

.btn-group .btn {
    border: 1px solid #e2e8f0;
}

.btn-group .btn:hover {
    background-color: #f1f5f9;
    z-index: 1;
}

.empty-state i {
    opacity: 0.3;
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

.form-control:focus,
.form-select:focus {
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

// Select All Checkbox
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});

// Confirm Finalize
function confirmFinalize(id) {
    if (confirm('Apakah Anda yakin ingin memfinalisasi periode penggajian ini?\n\nSetelah difinalisasi, data tidak dapat diubah lagi.')) {
        document.getElementById('finalize-form-' + id).submit();
    }
}

// Confirm Delete
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus periode ini?\n\nSemua data payroll dalam periode ini akan ikut terhapus!\n\nTindakan ini tidak dapat dibatalkan.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush