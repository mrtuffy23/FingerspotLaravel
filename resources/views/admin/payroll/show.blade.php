@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}" class="text-decoration-none">Penggajian</a></li>
                <li class="breadcrumb-item active">Detail Periode #{{ $period->id }}</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="h3 fw-bold text-dark mb-0">
                    <i class="bi bi-calendar-check text-primary me-2"></i>Periode Penggajian #{{ $period->id }}
                </h1>
                <p class="text-muted mt-1 mb-0">
                    <i class="bi bi-calendar-range me-1"></i>
                    {{ $period->start_date->format('d M Y') }} — {{ $period->end_date->format('d M Y') }}
                    <span class="mx-2">•</span>
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ $period->start_date->diffInDays($period->end_date) + 1 }} Hari
                </p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @if($period->status === 'finalized')
                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2">
                        <i class="bi bi-check-circle-fill me-1"></i>Finalized
                    </span>
                @else
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2">
                        <i class="bi bi-hourglass-split me-1"></i>Draft
                    </span>
                @endif
                <div class="btn-group">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="bi bi-download me-1"></i>Export
                    </button>
                    <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SUMMARY STATISTICS -->
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold">Total Karyawan</small>
                        <h2 class="fw-bold mt-2 mb-0">{{ $payrolls->count() }}</h2>
                        <small class="text-muted">Dalam periode ini</small>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people-fill text-primary fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold">Total Jam Kerja</small>
                        <h2 class="fw-bold mt-2 mb-0">{{ number_format($payrolls->sum('total_actual_hours'), 0) }}</h2>
                        <small class="text-info">
                            <i class="bi bi-plus-circle me-1"></i>
                            +{{ number_format($payrolls->sum('total_compensated_hours'), 0) }} kompensasi
                        </small>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-clock-fill text-info fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold">Total Gaji Kotor</small>
                        <h2 class="fw-bold mt-2 mb-0">Rp {{ number_format($payrolls->sum('total_salary') / 1000000, 1) }}M</h2>
                        <small class="text-success">
                            <i class="bi bi-wallet2 me-1"></i>
                            Total pendapatan
                        </small>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-cash-stack text-success fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold">Gaji Bersih</small>
                        <h2 class="fw-bold mt-2 mb-0">Rp {{ number_format($payrolls->sum('net_salary') / 1000000, 1) }}M</h2>
                        <small class="text-danger">
                            <i class="bi bi-dash-circle me-1"></i>
                            Setelah potongan
                        </small>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-wallet-fill text-warning fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DETAILED BREAKDOWN -->
<div class="row g-4 mb-4">
    
    <!-- Breakdown Chart -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-pie-chart text-primary me-2"></i>Breakdown Gaji
                </h6>
            </div>
            <div class="card-body">
                <div class="breakdown-item mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Gaji Pokok</span>
                        <strong>Rp {{ number_format($payrolls->sum('base_salary'), 0, ',', '.') }}</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: 50%"></div>
                    </div>
                </div>

                <div class="breakdown-item mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Tunjangan Tetap</span>
                        <strong>Rp {{ number_format($payrolls->sum('total_fixed_allowance'), 0, ',', '.') }}</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 30%"></div>
                    </div>
                </div>

                <div class="breakdown-item mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Tunjangan Tidak Tetap</span>
                        <strong>Rp {{ number_format($payrolls->sum('total_variable_allowance'), 0, ',', '.') }}</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: 20%"></div>
                    </div>
                </div>

                <div class="breakdown-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-danger">Total Potongan</span>
                        <strong class="text-danger">Rp {{ number_format($payrolls->sum('total_deduction'), 0, ',', '.') }}</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-danger" style="width: 15%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hours Breakdown -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-clock-history text-info me-2"></i>Rincian Jam Kerja
                </h6>
            </div>
            <div class="card-body">
                <div class="stat-box bg-light rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Jam Kerja Aktual</small>
                            <h4 class="fw-bold mb-0 text-primary">{{ number_format($payrolls->sum('total_actual_hours'), 1) }}</h4>
                        </div>
                        <i class="bi bi-clock text-primary fs-2 opacity-25"></i>
                    </div>
                </div>

                <div class="stat-box bg-light rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Jam Kompensasi</small>
                            <h4 class="fw-bold mb-0 text-warning">{{ number_format($payrolls->sum('total_compensated_hours'), 1) }}</h4>
                        </div>
                        <i class="bi bi-plus-circle text-warning fs-2 opacity-25"></i>
                    </div>
                </div>

                <div class="stat-box bg-primary bg-opacity-10 rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-primary d-block fw-semibold">Total Jam</small>
                            <h4 class="fw-bold mb-0 text-primary">{{ number_format($payrolls->sum('total_hours'), 1) }}</h4>
                        </div>
                        <i class="bi bi-calendar-check text-primary fs-2 opacity-50"></i>
                    </div>
                </div>

                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Rata-rata: {{ number_format($payrolls->avg('total_hours'), 1) }} jam/karyawan
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Deductions -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-dash-circle text-danger me-2"></i>Potongan
                </h6>
            </div>
            <div class="card-body">
                <div class="stat-box bg-light rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Potongan Tetap</small>
                            <h4 class="fw-bold mb-0 text-danger">Rp {{ number_format($payrolls->sum('total_fixed_deduction') / 1000, 0) }}K</h4>
                        </div>
                        <i class="bi bi-file-minus text-danger fs-2 opacity-25"></i>
                    </div>
                </div>

                <div class="stat-box bg-light rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Potongan Tidak Tetap</small>
                            <h4 class="fw-bold mb-0 text-warning">Rp {{ number_format($payrolls->sum('total_variable_deduction') / 1000, 0) }}K</h4>
                        </div>
                        <i class="bi bi-clipboard-minus text-warning fs-2 opacity-25"></i>
                    </div>
                </div>

                <div class="stat-box bg-danger bg-opacity-10 rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-danger d-block fw-semibold">Total Potongan</small>
                            <h4 class="fw-bold mb-0 text-danger">Rp {{ number_format($payrolls->sum('total_deduction') / 1000, 0) }}K</h4>
                        </div>
                        <i class="bi bi-exclamation-triangle text-danger fs-2 opacity-50"></i>
                    </div>
                </div>

                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ number_format(($payrolls->sum('total_deduction') / $payrolls->sum('total_salary')) * 100, 1) }}% dari gaji kotor
                    </small>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- PAYROLL TABLE -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-table text-primary me-2"></i>Detail Gaji Karyawan
                </h5>
                <small class="text-muted">Daftar lengkap penggajian periode ini</small>
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
                        <th class="border-0 ps-4">Karyawan</th>
                        <th class="border-0 text-center">Jam Aktual</th>
                        <th class="border-0 text-center">Kompensasi</th>
                        <th class="border-0 text-center">Total Jam</th>
                        <th class="border-0 text-end">Gaji Pokok</th>
                        <th class="border-0 text-end">Tunjangan</th>
                        <th class="border-0 text-end">Potongan</th>
                        <th class="border-0 text-end">Gaji Bersih</th>
                        <th class="border-0 text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr class="payroll-row">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <strong>{{ strtoupper(substr($payroll->employee->name, 0, 1)) }}</strong>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $payroll->employee->name }}</div>
                                        <small class="text-muted">{{ $payroll->employee->position->name ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                    {{ number_format($payroll->total_actual_hours, 1) }}h
                                </span>
                            </td>

                            <td class="text-center">
                                @if($payroll->total_compensated_hours > 0)
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">
                                        +{{ number_format($payroll->total_compensated_hours, 1) }}h
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <strong class="text-primary">{{ number_format($payroll->total_hours, 1) }}h</strong>
                            </td>

                            <td class="text-end">
                                <div class="fw-semibold">Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}</div>
                            </td>

                            <td class="text-end">
                                <div class="text-success">
                                    Rp {{ number_format($payroll->total_fixed_allowance + $payroll->total_variable_allowance, 0, ',', '.') }}
                                </div>
                                <small class="text-muted d-block">
                                    T:{{ number_format($payroll->total_fixed_allowance / 1000, 0) }}K 
                                    V:{{ number_format($payroll->total_variable_allowance / 1000, 0) }}K
                                </small>
                            </td>

                            <td class="text-end">
                                <div class="text-danger fw-semibold">
                                    Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="fw-bold text-primary fs-6">
                                    Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                                </div>
                            </td>

                            <td class="text-center pe-4">
                                <a href="{{ route('payroll.slip', $payroll->id) }}" 
                                   class="btn btn-sm btn-primary"
                                   data-bs-toggle="tooltip"
                                   title="Cetak Slip Gaji">
                                    <i class="bi bi-printer-fill"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                    <h6 class="text-muted">Tidak Ada Data Payroll</h6>
                                    <p class="text-muted small mb-0">Belum ada data penggajian untuk periode ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($payrolls->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Menampilkan {{ $payrolls->firstItem() ?? 0 }} - {{ $payrolls->lastItem() ?? 0 }} dari {{ $payrolls->total() }} karyawan
            </div>
            <div>
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-download text-primary me-2"></i>Export Data Payroll
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush">
                    <button class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-file-earmark-excel text-success fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold">Export ke Excel</div>
                            <small class="text-muted">Format .xlsx dengan semua detail</small>
                        </div>
                    </button>
                    <button class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-file-earmark-pdf text-danger fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold">Export ke PDF</div>
                            <small class="text-muted">Laporan ringkasan periode</small>
                        </div>
                    </button>
                    <button class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-filetype-csv text-primary fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold">Export ke CSV</div>
                            <small class="text-muted">Data mentah untuk analisis</small>
                        </div>
                    </button>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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

.avatar-circle {
    transition: all 0.2s ease;
}

.payroll-row:hover .avatar-circle {
    transform: scale(1.1);
}

.breakdown-item {
    padding-bottom: 12px;
}

.stat-box {
    transition: all 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    border-radius: 10px;
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

.list-group-item {
    border: none;
    border-radius: 12px !important;
    margin-bottom: 8px;
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
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

// Search functionality
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.payroll-row');
    
    rows.forEach(row => {
        const employeeName = row.querySelector('.fw-semibold').textContent.toLowerCase();
        if (employeeName.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endpush