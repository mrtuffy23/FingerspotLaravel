@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Manajemen Penggajian</h2>
            <p class="text-muted">Kelola periode penggajian dan status payroll dengan mudah</p>
        </div>
        <a href="{{ route('payroll.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle"></i> Buat Periode Payroll
        </a>
    </div>

    {{-- Card --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Daftar Periode Penggajian</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Periode #</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Total Payroll</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($periods as $period)
                            <tr>
                                <td class="fw-bold">#{{ $period->id }}</td>

                                <td>{{ $period->start_date->format('Y-m-d') }}</td>
                                <td>{{ $period->end_date->format('Y-m-d') }}</td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ $period->payrolls_count }}
                                    </span>
                                </td>

                                <td>
                                    @if($period->status === 'finalized')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="bi bi-check-circle"></i> Finalized
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="bi bi-hourglass-split"></i> Draft
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('payroll.show', $period) }}" 
                                       class="btn btn-sm btn-info me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if($period->status !== 'finalized')
                                        <form action="{{ route('payroll.finalize', $period) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                class="btn btn-sm btn-success me-1"
                                                onclick="return confirm('Yakin finalize payroll ini?')">
                                                <i class="bi bi-check2-circle"></i> Finalize
                                            </button>
                                        </form>

                                        <form action="{{ route('payroll.destroy', $period) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin hapus periode payroll ini? Semua data payroll di periode ini akan ikut terhapus!')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inboxes fs-3"></i>
                                    <p class="mt-2 mb-0">Belum ada periode payroll</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <div class="card-footer bg-light">
            <div class="d-flex justify-content-center">
                {{ $periods->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
