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
                                        'present' => 'PRESENT',
                                        'absent'  => 'ABSENT',
                                        'late'    => 'LATE',
                                        'sick'    => 'SICK',
                                        'on_leave' => 'ON LEAVE',
                                        'early_leave' => 'EARLY LEAVE',
                                        'accident' => 'ACCIDENT',
                                        'holiday' => 'HOLIDAY',
                                        'permission' => 'PERMISSION',
                                        'out_permission' => 'OUT PERMISSION',
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
                            <td colspan="7" class="py-4 text-muted">
                                <i class="bi bi-info-circle me-2"></i> Belum ada data absensi.
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
