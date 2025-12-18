@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="bi bi-calendar2-heart me-2"></i> Laporan Kompensasi Hari Libur
        </h2>

        <div class="d-flex gap-2">
            <a href="{{ route('holiday-compensation.holidays') }}" class="btn btn-info shadow-sm">
                <i class="bi bi-calendar-event me-1"></i> Daftar Hari Libur
            </a>
            <a href="{{ route('holiday-compensation.export', ['month' => $month, 'year' => $year]) }}" class="btn btn-success shadow-sm">
                <i class="bi bi-download me-1"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- FILTER CARD -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body">
            <form action="{{ route('holiday-compensation.report') }}" method="GET" class="d-flex gap-2 align-items-end">
                <div>
                    <label class="form-label fw-semibold">Bulan</label>
                    <select name="month" class="form-select" required>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="form-label fw-semibold">Tahun</label>
                    <select name="year" class="form-select" required>
                        @for ($y = 2020; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i> Tampilkan
                </button>
            </form>
        </div>
    </div>

    <!-- SUMMARY CARD -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Karyawan</h6>
                            <h3 class="fw-bold text-primary mb-0">{{ $totals['employees'] }}</h3>
                        </div>
                        <i class="bi bi-people fs-1 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
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

        <div class="col-md-3">
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

        <div class="col-md-3">
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
                <i class="bi bi-table me-2"></i> Detail Kompensasi per Karyawan
            </h5>
        </div>

        <!-- Table -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark text-center">
                        <tr>
                            <th class="py-3 text-start">Karyawan</th>
                            <th>Departemen</th>
                            <th>Hari Libur</th>
                            <th>Jam Kerja</th>
                            <th>Bonus Kompensasi</th>
                            <th style="width: 100px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @forelse($report as $row)
                        <tr>
                            <td class="fw-semibold text-start ps-4">
                                <i class="bi bi-person me-2 text-primary"></i>
                                {{ $row['employee_name'] }}
                            </td>
                            <td>{{ $row['department'] }}</td>
                            <td>
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    {{ $row['holiday_count'] }} hari
                                </span>
                            </td>
                            <td class="fw-semibold">{{ $row['total_work_hours'] }} jam</td>
                            <td>
                                <span class="badge bg-success px-3 py-2">
                                    {{ $row['total_compensated_hours'] }} jam
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('holiday-compensation.employee-detail', ['employeeId' => $row['employee_id'], 'month' => $month, 'year' => $year]) }}"
                                   class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-4 text-muted">
                                <i class="bi bi-info-circle me-2"></i> Belum ada data kompensasi untuk periode ini.
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
            <i class="bi bi-info-circle me-2"></i> Informasi Kompensasi Hari Libur
        </h6>
        <p class="mb-2">
            <strong>Bonus Kompensasi Berdasarkan Jam Kerja:</strong> Ketika karyawan bekerja pada hari libur/tanggal merah, 
            mereka mendapatkan bonus jam kerja tambahan sesuai dengan durasi kerja mereka.
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
