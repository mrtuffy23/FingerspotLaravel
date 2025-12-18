@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- TITLE -->
    <h2 class="fw-bold mb-4">
        <i class="bi bi-clock-history me-2"></i> Detail Izin Lembur
    </h2>

    <!-- MAIN CARD -->
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h5 class="mb-0">
                <i class="bi bi-card-list me-2"></i> Izin Lembur #{{ $overtimePermit->id }}
            </h5>
        </div>

        <div class="card-body px-4 py-4">

            <div class="row g-4">

                <!-- EMPLOYEE INFO -->
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light">
                        <h6 class="fw-bold mb-2">👤 Karyawan</h6>
                        <p class="mb-1"><strong>Nama:</strong> {{ $overtimePermit->employee->name }}</p>
                        <p class="mb-1"><strong>Posisi:</strong> {{ $overtimePermit->employee->position->name ?? '-' }}</p>
                        <p class="mb-0"><strong>Departemen:</strong> {{ $overtimePermit->employee->department->name ?? '-' }}</p>
                    </div>
                </div>

                <!-- DATE INFO -->
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light">
                        <h6 class="fw-bold mb-2">📅 Tanggal</h6>
                        <p class="mb-0">{{ $overtimePermit->date->format('l, d F Y') }}</p>
                    </div>
                </div>

                <!-- OVERTIME TIME -->
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-white shadow-sm">
                        <h6 class="fw-bold mb-2">🕒 Waktu Lembur</h6>
                        <p class="mb-0 text-muted">
                            <i class="bi bi-clock me-2"></i> Standar: 16:00
                            <br>
                            <i class="bi bi-arrow-right-short me-2"></i> 
                            <strong class="text-success">Sampai {{ \Carbon\Carbon::createFromFormat('H:i:s', $overtimePermit->overtime_end_time)->format('H:i') }}</strong>
                        </p>
                    </div>
                </div>

                <!-- STATUS -->
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-white shadow-sm">
                        <h6 class="fw-bold mb-2">📌 Status</h6>
                        @if ($overtimePermit->isApproved())
                            <span class="badge bg-success px-3 py-2 fs-6">
                                <i class="bi bi-check-circle me-1"></i> Disetujui
                            </span>
                            <p class="text-muted mt-2 mb-0 small">
                                Disetujui oleh: {{ $overtimePermit->approver->name ?? '-' }}<br>
                                Pada: {{ $overtimePermit->approved_at->format('d-m-Y H:i') }}
                            </p>
                        @else
                            <span class="badge bg-warning px-3 py-2 fs-6">
                                <i class="bi bi-hourglass-split me-1"></i> Menunggu Approval
                            </span>
                        @endif
                    </div>
                </div>

                <!-- REASON -->
                <div class="col-md-12">
                    <div class="p-3 border rounded bg-white shadow-sm">
                        <h6 class="fw-bold mb-2">💬 Alasan</h6>
                        <p class="text-muted mb-0">
                            {{ $overtimePermit->reason ?? '(Tidak ada alasan)' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="mt-4 d-flex gap-2 flex-wrap">
        <a href="{{ route('overtime-permit.edit', $overtimePermit) }}" class="btn btn-warning text-white">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>

        @if (!$overtimePermit->isApproved())
        <form action="{{ route('overtime-permit.approve', $overtimePermit) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success" 
                onclick="return confirm('Setujui izin lembur ini?')">
                <i class="bi bi-check-circle me-1"></i> Setujui
            </button>
        </form>
        @else
        <form action="{{ route('overtime-permit.reject', $overtimePermit) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger" 
                onclick="return confirm('Batalkan approval izin lembur ini?')">
                <i class="bi bi-x-circle me-1"></i> Batalkan Approval
            </button>
        </form>
        @endif

        <form action="{{ route('overtime-permit.destroy', $overtimePermit) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger" 
                onclick="return confirm('Hapus izin lembur ini? Aksi ini tidak bisa dibatalkan.')">
                <i class="bi bi-trash me-1"></i> Hapus
            </button>
        </form>

        <a href="{{ route('overtime-permit.index') }}" class="btn btn-secondary ms-auto">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

</div>
@endsection
