@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="bi bi-calendar-check me-2"></i> Rekam Absensi
        </h2>

        <div class="d-flex gap-2">
            <a href="{{ route('attendance.create') }}" class="btn btn-success shadow-sm px-3">
                <i class="bi bi-plus-lg me-1"></i> Tambah Absensi
            </a>
            <a href="{{ route('absen.import') }}" class="btn btn-info text-white shadow-sm px-3">
                <i class="bi bi-upload me-1"></i> Import CSV
            </a>
        </div>
    </div>

    <!-- CARD -->
    <div class="card shadow border-0 rounded-4 overflow-hidden">

        <!-- Card Header -->
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-table me-2"></i> Daftar Rekam Absensi
            </h5>
        </div>

        <!-- Search/Filter Form -->
        <div class="card-body bg-light border-bottom py-3">
            <form method="GET" action="{{ route('attendance.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar me-1"></i> Tanggal
                    </label>
                    <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-range me-1"></i> Dari Tanggal
                    </label>
                    <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-range me-1"></i> Sampai Tanggal
                    </label>
                    <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person-circle me-1"></i> Karyawan
                    </label>
                    <select class="form-select" name="employee_id">
                        <option value="">Semua Karyawan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-flag me-1"></i> Status
                    </label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Hadir</option>
                        <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Telat</option>
                        <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Alpa</option>
                        <option value="sick" {{ request('status') === 'sick' ? 'selected' : '' }}>Sakit</option>
                        <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>Cuti</option>
                        <option value="early_leave" {{ request('status') === 'early_leave' ? 'selected' : '' }}>Pulang Cepat</option>
                        <option value="accident" {{ request('status') === 'accident' ? 'selected' : '' }}>Kecelakaan</option>
                        <option value="permission" {{ request('status') === 'permission' ? 'selected' : '' }}>Izin</option>
                    </select>
                </div>

                <div class="col-md-9 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary shadow-sm px-4">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                    <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary shadow-sm px-4">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark text-center">
                        <tr>
                            <th class="py-3">Karyawan</th>
                            <th>Tanggal</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Jam Kerja</th>
                            <th>Kompensasi</th>
                            <th>Status</th>
                            <th style="width: 150px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @forelse($attendances as $attendance)
                        <tr class="table-row-hover">

                            <td class="fw-semibold text-start ps-4">
                                <i class="bi bi-person-badge me-1 text-primary"></i>
                                {{ $attendance->employee->name }}
                            </td>

                            <td>{{ $attendance->date->format('Y-m-d') }}</td>
                            <td>{{ $attendance->first_in?->format('H:i') ?? '-' }}</td>
                            <td>{{ $attendance->last_out?->format('H:i') ?? '-' }}</td>

                            <td class="fw-semibold">
                                {{ $attendance->work_hours ?? '-' }} Jam
                            </td>

                            <td>
                                @php
                                    $leaveComp = $attendance->getLeaveCompensation();
                                    $totalComp = $attendance->calculateTotalCompensation();
                                @endphp
                                @if($leaveComp > 0 || $totalComp > 0)
                                    <span class="badge bg-success">
                                        +{{ number_format($totalComp, 1) }} Jam
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $badgeColor = [
                                        'present' => 'success',
                                        'absent'  => 'danger',
                                        'late'    => 'warning',
                                        'sick'    => 'info',
                                        'on_leave' => 'secondary',
                                        'early_leave' => 'secondary',
                                        'accident' => 'danger',
                                        'holiday' => 'dark',
                                        'permission' => 'secondary',
                                        'out_permission' => 'secondary',
                                    ][$attendance->status] ?? 'dark';
                                    
                                    $statusLabel = [
                                        'present' => 'HADIR',
                                        'absent'  => 'ALPA',
                                        'late'    => 'TELAT',
                                        'sick'    => 'SAKIT',
                                        'on_leave' => 'CUTI',
                                        'early_leave' => 'PULANG CEPAT',
                                        'accident' => 'KECELAKAAN',
                                        'holiday' => 'LIBUR',
                                        'permission' => 'IZIN',
                                        'out_permission' => 'IZIN KELUAR',
                                    ][$attendance->status] ?? strtoupper($attendance->status);
                                @endphp

                                <span class="badge bg-{{ $badgeColor }} px-3 py-2 rounded-pill shadow-sm text-uppercase">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('attendance.show', $attendance) }}"
                                        class="btn btn-primary btn-sm px-3 shadow-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('attendance.edit', $attendance) }}"
                                        class="btn btn-warning btn-sm px-3 text-white shadow-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-4 text-center">
                                <div class="text-muted">
                                    <i class="bi bi-info-circle me-2"></i> 
                                    @if(request()->hasAny(['date', 'start_date', 'end_date', 'employee_id', 'status']))
                                        Tidak ada data absensi yang sesuai dengan filter.
                                    @else
                                        Belum ada data absensi.
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-light py-3">
            <div class="d-flex justify-content-center">
                {{ $attendances->links() }}
            </div>
        </div>

    </div>

</div>

<style>
    .table-row-hover:hover {
        background: #f4f8ff !important;
        transition: .2s;
    }
</style>
@endsection
