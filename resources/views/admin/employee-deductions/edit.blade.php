@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h3 class="fw-bold mb-1">
                <i class="bi bi-pencil-square me-2"></i>Edit Potongan Gaji
            </h3>
            <p class="text-muted mb-0">
                Karyawan: <span class="fw-bold">{{ $employee->name }}</span> ({{ $employee->nik }})
            </p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Form Edit Potongan: {{ $deduction->name }}</h5>
        </div>

        <form action="{{ route('employees.deductions.update', [$employee, $deduction]) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="card-body">

                {{-- Status Badge --}}
                <div class="mb-3 p-3 rounded bg-light border">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Status Potongan:</strong>
                        </div>
                        <div>
                            @php
                                $isActive = $deduction->isActiveOn(now());
                            @endphp
                            @if($isActive)
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle me-1"></i>Aktif Hari Ini
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">
                                    <i class="bi bi-pause-circle me-1"></i>Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        Mulai: {{ $deduction->start_date ? $deduction->start_date->format('d-m-Y') : 'Tanpa batas awal' }} | 
                        Berakhir: {{ $deduction->end_date ? $deduction->end_date->format('d-m-Y') : 'Tanpa batas akhir' }}
                    </small>
                </div>

                {{-- Nama Potongan --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">
                        <i class="bi bi-input-cursor me-1"></i>Nama Potongan
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name"
                           name="name"
                           placeholder="Contoh: Asuransi BPJS, Cicilan Hutang, dll"
                           value="{{ old('name', $deduction->name) }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kode Potongan --}}
                <div class="mb-3">
                    <label for="code" class="form-label fw-semibold">
                        <i class="bi bi-tag me-1"></i>Kode Potongan (Opsional)
                    </label>
                    <input type="text" 
                           class="form-control @error('code') is-invalid @enderror" 
                           id="code"
                           name="code"
                           placeholder="Contoh: BPJS, HUT, dll"
                           value="{{ old('code', $deduction->code) }}">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Kode singkat untuk identifikasi potongan</small>
                </div>

                {{-- Tipe Potongan --}}
                <div class="mb-3">
                    <label for="type" class="form-label fw-semibold">
                        <i class="bi bi-shuffle me-1"></i>Tipe Potongan
                    </label>
                    <select class="form-select @error('type') is-invalid @enderror" 
                            id="type"
                            name="type"
                            required>
                        <option value="fixed" {{ old('type', $deduction->type) === 'fixed' ? 'selected' : '' }}>
                            Potongan Tetap (sama setiap bulan)
                        </option>
                        <option value="variable" {{ old('type', $deduction->type) === 'variable' ? 'selected' : '' }}>
                            Potongan Per Hari (dihitung berdasarkan hari kerja)
                        </option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-2">
                        <strong>Tetap:</strong> Potongan dengan jumlah sama setiap bulan (misal: asuransi BPJS)<br>
                        <strong>Per Hari:</strong> Potongan dihitung × jumlah hari kerja (misal: cicilan hutang per hari)
                    </small>
                </div>

                {{-- Jumlah Potongan --}}
                <div class="mb-3">
                    <label for="amount" class="form-label fw-semibold">
                        <i class="bi bi-cash me-1"></i>Jumlah Potongan
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" 
                               class="form-control @error('amount') is-invalid @enderror" 
                               id="amount"
                               name="amount"
                               placeholder="0"
                               step="1000"
                               min="0"
                               value="{{ old('amount', $deduction->amount) }}"
                               required>
                    </div>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-2">
                        Masukkan jumlah potongan. Jika tipe "Per Hari", jumlah ini akan dikalikan dengan hari kerja.
                    </small>
                </div>

                <div class="row">
                    {{-- Tanggal Mulai --}}
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Tanggal Mulai (Opsional)
                        </label>
                        <input type="date" 
                               class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date"
                               name="start_date"
                               value="{{ old('start_date', $deduction->start_date?->format('Y-m-d')) }}">
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Potongan mulai berlaku dari tanggal ini</small>
                    </div>

                    {{-- Tanggal Berakhir --}}
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Tanggal Berakhir (Opsional)
                        </label>
                        <input type="date" 
                               class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date"
                               name="end_date"
                               value="{{ old('end_date', $deduction->end_date?->format('Y-m-d')) }}">
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Potongan berakhir pada tanggal ini. Kosongkan jika tidak ada batas akhir</small>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="mb-3">
                    <label for="notes" class="form-label fw-semibold">
                        <i class="bi bi-chat-left-text me-1"></i>Catatan (Opsional)
                    </label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes"
                              name="notes"
                              rows="3"
                              placeholder="Catatan tambahan tentang potongan ini...">{{ old('notes', $deduction->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Info Box --}}
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Informasi:</strong> Perubahan potongan akan berlaku untuk payroll periode berikutnya.
                </div>

            </div>

            <div class="card-footer bg-light">
                <div class="d-flex gap-2 justify-content-between">
                    <div>
                        <form action="{{ route('employees.deductions.destroy', [$employee, $deduction]) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Yakin hapus potongan ini? Tindakan tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash me-1"></i>Hapus Potongan
                            </button>
                        </form>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('employees.deductions.index', $employee) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>

<script>
    // Form validation
    (function () {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Sync end_date ke after_or_equal:start_date
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    startDateInput.addEventListener('change', () => {
        if (startDateInput.value && endDateInput.value) {
            if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
                endDateInput.value = startDateInput.value;
            }
        }
    });
</script>
@endsection
