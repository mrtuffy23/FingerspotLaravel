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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
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

    {{-- Navigation --}}
    <div class="mt-4 d-flex justify-content-start">
        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>
    </div>

</div>
@endsection
