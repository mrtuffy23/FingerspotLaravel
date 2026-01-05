@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- PAGE HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h3 fw-bold text-dark mb-2">
                        <i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
                    </p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-filter me-1"></i> Filter Periode
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                        <li><a class="dropdown-item" href="#">Minggu Ini</a></li>
                        <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Custom Range</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="row g-4 mb-4">
        <!-- Total Karyawan -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm stat-card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase small fw-semibold">Total Karyawan</p>
                            <h2 class="fw-bold mb-0 display-6">
                                {{ \App\Models\Employee::count() }}
                            </h2>
                            <div class="mt-3">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-arrow-up-short"></i> +5% dari bulan lalu
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-people-fill fs-2 text-primary"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hadir Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm stat-card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase small fw-semibold">Hadir Hari Ini</p>
                            <h2 class="fw-bold mb-0 display-6">
                                {{ \App\Models\Attendance::whereDate('date', today())->count() }}
                            </h2>
                            <div class="mt-3">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-check-circle-fill"></i> {{ round((\App\Models\Attendance::whereDate('date', today())->count() / max(\App\Models\Employee::count(), 1)) * 100, 1) }}% Kehadiran
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-calendar-check-fill fs-2 text-success"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ round((\App\Models\Attendance::whereDate('date', today())->count() / max(\App\Models\Employee::count(), 1)) * 100, 1) }}%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cuti Tertunda -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm stat-card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase small fw-semibold">Cuti Tertunda</p>
                            <h2 class="fw-bold mb-0 display-6">
                                {{ \App\Models\Leave::whereNull('approved_at')->count() }}
                            </h2>
                            <div class="mt-3">
                                <span class="badge bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-hourglass-split"></i> Perlu Approval
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-file-earmark-text-fill fs-2 text-warning"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Periode Gaji -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm stat-card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 text-uppercase small fw-semibold">Periode Gaji</p>
                            <h2 class="fw-bold mb-0 display-6">
                                {{ \App\Models\PayrollPeriod::count() }}
                            </h2>
                            <div class="mt-3">
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-cash-stack"></i> Total Periode
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-wallet2 fs-2 text-info"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS & TABLES ROW -->
    <div class="row g-4 mb-4">
        
        <!-- Attendance Chart -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-bar-chart-fill text-primary me-2"></i>Statistik Kehadiran
                            </h5>
                            <small class="text-muted">Kehadiran 7 hari terakhir</small>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active">7 Hari</button>
                            <button type="button" class="btn btn-outline-secondary">30 Hari</button>
                            <button type="button" class="btn btn-outline-secondary">Bulan Ini</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-pie-chart-fill text-success me-2"></i>Status Karyawan
                    </h5>
                    <small class="text-muted">Distribusi status karyawan</small>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <canvas id="statusChart" height="200"></canvas>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2" style="width: 12px; height: 12px;"></span>
                                <span class="small">Aktif</span>
                            </div>
                            <strong>{{ \App\Models\Employee::where('status', 'aktif')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning me-2" style="width: 12px; height: 12px;"></span>
                                <span class="small">Kontrak</span>
                            </div>
                            <strong>{{ \App\Models\Employee::where('status', 'kontrak')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary me-2" style="width: 12px; height: 12px;"></span>
                                <span class="small">Nonaktif</span>
                            </div>
                            <strong>{{ \App\Models\Employee::where('status', 'nonaktif')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-danger me-2" style="width: 12px; height: 12px;"></span>
                                <span class="small">Resign</span>
                            </div>
                            <strong>{{ \App\Models\Employee::where('status', 'resign')->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- TABLES ROW -->
    <div class="row g-4 mb-4">

        <!-- KARYAWAN TERBARU -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-person-lines-fill text-primary me-2"></i>Karyawan Terbaru
                            </h5>
                            <small class="text-muted">5 karyawan terakhir ditambahkan</small>
                        </div>
                        <a href="{{ route('karyawan.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4">Karyawan</th>
                                    <th class="border-0">Posisi</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Employee::latest()->limit(5)->get() as $emp)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    @if($emp->photo)
                                                        <img src="{{ asset($emp->photo) }}" alt="{{ $emp->name }}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <strong>{{ strtoupper(substr($emp->name, 0, 1)) }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $emp->name }}</div>
                                                    <small class="text-muted">{{ $emp->nik }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $emp->position->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'aktif' => 'success',
                                                    'kontrak' => 'warning',
                                                    'nonaktif' => 'secondary',
                                                    'resign' => 'danger'
                                                ];
                                                $color = $statusColors[$emp->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge rounded-pill bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }}">
                                                {{ ucfirst($emp->status) }}
                                            </span>
                                        </td>
                                        <td class="pe-4">
                                            <a href="{{ route('karyawan.show', $emp) }}" class="btn btn-sm btn-light">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                            <p class="mb-0">Belum ada data karyawan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- CUTI TERTUNDA -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-file-earmark-text-fill text-warning me-2"></i>Cuti Tertunda
                            </h5>
                            <small class="text-muted">Pengajuan cuti yang perlu disetujui</small>
                        </div>
                        <a href="{{ route('leave.index') }}" class="btn btn-sm btn-outline-warning">
                            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4">Karyawan</th>
                                    <th class="border-0">Jenis</th>
                                    <th class="border-0">Periode</th>
                                    <th class="border-0 pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Leave::whereNull('approved_at')->latest()->limit(5)->get() as $leave)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    @if($leave->employee->photo)
                                                        <img src="{{ asset($leave->employee->photo) }}" alt="{{ $leave->employee->name }}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <strong>{{ strtoupper(substr($leave->employee->name, 0, 1)) }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $leave->employee->name }}</div>
                                                    <small class="text-muted">{{ $leave->employee->position->name ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                                <i class="bi bi-tag-fill me-1"></i>{{ $leave->type }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <i class="bi bi-calendar-range me-1"></i>
                                                {{ $leave->start_date->format('d M') }} – {{ $leave->end_date->format('d M Y') }}
                                            </div>
                                            <small class="text-muted">{{ $leave->start_date->diffInDays($leave->end_date) + 1 }} hari</small>
                                        </td>
                                        <td class="pe-4">
                                            <a href="{{ route('leave.show', $leave) }}" class="btn btn-sm btn-light">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="bi bi-check-circle fs-1 d-block mb-2 opacity-50"></i>
                                            <p class="mb-0">Tidak ada cuti yang menunggu approval</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-1">
                <i class="bi bi-lightning-charge-fill text-warning me-2"></i>Quick Actions
            </h5>
            <small class="text-muted">Akses cepat ke fitur utama sistem</small>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="{{ route('karyawan.create') }}" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center quick-action-btn">
                        <i class="bi bi-person-plus-fill fs-2 mb-2"></i>
                        <span class="small fw-semibold">+ Karyawan</span>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="{{ route('absen.import') }}" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center quick-action-btn">
                        <i class="bi bi-upload fs-2 mb-2"></i>
                        <span class="small fw-semibold">Import Absensi</span>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="{{ route('payroll.create') }}" class="btn btn-outline-warning w-100 py-3 d-flex flex-column align-items-center quick-action-btn">
                        <i class="bi bi-cash-stack fs-2 mb-2"></i>
                        <span class="small fw-semibold">Buat Payroll</span>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="{{ route('allowance.index') }}" class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center quick-action-btn">
                        <i class="bi bi-wallet2 fs-2 mb-2"></i>
                        <span class="small fw-semibold">Tunjangan</span>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="{{ route('leave.create') }}" class="btn btn-outline-danger w-100 py-3 d-flex flex-column align-items-center quick-action-btn">
                        <i class="bi bi-calendar-plus fs-2 mb-2"></i>
                        <span class="small fw-semibold">Ajukan Cuti</span>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="{{ route('shift-assignments.index') }}" class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center quick-action-btn">
                        <i class="bi bi-clock-history fs-2 mb-2"></i>
                        <span class="small fw-semibold">Atur Shift</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.stat-card-hover {
    transition: all 0.3s ease;
}

.stat-card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

.stat-icon {
    transition: all 0.3s ease;
}

.stat-card-hover:hover .stat-icon {
    transform: scale(1.1);
}

.quick-action-btn {
    transition: all 0.3s ease;
    border-width: 2px;
}

.quick-action-btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.1);
}

.table tbody tr {
    transition: background-color 0.2s ease;
}

.card {
    transition: all 0.3s ease;
}

.avatar-circle img,
.avatar-circle > div {
    transition: all 0.2s ease;
}

.table tbody tr:hover .avatar-circle img,
.table tbody tr:hover .avatar-circle > div {
    transform: scale(1.1);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart');
    if (attendanceCtx) {
        new Chart(attendanceCtx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Hadir',
                    data: [85, 92, 88, 95, 90, 45, 30],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Status Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Kontrak', 'Nonaktif', 'Resign'],
                datasets: [{
                    data: [
                        {{ \App\Models\Employee::where('status', 'aktif')->count() }},
                        {{ \App\Models\Employee::where('status', 'kontrak')->count() }},
                        {{ \App\Models\Employee::where('status', 'nonaktif')->count() }},
                        {{ \App\Models\Employee::where('status', 'resign')->count() }}
                    ],
                    backgroundColor: [
                        '#198754',
                        '#ffc107',
                        '#6c757d',
                        '#dc3545'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    }
});
</script>
@endsection