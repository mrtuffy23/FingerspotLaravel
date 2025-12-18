<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $payroll->employee->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .employee-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-weight: bold;
            color: #333;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            color: #666;
            font-size: 14px;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .salary-table th {
            background-color: #2c3e50;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
        }
        .salary-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }
        .salary-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .label-col {
            color: #333;
            font-weight: 500;
            width: 60%;
        }
        .amount-col {
            text-align: right;
            color: #0066cc;
            font-weight: 600;
        }
        .total-row {
            background-color: #e8f0fe !important;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #0066cc;
            border-bottom: 2px solid #0066cc;
            padding: 15px 12px;
        }
        .total-amount {
            font-size: 16px;
            color: #0066cc;
        }
        .footer {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 30px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #ddd;
            text-align: center;
        }
        .signature-block {
            display: flex;
            flex-direction: column;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 12px;
            font-weight: 600;
            color: #333;
        }
        .notes {
            margin-top: 20px;
            padding: 10px;
            background-color: #fffacd;
            border-left: 4px solid #ffc700;
            font-size: 12px;
            color: #666;
        }
        .notes strong {
            color: #333;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            padding: 10px 20px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        .btn-print:hover {
            background-color: #0052a3;
        }
    </style>
</head>
<body>
    <div class="print-button no-print">
        <button class="btn-print" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Slip
        </button>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>SLIP GAJI KARYAWAN</h1>
            <p>Periode: {{ $payroll->payrollPeriod->start_date->format('d-m-Y') }} s/d {{ $payroll->payrollPeriod->end_date->format('d-m-Y') }}</p>
        </div>

        <!-- Employee Information -->
        <div class="employee-info">
            <div class="info-item">
                <span class="info-label">Nama Karyawan</span>
                <span class="info-value">{{ $payroll->employee->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">NIK</span>
                <span class="info-value">{{ $payroll->employee->nik }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Posisi</span>
                <span class="info-value">{{ $payroll->employee->position->name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Departemen</span>
                <span class="info-value">{{ $payroll->employee->department->name ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- Salary Details -->
        <table class="salary-table">
            <thead>
                <tr>
                    <th colspan="2">RINCIAN GAJI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="label-col">Jam Kerja Aktual</td>
                    <td class="amount-col">{{ number_format($payroll->total_actual_hours, 2) }} jam</td>
                </tr>
                <tr>
                    <td class="label-col">Kompensasi (Cuti, Sakit, Libur)</td>
                    <td class="amount-col">{{ number_format($payroll->total_compensated_hours, 2) }} jam</td>
                </tr>
                <tr class="total-row">
                    <td class="label-col">Total Jam Kerja</td>
                    <td class="amount-col">{{ number_format($payroll->total_hours, 2) }} jam</td>
                </tr>
                <tr>
                    <td class="label-col">Tarif Per Jam</td>
                    <td class="amount-col">Rp {{ number_format($payroll->rate_base, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label-col">Gaji Pokok</td>
                    <td class="amount-col total-amount">Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label-col">Tunjangan Jabatan</td>
                    <td class="amount-col">Rp {{ number_format($payroll->allowance_amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label-col">TOTAL GAJI KOTOR</td>
                    <td class="amount-col total-amount">Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <div class="signature-block">
                <div>Pembuat Slip</div>
                <div class="signature-line">{{ auth()->user()->name ?? 'Admin' }}</div>
            </div>
            <div class="signature-block">
                <div>Disetujui Oleh</div>
                <div class="signature-line">_________________</div>
            </div>
            <div class="signature-block">
                <div>Karyawan</div>
                <div class="signature-line">{{ $payroll->employee->name }}</div>
            </div>
        </div>

        <!-- Notes -->
        <div class="notes">
            <strong>Catatan:</strong>
            <ul style="margin-left: 15px; margin-top: 5px;">
                <li>Slip ini adalah dokumen resmi dari perusahaan</li>
                <li>Kompensasi termasuk cuti, sakit, dan libur yang disetujui</li>
                <li>Tanggal cetak: {{ now()->format('d-m-Y H:i') }}</li>
            </ul>
        </div>
    </div>
</body>
</html>
