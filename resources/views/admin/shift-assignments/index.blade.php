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
                        <li class="breadcrumb-item active">Penugasan Shift</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-dark mb-0">
                    <i class="bi bi-clock-history text-primary me-2"></i>Penugasan Shift Karyawan
                </h1>
                <p class="text-muted mt-1 mb-0">Kelola jadwal shift untuk karyawan harian</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkAssignModal">
                    <i class="bi bi-people me-2"></i>Assign Massal
                </button>
                <a href="{{ route('shift-assignments.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Penugasan
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
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Total Assignment</p>
                        <h3 class="fw-bold mb-0">{{ $assignments->total() }}</h3>
                        <small class="text-muted">Semua penugasan</small>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-clipboard-check-fill text-primary fs-4"></i>
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
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Shift Aktif</p>
                        <h3 class="fw-bold mb-0">
                            {{ $assignments->filter(function($a) { 
                                return !$a->end_date || \Carbon\Carbon::parse($a->end_date)->isFuture(); 
                            })->count() }}
                        </h3>
                        <small class="text-success"><i class="bi bi-check-circle"></i> Sedang berjalan</small>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-play-circle-fill text-success fs-4"></i>
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
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Karyawan Harian</p>
                        <h3 class="fw-bold mb-0">
                            {{ $assignments->where('employee.employment_type', 'daily')->unique('employee_id')->count() }}
                        </h3>
                        <small class="text-info"><i class="bi bi-calendar-week"></i> Dengan shift</small>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people-fill text-info fs-4"></i>
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
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Shift Berakhir</p>
                        <h3 class="fw-bold mb-0">
                            {{ $assignments->filter(function($a) { 
                                return $a->end_date && \Carbon\Carbon::parse($a->end_date)->isPast(); 
                            })->count() }}
                        </h3>
                        <small class="text-danger"><i class="bi bi-x-circle"></i> Sudah selesai</small>
                    </div>
                    <div class="stat-icon bg-danger bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-stop-circle-fill text-danger fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FILTER CARD -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-funnel-fill text-primary me-2"></i>Filter Penugasan
            </h5>
            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="bi bi-chevron-down me-1"></i>Toggle Filter
            </button>
        </div>
    </div>
    
    <div class="collapse show" id="filterCollapse">
        <div class="card-body bg-light">
            <form method="GET" action="{{ route('shift-assignments.index') }}">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold small">
                            <i class="bi bi-person-circle me-1"></i>Karyawan
                        </label>
                        <select name="employee_id" class="form-select">
                            <option value="">🔍 Semua Karyawan</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold small">
                            <i class="bi bi-clock me-1"></i>Shift
                        </label>
                        <select name="shift_id" class="form-select">
                            <option value="">⏰ Semua Shift</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}" {{ request('shift_id') == $shift->id ? 'selected' : '' }}>
                                    {{ $shift->code }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold small">
                            <i class="bi bi-briefcase me-1"></i>Tipe Karyawan
                        </label>
                        <select name="employment_type" class="form-select">
                            <option value="">📋 Semua Tipe</option>
                            <option value="monthly" {{ request('employment_type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="daily" {{ request('employment_type') == 'daily' ? 'selected' : '' }}>Harian</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fw-semibold small">
                            <i class="bi bi-flag me-1"></i>Status
                        </label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Aktif</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>❌ Berakhir</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-12 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('shift-assignments.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MAIN TABLE -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-table text-primary me-2"></i>Daftar Penugasan Shift
                </h5>
                <small class="text-muted">Menampilkan {{ $assignments->count() }} dari {{ $assignments->total() }} penugasan</small>
            </div>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control border-start-0" placeholder="Cari karyawan..." id="searchInput">
            </div>
        </div>
    </div>

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
                        <th class="border-0 text-center">Departemen</th>
                        <th class="border-0 text-center">Tipe</th>
                        <th class="border-0 text-center">Shift</th>
                        <th class="border-0 text-center">Periode</th>
                        <th class="border-0 text-center">Status</th>
                        <th class="border-0 text-center pe-4">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($assignments as $assignment)
                        <tr class="assignment-row">
                            <td class="ps-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $assignment->id }}">
                                </div>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <strong>{{ strtoupper(substr($assignment->employee->name, 0, 1)) }}</strong>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $assignment->employee->name }}</div>
                                        <small class="text-muted">PIN: {{ $assignment->employee->pin }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-light text-dark border">
                                    {{ $assignment->employee->department->name ?? '-' }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if($assignment->employee->employment_type == 'monthly')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2">
                                        <i class="bi bi-calendar-month me-1"></i>Bulanan
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2">
                                        <i class="bi bi-calendar-week me-1"></i>Harian
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="shift-badge">
                                    <div class="fw-bold text-primary">{{ $assignment->shift->code }}</div>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $assignment->shift->start_time }} - {{ $assignment->shift->end_time }}
                                    </small>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="d-flex flex-column">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-range me-1"></i>Mulai
                                    </small>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($assignment->start_date)->format('d M Y') }}</span>
                                    
                                    @if($assignment->end_date)
                                        <small class="text-muted mt-1">
                                            <i class="bi bi-calendar-x me-1"></i>Selesai
                                        </small>
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($assignment->end_date)->format('d M Y') }}</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success mt-1">
                                            <i class="bi bi-infinity"></i> Tidak Terbatas
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="text-center">
                                @if(!$assignment->end_date || \Carbon\Carbon::parse($assignment->end_date)->isFuture())
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2">
                                        <i class="bi bi-x-circle-fill me-1"></i>Berakhir
                                    </span>
                                @endif
                            </td>

                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('shift-assignments.edit', $assignment) }}" 
                                       class="btn btn-sm btn-light text-warning"
                                       data-bs-toggle="tooltip"
                                       title="Edit Assignment">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-light text-danger"
                                            data-bs-toggle="tooltip"
                                            title="Hapus"
                                            onclick="confirmDelete({{ $assignment->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $assignment->id }}" 
                                      action="{{ route('shift-assignments.destroy', $assignment) }}" 
                                      method="POST" 
                                      class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                    <h6 class="text-muted mb-2">Tidak Ada Penugasan Shift</h6>
                                    <p class="text-muted small mb-3">Belum ada karyawan yang ditugaskan ke shift</p>
                                    <a href="{{ route('shift-assignments.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>Tambah Penugasan Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($assignments->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Menampilkan {{ $assignments->firstItem() ?? 0 }} - {{ $assignments->lastItem() ?? 0 }} dari {{ $assignments->total() }} penugasan
            </div>
            <div>
                {{ $assignments->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Bulk Assign Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-people text-primary me-2"></i>Assign Shift Massal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Karyawan</label>
                        <select class="form-select" multiple size="5">
                            @foreach($employees->where('employment_type', 'daily') as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} - {{ $emp->nik }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd untuk pilih multiple</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Shift</label>
                        <select class="form-select">
                            <option value="">Pilih Shift</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->code }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tanggal Akhir</label>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Assign Semua
                </button>
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

.assignment-row {
    transition: all 0.2s ease;
}

.assignment-row:hover {
    background-color: #f8f9fa;
}

.avatar-circle {
    transition: all 0.2s ease;
}

.assignment-row:hover .avatar-circle {
    transform: scale(1.1);
}

.shift-badge {
    padding: 8px;
    background: #f8fafc;
    border-radius: 8px;
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

// Select All
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});

// Search functionality
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.assignment-row');
    
    rows.forEach(row => {
        const employeeName = row.querySelector('.fw-semibold').textContent.toLowerCase();
        if (employeeName.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Confirm Delete
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus penugasan shift ini?\n\nTindakan ini tidak dapat dibatalkan.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush