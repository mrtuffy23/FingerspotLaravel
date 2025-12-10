@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- HERO HEADER -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex align-items-center">

            <!-- Avatar -->
            <div class="me-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&background=0D6EFD&color=fff&size=110"
                     class="rounded-circle shadow-sm" width="90" height="90">
            </div>

            <!-- Employee Info -->
            <div>
                <h2 class="fw-bold mb-1">{{ $employee->name }}</h2>
                <div class="text-muted">
                    {{ $employee->position->name ?? '—' }} • {{ $employee->department->name ?? '—' }}
                </div>
                <span class="badge bg-{{ $employee->status === 'active' ? 'success' : 'secondary' }} mt-2 px-3 py-2">
                    {{ ucfirst($employee->status) }}
                </span>
            </div>

            <!-- Actions -->
            <div class="ms-auto">
                <a href="{{ route('karyawan.edit', $employee) }}" class="btn btn-warning me-2">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- CONTENT ROW -->
    <div class="row g-4">

        <!-- Left Card -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-vcard"></i> Informasi Pribadi</h5>
                </div>
                <div class="card-body">
                    <p><strong>PIN:</strong> {{ $employee->pin }}</p>
                    <p><strong>NIK:</strong> {{ $employee->nik }}</p>
                    <p><strong>Tempat Lahir:</strong> {{ $employee->birth_place ?? '-' }}</p>
                    <p><strong>Tanggal Lahir:</strong> {{ $employee->birth_date?->format('Y-m-d') ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Right Card -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-briefcase"></i> Informasi Pekerjaan</h5>
                </div>
                <div class="card-body">
                    <p><strong>Posisi:</strong> {{ $employee->position->name ?? '-' }}</p>
                    <p><strong>Departemen:</strong> {{ $employee->department->name ?? '-' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $employee->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </p>
                    <p><strong>Bergabung Tahun:</strong> {{ $employee->join_year ?? '-' }}</p>
                    <p><strong>UMK:</strong> Rp {{ number_format($employee->umk ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

    </div>

    <!-- TABS -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">

            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" data-bs-toggle="tab" href="#attendances">
                        <i class="bi bi-clock-history"></i> Kehadiran
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" data-bs-toggle="tab" href="#payrolls">
                        <i class="bi bi-wallet2"></i> Payroll
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" data-bs-toggle="tab" href="#leaves">
                        <i class="bi bi-calendar-event"></i> Cuti
                    </a>
                </li>
            </ul>

            <div class="tab-content mt-4">

                <!-- Attendance Tab -->
                <div id="attendances" class="tab-pane fade show active">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Hours</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->attendances()->latest()->limit(10)->get() as $att)
                                    <tr>
                                        <td>{{ $att->date->format('Y-m-d') }}</td>
                                        <td>{{ $att->check_in?->format('H:i') ?? '-' }}</td>
                                        <td>{{ $att->check_out?->format('H:i') ?? '-' }}</td>
                                        <td>{{ $att->work_hours ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ ucfirst($att->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Tidak ada kehadiran</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payroll Tab -->
                <div id="payrolls" class="tab-pane fade">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Periode</th>
                                    <th>Total Jam</th>
                                    <th>Gaji Pokok</th>
                                    <th>Upah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->payrolls()->latest()->limit(10)->get() as $payroll)
                                    <tr>
                                        <td>#{{ $payroll->payroll_period_id }}</td>
                                        <td>{{ $payroll->total_hours }}</td>
                                        <td>Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($payroll->allowance_amount, 0, ',', '.') }}</td>
                                        <td class="fw-bold text-primary">
                                            Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Tidak ada payroll</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Leave Tab -->
                <div id="leaves" class="tab-pane fade">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Hari</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->leaves()->latest()->limit(10)->get() as $leave)
                                    <tr>
                                        <td>{{ ucfirst($leave->leave_type) }}</td>
                                        <td>{{ $leave->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $leave->end_date->format('Y-m-d') }}</td>
                                        <td>{{ $leave->duration }}</td>
                                        <td>
                                            <span class="badge bg-{{ $leave->status === 'approved' ? 'success' : 'warning' }}">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Tidak ada data cuti</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> <!-- End Tab Content -->

        </div>
    </div>

</div>
@endsection
