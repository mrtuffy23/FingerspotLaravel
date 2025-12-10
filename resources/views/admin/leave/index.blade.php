@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Manajemen Cuti & Izin</h2>
            <p class="text-muted mb-0">Pantau dan kelola seluruh pengajuan cuti karyawan.</p>
        </div>

        <a href="{{ route('leave.create') }}" class="btn btn-success shadow-sm px-4">
            <i class="bi bi-plus-circle"></i> Ajukan Cuti
        </a>
    </div>

    <!-- Card Container -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Karyawan</th>
                            <th>Jenis</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td class="fw-semibold">{{ $leave->employee->name }}</td>

                                <td>
                                    <span class="badge bg-info text-dark px-3">
                                        <i class="bi bi-card-heading"></i> 
                                        {{ ucfirst(str_replace('_', ' ', $leave->type)) }}
                                    </span>
                                </td>

                                <td>{{ $leave->start_date->format('Y-m-d') }}</td>
                                <td>{{ $leave->end_date->format('Y-m-d') }}</td>

                                <td>
                                    <span class="badge bg-secondary px-3">
                                        {{ $leave->duration }} hari
                                    </span>
                                </td>

                                <td>
                                    <span class="badge 
                                        bg-{{ 
                                            $leave->status === 'approved' ? 'success' : 
                                            ($leave->status === 'rejected' ? 'danger' : 'warning')
                                        }} px-3">
                                        <i class="bi 
                                            bi-{{ 
                                                $leave->status === 'approved' ? 'check-circle' : 
                                                ($leave->status === 'rejected' ? 'x-circle' : 'hourglass-split')
                                            }}">
                                        </i>
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>

                                <td class="text-center">

                                    @if($leave->status === 'pending')
                                        <form action="{{ route('leave.approve', $leave) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                class="btn btn-sm btn-success px-3">
                                                <i class="bi bi-check2-circle"></i> Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('leave.reject', $leave) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                class="btn btn-sm btn-danger px-3">
                                                <i class="bi bi-x-circle"></i> Tolak
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted mb-0">Belum ada pengajuan cuti.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $leaves->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
