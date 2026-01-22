@extends('layouts.admin')

@section('content')
<style>
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
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.25rem;
    }
    
    .info-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 500;
        color: #6c757d;
    }
    
    .info-value {
        color: #212529;
        font-weight: 500;
    }
    
    .stat-box {
        text-align: center;
        padding: 1.5rem 1rem;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #212529;
        margin: 0.5rem 0;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .badge {
        padding: 0.5rem 0.875rem;
        font-weight: 500;
        font-size: 0.875rem;
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
    
    .points-display {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1;
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
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Detail Poin Karyawan</h1>
                <p class="text-muted mb-0">{{ $employee->name }} • PIN: {{ $employee->pin }}</p>
            </div>
            <a href="{{ route('points.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Employee Info Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-person-badge"></i> Informasi Karyawan
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="info-card m-3">
                        <div class="info-row">
                            <span class="info-label">Nama</span>
                            <span class="info-value">{{ $employee->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">PIN</span>
                            <span class="info-value">
                                <code class="bg-light px-2 py-1 rounded">{{ $employee->pin }}</code>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">NIK</span>
                            <span class="info-value">
                                <code class="bg-light px-2 py-1 rounded">{{ $employee->nik }}</code>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Departemen</span>
                            <span class="info-value">{{ $employee->department?->name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Posisi</span>
                            <span class="info-value">{{ $employee->position?->name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="badge bg-success">{{ ucfirst($employee->status) }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Points -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-star-fill text-warning"></i> Poin Saat Ini
                    </h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="points-display text-primary">
                        {{ $employee->current_points }}
                    </div>
                    <div class="text-muted mt-2">
                        dari {{ $employee->initial_points }} poin awal
                    </div>
                    @if($employee->current_points < 20)
                        <div class="alert alert-danger mt-3 mb-0">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <strong>Status Kritis</strong>
                        </div>
                    @elseif($employee->current_points < 50)
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <strong>Perlu Perhatian</strong>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Period Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-calendar-range"></i> Periode Payroll
                    </h5>
                </div>
                <div class="card-body">
                    @if($employee->currentPayrollPeriod)
                        <div class="text-center">
                            <h6 class="mb-2">{{ $employee->currentPayrollPeriod->name }}</h6>
                            <small class="text-muted d-block">
                                {{ $employee->currentPayrollPeriod->start_date->format('d M Y') }} -
                                {{ $employee->currentPayrollPeriod->end_date->format('d M Y') }}
                            </small>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="bi bi-calendar-x d-block mb-2" style="font-size: 2rem;"></i>
                            Belum ada periode aktif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Summary Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-bar-chart-fill"></i> Ringkasan Transaksi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="stat-box">
                                <div class="stat-label">Total Transaksi</div>
                                <div class="stat-number">{{ $summary['total_transactions'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-box border-start">
                                <div class="stat-label">Pengurangan</div>
                                <div class="stat-number text-danger">{{ $summary['total_deductions'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-box border-start">
                                <div class="stat-label">Penambahan</div>
                                <div class="stat-number text-success">{{ $summary['total_additions'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-box border-start">
                                <div class="stat-label">Rata-rata</div>
                                <div class="stat-number">{{ round(($summary['total_deductions'] + $summary['total_additions']) / max($summary['total_transactions'], 1), 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Breakdown by Reason -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-tags-fill"></i> Breakdown per Alasan
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Alasan</th>
                                    <th style="width: 120px;">Jumlah</th>
                                    <th style="width: 120px;">Total Poin</th>
                                    <th style="width: 120px;">Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($summary['by_reason'] as $reason => $data)
                                    <tr>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $reason }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $data['count'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge @if($data['total'] < 0) bg-danger @elseif($data['total'] > 0) bg-success @else bg-secondary @endif">
                                                {{ $data['total'] > 0 ? '+' : '' }}{{ $data['total'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ number_format($data['average'], 1) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox d-block mb-2" style="font-size: 2rem;"></i>
                                            Belum ada transaksi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-clock-history"></i> Riwayat Transaksi Poin
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 120px;">Tanggal</th>
                                    <th>Alasan</th>
                                    <th style="width: 100px;">Perubahan</th>
                                    <th style="width: 150px;">Periode</th>
                                    <th style="width: 120px;">Referensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ $transaction->date->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $transaction->reason }}</code>
                                        </td>
                                        <td>
                                            <span class="badge @if($transaction->delta < 0) bg-danger @elseif($transaction->delta > 0) bg-success @else bg-secondary @endif">
                                                {{ $transaction->delta > 0 ? '+' : '' }}{{ $transaction->delta }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($transaction->payrollPeriod)
                                                <small class="text-muted">{{ $transaction->payrollPeriod->name }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->source_id)
                                                <small class="text-muted">
                                                    <code class="bg-light px-2 py-1 rounded">{{ $transaction->source_id }}</code>
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="bi bi-inbox d-block mb-2" style="font-size: 2.5rem;"></i>
                                            Belum ada riwayat transaksi
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
</div>
@endsection