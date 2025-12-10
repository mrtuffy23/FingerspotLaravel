<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AttendanceImportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\WorkCalendarController;


// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Login Routes (temporary - no auth required)
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    return redirect('/dashboard');
})->name('login.store');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

// Main Routes (Auth middleware disabled for now)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Employee Management
Route::resource('karyawan', KaryawanController::class);

// Attendance
Route::resource('attendance', AttendanceController::class);
Route::get('absen/import', [AttendanceImportController::class, 'index'])->name('absen.import');
Route::post('absen/import', [AttendanceImportController::class, 'store'])->name('absen.import.store');

// Payroll
Route::resource('payroll', PayrollController::class);
Route::post('payroll/{payroll}/finalize', [PayrollController::class, 'finalize'])->name('payroll.finalize');

// Leave Management
Route::resource('leave', LeaveController::class);
Route::post('leave/{leave}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
Route::post('leave/{leave}/reject', [LeaveController::class, 'reject'])->name('leave.reject');

// Work Calendar Management
Route::resource('work-calendars', WorkCalendarController::class);

