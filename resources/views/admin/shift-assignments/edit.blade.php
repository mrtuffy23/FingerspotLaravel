<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penugasan Shift</title>
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
            max-width: 800px;
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
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
        }

        .content {
            padding: 40px;
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

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
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
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Edit Penugasan Shift</h1>
        </div>

        <div class="content">
            <a href="{{ route('shift-assignments.index') }}" class="back-link">← Kembali</a>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>⚠ Ada kesalahan:</strong>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('shift-assignments.update', $shiftAssignment) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="employee_id">Karyawan *</label>
                    <select name="employee_id" id="employee_id" class="form-control" required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ $shiftAssignment->employee_id == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} ({{ $emp->pin }}) - {{ $emp->employment_type == 'monthly' ? 'Bulanan' : 'Harian' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="shift_id">Shift *</label>
                    <select name="shift_id" id="shift_id" class="form-control" required>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ $shiftAssignment->shift_id == $shift->id ? 'selected' : '' }}>
                                {{ $shift->code }} - {{ $shift->start_time }} s/d {{ $shift->end_time }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date">Tanggal Mulai *</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $shiftAssignment->start_date->format('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="end_date">Tanggal Akhir (Opsional)</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $shiftAssignment->end_date?->format('Y-m-d') }}">
                    <small style="color: #6b7280; display: block; margin-top: 5px;">
                        Kosongkan jika penugasan berlaku terus menerus
                    </small>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">💾 Update Penugasan</button>
                    <a href="{{ route('shift-assignments.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
