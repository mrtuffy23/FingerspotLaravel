<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AttendanceImportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\WorkCalendarController;
use App\Http\Controllers\HolidayCompensationController;
use App\Http\Controllers\OvertimePermitController;
use App\Http\Controllers\ShiftAssignmentController;


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
Route::get('attendance/check-leave', [AttendanceController::class, 'checkLeave'])->name('attendance.check-leave');

// Overtime Permits
Route::resource('overtime-permit', OvertimePermitController::class);
Route::post('overtime-permit/{overtimePermit}/approve', [OvertimePermitController::class, 'approve'])->name('overtime-permit.approve');
Route::post('overtime-permit/{overtimePermit}/reject', [OvertimePermitController::class, 'reject'])->name('overtime-permit.reject');

// Holiday Compensation
Route::get('holiday-compensation/report', [HolidayCompensationController::class, 'holidayAttendanceReport'])->name('holiday-compensation.report');
Route::get('holiday-compensation/holidays', [HolidayCompensationController::class, 'holidayList'])->name('holiday-compensation.holidays');
Route::post('holiday-compensation/process', [HolidayCompensationController::class, 'processCompensation'])->name('holiday-compensation.process');
Route::get('holiday-compensation/employee/{employeeId}', [HolidayCompensationController::class, 'employeeCompensationDetail'])->name('holiday-compensation.employee-detail');
Route::get('holiday-compensation/export', [HolidayCompensationController::class, 'exportReport'])->name('holiday-compensation.export');

// Payroll
Route::resource('payroll', PayrollController::class);
Route::post('payroll/{payroll}/finalize', [PayrollController::class, 'finalize'])->name('payroll.finalize');
Route::get('payroll/{payroll}/print-slip', [PayrollController::class, 'printSlip'])->name('payroll.print-slip');

// Leave Management
Route::resource('leave', LeaveController::class);
Route::post('leave/{leave}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
Route::post('leave/{leave}/reject', [LeaveController::class, 'reject'])->name('leave.reject');

// Work Calendar Management
Route::resource('work-calendars', WorkCalendarController::class);

// Shift Assignment Management
Route::resource('shift-assignments', ShiftAssignmentController::class);

