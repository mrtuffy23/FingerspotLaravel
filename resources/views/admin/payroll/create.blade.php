@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold">Buat Periode Penggajian</h2>
        <p class="text-muted">Tetapkan rentang tanggal periode gaji yang akan diproses.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <form action="{{ route('payroll.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event"></i> Start Date
                        </label>
                        <input type="date" 
                               class="form-control shadow-sm @error('start_date') is-invalid @enderror"
                               id="start_date" 
                               name="start_date" 
                               value="{{ old('start_date') }}" 
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label fw-semibold">
                            <i class="bi bi-calendar-check"></i> End Date
                        </label>
                        <input type="date" 
                               class="form-control shadow-sm @error('end_date') is-invalid @enderror"
                               id="end_date" 
                               name="end_date" 
                               value="{{ old('end_date') }}" 
                               required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label fw-semibold">
                        <i class="bi bi-pencil-square"></i> Catatan (Opsional)
                    </label>
                    <textarea 
                        class="form-control shadow-sm" 
                        id="notes" 
                        name="notes" 
                        rows="3"
                        placeholder="Tambahkan catatan untuk periode ini (jika ada)...">{{ old('notes') }}</textarea>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Buat Periode
                    </button>
                    <a href="{{ route('payroll.index') }}" class="btn btn-secondary px-4">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
