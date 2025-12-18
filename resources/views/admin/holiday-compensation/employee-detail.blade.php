@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-2">
                <i class="bi bi-person-badge me-2"></i> Detail Kompensasi Hari Libur
            </h2>
            @if($employee)
            <p class="text-muted mb-0">
                <strong>{{ $employee->name }}</strong> - {{ $employee->department->name ?? '-' }}
            </p>
            @endif
        </div>

        <a href="{{ route('holiday-compensation.report', ['month' => $month, 'year' => $year]) }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Hari Libur Kerja</h6>
                            <h3 class="fw-bold text-warning mb-0">{{ $totals['holiday_days'] }}</h3>
                        </div>
                        <i class="bi bi-calendar-heart fs-1 text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Jam Kerja</h6>
                            <h3 class="fw-bold text-success mb-0">{{ $totals['work_hours'] }}</h3>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Kompensasi</h6>
                            <h3 class="fw-bold text-info mb-0">{{ $totals['compensated_hours'] }}</h3>
                        </div>
                        <i class="bi bi-gift fs-1 text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="card shadow border-0 rounded-4 overflow-hidden">

        <!-- Card Header -->
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-table me-2"></i> Detail Kompensasi Hari Libur
            </h5>
        </div>

        <!-- Table -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark text-center">
                        <tr>
                            <th class="py-3">Tanggal</th>
                            <th>Hari</th>
                            <th>Deskripsi Libur</th>
                            <th>Jam Kerja</th>
                            <th>Bonus</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @forelse($holidays as $holiday)
                        <tr>
                            <td class="fw-semibold">
                                <i class="bi bi-calendar me-1 text-primary"></i>
                                {{ $holiday['date'] }}
                            </td>
                            <td>{{ $holiday['day_name'] }}</td>
                            <td class="text-start">
                                <small class="text-muted d-block">
                                    {{ $holiday['holiday_description'] }}
                                </small>
                            </td>
                            <td class="fw-semibold">{{ $holiday['work_hours'] }} jam</td>
                            <td>
                                <span class="badge bg-warning px-3 py-2">
                                    +{{ $holiday['compensated_hours'] }} jam
                                </span>
                            </td>
                            <td class="fw-semibold">
                                <span class="badge bg-success px-3 py-2">
                                    {{ $holiday['work_hours'] + $holiday['compensated_hours'] }} jam
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusBadge = [
                                        'present' => 'success',
                                        'late' => 'warning',
                                        'absent' => 'danger',
                                    ][$holiday['status']] ?? 'secondary';

                                    $statusLabel = [
                                        'present' => 'HADIR',
                                        'late' => 'TELAT',
                                        'absent' => 'ALPA',
                                    ][$holiday['status']] ?? strtoupper($holiday['status']);
                                @endphp
                                <span class="badge bg-{{ $statusBadge }} px-2 py-1">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-4 text-muted">
                                <i class="bi bi-info-circle me-2"></i> Tidak ada hari libur dengan kerja untuk periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </div>

    <!-- INFO CARD -->
    <div class="alert alert-info border-0 rounded-3 mt-4" role="alert">
        <h6 class="fw-bold mb-3">
            <i class="bi bi-info-circle me-2"></i> Penjelasan Kompensasi Hari Libur
        </h6>
        <p class="mb-2">
            <strong>Bonus Kompensasi Berdasarkan Jam Kerja:</strong> Ketika karyawan bekerja pada hari libur/tanggal merah, 
            mereka mendapatkan bonus jam kerja tambahan berdasarkan durasi kerja mereka.
        </p>
        <div class="ms-3 mb-2">
            <p class="mb-1"><i class="bi bi-check-circle me-2 text-success"></i> <strong>5 jam kerja</strong> → Bonus <strong>+3 jam</strong> (Total: 8 jam)</p>
            <p class="mb-1"><i class="bi bi-check-circle me-2 text-success"></i> <strong>6 jam kerja</strong> → Bonus <strong>+4 jam</strong> (Total: 10 jam)</p>
            <p class="mb-0"><i class="bi bi-check-circle me-2 text-success"></i> <strong>≥ 7 jam kerja</strong> → Bonus <strong>+5 jam</strong> (Total: 12+ jam)</p>
        </div>
        <p class="mb-0 text-muted">
            <small><strong>Catatan:</strong> Jam kerja kurang dari 5 jam tidak mendapatkan bonus kompensasi.</small>
        </p>
    </div>

</div>

<style>
    .table-row-hover:hover {
        background: #f4f8ff !important;
        transition: .2s;
    }
</style>
@endsection
