@extends('layouts.admin')

@section('content')

<!-- PAGE HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('allowance.index') }}" class="text-decoration-none">Konfigurasi Tunjangan</a></li>
                <li class="breadcrumb-item active">Edit Tunjangan</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="h3 fw-bold text-dark mb-0">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Edit Tunjangan
                </h1>
                <p class="text-muted mt-1 mb-0">
                    Golongan: <strong>{{ $classification->name }}</strong> ({{ $classification->code }})
                </p>
            </div>
            <a href="{{ route('allowance.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- CLASSIFICATION INFO CARD -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-4">
                    <div class="info-item">
                        <i class="bi bi-bookmark-fill text-primary me-2"></i>
                        <strong>{{ $classification->name }}</strong>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-tag-fill text-success me-2"></i>
                        <span class="text-muted">Kode:</span> <strong>{{ $classification->code }}</strong>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-bar-chart-fill text-info me-2"></i>
                        <span class="text-muted">Level:</span> <strong>{{ $classification->level }}</strong>
                    </div>
                    <div class="info-item">
                        <i class="bi bi-people-fill text-warning me-2"></i>
                        <strong>{{ $classification->employees->count() ?? 0 }}</strong> Karyawan
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                    <i class="bi bi-wallet2 me-1"></i>
                    {{ $classification->fixedAllowances->count() + $classification->variableAllowances->count() }} Total Tunjangan
                </span>
            </div>
        </div>
    </div>
</div>

<!-- FORM -->
<form action="{{ route('allowance.batch-update', $classification) }}" method="POST" id="allowanceForm">
    @csrf
    
    <div class="row g-4">
        
        <!-- FIXED ALLOWANCES -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success bg-gradient text-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-cash-stack me-2"></i>Tunjangan Tetap
                            </h5>
                            <small class="opacity-75">Nilai diberikan per bulan</small>
                        </div>
                        <span class="badge bg-white bg-opacity-25 px-3 py-2">
                            {{ $classification->fixedAllowances->count() }} Item
                        </span>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    @forelse ($classification->fixedAllowances as $index => $allowance)
                        <div class="allowance-item mb-3">
                            <input type="hidden" name="fixed_allowances[{{ $index }}][id]" value="{{ $allowance->id }}">
                            
                            <div class="allowance-header mb-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $allowance->name }}</div>
                                        <small class="text-muted">
                                            <i class="bi bi-tag me-1"></i>{{ $allowance->code }}
                                        </small>
                                    </div>
                                    <span class="badge bg-success bg-opacity-10 text-success">Per Bulan</span>
                                </div>
                            </div>
                            
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-currency-dollar"></i>
                                </span>
                                <input type="number" 
                                       name="fixed_allowances[{{ $index }}][amount]" 
                                       class="form-control border-start-0 fw-bold text-end" 
                                       value="{{ old("fixed_allowances.$index.amount", $allowance->amount) }}" 
                                       step="1000"
                                       min="0"
                                       placeholder="0"
                                       required>
                                <span class="input-group-text bg-light">
                                    <small class="text-muted">Rupiah</small>
                                </span>
                            </div>
                            
                            @if($allowance->amount > 0)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Nilai saat ini: <strong>Rp {{ number_format($allowance->amount, 0, ',', '.') }}</strong>
                                    </small>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3 opacity-25"></i>
                            <h6 class="text-muted">Belum Ada Tunjangan Tetap</h6>
                            <p class="text-muted small mb-0">Tidak ada tunjangan tetap untuk golongan ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- VARIABLE ALLOWANCES -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning bg-gradient text-dark border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-calendar-range me-2"></i>Tunjangan Tidak Tetap
                            </h5>
                            <small class="opacity-75">Nilai diberikan per hari kerja</small>
                        </div>
                        <span class="badge bg-white bg-opacity-25 px-3 py-2">
                            {{ $classification->variableAllowances->count() }} Item
                        </span>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    @forelse ($classification->variableAllowances as $index => $allowance)
                        <div class="allowance-item mb-3">
                            <input type="hidden" name="variable_allowances[{{ $index }}][id]" value="{{ $allowance->id }}">
                            
                            <div class="allowance-header mb-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $allowance->name }}</div>
                                        <small class="text-muted">
                                            <i class="bi bi-tag me-1"></i>{{ $allowance->code }}
                                        </small>
                                    </div>
                                    <span class="badge bg-warning bg-opacity-10 text-warning">Per Hari</span>
                                </div>
                            </div>
                            
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-currency-dollar"></i>
                                </span>
                                <input type="number" 
                                       name="variable_allowances[{{ $index }}][amount_per_day]" 
                                       class="form-control border-start-0 fw-bold text-end" 
                                       value="{{ old("variable_allowances.$index.amount_per_day", $allowance->amount_per_day) }}" 
                                       step="1000"
                                       min="0"
                                       placeholder="0"
                                       required>
                                <span class="input-group-text bg-light">
                                    <small class="text-muted">/Hari</small>
                                </span>
                            </div>
                            
                            @if($allowance->amount_per_day > 0)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Nilai saat ini: <strong>Rp {{ number_format($allowance->amount_per_day, 0, ',', '.') }}/hari</strong>
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-calculator me-1"></i>
                                        Estimasi 22 hari: <strong>Rp {{ number_format($allowance->amount_per_day * 22, 0, ',', '.') }}</strong>
                                    </small>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3 opacity-25"></i>
                            <h6 class="text-muted">Belum Ada Tunjangan Tidak Tetap</h6>
                            <p class="text-muted small mb-0">Tidak ada tunjangan tidak tetap untuk golongan ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- ACTION BUTTONS -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="alert alert-info bg-info bg-opacity-10 border-0 mb-0">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Perhatian:</strong> Perubahan tunjangan akan berlaku untuk penghitungan payroll berikutnya. 
                        Pastikan semua nilai sudah benar sebelum menyimpan.
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('allowance.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-save-fill me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

<!-- CALCULATION PREVIEW -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-calculator text-primary me-2"></i>Estimasi Total Tunjangan
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-box bg-success bg-opacity-10 rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-success fw-semibold">Tunjangan Tetap/Bulan</small>
                            <h4 class="fw-bold mb-0 mt-1 text-success" id="totalFixed">
                                Rp {{ number_format($classification->fixedAllowances->sum('amount'), 0, ',', '.') }}
                            </h4>
                        </div>
                        <i class="bi bi-cash-stack fs-2 text-success opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-box bg-warning bg-opacity-10 rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-warning fw-semibold">Tunjangan Tidak Tetap/Hari</small>
                            <h4 class="fw-bold mb-0 mt-1 text-warning" id="totalVariable">
                                Rp {{ number_format($classification->variableAllowances->sum('amount_per_day'), 0, ',', '.') }}
                            </h4>
                        </div>
                        <i class="bi bi-calendar-check fs-2 text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-box bg-primary bg-opacity-10 rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-primary fw-semibold">Estimasi Total (22 Hari)</small>
                            <h4 class="fw-bold mb-0 mt-1 text-primary" id="totalEstimate">
                                Rp {{ number_format($classification->fixedAllowances->sum('amount') + ($classification->variableAllowances->sum('amount_per_day') * 22), 0, ',', '.') }}
                            </h4>
                        </div>
                        <i class="bi bi-wallet2 fs-2 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.allowance-item {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.2s ease;
}

.allowance-item:hover {
    background: #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.allowance-item:last-child {
    margin-bottom: 0 !important;
}

.input-group-lg input[type="number"] {
    font-size: 1.25rem;
}

.input-group-text {
    background-color: #f8f9fa;
}

.form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1);
}

.empty-state i {
    opacity: 0.3;
}

.bg-gradient {
    position: relative;
}

.bg-success.bg-gradient {
    background: linear-gradient(135deg, #198754, #20c997) !important;
}

.bg-warning.bg-gradient {
    background: linear-gradient(135deg, #ffc107, #fd7e14) !important;
}

.stat-box {
    transition: all 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.card {
    transition: all 0.3s ease;
    border-radius: 16px;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
}

.breadcrumb-item a {
    color: #6c757d;
}

.breadcrumb-item a:hover {
    color: #0d6efd;
}

.info-item {
    display: inline-flex;
    align-items: center;
    padding: 8px 0;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    opacity: 1;
}
</style>
@endpush

@push('scripts')
<script>
// Real-time calculation update
function updateTotals() {
    let totalFixed = 0;
    let totalVariable = 0;
    
    // Calculate fixed allowances
    document.querySelectorAll('input[name*="fixed_allowances"][name*="[amount]"]').forEach(input => {
        totalFixed += parseFloat(input.value) || 0;
    });
    
    // Calculate variable allowances
    document.querySelectorAll('input[name*="variable_allowances"][name*="[amount_per_day]"]').forEach(input => {
        totalVariable += parseFloat(input.value) || 0;
    });
    
    // Update display
    document.getElementById('totalFixed').textContent = 'Rp ' + totalFixed.toLocaleString('id-ID');
    document.getElementById('totalVariable').textContent = 'Rp ' + totalVariable.toLocaleString('id-ID');
    document.getElementById('totalEstimate').textContent = 'Rp ' + (totalFixed + (totalVariable * 22)).toLocaleString('id-ID');
}

// Add event listeners to all inputs
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('input', updateTotals);
});

// Form validation
document.getElementById('allowanceForm').addEventListener('submit', function(e) {
    const inputs = this.querySelectorAll('input[type="number"]');
    let hasError = false;
    
    inputs.forEach(input => {
        if (input.value < 0) {
            hasError = true;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    if (hasError) {
        e.preventDefault();
        alert('Nilai tunjangan tidak boleh negatif!');
        return false;
    }
    
    // Confirmation
    if (!confirm('Apakah Anda yakin ingin menyimpan perubahan tunjangan ini?')) {
        e.preventDefault();
        return false;
    }
});

// Format number on blur
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value) {
            // Round to nearest 1000
            const rounded = Math.round(this.value / 1000) * 1000;
            this.value = rounded;
            updateTotals();
        }
    });
});
</script>
@endpush