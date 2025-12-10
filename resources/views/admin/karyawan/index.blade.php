@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-people-fill me-2"></i> Data Karyawan
            </h2>
            <p class="text-muted mt-1">Kelola informasi semua karyawan yang terdaftar</p>
        </div>

        <a href="{{ route('karyawan.create') }}" class="btn btn-success btn-lg shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Karyawan
        </a>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4">

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-dark text-center">
                        <tr>
                            <th>PIN</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Posisi</th>
                            <th>Email</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($employees as $employee)
                            <tr class="text-center">
                                <td><span class="fw-bold">{{ $employee->pin }}</span></td>
                                <td>{{ $employee->nik }}</td>
                                <td class="text-start">
                                    <div class="fw-semibold">{{ $employee->name }}</div>
                                    <span class="text-muted small">ID: {{ $employee->id }}</span>
                                </td>
                                <td>{{ $employee->department?->name ?? '-' }}</td>
                                <td>{{ $employee->position?->name ?? '-' }}</td>
                                <td>{{ $employee->email }}</td>

                                <td>
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('karyawan.show', $employee->id) }}" 
                                           class="btn btn-info btn-sm rounded-pill px-3">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('karyawan.edit', $employee->id) }}" 
                                           class="btn btn-warning btn-sm rounded-pill px-3">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('karyawan.destroy', $employee->id) }}" 
                                              method="POST" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="btn btn-danger btn-sm rounded-pill px-3">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-emoji-neutral display-6 text-muted d-block mb-2"></i>
                                    <p class="text-muted">Belum ada data karyawan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $employees->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
