@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-clock-history me-2"></i> Izin Lembur
        </h2>
        <a href="{{ route('overtime-permit.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Izin Lembur
        </a>
    </div>

    <!-- FILTER CARD -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Karyawan</label>
                    <select name="employee_id" class="form-select form-select-sm">
                        <option value="">-- Semua Karyawan --</option>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" 
                            {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Tanggal</label>
                    <input type="date" name="date" class="form-control form-control-sm" 
                        value="{{ request('date') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3 rounded-top-3">
            <h5 class="mb-0">Daftar Izin Lembur</h5>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 20%">Karyawan</th>
                        <th style="width: 15%">Tanggal</th>
                        <th style="width: 15%">Waktu Lembur Sampai</th>
                        <th style="width: 20%">Alasan</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($overtimePermits as $permit)
                    <tr>
                        <td class="fw-bold">{{ $permit->id }}</td>
                        <td>
                            <strong>{{ $permit->employee->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $permit->employee->department->name ?? '-' }}</small>
                        </td>
                        <td>{{ $permit->date->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge bg-info">{{ \Carbon\Carbon::createFromFormat('H:i:s', $permit->overtime_end_time)->format('H:i') }}</span>
                        </td>
                        <td>
                            <small>{{ $permit->reason ?? '-' }}</small>
                        </td>
                        <td>
                            @if ($permit->isApproved())
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Disetujui
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Approval
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('overtime-permit.show', $permit) }}" class="btn btn-outline-primary" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('overtime-permit.edit', $permit) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if (!$permit->isApproved())
                                <form action="{{ route('overtime-permit.approve', $permit) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" title="Setujui" 
                                        onclick="return confirm('Setujui izin lembur ini?')">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('overtime-permit.reject', $permit) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Batalkan Approval" 
                                        onclick="return confirm('Batalkan approval izin lembur ini?')">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox"></i> Tidak ada data izin lembur
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="card-footer bg-light p-3 rounded-bottom-3">
            {{ $overtimePermits->links() }}
        </div>
    </div>

</div>
@endsection
