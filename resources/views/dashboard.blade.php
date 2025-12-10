@extends('layouts.admin')

@section('content')

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    .card-modern {
        border: none;
        border-radius: 18px;
        transition: .25s ease;
    }
    .card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
    .quick-btn {
        border-radius: 12px;
        padding: 15px 22px;
        font-size: 14px;
        transition: .2s;
    }
    .quick-btn:hover {
        transform: scale(1.05);
    }
</style>

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="fw-bold mb-3">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard Penggajian
        </h1>
        <p class="text-muted">Ringkasan aktivitas sistem penggajian & kehadiran</p>
    </div>
</div>

<div class="row g-3">

    <!-- Total Karyawan -->
    <div class="col-md-3">
        <div class="card card-modern bg-primary text-white shadow-sm">
            <div class="card-body">
                <h6 class="text-white-50">Total Karyawan</h6>
                <h2><i class="bi bi-people-fill me-2"></i>{{ \App\Models\Employee::count() }}</h2>
            </div>
        </div>
    </div>

    <!-- Kehadiran Hari Ini -->
    <div class="col-md-3">
        <div class="card card-modern bg-success text-white shadow-sm">
            <div class="card-body">
                <h6 class="text-white-50">Kehadiran Hari Ini</h6>
                <h2><i class="bi bi-calendar-check me-2"></i>{{ \App\Models\Attendance::whereDate('date', today())->count() }}</h2>
            </div>
        </div>
    </div>

    <!-- Cuti Tertunda -->
    <div class="col-md-3">
        <div class="card card-modern bg-warning text-dark shadow-sm">
            <div class="card-body">
                <h6 class="text-dark-50">Cuti Tertunda</h6>
                <h2><i class="bi bi-hourglass-split me-2"></i>{{ \App\Models\Leave::whereNull('approved_at')->count() }}</h2>
            </div>
        </div>
    </div>

    <!-- Masa Aktif -->
    <div class="col-md-3">
        <div class="card card-modern bg-info text-white shadow-sm">
            <div class="card-body">
                <h6 class="text-white-50">Periode Penggajian</h6>
                <h2><i class="bi bi-clock-history me-2"></i>{{ \App\Models\PayrollPeriod::count() }}</h2>
            </div>
        </div>
    </div>

</div>


<!-- Karyawan & Cuti -->
<div class="row mt-4">

    <!-- Karyawan Terkini -->
    <div class="col-md-6">
        <div class="card card-modern shadow-sm">
            <div class="card-header bg-white">
                <h5 class="fw-semibold"><i class="bi bi-person-lines-fill me-1"></i> Karyawan Terkini</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Posisi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Employee::latest()->limit(5)->get() as $emp)
                        <tr>
                            <td>{{ $emp->name }}</td>
                            <td>{{ $emp->position->name ?? '-' }}</td>
                            <td>
                                <span class="badge rounded-pill bg-{{ $emp->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($emp->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Tidak ada karyawan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cuti Tertunda -->
    <div class="col-md-6">
        <div class="card card-modern shadow-sm">
            <div class="card-header bg-white">
                <h5 class="fw-semibold"><i class="bi bi-file-earmark-text me-1"></i> Permintaan Cuti Tertunda</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Type</th>
                            <th>Dates</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Leave::whereNull('approved_at')->latest()->limit(5)->get() as $leave)
                        <tr>
                            <td>{{ $leave->employee->name }}</td>
                            <td><span class="badge bg-info rounded-pill">{{ $leave->type }}</span></td>
                            <td>{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}</td>
                        </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Tidak ada cuti tertunda</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>


<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-modern shadow-sm">
            <div class="card-header bg-white">
                <h5 class="fw-semibold"><i class="bi bi-lightning-charge-fill me-1"></i> Quick Actions</h5>
            </div>
            <div class="card-body">

                <a href="{{ route('karyawan.create') }}" class="btn btn-primary quick-btn me-2">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah Karyawan
                </a>

                <a href="{{ route('absen.import') }}" class="btn btn-success quick-btn me-2">
                    <i class="bi bi-upload me-1"></i> Impor Kehadiran
                </a>

                <a href="{{ route('payroll.create') }}" class="btn btn-warning quick-btn me-2">
                    <i class="bi bi-cash-stack me-1"></i> Buat Penggajian
                </a>

                <a href="{{ route('leave.create') }}" class="btn btn-info quick-btn">
                    <i class="bi bi-calendar-plus me-1"></i> Cuti Baru
                </a>

            </div>
        </div>
    </div>
</div>

@endsection
