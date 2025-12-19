<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penugasan Shift - Laravel Payroll</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: white;
            color: #667eea;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-info {
            background: #3b82f6;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .content {
            padding: 30px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .filter-section {
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-control {
            padding: 10px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f3f4f6;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f3f4f6;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-monthly {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-daily {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-ended {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: #667eea;
            border: 1px solid #e5e7eb;
        }

        .pagination .active {
            background: #667eea;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Penugasan Shift Karyawan</h1>
            <a href="{{ route('shift-assignments.create') }}" class="btn btn-primary">+ Tambah Penugasan</a>
        </div>

        <div class="content">
            <a href="{{ route('dashboard') }}" class="back-link">← Kembali ke Dashboard</a>

            @if(session('success'))
                <div class="alert alert-success">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <!-- Filter Section -->
            <form method="GET" action="{{ route('shift-assignments.index') }}">
                <div class="filter-section">
                    <h3 style="margin-bottom: 15px;">🔍 Filter Penugasan</h3>
                    <div class="filter-row">
                        <div class="form-group">
                            <label>Karyawan</label>
                            <select name="employee_id" class="form-control">
                                <option value="">Semua Karyawan</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Shift</label>
                            <select name="shift_id" class="form-control">
                                <option value="">Semua Shift</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ request('shift_id') == $shift->id ? 'selected' : '' }}>
                                        {{ $shift->code }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tipe Karyawan</label>
                            <select name="employment_type" class="form-control">
                                <option value="">Semua Tipe</option>
                                <option value="monthly" {{ request('employment_type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                <option value="daily" {{ request('employment_type') == 'daily' ? 'selected' : '' }}>Harian</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="checkbox-group">
                                <input type="checkbox" name="active_only" id="active_only" value="1" {{ request('active_only') ? 'checked' : '' }}>
                                <label for="active_only" style="margin: 0;">Hanya Aktif</label>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-info">Terapkan Filter</button>
                        <a href="{{ route('shift-assignments.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Departemen</th>
                            <th>Tipe</th>
                            <th>Shift</th>
                            <th>Jam Kerja</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Akhir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr>
                                <td>
                                    <strong>{{ $assignment->employee->name }}</strong><br>
                                    <small style="color: #6b7280;">PIN: {{ $assignment->employee->pin }}</small>
                                </td>
                                <td>{{ $assignment->employee->department->name ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $assignment->employee->employment_type == 'monthly' ? 'monthly' : 'daily' }}">
                                        {{ $assignment->employee->employment_type == 'monthly' ? 'Bulanan' : 'Harian' }}
                                    </span>
                                </td>
                                <td><strong>{{ $assignment->shift->code }}</strong></td>
                                <td>{{ $assignment->shift->start_time }} - {{ $assignment->shift->end_time }}</td>
                                <td>{{ \Carbon\Carbon::parse($assignment->start_date)->format('d M Y') }}</td>
                                <td>
                                    @if($assignment->end_date)
                                        {{ \Carbon\Carbon::parse($assignment->end_date)->format('d M Y') }}
                                    @else
                                        <span style="color: #10b981;">Tidak Terbatas</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$assignment->end_date || \Carbon\Carbon::parse($assignment->end_date)->isFuture())
                                        <span class="badge badge-active">✓ Aktif</span>
                                    @else
                                        <span class="badge badge-ended">✗ Berakhir</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('shift-assignments.edit', $assignment) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('shift-assignments.destroy', $assignment) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 40px; color: #6b7280;">
                                    Tidak ada data penugasan shift
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $assignments->links() }}
            </div>
        </div>
    </div>
</body>
</html>
