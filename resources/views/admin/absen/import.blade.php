@extends('layouts.admin')
@section('content')
<h3>Import Absensi Fingerspot</h3>
<form method="POST" enctype="multipart/form-data" action="{{ route('absen.import.store') }}">
    @csrf
    <input type="file" name="file" class="form-control mb-3" required>
    <button class="btn btn-success">Upload</button>
</form>
@endsection
