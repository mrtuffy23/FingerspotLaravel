<table class="table table-hover">
    <thead>
        <tr>
            <th>Periode</th>
            <th>Gaji Pokok</th>
            <th>Tunjangan</th>
            <th>Potongan</th>
            <th>Gaji Bersih</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($employee->payrolls()->latest('created_at')->limit(10)->get() as $payroll)
            <tr>
                <td>{{ $payroll->payrollPeriod->start_date->format('d-m-Y') }} s/d {{ $payroll->payrollPeriod->end_date->format('d-m-Y') }}</td>
                <td>Rp {{ number_format($payroll->basic_salary ?? 0, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($payroll->total_allowance ?? 0, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($payroll->total_deduction ?? 0, 0, ',', '.') }}</td>
                <td class="fw-bold text-success">Rp {{ number_format($payroll->net_salary ?? 0, 0, ',', '.') }}</td>
                <td>
                    @if($payroll->status === 'paid')
                        <span class="badge bg-success">Dibayar</span>
                    @elseif($payroll->status === 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($payroll->status) }}</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">Belum ada data penggajian</td>
            </tr>
        @endforelse
    </tbody>
</table>
