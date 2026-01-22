@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Import Absensi Fingerspot</h3>
        <a href="{{ route('absen.process') }}" class="btn btn-info">
            <i class="fas fa-cogs"></i> Proses Absensi
        </a>
    </div>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data" action="{{ route('absen.import.store') }}">
        @csrf
        <div class="form-group mb-3">
            <label for="file">File Excel/CSV:</label>
            <input type="file" name="file" id="file" class="form-control" 
                   accept=".csv,.txt,.xlsx" required>
            <small class="form-text text-muted">
                Format yang diterima: CSV, TXT, XLSX (Maksimal 5MB)
            </small>
        </div>

        <div class="alert alert-info mb-3">
            <strong>Format File Excel yang diharapkan:</strong>
            <ul class="mb-0 mt-2">
                <li><strong>Kolom PIN/ID:</strong> pin, PIN, employee_pin, employee id, id, userid, user_id</li>
                <li><strong>Kolom Waktu:</strong> datetime, event_time, timestamp, date, time, tanggal_jam, waktu</li>
                <li><strong>Kolom Device (opsional):</strong> device, device_id, device_name, mesin, terminal</li>
                <li><strong>Baris pertama:</strong> harus berisi header (nama kolom)</li>
            </ul>
            <hr>
            <p class="mb-0"><strong>Format tanggal yang didukung:</strong></p>
            <small>YYYY-MM-DD HH:MM:SS, DD-MM-YYYY HH:MM:SS, DD/MM/YYYY HH:MM:SS, YYYY/MM/DD HH:MM:SS</small>
        </div>

        <button type="submit" class="btn btn-success">Upload</button>
    </form>
</div>
@endsection
