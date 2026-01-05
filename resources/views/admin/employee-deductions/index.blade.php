@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="bi bi-person-badge me-2"></i>{{ $employee->name }}
                </h3>
                <p class="text-muted mb-0">
                    Manajemen Potongan Gaji | NIK: <span class="fw-bold">{{ $employee->nik }}</span>
                </p>
            </div>

            <a href="{{ route('karyawan.show', $employee) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Detail Karyawan
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if ($message = session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Info Card --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small mb-1">Departemen</h6>
                    <h5 class="fw-bold">{{ $employee->department->name ?? 'N/A' }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small mb-1">Klasifikasi</h6>
                    <h5 class="fw-bold">{{ $employee->classification->name ?? 'N/A' }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted small mb-1">Status</h6>
                    <h5 class="fw-bold">
                        <span class="badge bg-{{ $employee->status === 'aktif' ? 'success' : 'danger' }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="mb-3">
        <a href="{{ route('employees.deductions.create', $employee) }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-2"></i>Tambah Potongan Baru
        </a>
    </div>

    {{-- Deductions Table --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="bi bi-scissors me-2"></i>Daftar Potongan Gaji</h5>
        </div>

        <div class="card-body p-0">
            @if($deductions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="fw-bold text-dark">
                                <th>Nama Potongan</th>
                                <th>Kode</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Mulai</th>
                                <th>Berakhir</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deductions as $deduction)
                                <tr class="table-row-hover">
                                    <td class="fw-semibold">
                                        <i class="bi bi-dash-circle text-danger me-2"></i>
                                        {{ $deduction->name }}
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $deduction->code ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($deduction->type === 'fixed')
                                            <span class="badge bg-primary">Tetap</span>
                                        @else
                                            <span class="badge bg-info">Per Hari</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-danger">
                                        Rp {{ number_format($deduction->amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        {{ $deduction->start_date ? $deduction->start_date->format('d-m-Y') : '-' }}
                                    </td>
                                    <td>
                                        {{ $deduction->end_date ? $deduction->end_date->format('d-m-Y') : '∞' }}
                                    </td>
                                    <td>
                                        @php
                                            $isActive = $deduction->isActiveOn(now());
                                        @endphp
                                        @if($isActive)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('employees.deductions.edit', [$employee, $deduction]) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('employees.deductions.destroy', [$employee, $deduction]) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus potongan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-center">
                        {{ $deductions->links() }}
                    </div>
                </div>
            @else
                <div class="p-5 text-center">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted mt-3">Tidak ada potongan gaji yang ditetapkan untuk karyawan ini.</p>
                    <a href="{{ route('employees.deductions.create', $employee) }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Potongan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Summary Card --}}
    @if($deductions->count() > 0)
        <div class="card shadow-lg border-0 mt-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-calculator me-2"></i>Ringkasan Potongan</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 bg-danger bg-opacity-10">
                            <div class="card-body">
                                <h6 class="text-muted small mb-2">Total Potongan Tetap</h6>
                                <h3 class="fw-bold text-danger">
                                    Rp {{ number_format($deductions->where('type', 'fixed')->sum('amount'), 0, ',', '.') }}
                                </h3>
                                <small class="text-muted">
                                    {{ $deductions->where('type', 'fixed')->count() }} potongan tetap aktif
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-info bg-opacity-10">
                            <div class="card-body">
                                <h6 class="text-muted small mb-2">Total Potongan Per Hari Kerja</h6>
                                <h3 class="fw-bold text-info">
                                    Rp {{ number_format($deductions->where('type', 'variable')->sum('amount'), 0, ',', '.') }}
                                </h3>
                                <small class="text-muted">
                                    {{ $deductions->where('type', 'variable')->count() }} potongan per hari aktif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

<style>
    .table-row-hover:hover {
        background-color: #f8f9fa !important;
    }
</style>
@endsection
