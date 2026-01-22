<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AttendanceImportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\WorkCalendarController;
use App\Http\Controllers\HolidayCompensationController;
use App\Http\Controllers\OvertimePermitController;
use App\Http\Controllers\ShiftAssignmentController;
use App\Http\Controllers\AllowanceConfigController;
use App\Http\Controllers\EmployeeDeductionController;
use App\Http\Controllers\AttendanceProcessingController;
use App\Http\Controllers\PointController;


// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Main Routes (Protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Employee Management
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('employees.deductions', EmployeeDeductionController::class);

    // Attendance
    Route::resource('attendance', AttendanceController::class);
    Route::post('attendance/bulk-delete', [AttendanceController::class, 'bulkDelete'])->name('attendance.bulk-delete');
    Route::get('absen/import', [AttendanceImportController::class, 'index'])->name('absen.import');
    Route::post('absen/import', [AttendanceImportController::class, 'store'])->name('absen.import.store');
    Route::get('absen/process', [AttendanceProcessingController::class, 'index'])->name('absen.process');
    Route::post('absen/process', [AttendanceProcessingController::class, 'process'])->name('absen.process.store');
    Route::post('absen/process-today', [AttendanceProcessingController::class, 'processToday'])->name('absen.process.today');
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

    // Payroll - CONSOLIDATED ROUTES
    Route::prefix('payroll')->name('payroll.')->group(function () {
        Route::get('/', [PayrollController::class, 'index'])->name('index');
        Route::get('create', [PayrollController::class, 'create'])->name('create');
        Route::post('/', [PayrollController::class, 'store'])->name('store');
        Route::get('{period}', [PayrollController::class, 'show'])->name('show');
        Route::post('{period}/finalize', [PayrollController::class, 'finalize'])->name('finalize');
        Route::delete('{period}', [PayrollController::class, 'destroy'])->name('destroy');
        Route::get('{period}/slip', [PayrollController::class, 'slip'])->name('slip');
    });

    // Allowance Configuration
    Route::get('allowance-config', [AllowanceConfigController::class, 'index'])->name('allowance.index');
    Route::get('allowance-config/{classification}/edit', [AllowanceConfigController::class, 'edit'])->name('allowance.edit');
    Route::put('allowance-config/fixed/{fixedAllowance}', [AllowanceConfigController::class, 'updateFixedAllowance'])->name('allowance.fixed.update');
    Route::put('allowance-config/variable/{variableAllowance}', [AllowanceConfigController::class, 'updateVariableAllowance'])->name('allowance.variable.update');
    Route::post('allowance-config/{classification}/batch-update', [AllowanceConfigController::class, 'batchUpdate'])->name('allowance.batch-update');

    // Leave Management
    Route::resource('leave', LeaveController::class);
    Route::post('leave/{leave}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
    Route::post('leave/{leave}/reject', [LeaveController::class, 'reject'])->name('leave.reject');

    // Work Calendar Management
    Route::resource('work-calendars', WorkCalendarController::class);

    // Shift Assignment Management
    Route::resource('shift-assignments', ShiftAssignmentController::class);

    // Point Management
    Route::prefix('points')->name('points.')->group(function () {
        Route::get('/', [PointController::class, 'dashboard'])->name('dashboard');
        Route::get('history', [PointController::class, 'history'])->name('history');
        Route::get('employee/{employeeId}', [PointController::class, 'employeePointDetail'])->name('employee-detail');
        Route::get('adjustment', [PointController::class, 'adjustmentForm'])->name('adjustment-form');
        Route::post('adjustment', [PointController::class, 'submitAdjustment'])->name('submit-adjustment');
        Route::post('initialize-period', [PointController::class, 'initializePeriod'])->name('initialize-period');
        Route::get('export', [PointController::class, 'exportReport'])->name('export-report');
        
        // API endpoints
        Route::get('api/employee/{employeeId}/balance', [PointController::class, 'getEmployeePoints'])->name('api.employee-points');
        Route::get('api/employee/{employeeId}/transactions', [PointController::class, 'getPointTransactions'])->name('api.transactions');
    });
});

// Admin Routes - Removed (no implementation needed for MVP)
// If needed in the future, implement proper admin panel with:
// - Admin controllers in app/Http/Controllers/Admin/
// - Admin views in resources/views/admin/
// - Proper middleware and authorization checks

