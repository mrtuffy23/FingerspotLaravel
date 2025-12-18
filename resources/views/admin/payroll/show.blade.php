@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">Periode Penggajian #{{ $period->id }}</h3>
                <p class="text-muted mb-0">
                    {{ $period->start_date->format('Y-m-d') }} — {{ $period->end_date->format('Y-m-d') }}
                </p>
            </div>

            <span class="badge px-3 py-2 fs-6 bg-{{ 
                $period->status === 'finalized' ? 'success' : 'warning text-dark' }}">
                <i class="bi bi-{{ $period->status === 'finalized' ? 'check-circle' : 'hourglass-split' }}"></i>
                {{ ucfirst($period->status) }}
            </span>
        </div>
    </div>

    {{-- Success Message --}}
    @if ($message = session('success'))
        <div class="alert alert-success shadow-sm">{{ $message }}</div>
    @endif

    {{-- Table --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-people-fill"></i> Daftar Gaji Karyawan</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="fw-bold text-dark">
                            <th>Karyawan</th>
                            <th>Jam</th>
                            <th>Kompensasi</th>
                            <th>Total Jam</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Total Gaji</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                            <tr>
                                <td class="fw-semibold">
                                    <i class="bi bi-person-circle"></i>
                                    {{ $payroll->employee->name }}
                                </td>

                                <td>{{ number_format($payroll->total_actual_hours, 2) }}</td>
                                <td>{{ number_format($payroll->total_compensated_hours, 2) }}</td>
                                <td class="fw-bold text-primary">{{ number_format($payroll->total_hours, 2) }}</td>

                                <td>Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($payroll->allowance_amount, 0, ',', '.') }}</td>

                                <td class="fw-bold text-success">
                                    Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('payroll.print-slip', $payroll->id) }}" 
                                       class="btn btn-sm btn-primary" 
                                       target="_blank"
                                       title="Cetak Slip Gaji">
                                        <i class="bi bi-printer"></i> Cetak
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inboxes fs-3"></i>
                                    <p class="mt-2 mb-0">Tidak ada catatan payroll yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-light">
            <div class="d-flex justify-content-center">
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>

    {{-- Total Payroll Summary --}}
    <div class="card shadow-lg border-0 mt-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-calculator"></i> Ringkasan Total Penggajian</h5>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-info bg-opacity-10 rounded">
                        <h6 class="text-muted small mb-2">Total Jam Kerja</h6>
                        <h3 class="fw-bold text-info">{{ number_format($payrolls->sum('total_actual_hours'), 2) }} jam</h3>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                        <h6 class="text-muted small mb-2">Total Kompensasi</h6>
                        <h3 class="fw-bold text-warning">{{ number_format($payrolls->sum('total_compensated_hours'), 2) }} jam</h3>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-primary bg-opacity-10 rounded">
                        <h6 class="text-muted small mb-2">Total Gaji Pokok</h6>
                        <h3 class="fw-bold text-primary">Rp {{ number_format($payrolls->sum('base_salary'), 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="p-3 bg-success bg-opacity-10 rounded">
                        <h6 class="text-muted small mb-2">Total Tunjangan</h6>
                        <h3 class="fw-bold text-success">Rp {{ number_format($payrolls->sum('allowance_amount'), 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="col-12">
                    <div class="p-4 bg-dark text-white rounded" style="background-color: #1a1a1a !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">TOTAL GAJI KOTOR PERIODE</h5>
                            <h2 class="fw-bold mb-0" style="font-size: 32px; color: #00ff00;">
                                Rp {{ number_format($payrolls->sum('total_salary'), 0, ',', '.') }}
                            </h2>
                        </div>
                        <hr class="border-secondary my-3">
                        <p class="text-secondary mb-0">
                            <i class="bi bi-info-circle"></i>
                            Untuk {{ $payrolls->count() }} karyawan | Periode: {{ $period->start_date->format('d-m-Y') }} s/d {{ $period->end_date->format('d-m-Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="mt-4 d-flex justify-content-start">
        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>
    </div>

</div>
@endsection
