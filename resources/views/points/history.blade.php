@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>📜 Riwayat Transaksi Poin</h1>
                <a href="{{ route('points.dashboard') }}" class="btn btn-secondary">← Kembali</a>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('points.history') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Karyawan</label>
                            <select name="employee_id" class="form-select">
                                <option value="">-- Semua Karyawan --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" @selected(request('employee_id') == $emp->id)>
                                        {{ $emp->name }} ({{ $emp->pin }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Alasan</label>
                            <input type="text" name="reason" class="form-control" placeholder="Cari alasan" value="{{ request('reason') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('points.history') }}" class="btn btn-secondary w-100 ms-2">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Total: {{ $transactions->total() }} transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Karyawan</th>
                                    <th>PIN</th>
                                    <th>Departemen</th>
                                    <th>Alasan</th>
                                    <th>Delta Poin</th>
                                    <th>Periode</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->date->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('points.employee-detail', $transaction->employee_id) }}">
                                                {{ $transaction->employee->name }}
                                            </a>
                                        </td>
                                        <td><code>{{ $transaction->employee->pin }}</code></td>
                                        <td>{{ $transaction->employee->department?->name ?? '-' }}</td>
                                        <td>
                                            <code class="text-break">{{ $transaction->reason }}</code>
                                        </td>
                                        <td>
                                            <span class="badge @if($transaction->delta < 0) bg-danger @elseif($transaction->delta > 0) bg-success @else bg-secondary @endif">
                                                {{ $transaction->delta > 0 ? '+' : '' }}{{ $transaction->delta }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($transaction->payrollPeriod)
                                                <small>{{ $transaction->payrollPeriod->name }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $transaction->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">Tidak ada transaksi ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $transactions->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
