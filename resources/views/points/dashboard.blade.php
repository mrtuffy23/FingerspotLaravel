@extends('layouts.admin')

@section('content')
<style>
    .stat-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.2s ease;
        background: white;
    }
    
    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #212529;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .card-header {
        background: white;
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }
    
    .card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #212529;
        margin: 0;
    }
    
    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        color: #495057;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
    }
    
    .table tbody tr {
        transition: background-color 0.15s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .badge {
        padding: 0.5rem 0.875rem;
        font-weight: 500;
        font-size: 0.875rem;
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: #6c757d;
        font-size: 0.95rem;
    }
    
    .alert-modern {
        border: 1px solid #f5c6cb;
        border-left: 4px solid #dc3545;
        border-radius: 8px;
        background: #fff5f5;
    }
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Dashboard Poin Karyawan</h1>
                <p class="page-subtitle mb-0">Monitoring dan manajemen poin karyawan</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('points.history') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-clock-history"></i> Riwayat
                </a>
                <a href="{{ route('points.adjustment-form') }}" class="btn btn-outline-warning">
                    <i class="bi bi-gear"></i> Adjustment
                </a>
                <a href="{{ route('points.export-report') }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="stat-label mb-1">Total Karyawan Aktif</div>
                <div class="stat-number">{{ $statistics['total_active_employees'] }}</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
                <div class="stat-label mb-1">Rata-rata Poin</div>
                <div class="stat-number">{{ number_format($statistics['average_points'], 1) }}</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
                <div class="stat-label mb-1">Poin < 50</div>
                <div class="stat-number">{{ $statistics['below_50_points'] }}</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-x-octagon-fill"></i>
                    </div>
                </div>
                <div class="stat-label mb-1">KRITIS (< 20)</div>
                <div class="stat-number">{{ $statistics['critical_below_20'] }}</div>
            </div>
        </div>
    </div>

    <!-- Alert Section -->
    @if($statistics['critical_below_20'] > 0)
    <div class="alert alert-modern alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-circle-fill text-danger me-3" style="font-size: 1.5rem;"></i>
            <div>
                <h6 class="alert-heading mb-1">Perhatian: Karyawan Kritis Terdeteksi</h6>
                <p class="mb-0">Terdapat <strong>{{ $statistics['critical_below_20'] }} karyawan</strong> dengan poin di bawah 20. Segera lakukan tindakan korektif.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Charts and Lowest Points -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Karyawan dengan Poin Terendah</h5>
                        <span class="badge bg-danger">Top 5</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nama</th>
                                    <th>PIN</th>
                                    <th>Poin</th>
                                    <th style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowestPointEmployees as $index => $emp)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="fw-semibold">{{ $emp->name }}</td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $emp->pin }}</code>
                                        </td>
                                        <td>
                                            <span class="badge @if($emp->current_points < 20) bg-danger @elseif($emp->current_points < 50) bg-warning text-dark @else bg-success @endif">
                                                {{ $emp->current_points }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('points.employee-detail', $emp->id) }}" class="btn btn-sm btn-primary">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-check-circle d-block mb-2" style="font-size: 2rem;"></i>
                                            Semua karyawan memiliki poin baik
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Points Distribution Chart -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Distribusi Poin Karyawan</h5>
                </div>
                <div class="card-body">
                    <canvas id="pointsChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Daftar Semua Karyawan</h5>
                        <span class="badge bg-primary">{{ $statistics['total_active_employees'] }} Karyawan</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>PIN</th>
                                    <th>Nama</th>
                                    <th>Departemen</th>
                                    <th>Posisi</th>
                                    <th>Poin</th>
                                    <th>Status</th>
                                    <th style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    <tr>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $employee->pin }}</code>
                                        </td>
                                        <td class="fw-semibold">{{ $employee->name }}</td>
                                        <td>{{ $employee->department?->name ?? '-' }}</td>
                                        <td>{{ $employee->position?->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge @if($employee->current_points < 20) bg-danger @elseif($employee->current_points < 50) bg-warning text-dark @else bg-success @endif">
                                                {{ $employee->current_points }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($employee->status) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('points.employee-detail', $employee->id) }}" class="btn btn-sm btn-primary">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="bi bi-inbox d-block mb-2" style="font-size: 2.5rem;"></i>
                                            Tidak ada karyawan aktif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-3 bg-light border-top">
                        {{ $employees->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Points distribution chart
    const pointRanges = ['0-20', '21-50', '51-75', '76-100'];
    const pointCounts = [
        {{ $statistics['critical_below_20'] }},
        {{ $statistics['below_50_points'] - $statistics['critical_below_20'] }},
        {{ $statistics['total_active_employees'] - $statistics['below_50_points'] - ($statistics['total_active_employees'] - $statistics['below_50_points']) / 2 }},
        {{ ($statistics['total_active_employees'] - $statistics['below_50_points']) / 2 }}
    ];

    const ctx = document.getElementById('pointsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: pointRanges,
            datasets: [{
                label: 'Jumlah Karyawan',
                data: pointCounts,
                backgroundColor: ['#dc3545', '#ffc107', '#0dcaf0', '#198754'],
                borderColor: ['#dc3545', '#ffc107', '#0dcaf0', '#198754'],
                borderWidth: 2,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: '#212529',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 13,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 12
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: '#e9ecef',
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: '500'
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection