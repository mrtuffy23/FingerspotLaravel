@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-people-fill me-2 text-primary"></i> Data Karyawan
                </h2>
                <p class="text-muted mb-0">
                    Total: <strong>{{ $employees->total() }}</strong> karyawan terdaftar
                </p>
            </div>

            <div class="d-flex gap-2">
                <form class="d-none d-md-block">
                    <input type="text" class="form-control rounded-pill"
                           placeholder="🔍 Cari nama / NIK..."
                           style="min-width: 220px">
                </form>

                <a href="{{ route('karyawan.create') }}"
                   class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light text-center">
                        <tr>
                            <th>Profil</th>
                            <th>PIN</th>
                            <th>NIK</th>
                            <th>Departemen</th>
                            <th>Posisi</th>
                            <th>Gol</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($employees as $employee)
                        <tr class="text-center">

                            <!-- Profil -->
                            <td class="text-start">
                                <div class="d-flex align-items-center gap-3">
                                    @if($employee->photo)
                                        <img src="{{ asset($employee->photo) }}"
                                             alt="{{ $employee->name }}"
                                             class="rounded-circle"
                                             style="width:40px;height:40px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                             style="width:40px;height:40px;">
                                            {{ strtoupper(substr($employee->name,0,1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $employee->name }}</div>
                                        <small class="text-muted">ID: {{ $employee->id }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="fw-semibold">{{ $employee->pin }}</td>
                            <td>{{ $employee->nik }}</td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $employee->department?->name ?? '-' }}
                                </span>
                            </td>

                            <td>{{ $employee->position?->name ?? '-' }}</td>

                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $employee->classification?->code ?? '-' }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td>
                                <div class="btn-group">

                                    <a href="{{ route('karyawan.show', $employee->id) }}"
                                       class="btn btn-outline-info btn-sm"
                                       data-bs-toggle="tooltip" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('karyawan.edit', $employee->id) }}"
                                       class="btn btn-outline-warning btn-sm"
                                       data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('karyawan.destroy', $employee->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="tooltip" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox display-6 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">Belum ada data karyawan</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>

            <!-- Pagination -->
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $employees->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
