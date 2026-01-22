@extends('layouts.admin')
@section('content')
<div class="container">
    <h3>Proses Absensi dari Scan Data</h3>

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

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Pilih Periode Absensi</h5>

            <form method="POST" action="{{ route('absen.process.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai:</label>
                            <input type="date" name="start_date" id="start_date" 
                                   class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">Tanggal Akhir:</label>
                            <input type="date" name="end_date" id="end_date" 
                                   class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-cogs"></i> Proses Absensi
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <hr>

            <h5>Quick Process</h5>
            <form method="POST" action="{{ route('absen.process.today') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-info">
                    <i class="fas fa-bolt"></i> Proses Hari Ini
                </button>
            </form>

            <div class="alert alert-info mt-3">
                <strong>Informasi:</strong>
                <ul class="mb-0 mt-2">
                    <li>Proses ini akan mengubah <strong>scan data mentah</strong> (AttendanceEvent) menjadi <strong>absensi</strong> (Attendance)</li>
                    <li>Setiap karyawan per hari akan menjadi 1 record absensi</li>
                    <li>Scan pertama = Check-in, Scan terakhir = Check-out</li>
                    <li>Sistem akan menghitung jam kerja otomatis</li>
                    <li>Status (Present/Late/Absent) ditentukan berdasarkan jam masuk dan durasi kerja</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Alur Proses</h5>
            <ol>
                <li><strong>Upload File Absensi</strong> → [Absen > Import] → Hasilnya: Scan data mentah di tabel attendance_events</li>
                <li><strong>Proses Scan Data</strong> → [Absen > Proses] → Hasilnya: Absensi per karyawan per hari di tabel attendances</li>
                <li><strong>Lihat Absensi</strong> → [Kepegawaian > Absensi] → Lihat & edit absensi yang sudah diproses</li>
                <li><strong>Generate Payroll</strong> → [Penggajian > Create] → Buat payroll dengan data absensi</li>
            </ol>
        </div>
    </div>
</div>
@endsection
