@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- TITLE -->
    <h2 class="fw-bold mb-4">
        <i class="bi bi-card-list me-2"></i> Rincian Kehadiran
    </h2>

    <!-- MAIN CARD -->
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h5 class="mb-0">
                <i class="bi bi-calendar-check me-2"></i> Record #{{ $attendance->id }}
            </h5>
        </div>

        <div class="card-body px-4 py-4">

            @php
                $badgeColor = [
                    'present' => 'success',
                    'absent' => 'danger',
                    'late' => 'warning',
                    'sick' => 'info',
                    'leave' => 'secondary',
                ][$attendance->status] ?? 'dark';
            @endphp

            <!-- EMPLOYEE INFO -->
            <div class="mb-4 p-3 rounded bg-light border">
                <h5 class="fw-bold mb-2">👤 Informasi Karyawan</h5>
                <p class="mb-1"><strong>Nama:</strong> {{ $attendance->employee->name }}</p>
                <p class="mb-1"><strong>Tanggal:</strong> {{ $attendance->date->format('Y-m-d') }}</p>
            </div>

            <!-- ATTENDANCE INFO -->
            <div class="row g-4">

                <div class="col-md-6">
                    <div class="p-3 border rounded bg-white shadow-sm">
                        <h6 class="fw-bold">⏰ Waktu Masuk</h6>
                        <p class="mb-0 text-muted">
                            {{ $attendance->check_in?->format('Y-m-d H:i') ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded bg-white shadow-sm">
                        <h6 class="fw-bold">🏁 Waktu Keluar</h6>
                        <p class="mb-0 text-muted">
                            {{ $attendance->check_out?->format('Y-m-d H:i') ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded bg-white shadow-sm">
                        <h6 class="fw-bold">🕒 Jam Kerja</h6>
                        <p class="mb-0 text-muted">
                            {{ $attendance->work_hours ?? '-' }} Jam
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded bg-white shadow-sm">
                        <h6 class="fw-bold">💼 Kompensasi Jam</h6>
                        <p class="mb-0 text-muted">
                            {{ $attendance->compensated_hours ?? '-' }} Jam
                        </p>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="p-3 border rounded bg-white shadow-sm">
                        <h6 class="fw-bold">📌 Status</h6>
                        <span class="badge bg-{{ $badgeColor }} px-3 py-2 fs-6">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="p-3 border rounded bg-white shadow-sm">
                        <h6 class="fw-bold">📝 Catatan</h6>
                        <p class="text-muted mb-0">
                            {{ $attendance->notes ?: '-' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-warning text-white">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

</div>
@endsection
