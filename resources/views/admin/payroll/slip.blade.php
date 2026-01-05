@extends('layouts.admin')

@section('content')

<!-- NO PRINT: Action Buttons -->
<div class="no-print container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Slip Gaji Karyawan</h1>
            <p class="text-muted mb-0">{{ $payroll->employee->name }} - Periode #{{ $payroll->payrollPeriod->id }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="toggleFormat()">
                <i class="bi bi-layout-split me-1"></i>
                <span id="formatToggle">Nota Kecil</span>
            </button>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer-fill me-1"></i>Cetak
            </button>
            <a href="{{ route('payroll.show', $payroll->payrollPeriod->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- FORMAT A4 (Default) -->
<div id="formatA4" class="print-container">
    <div class="payslip-a4">
        
        <!-- Header -->
        <div class="slip-header">
            <div class="company-info">
                <div class="company-logo">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <h1 class="company-name">NAMA PERUSAHAAN</h1>
                    <p class="company-address">Jl. Contoh Alamat No. 123, Yogyakarta</p>
                    <p class="company-contact">Telp: (0274) 123456 | Email: hr@company.com</p>
                </div>
            </div>
            <div class="slip-title">
                <h2>SLIP GAJI</h2>
                <div class="slip-number">No: {{ str_pad($payroll->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Period Info -->
        <div class="period-info">
            <div class="info-box">
                <i class="bi bi-calendar-range"></i>
                <div>
                    <div class="label">Periode Penggajian</div>
                    <div class="value">{{ $payroll->payrollPeriod->start_date->format('d M Y') }} - {{ $payroll->payrollPeriod->end_date->format('d M Y') }}</div>
                </div>
            </div>
            <div class="info-box">
                <i class="bi bi-calendar-check"></i>
                <div>
                    <div class="label">Tanggal Cetak</div>
                    <div class="value">{{ now()->format('d M Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Employee Info -->
        <div class="employee-section">
            <h3 class="section-title">Informasi Karyawan</h3>
            <div class="employee-grid">
                <div class="info-item">
                    <span class="label">Nama</span>
                    <span class="value">{{ $payroll->employee->name }}</span>
                </div>
                <div class="info-item">
                    <span class="label">NIK</span>
                    <span class="value">{{ $payroll->employee->nik }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Posisi</span>
                    <span class="value">{{ $payroll->employee->position->name ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Departemen</span>
                    <span class="value">{{ $payroll->employee->department->name ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Golongan</span>
                    <span class="value">{{ $payroll->employee->classification->name ?? '-' }} ({{ $payroll->employee->classification->code ?? '-' }})</span>
                </div>
                <div class="info-item">
                    <span class="label">Status</span>
                    <span class="value">{{ ucfirst($payroll->employee->status) }}</span>
                </div>
            </div>
        </div>

        <!-- Salary Details -->
        <div class="salary-section">
            <h3 class="section-title">Rincian Penggajian</h3>

            <!-- Working Hours -->
            <div class="detail-group">
                <div class="group-header">Jam Kerja</div>
                <div class="detail-row">
                    <span>Jam Kerja Aktual</span>
                    <span class="amount">{{ number_format($payroll->total_actual_hours, 2) }} jam</span>
                </div>
                <div class="detail-row">
                    <span>Kompensasi (Cuti/Sakit/Libur)</span>
                    <span class="amount">{{ number_format($payroll->total_compensated_hours, 2) }} jam</span>
                </div>
                <div class="detail-row total">
                    <span>Total Jam Kerja</span>
                    <span class="amount">{{ number_format($payroll->total_hours, 2) }} jam</span>
                </div>
            </div>

            <!-- Base Salary -->
            <div class="detail-group">
                <div class="group-header">Gaji Pokok</div>
                <div class="detail-row">
                    <span>Tarif per Jam</span>
                    <span class="amount">Rp {{ number_format($payroll->rate_base, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row total">
                    <span>Gaji Pokok</span>
                    <span class="amount">Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Fixed Allowances -->
            <div class="detail-group">
                <div class="group-header">Tunjangan Tetap</div>
                @forelse($payroll->payrollDetails->where('type', 'ALLOWANCE')->where('category', 'FIXED') as $detail)
                    <div class="detail-row">
                        <span>{{ $detail->name }}</span>
                        <span class="amount">Rp {{ number_format($detail->amount, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="detail-row empty">
                        <span>Tidak ada tunjangan tetap</span>
                    </div>
                @endforelse
                <div class="detail-row total">
                    <span>Subtotal Tunjangan Tetap</span>
                    <span class="amount">Rp {{ number_format($payroll->total_fixed_allowance, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Variable Allowances -->
            <div class="detail-group">
                <div class="group-header">Tunjangan Tidak Tetap</div>
                @forelse($payroll->payrollDetails->where('type', 'ALLOWANCE')->where('category', 'VARIABLE') as $detail)
                    <div class="detail-row">
                        <span>{{ $detail->name }}</span>
                        <span class="amount">Rp {{ number_format($detail->amount, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="detail-row empty">
                        <span>Tidak ada tunjangan tidak tetap</span>
                    </div>
                @endforelse
                <div class="detail-row total">
                    <span>Subtotal Tunjangan Tidak Tetap</span>
                    <span class="amount">Rp {{ number_format($payroll->total_variable_allowance, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Gross Salary -->
            <div class="summary-row gross">
                <span>TOTAL GAJI KOTOR</span>
                <span class="amount">Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}</span>
            </div>

            <!-- Fixed Deductions -->
            <div class="detail-group">
                <div class="group-header deduction">Potongan Tetap</div>
                @forelse($payroll->payrollDetails->where('type', 'DEDUCTION')->where('category', 'FIXED') as $detail)
                    <div class="detail-row">
                        <span>{{ $detail->name }}</span>
                        <span class="amount deduction">Rp {{ number_format($detail->amount, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="detail-row empty">
                        <span>Tidak ada potongan tetap</span>
                    </div>
                @endforelse
                <div class="detail-row total">
                    <span>Subtotal Potongan Tetap</span>
                    <span class="amount deduction">Rp {{ number_format($payroll->total_fixed_deduction, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Variable Deductions -->
            <div class="detail-group">
                <div class="group-header deduction">Potongan Tidak Tetap</div>
                @forelse($payroll->payrollDetails->where('type', 'DEDUCTION')->where('category', 'VARIABLE') as $detail)
                    <div class="detail-row">
                        <span>{{ $detail->name }}</span>
                        <span class="amount deduction">Rp {{ number_format($detail->amount, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="detail-row empty">
                        <span>Tidak ada potongan tidak tetap</span>
                    </div>
                @endforelse
                <div class="detail-row total">
                    <span>Subtotal Potongan Tidak Tetap</span>
                    <span class="amount deduction">Rp {{ number_format($payroll->total_variable_deduction, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Total Deductions -->
            <div class="summary-row deduction-total">
                <span>TOTAL POTONGAN</span>
                <span class="amount">Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</span>
            </div>

            <!-- Net Salary -->
            <div class="summary-row net">
                <span>GAJI BERSIH</span>
                <span class="amount">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">Dibuat Oleh</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ auth()->user()->name ?? 'HR Department' }}</div>
                <div class="signature-position">HRD</div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Disetujui Oleh</div>
                <div class="signature-space"></div>
                <div class="signature-name">___________________</div>
                <div class="signature-position">Manager</div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Diterima Oleh</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $payroll->employee->name }}</div>
                <div class="signature-position">Karyawan</div>
            </div>
        </div>

        <!-- Footer Notes -->
        <div class="footer-notes">
            <div class="note-title">Catatan Penting:</div>
            <ul>
                <li>Slip gaji ini adalah dokumen resmi dari perusahaan</li>
                <li>Kompensasi jam termasuk cuti, sakit, dan hari libur yang disetujui</li>
                <li>Mohon simpan slip ini sebagai bukti pembayaran gaji</li>
                <li>Untuk pertanyaan silakan hubungi HRD</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="slip-footer">
            Dicetak pada: {{ now()->format('d F Y H:i') }} | Dokumen ini dihasilkan oleh sistem dan sah tanpa tanda tangan basah
        </div>
    </div>
</div>

<!-- FORMAT NOTA KECIL -->
<div id="formatReceipt" class="print-container" style="display: none;">
    <div class="payslip-receipt">
        <div class="receipt-header">
            <div class="company-name-receipt">NAMA PERUSAHAAN</div>
            <div class="receipt-line">================================</div>
            <div class="receipt-title">SLIP GAJI</div>
            <div class="receipt-number">No: {{ str_pad($payroll->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="receipt-line">================================</div>
        </div>

        <div class="receipt-info">
            <div class="receipt-row">
                <span>Nama</span>
                <span>: {{ $payroll->employee->name }}</span>
            </div>
            <div class="receipt-row">
                <span>NIK</span>
                <span>: {{ $payroll->employee->nik }}</span>
            </div>
            <div class="receipt-row">
                <span>Posisi</span>
                <span>: {{ $payroll->employee->position->name ?? '-' }}</span>
            </div>
            <div class="receipt-row">
                <span>Periode</span>
                <span>: {{ $payroll->payrollPeriod->start_date->format('d/m/Y') }} - {{ $payroll->payrollPeriod->end_date->format('d/m/Y') }}</span>
            </div>
            <div class="receipt-line">--------------------------------</div>
        </div>

        <div class="receipt-details">
            <div class="receipt-section-title">JAM KERJA</div>
            <div class="receipt-row">
                <span>Jam Aktual</span>
                <span>{{ number_format($payroll->total_actual_hours, 1) }}h</span>
            </div>
            <div class="receipt-row">
                <span>Kompensasi</span>
                <span>{{ number_format($payroll->total_compensated_hours, 1) }}h</span>
            </div>
            <div class="receipt-row bold">
                <span>Total Jam</span>
                <span>{{ number_format($payroll->total_hours, 1) }}h</span>
            </div>

            <div class="receipt-line">--------------------------------</div>

            <div class="receipt-section-title">PENDAPATAN</div>
            <div class="receipt-row">
                <span>Gaji Pokok</span>
                <span>{{ number_format($payroll->base_salary, 0) }}</span>
            </div>
            <div class="receipt-row">
                <span>Tunj. Tetap</span>
                <span>{{ number_format($payroll->total_fixed_allowance, 0) }}</span>
            </div>
            <div class="receipt-row">
                <span>Tunj. Tdk Tetap</span>
                <span>{{ number_format($payroll->total_variable_allowance, 0) }}</span>
            </div>
            <div class="receipt-row bold">
                <span>Gaji Kotor</span>
                <span>{{ number_format($payroll->total_salary, 0) }}</span>
            </div>

            <div class="receipt-line">--------------------------------</div>

            <div class="receipt-section-title">POTONGAN</div>
            <div class="receipt-row">
                <span>Pot. Tetap</span>
                <span>{{ number_format($payroll->total_fixed_deduction, 0) }}</span>
            </div>
            <div class="receipt-row">
                <span>Pot. Tdk Tetap</span>
                <span>{{ number_format($payroll->total_variable_deduction, 0) }}</span>
            </div>
            <div class="receipt-row bold">
                <span>Total Potongan</span>
                <span>{{ number_format($payroll->total_deduction, 0) }}</span>
            </div>

            <div class="receipt-line">================================</div>

            <div class="receipt-total">
                <div>GAJI BERSIH</div>
                <div>Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</div>
            </div>

            <div class="receipt-line">================================</div>
        </div>

        <div class="receipt-footer">
            <div class="receipt-note">Terima kasih</div>
            <div class="receipt-note">{{ now()->format('d/m/Y H:i') }}</div>
            <div class="receipt-note">Simpan slip ini sebagai bukti</div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Hide on screen */
@media screen {
    .print-container {
        background: white;
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }
}

/* Print Styles */
@media print {
    body * {
        visibility: hidden;
    }
    
    .no-print {
        display: none !important;
    }
    
    .print-container,
    .print-container * {
        visibility: visible;
    }
    
    .print-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
    }
    
    @page {
        size: A4;
        margin: 15mm;
    }
}

/* A4 Format Styles */
.payslip-a4 {
    background: white;
    padding: 30px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    line-height: 1.6;
}

.slip-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 20px;
}

.company-info {
    display: flex;
    gap: 15px;
    align-items: start;
}

.company-logo {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 28px;
}

.company-name {
    font-size: 20px;
    font-weight: bold;
    margin: 0;
    color: #1e293b;
}

.company-address,
.company-contact {
    font-size: 12px;
    color: #64748b;
    margin: 2px 0;
}

.slip-title {
    text-align: right;
}

.slip-title h2 {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
    color: #4f46e5;
}

.slip-number {
    font-size: 12px;
    color: #64748b;
    margin-top: 5px;
}

.divider {
    border-top: 3px solid #4f46e5;
    margin: 20px 0;
}

.period-info {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
}

.info-box {
    flex: 1;
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #4f46e5;
}

.info-box i {
    font-size: 24px;
    color: #4f46e5;
}

.info-box .label {
    font-size: 11px;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 600;
}

.info-box .value {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

.employee-section,
.salary-section {
    margin-bottom: 25px;
}

.section-title {
    font-size: 16px;
    font-weight: bold;
    color: #1e293b;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e2e8f0;
}

.employee-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background: #f8fafc;
    border-radius: 6px;
}

.info-item .label {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
}

.info-item .value {
    font-size: 13px;
    color: #1e293b;
    font-weight: 600;
}

.detail-group {
    margin-bottom: 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}

.group-header {
    background: #f1f5f9;
    padding: 8px 15px;
    font-size: 13px;
    font-weight: bold;
    color: #475569;
}

.group-header.deduction {
    background: #fef2f2;
    color: #dc2626;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 15px;
    font-size: 13px;
    border-bottom: 1px solid #f1f5f9;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row.empty {
    color: #94a3b8;
    font-style: italic;
    justify-content: center;
}

.detail-row.total {
    background: #f8fafc;
    font-weight: bold;
}

.detail-row .amount {
    font-weight: 600;
    color: #1e293b;
}

.detail-row .amount.deduction {
    color: #dc2626;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 15px;
    font-size: 15px;
    font-weight: bold;
    margin: 15px 0;
    border-radius: 8px;
}

.summary-row.gross {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: white;
}

.summary-row.deduction-total {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.summary-row.net {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: white;
    font-size: 18px;
    padding: 20px;
}

.signature-section {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin: 30px 0;
}

.signature-box {
    text-align: center;
}

.signature-label {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 10px;
}

.signature-space {
    height: 60px;
    border-bottom: 1px solid #cbd5e1;
    margin: 10px 0;
}

.signature-name {
    font-size: 13px;
    font-weight: bold;
    color: #1e293b;
    margin-top: 10px;
}

.signature-position {
    font-size: 11px;
    color: #64748b;
}

.footer-notes {
    background: #fef3c7;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #f59e0b;
    margin: 20px 0;
}

.note-title {
    font-weight: bold;
    color: #92400e;
    margin-bottom: 8px;
}

.footer-notes ul {
    margin: 0;
    padding-left: 20px;
    color: #78350f;
    font-size: 12px;
}

.footer-notes li {
    margin: 4px 0;
}

.slip-footer {
    text-align: center;
    font-size: 11px;
    color: #94a3b8;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
}

/* Receipt Format Styles */
.payslip-receipt {
    width: 80mm;
    margin: 0 auto;
    padding: 10px;
    font-family: 'Courier New', monospace;
    font-size: 11px;
    line-height: 1.4;
}

.receipt-header {
    text-align: center;
    margin-bottom: 10px;
}

.company-name-receipt {
    font-size: 14px;
    font-weight: bold;
}

.receipt-line {
    margin: 5px 0;
}

.receipt-title {
    font-size: 13px;
    font-weight: bold;
    margin: 5px 0;
}

.receipt-number {
    font-size: 10px;
}

.receipt-info,
.receipt-details {
    margin: 10px 0;
}

.receipt-row {
    display: flex;
    justify-content: space-between;
    margin: 3px 0;
}

.receipt-row.bold {
    font-weight: bold;
}

.receipt-section-title {
    font-weight: bold;
    margin: 8px 0 5px 0;
    text-align: center;
}

.receipt-total {
    text-align: center;
    font-weight: bold;
    font-size: 14px;
    margin: 10px 0;
}

.receipt-total div:first-child {
    margin-bottom: 5px;
}

.receipt-footer {
    text-align: center;
    margin-top: 10px;
    font-size: 10px;
}

.receipt-note {
    margin: 3px 0;
}

@media print {
    #formatReceipt.print-container {
        width: 80mm;
    }
    
    #formatReceipt @page {
        size: 80mm auto;
        margin: 0;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentFormat = 'a4';

function toggleFormat() {
    const formatA4 = document.getElementById('formatA4');
    const formatReceipt = document.getElementById('formatReceipt');
    const toggleBtn = document.getElementById('formatToggle');
    
    if (currentFormat === 'a4') {
        formatA4.style.display = 'none';
        formatReceipt.style.display = 'block';
        toggleBtn.textContent = 'Format A4';
        currentFormat = 'receipt';
    } else {
        formatA4.style.display = 'block';
        formatReceipt.style.display = 'none';
        toggleBtn.textContent = 'Nota Kecil';
        currentFormat = 'a4';
    }
}
</script>
@endpush