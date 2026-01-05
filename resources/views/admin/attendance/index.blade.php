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
                            <li class="breadcrumb-item active">Kehadiran</li>
                        </ol>
                    </nav>
                    <h1 class="h3 fw-bold text-dark mb-0">
                        <i class="bi bi-calendar-check-fill text-primary me-2"></i>Manajemen Kehadiran
                    </h1>
                    <p class="text-muted mt-1 mb-0">Kelola dan pantau kehadiran karyawan</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('attendance.create') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Absensi
                    </a>
                    <a href="{{ route('absen.import') }}" class="btn btn-success shadow-sm">
                        <i class="bi bi-upload me-2"></i>Import CSV
                    </a>
                    <button class="btn btn-outline-secondary shadow-sm" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="bi bi-download me-2"></i>Export
                    </button>
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
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Total Hadir Hari Ini</p>
                            <h3 class="fw-bold mb-0">{{ $attendances->where('date', today())->where('status', 'present')->count() }}</h3>
                            <small class="text-success"><i class="bi bi-arrow-up"></i> Dari total karyawan</small>
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
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Terlambat</p>
                            <h3 class="fw-bold mb-0">{{ $attendances->where('date', today())->where('status', 'late')->count() }}</h3>
                            <small class="text-warning"><i class="bi bi-exclamation-triangle"></i> Perlu perhatian</small>
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
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Tidak Hadir</p>
                            <h3 class="fw-bold mb-0">{{ $attendances->where('date', today())->where('status', 'absent')->count() }}</h3>
                            <small class="text-danger"><i class="bi bi-x-circle"></i> Alpa hari ini</small>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-person-x-fill text-danger fs-4"></i>
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
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Izin/Cuti</p>
                            <h3 class="fw-bold mb-0">{{ $attendances->where('date', today())->whereIn('status', ['on_leave', 'permission', 'sick'])->count() }}</h3>
                            <small class="text-info"><i class="bi bi-file-text"></i> Dengan keterangan</small>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-file-earmark-check-fill text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & TABLE CARD -->
    <div class="card border-0 shadow-sm">
        
        <!-- Filter Section -->
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-funnel-fill text-primary me-2"></i>Filter & Pencarian
                    </h5>
                    <small class="text-muted">Gunakan filter untuk menemukan data kehadiran</small>
                </div>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="bi bi-chevron-down me-1"></i>Toggle Filter
                </button>
            </div>
        </div>

        <div class="collapse show" id="filterCollapse">
            <div class="card-body bg-light border-bottom">
                <form method="GET" action="{{ route('attendance.index') }}">
                    <div class="row g-3">
                        
                        <!-- Single Date -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-calendar3 me-1"></i>Tanggal Spesifik
                            </label>
                            <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                        </div>

                        <!-- Date Range Start -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-calendar-range me-1"></i>Dari Tanggal
                            </label>
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                        </div>

                        <!-- Date Range End -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-calendar-range me-1"></i>Sampai Tanggal
                            </label>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                        </div>

                        <!-- Employee -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-person-circle me-1"></i>Karyawan
                            </label>
                            <select class="form-select" name="employee_id">
                                <option value="">🔍 Semua Karyawan</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-flag-fill me-1"></i>Status Kehadiran
                            </label>
                            <select class="form-select" name="status">
                                <option value="">📋 Semua Status</option>
                                <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>✅ Hadir</option>
                                <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>⚠️ Telat</option>
                                <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>❌ Alpa</option>
                                <option value="sick" {{ request('status') === 'sick' ? 'selected' : '' }}>🏥 Sakit</option>
                                <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>🏖️ Cuti</option>
                                <option value="permission" {{ request('status') === 'permission' ? 'selected' : '' }}>📝 Izin</option>
                                <option value="early_leave" {{ request('status') === 'early_leave' ? 'selected' : '' }}>🏃 Pulang Cepat</option>
                                <option value="accident" {{ request('status') === 'accident' ? 'selected' : '' }}>🚑 Kecelakaan</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="col-lg-9 col-md-6 d-flex gap-2 align-items-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-search me-2"></i>Cari Data
                            </button>
                            <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                            </a>
                            @if(request()->hasAny(['date', 'start_date', 'end_date', 'employee_id', 'status']))
                                <span class="badge bg-primary py-2 px-3 ms-2">
                                    <i class="bi bi-funnel me-1"></i>Filter Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 ps-4 py-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th class="border-0">Karyawan</th>
                            <th class="border-0 text-center">Tanggal</th>
                            <th class="border-0 text-center">Check In</th>
                            <th class="border-0 text-center">Check Out</th>
                            <th class="border-0 text-center">Jam Kerja</th>
                            <th class="border-0 text-center">Kompensasi</th>
                            <th class="border-0 text-center">Status</th>
                            <th class="border-0 text-center pe-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr class="attendance-row">
                            <td class="ps-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $attendance->id }}">
                                </div>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-wrapper me-3">
                                        @if($attendance->employee->photo)
                                            <img src="{{ asset($attendance->employee->photo) }}" alt="{{ $attendance->employee->name }}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                        @else
                                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <strong>{{ strtoupper(substr($attendance->employee->name, 0, 1)) }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $attendance->employee->name }}</div>
                                        <small class="text-muted">{{ $attendance->employee->nik }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $attendance->date->format('d M Y') }}</span>
                                    <small class="text-muted">{{ $attendance->date->isoFormat('dddd') }}</small>
                                </div>
                            </td>

                            <td class="text-center">
                                @if($attendance->first_in)
                                    <div class="time-badge bg-success bg-opacity-10 text-success rounded-3 px-3 py-2">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>
                                        <strong>{{ $attendance->first_in->format('H:i') }}</strong>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($attendance->last_out)
                                    <div class="time-badge bg-danger bg-opacity-10 text-danger rounded-3 px-3 py-2">
                                        <i class="bi bi-box-arrow-right me-1"></i>
                                        <strong>{{ $attendance->last_out->format('H:i') }}</strong>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($attendance->work_hours)
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-primary">{{ $attendance->work_hours }} Jam</span>
                                        <small class="text-muted">Total Kerja</small>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @php
                                    $leaveComp = $attendance->getLeaveCompensation();
                                    $totalComp = $attendance->calculateTotalCompensation();
                                @endphp
                                @if($leaveComp > 0 || $totalComp > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2">
                                        <i class="bi bi-plus-circle-fill me-1"></i>
                                        {{ number_format($totalComp, 1) }} Jam
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @php
                                    $statusConfig = [
                                        'present' => ['color' => 'success', 'icon' => 'check-circle-fill', 'label' => 'Hadir'],
                                        'absent' => ['color' => 'danger', 'icon' => 'x-circle-fill', 'label' => 'Alpa'],
                                        'late' => ['color' => 'warning', 'icon' => 'clock-fill', 'label' => 'Telat'],
                                        'sick' => ['color' => 'info', 'icon' => 'hospital-fill', 'label' => 'Sakit'],
                                        'on_leave' => ['color' => 'secondary', 'icon' => 'calendar-x-fill', 'label' => 'Cuti'],
                                        'early_leave' => ['color' => 'warning', 'icon' => 'door-open-fill', 'label' => 'Pulang Cepat'],
                                        'accident' => ['color' => 'danger', 'icon' => 'bandaid-fill', 'label' => 'Kecelakaan'],
                                        'holiday' => ['color' => 'dark', 'icon' => 'calendar-event-fill', 'label' => 'Libur'],
                                        'permission' => ['color' => 'secondary', 'icon' => 'file-earmark-text-fill', 'label' => 'Izin'],
                                        'out_permission' => ['color' => 'secondary', 'icon' => 'arrow-right-circle-fill', 'label' => 'Izin Keluar'],
                                    ];
                                    
                                    $status = $statusConfig[$attendance->status] ?? ['color' => 'dark', 'icon' => 'question-circle-fill', 'label' => $attendance->status];
                                @endphp

                                <span class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }} border border-{{ $status['color'] }} px-3 py-2 text-uppercase">
                                    <i class="bi bi-{{ $status['icon'] }} me-1"></i>
                                    {{ $status['label'] }}
                                </span>
                            </td>

                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('attendance.show', $attendance) }}" 
                                       class="btn btn-sm btn-light" 
                                       data-bs-toggle="tooltip" 
                                       title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('attendance.edit', $attendance) }}" 
                                       class="btn btn-sm btn-light text-warning" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn btn-sm btn-light text-danger" 
                                            data-bs-toggle="tooltip" 
                                            title="Hapus"
                                            onclick="confirmDelete({{ $attendance->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                    <h5 class="text-muted mb-2">Tidak Ada Data</h5>
                                    <p class="text-muted mb-0">
                                        @if(request()->hasAny(['date', 'start_date', 'end_date', 'employee_id', 'status']))
                                            Tidak ada data absensi yang sesuai dengan filter yang dipilih.
                                        @else
                                            Belum ada data absensi yang tersimpan di sistem.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Footer -->
        @if($attendances->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan {{ $attendances->firstItem() ?? 0 }} - {{ $attendances->lastItem() ?? 0 }} dari {{ $attendances->total() }} data
                </div>
                <div>
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
        @endif

    </div>

</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-download text-primary me-2"></i>Export Data Kehadiran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Format Export</label>
                        <select class="form-select">
                            <option>Excel (.xlsx)</option>
                            <option>CSV (.csv)</option>
                            <option>PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Periode</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" class="form-control" placeholder="Dari">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control" placeholder="Sampai">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-download me-2"></i>Download
                </button>
            </div>
        </div>
    </div>@endsection

@push('styles')
<style>
.stat-card {
    transition: all 0.3s ease;
    border-radius: 12px;
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

.attendance-row {
    transition: all 0.2s ease;
}

.attendance-row:hover {
    background-color: #f8f9fa;
}

.avatar-wrapper img,
.avatar-circle {
    transition: all 0.2s ease;
}

.attendance-row:hover .avatar-wrapper img,
.attendance-row:hover .avatar-circle {
    transform: scale(1.1);
}

.time-badge {
    display: inline-block;
    font-size: 13px;
    min-width: 80px;
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
    border-radius: 12px;
}

.form-control:focus,
.form-select:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1);
}
</style>
@endpush

@push('scripts')
<script>
// Select All Checkbox
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});

// Initialize Tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

// Confirm Delete
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data kehadiran ini?')) {
        // Implement delete logic
        console.log('Delete attendance:', id);
    }
}
</script>
@endpush