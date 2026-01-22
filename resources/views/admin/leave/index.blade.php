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
                        <li class="breadcrumb-item active">Cuti & Izin</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-dark mb-0">
                    <i class="bi bi-calendar-x text-primary me-2"></i>Manajemen Cuti & Izin
                </h1>
                <p class="text-muted mt-1 mb-0">Kelola dan pantau pengajuan cuti karyawan</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <a href="{{ route('leave.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>Ajukan Cuti
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
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Total Pengajuan</p>
                        <h3 class="fw-bold mb-0">{{ $leaves->total() }}</h3>
                        <small class="text-muted">Semua periode</small>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-file-earmark-text-fill text-primary fs-4"></i>
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
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Menunggu Approval</p>
                        <h3 class="fw-bold mb-0">{{ $leaves->where('status', 'pending')->count() }}</h3>
                        <small class="text-warning"><i class="bi bi-hourglass-split"></i> Perlu tindakan</small>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-clock-history text-warning fs-4"></i>
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
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Disetujui</p>
                        <h3 class="fw-bold mb-0">{{ $leaves->where('status', 'approved')->count() }}</h3>
                        <small class="text-success"><i class="bi bi-check-circle"></i> Approved</small>
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
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Ditolak</p>
                        <h3 class="fw-bold mb-0">{{ $leaves->where('status', 'rejected')->count() }}</h3>
                        <small class="text-danger"><i class="bi bi-x-circle"></i> Rejected</small>
                    </div>
                    <div class="stat-icon bg-danger bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QUICK FILTERS -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-muted small fw-semibold">Quick Filter:</span>
            <button class="btn btn-sm btn-outline-primary active">
                <i class="bi bi-list-ul me-1"></i>Semua
            </button>
            <button class="btn btn-sm btn-outline-warning">
                <i class="bi bi-hourglass-split me-1"></i>Pending ({{ $leaves->where('status', 'pending')->count() }})
            </button>
            <button class="btn btn-sm btn-outline-success">
                <i class="bi bi-check-circle me-1"></i>Approved
            </button>
            <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-x-circle me-1"></i>Rejected
            </button>
            <div class="ms-auto">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="Cari karyawan..." id="searchInput">
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
                    <i class="bi bi-table text-primary me-2"></i>Daftar Pengajuan Cuti
                </h5>
                <small class="text-muted">Menampilkan {{ $leaves->count() }} dari {{ $leaves->total() }} pengajuan</small>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-secondary">
                    <i class="bi bi-download me-1"></i>Export
                </button>
                <button type="button" class="btn btn-outline-secondary">
                    <i class="bi bi-printer me-1"></i>Print
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
                        <th class="border-0">Karyawan</th>
                        <th class="border-0 text-center">Jenis Cuti</th>
                        <th class="border-0 text-center">Tanggal Mulai</th>
                        <th class="border-0 text-center">Tanggal Selesai</th>
                        <th class="border-0 text-center">Durasi</th>
                        <th class="border-0 text-center">Status</th>
                        <th class="border-0 text-center pe-4">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($leaves as $leave)
                        <tr class="leave-row">
                            <td class="ps-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $leave->id }}">
                                </div>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <strong>{{ strtoupper(substr($leave->employee->name, 0, 1)) }}</strong>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $leave->employee->name }}</div>
                                        <small class="text-muted">{{ $leave->employee->position->name ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                @php
                                    $typeConfig = [
                                        'annual' => ['color' => 'primary', 'icon' => 'calendar-event', 'label' => 'Tahunan'],
                                        'sick' => ['color' => 'danger', 'icon' => 'hospital', 'label' => 'Sakit'],
                                        'maternity' => ['color' => 'info', 'icon' => 'heart', 'label' => 'Melahirkan'],
                                        'paternity' => ['color' => 'success', 'icon' => 'people', 'label' => 'Ayah'],
                                        'unpaid' => ['color' => 'warning', 'icon' => 'dash-circle', 'label' => 'Tidak Dibayar'],
                                        'marriage' => ['color' => 'danger', 'icon' => 'heart-fill', 'label' => 'Nikah'],
                                        'other' => ['color' => 'secondary', 'icon' => 'three-dots', 'label' => 'Lainnya'],
                                    ];
                                    $type = $typeConfig[$leave->type] ?? ['color' => 'secondary', 'icon' => 'file-text', 'label' => ucfirst($leave->type)];
                                @endphp
                                <span class="badge bg-{{ $type['color'] }} bg-opacity-10 text-{{ $type['color'] }} border border-{{ $type['color'] }} px-3 py-2">
                                    <i class="bi bi-{{ $type['icon'] }} me-1"></i>{{ $type['label'] }}
                                </span>
                            </td>

                            <td class="text-center">
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $leave->start_date->format('d M Y') }}</span>
                                    <small class="text-muted">{{ $leave->start_date->isoFormat('dddd') }}</small>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $leave->end_date->format('d M Y') }}</span>
                                    <small class="text-muted">{{ $leave->end_date->isoFormat('dddd') }}</small>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $leave->duration }} Hari
                                </div>
                            </td>

                            <td class="text-center">
                                @php
                                    $statusConfig = [
                                        'pending' => ['color' => 'warning', 'icon' => 'hourglass-split', 'label' => 'Pending'],
                                        'approved' => ['color' => 'success', 'icon' => 'check-circle-fill', 'label' => 'Approved'],
                                        'rejected' => ['color' => 'danger', 'icon' => 'x-circle-fill', 'label' => 'Rejected'],
                                    ];
                                    $status = $statusConfig[$leave->status] ?? ['color' => 'secondary', 'icon' => 'question-circle', 'label' => $leave->status];
                                @endphp
                                <span class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} border border-{{ $status['color'] }} px-3 py-2">
                                    <i class="bi bi-{{ $status['icon'] }} me-1"></i>{{ $status['label'] }}
                                </span>
                            </td>

                            <td class="text-center pe-4">
                                @if($leave->status === 'pending')
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-success"
                                                data-bs-toggle="tooltip"
                                                title="Setujui"
                                                onclick="confirmApprove({{ $leave->id }})">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-danger"
                                                data-bs-toggle="tooltip"
                                                title="Tolak"
                                                onclick="confirmReject({{ $leave->id }})">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-primary"
                                                data-bs-toggle="tooltip"
                                                title="Detail"
                                                onclick="showDetail({{ $leave->id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-danger"
                                                data-bs-toggle="tooltip"
                                                title="Hapus"
                                                onclick="confirmDelete({{ $leave->id }})">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>

                                    <!-- Hidden Forms -->
                                    <form id="approve-form-{{ $leave->id }}" 
                                          action="{{ route('leave.approve', $leave) }}" 
                                          method="POST" 
                                          class="d-none">
                                        @csrf
                                    </form>

                                    <form id="reject-form-{{ $leave->id }}" 
                                          action="{{ route('leave.reject', $leave) }}" 
                                          method="POST" 
                                          class="d-none">
                                        @csrf
                                    </form>

                                    <form id="delete-form-{{ $leave->id }}" 
                                          action="{{ route('leave.destroy', $leave) }}" 
                                          method="POST" 
                                          class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @else
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-primary"
                                                data-bs-toggle="tooltip"
                                                title="Detail"
                                                onclick="showDetail({{ $leave->id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-secondary"
                                                data-bs-toggle="tooltip"
                                                title="Download">
                                            <i class="bi bi-download"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-danger"
                                                data-bs-toggle="tooltip"
                                                title="Hapus"
                                                onclick="confirmDelete({{ $leave->id }})">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>

                                    <form id="delete-form-{{ $leave->id }}" 
                                          action="{{ route('leave.destroy', $leave) }}" 
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
                                    <h5 class="text-muted mb-2">Belum Ada Pengajuan Cuti</h5>
                                    <p class="text-muted mb-3">Belum ada data pengajuan cuti dari karyawan</p>
                                    <a href="{{ route('leave.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>Ajukan Cuti Baru
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
    @if($leaves->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Menampilkan {{ $leaves->firstItem() ?? 0 }} - {{ $leaves->lastItem() ?? 0 }} dari {{ $leaves->total() }} pengajuan
            </div>
            <div>
                {{ $leaves->links() }}
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
                    <i class="bi bi-funnel text-primary me-2"></i>Filter Pengajuan Cuti
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Cuti</label>
                        <select class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="annual">Tahunan</option>
                            <option value="sick">Sakit</option>
                            <option value="maternity">Melahirkan</option>
                            <option value="marriage">Nikah</option>
                            <option value="unpaid">Tidak Dibayar</option>
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
                        <label class="form-label fw-semibold">Karyawan</label>
                        <input type="text" class="form-control" placeholder="Cari nama karyawan...">
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

.leave-row {
    transition: all 0.2s ease;
}

.leave-row:hover {
    background-color: #f8f9fa;
}

.avatar-circle {
    transition: all 0.2s ease;
}

.leave-row:hover .avatar-circle {
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

.quick-filter .btn-sm.active {
    background-color: #4f46e5;
    border-color: #4f46e5;
    color: white;
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

// Search functionality
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.leave-row');
    
    rows.forEach(row => {
        const employeeName = row.querySelector('.fw-semibold').textContent.toLowerCase();
        if (employeeName.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Confirm Approve
function confirmApprove(id) {
    if (confirm('Apakah Anda yakin ingin menyetujui pengajuan cuti ini?')) {
        document.getElementById('approve-form-' + id).submit();
    }
}

// Confirm Reject
function confirmReject(id) {
    const reason = prompt('Masukkan alasan penolakan (opsional):');
    if (reason !== null) {
        document.getElementById('reject-form-' + id).submit();
    }
}

// Confirm Delete
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pengajuan cuti ini? Data tidak dapat dikembalikan.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Show Detail
function showDetail(id) {
    // Implement detail view logic
    console.log('Show detail for leave ID:', id);
    alert('Detail modal akan ditampilkan untuk ID: ' + id);
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