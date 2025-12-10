@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-person-badge-fill me-2"></i> {{ $employee->name }}
            </h2>
            <p class="text-muted">Detail dan riwayat karyawan</p>
        </div>

        <div>
            <a href="{{ route('karyawan.edit', $employee) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Informasi Pribadi & Pekerjaan -->
    <div class="row mt-4">
        <!-- Card Informasi Pribadi -->
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i> Informasi Pribadi</h5>
                </div>
                <div class="card-body">
                    <p><strong>PIN:</strong> {{ $employee->pin }}</p>
                    <p><strong>NIK:</strong> {{ $employee->nik }}</p>
                    <p><strong>Tempat Lahir:</strong> {{ $employee->birth_place ?? '-' }}</p>
                    <p><strong>Tanggal Lahir:</strong>
                        @if($employee->birth_date)
                            {{ is_string($employee->birth_date) ? $employee->birth_date : $employee->birth_date->format('d M Y') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Card Informasi Pekerjaan -->
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-success text-white rounded-top-4">
                    <h5 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i> Informasi Pekerjaan</h5>
                </div>
                <div class="card-body">
                    <p><strong>Posisi:</strong> {{ $employee->position->name ?? '-' }}</p>
                    <p><strong>Departemen:</strong> {{ $employee->department->name ?? '-' }}</p>

                    <p><strong>Status:</strong>
                        @php
                            $statusColors = [
                                'aktif' => 'success',
                                'nonaktif' => 'secondary',
                                'kontrak' => 'info',
                                'resign' => 'danger'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$employee->status] ?? 'dark' }} px-3 py-1">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </p>

                    <p><strong>Bergabung Tahun:</strong> {{ $employee->join_year ?? '-' }}</p>
                    <p><strong>UMK:</strong> Rp {{ number_format($employee->umk ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Kehadiran -->
    <div class="card shadow-sm border-0 rounded-4 mt-4">
        <div class="card-header bg-info text-white rounded-top-4">
            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i> Riwayat Kehadiran Terbaru</h5>
        </div>
        <div class="card-body">
            @if($employee->attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->attendances->take(5) as $att)
                                <tr>
                                    <td>{{ is_string($att->date) ? $att->date : $att->date->format('Y-m-d') }}</td>
                                    <td>{{ $att->check_in ? (is_string($att->check_in) ? substr($att->check_in, 0, 5) : $att->check_in->format('H:i')) : '-' }}</td>
                                    <td>{{ $att->check_out ? (is_string($att->check_out) ? substr($att->check_out, 0, 5) : $att->check_out->format('H:i')) : '-' }}</td>
                                    <td><span class="badge bg-primary">{{ ucfirst($att->status) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Tidak ada riwayat kehadiran</p>
            @endif
        </div>
    </div>

    <!-- Riwayat Gaji -->
    <div class="card shadow-sm border-0 rounded-4 mt-4 mb-4">
        <div class="card-header bg-warning rounded-top-4">
            <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i> Riwayat Penggajian Terbaru</h5>
        </div>
        <div class="card-body">
            @if($employee->payrolls->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Periode</th>
                                <th>Gaji Pokok</th>
                                <th>Bonus</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->payrolls->take(5) as $payroll)
                                <tr>
                                    <td>
                                        @if($payroll->payroll_period)
                                            {{ is_string($payroll->payroll_period->start_date) 
                                                ? substr($payroll->payroll_period->start_date, 0, 7) 
                                                : $payroll->payroll_period->start_date->format('M Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($payroll->base_salary ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($payroll->bonus ?? 0, 0, ',', '.') }}</td>
                                    <td><strong>Rp {{ number_format($payroll->total ?? 0, 0, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Tidak ada riwayat penggajian</p>
            @endif
        </div>
    </div>

</div>
@endsection
