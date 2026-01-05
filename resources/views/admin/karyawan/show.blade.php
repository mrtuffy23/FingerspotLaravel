@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- HEADER PROFIL -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                @if($employee->photo)
                    <img src="{{ asset($employee->photo) }}"
                         class="rounded-circle shadow-sm"
                         style="width:72px;height:72px;object-fit:cover">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                         style="width:72px;height:72px">
                        <i class="bi bi-person fs-1 text-muted"></i>
                    </div>
                @endif

                <div>
                    <h4 class="fw-bold mb-0">{{ $employee->name }}</h4>
                    <div class="text-muted small">
                        {{ $employee->position->name ?? '-' }} • {{ $employee->department->name ?? '-' }}
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('karyawan.edit',$employee) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i>
                </a>
                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- INFO RINGKAS -->
    <div class="row g-3 mb-4">

        <!-- PRIBADI -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-primary">
                        <i class="bi bi-person-lines-fill me-1"></i> Pribadi
                    </h6>
                    <div class="small text-muted">PIN</div>
                    <div class="fw-semibold mb-2">{{ $employee->pin }}</div>

                    <div class="small text-muted">NIK</div>
                    <div class="fw-semibold mb-2">{{ $employee->nik }}</div>

                    <div class="small text-muted">TTL</div>
                    <div class="fw-semibold">
                        {{ $employee->birth_place ?? '-' }},
                        {{ $employee->birth_date ? $employee->birth_date->format('d M Y') : '-' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- PEKERJAAN -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-success">
                        <i class="bi bi-briefcase-fill me-1"></i> Pekerjaan
                    </h6>

                    <div class="mb-2">
                        <span class="badge {{ $employee->employment_type === 'monthly' ? 'bg-info' : 'bg-warning text-dark' }}">
                            {{ $employee->employment_type === 'monthly' ? 'Bulanan' : 'Harian' }}
                        </span>
                    </div>

                    <div class="small text-muted">Golongan</div>
                    <div class="fw-semibold mb-2">
                        {{ $employee->classification->name ?? '-' }}
                        ({{ $employee->classification->code ?? '-' }})
                    </div>

                    <div class="small text-muted">Status</div>
                    <span class="badge bg-success">{{ ucfirst($employee->status) }}</span>
                </div>
            </div>
        </div>

        <!-- GAJI -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-warning">
                        <i class="bi bi-cash-stack me-1"></i> Gaji
                    </h6>

                    <div class="small text-muted">UMK</div>
                    <div class="fw-bold fs-5 text-success mb-3">
                        Rp {{ number_format($employee->umk ?? 0,0,',','.') }}
                    </div>

                    <a href="{{ route('employees.deductions.index',$employee) }}"
                       class="btn btn-sm btn-outline-danger w-100">
                        <i class="bi bi-scissors"></i> Potongan Gaji
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- TAB RIWAYAT -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <ul class="nav nav-pills mb-3">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#absensi">
                        <i class="bi bi-clock-history me-1"></i> Absensi
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#gaji">
                        <i class="bi bi-cash me-1"></i> Penggajian
                    </button>
                </li>
            </ul>

            <div class="tab-content">

                <!-- ABSENSI -->
                <div class="tab-pane fade show active" id="absensi">
                    @include('employees.partials.attendance-table')
                </div>

                <!-- GAJI -->
                <div class="tab-pane fade" id="gaji">
                    @include('employees.partials.payroll-table')
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
