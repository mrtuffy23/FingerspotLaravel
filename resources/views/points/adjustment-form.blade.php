@extends('layouts.admin')

@section('content')
<style>
    .card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .card-header {
        background: white;
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }
    
    .card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #212529;
        margin: 0;
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.5rem;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .alert {
        border-radius: 8px;
        border: 1px solid;
    }
    
    .alert-info {
        background-color: #e7f3ff;
        border-color: #b6d9f8;
        color: #084298;
    }
    
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffecb5;
        color: #664d03;
    }
    
    .info-box {
        background: #f8f9fa;
        border-left: 4px solid #0d6efd;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .info-item {
        display: flex;
        align-items: start;
        margin-bottom: 0.75rem;
    }
    
    .info-item:last-child {
        margin-bottom: 0;
    }
    
    .info-item i {
        color: #0d6efd;
        margin-right: 0.5rem;
        margin-top: 0.25rem;
    }
    
    .text-required {
        color: #dc3545;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Adjustment Poin Karyawan</h1>
                <p class="text-muted mb-0">Gunakan fitur ini untuk mengubah poin karyawan secara manual</p>
            </div>
            <a href="{{ route('points.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Alert Error -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.25rem;"></i>
                <div>
                    <h6 class="alert-heading mb-2">Terdapat Kesalahan</h6>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Form Adjustment Poin</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('points.submit-adjustment') }}">
                        @csrf

                        <!-- Employee Selection -->
                        <div class="mb-4">
                            <label for="employee_id" class="form-label">
                                Pilih Karyawan <span class="text-required">*</span>
                            </label>
                            <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
                                        {{ $employee->name }} (PIN: {{ $employee->pin }}) - Poin: {{ $employee->current_points }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih karyawan yang akan dilakukan adjustment</small>
                        </div>

                        <!-- Current Points Info -->
                        <div id="current_info" class="alert alert-info d-none mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <div>
                                    <div class="mb-1">
                                        <strong>Poin Saat Ini:</strong> <span id="current_points" class="badge bg-primary">-</span>
                                    </div>
                                    <div>
                                        <strong>Periode:</strong> <span id="period_info">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Point Delta -->
                        <div class="mb-4">
                            <label for="point_delta" class="form-label">
                                Perubahan Poin <span class="text-required">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" name="point_delta" id="point_delta" class="form-control @error('point_delta') is-invalid @enderror" 
                                    placeholder="Masukkan jumlah poin (positif atau negatif)" value="{{ old('point_delta') }}" required>
                                <span class="input-group-text">poin</span>
                            </div>
                            <small class="text-muted">
                                Nilai positif untuk menambah poin, nilai negatif untuk mengurangi poin. Contoh: 5 atau -10
                            </small>
                            @error('point_delta')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Preview -->
                        <div id="preview_info" class="alert d-none mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-eye-fill me-2"></i>
                                <div>
                                    <strong>Preview Perubahan:</strong><br>
                                    <span id="preview_text"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Reason/Notes -->
                        <div class="mb-4">
                            <label for="reason" class="form-label">
                                Alasan/Keterangan <span class="text-required">*</span>
                            </label>
                            <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" 
                                rows="4" placeholder="Jelaskan alasan perubahan poin secara detail..." required>{{ old('reason') }}</textarea>
                            <small class="text-muted">
                                Contoh: "Reward kinerja luar biasa", "Koreksi data absensi", "Bonus project completion"
                            </small>
                            @error('reason')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('points.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-lg"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle"></i> Informasi Penting
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Setiap adjustment akan dicatat dalam sistem dengan lengkap</span>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Admin yang melakukan perubahan akan tercatat secara otomatis</span>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Riwayat lengkap tersedia di menu "Riwayat Transaksi"</span>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Poin minimum adalah 0 (tidak akan kurang dari 0)</span>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Gunakan fitur ini hanya untuk kasus khusus atau koreksi</span>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-lightbulb"></i> Tips
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <i class="bi bi-arrow-right-circle text-primary"></i>
                        <span>Pastikan alasan yang ditulis jelas dan dapat dipertanggungjawabkan</span>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-arrow-right-circle text-primary"></i>
                        <span>Cek preview sebelum menyimpan perubahan</span>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-arrow-right-circle text-primary"></i>
                        <span>Koordinasi dengan atasan jika melakukan perubahan besar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const employeeSelect = document.getElementById('employee_id');
    const currentInfoDiv = document.getElementById('current_info');
    const previewInfoDiv = document.getElementById('preview_info');
    const pointDeltaInput = document.getElementById('point_delta');

    // Parse employee data from options
    const employeesData = {
        @foreach($employees as $emp)
            '{{ $emp->id }}': { 
                name: '{{ $emp->name }}', 
                current_points: {{ $emp->current_points }}, 
                period: '{{ optional($emp->currentPayrollPeriod)->name ?? "-" }}' 
            },
        @endforeach
    };

    employeeSelect.addEventListener('change', updatePreview);
    pointDeltaInput.addEventListener('input', updatePreview);

    function updatePreview() {
        const employeeId = employeeSelect.value;
        const delta = parseInt(pointDeltaInput.value) || 0;

        if (!employeeId) {
            currentInfoDiv.classList.add('d-none');
            previewInfoDiv.classList.add('d-none');
            return;
        }

        const employee = employeesData[employeeId];
        const newPoints = Math.max(0, employee.current_points + delta);

        // Show current info
        document.getElementById('current_points').textContent = employee.current_points;
        document.getElementById('period_info').textContent = employee.period;
        currentInfoDiv.classList.remove('d-none');

        // Show preview
        if (delta !== 0) {
            const arrow = delta > 0 ? '→' : '→';
            const badge = newPoints < 20 ? 'bg-danger' : (newPoints < 50 ? 'bg-warning text-dark' : 'bg-success');
            
            document.getElementById('preview_text').innerHTML = `
                Poin akan berubah dari <span class="badge bg-secondary">${employee.current_points}</span> 
                ${arrow} 
                <span class="badge ${badge}">${newPoints}</span>
                ${delta > 0 ? '<span class="text-success">(+' + delta + ')</span>' : '<span class="text-danger">(' + delta + ')</span>'}
            `;
            
            // Set alert color based on result
            let alertClass = 'alert-info';
            if (newPoints < 20) {
                alertClass = 'alert-danger';
            } else if (newPoints < 50) {
                alertClass = 'alert-warning';
            } else if (delta > 0) {
                alertClass = 'alert-success';
            }
            
            previewInfoDiv.className = `alert ${alertClass}`;
            previewInfoDiv.classList.remove('d-none');
        } else {
            previewInfoDiv.classList.add('d-none');
        }
    }
</script>
@endpush
@endsection