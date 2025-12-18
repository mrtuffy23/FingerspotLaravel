@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="bi bi-calendar2-check me-2"></i> Daftar Hari Libur
        </h2>

        <a href="{{ route('holiday-compensation.report', ['month' => $month, 'year' => $year]) }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- FILTER CARD -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body">
            <form action="{{ route('holiday-compensation.holidays') }}" method="GET" class="d-flex gap-2 align-items-end">
                <div>
                    <label class="form-label fw-semibold">Bulan</label>
                    <select name="month" class="form-select" required>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="form-label fw-semibold">Tahun</label>
                    <select name="year" class="form-select" required>
                        @for ($y = 2020; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i> Tampilkan
                </button>
            </form>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="card shadow border-0 rounded-4 overflow-hidden">

        <!-- Card Header -->
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-table me-2"></i> Daftar Hari Libur - {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
            </h5>
        </div>

        <!-- Table -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark text-center">
                        <tr>
                            <th class="py-3">Tanggal</th>
                            <th>Hari</th>
                            <th>Deskripsi</th>
                            <th>Tipe</th>
                            <th style="width: 150px">Status</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @forelse($holidays as $holiday)
                        <tr>
                            <td class="fw-semibold">
                                <i class="bi bi-calendar me-1 text-primary"></i>
                                {{ $holiday->date ?? $holiday->date }}
                            </td>
                            <td>
                                @php
                                    $date = \Carbon\Carbon::parse($holiday->date);
                                    $dayName = $date->translatedFormat('l');
                                @endphp
                                {{ $dayName }}
                            </td>
                            <td class="text-start">{{ $holiday->description }}</td>
                            <td>
                                @php
                                    $typeLabel = [
                                        'national_holiday' => 'Hari Nasional',
                                        'collective_leave' => 'Cuti Bersama',
                                        'weekend' => 'Hari Minggu',
                                    ][$holiday->type] ?? $holiday->type;

                                    $typeBadge = [
                                        'national_holiday' => 'danger',
                                        'collective_leave' => 'warning',
                                        'weekend' => 'secondary',
                                    ][$holiday->type] ?? 'dark';
                                @endphp
                                <span class="badge bg-{{ $typeBadge }} px-2 py-1">
                                    {{ $typeLabel }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i> Libur
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-muted">
                                <i class="bi bi-info-circle me-2"></i> Tidak ada hari libur di bulan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </div>

    <!-- INFO CARD -->
    <div class="alert alert-info border-0 rounded-3 mt-4" role="alert">
        <h6 class="fw-bold mb-2">
            <i class="bi bi-info-circle me-2"></i> Keterangan Tipe Hari Libur
        </h6>
        <ul class="mb-0">
            <li><strong>Hari Nasional:</strong> Hari libur resmi sesuai penetapan pemerintah</li>
            <li><strong>Cuti Bersama:</strong> Cuti bersama yang ditetapkan perusahaan/pemerintah</li>
            <li><strong>Hari Minggu:</strong> Hari minggu sebagai hari libur mingguan</li>
        </ul>
    </div>

</div>

<style>
    .table-row-hover:hover {
        background: #f4f8ff !important;
        transition: .2s;
    }
</style>
@endsection
