# Laravel Payroll & Attendance System

A complete Laravel-based payroll and attendance management system with employee management, attendance tracking, leave management, and payroll generation capabilities.

## Features

✅ **Employee Management**
- Add, edit, and delete employee information
- Track employee positions and departments
- Manage UMK (minimum wage) and allowances

✅ **Attendance Tracking**
- Import attendance data from CSV/Fingerspot devices
- Manual attendance record management
- Track check-in/check-out times
- Calculate work hours automatically

✅ **Leave Management**
- Create and manage leave requests
- Support for multiple leave types (annual, sick, personal)
- Approve/reject leave requests
- Track leave duration

✅ **Payroll Processing**
- Generate payroll by period
- Calculate salary based on actual/compensated hours
- Apply base salary and allowances
- Export payroll reports

## System Requirements

- PHP 8.0+
- Laravel 9.0 or 10.0
- MySQL 5.7+
- Composer

## Installation Guide

### Step 1: Install Dependencies
```bash
composer install
```

### Step 2: Setup Environment
```bash
copy .env.example .env
php artisan key:generate
```

### Step 3: Configure Database
Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=payroll_attendance
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Run Migrations
```bash
php artisan migrate
```

### Step 5: Start Server
```bash
php artisan serve
```

Access: `http://localhost:8000`

## Project Structure

- **app/Models/** - Eloquent models
- **app/Http/Controllers/** - Route controllers
- **app/Services/** - Business logic
- **database/migrations/** - Schema definitions
- **resources/views/** - Blade templates
- **routes/web.php** - Route definitions

## Usage

### Employees
1. Go to **Employees** menu
2. Add, edit, or view employee records
3. Assign positions and departments

### Attendance
1. Use **Import Attendance** to upload CSV
2. Or manually create attendance records
3. Track check-in/check-out times

### Payroll
1. Create payroll period with dates
2. System auto-calculates based on hours
3. View and finalize payroll

### Leave
1. Create leave requests
2. Approve/reject requests
3. Track leave balance

## Database Schema

**Departments** - Company departments
**Positions** - Job positions with allowances
**Employees** - Employee master data
**Shifts** - Work shift definitions
**ShiftAssignments** - Employee shift schedules
**AttendanceEvents** - Raw device events
**Attendances** - Processed attendance records
**Leaves** - Leave requests
**PayrollPeriods** - Payroll period definitions
**Payrolls** - Calculated payroll data
**PointTransactions** - Discipline point tracking

## Commands

```bash
# Import attendance from CSV
php artisan fingerspot:import path/to/file.csv

# Cache management
php artisan cache:clear
php artisan config:clear

# Database
php artisan migrate:refresh
```

## Configuration

Edit `config/company.php` for:
- Default UMK (minimum wage)
- Base allowance amounts

## Payroll Calculation

```
Total Hours = Actual Hours + Compensated Hours
Rate Base = UMK / 160
Base Salary = Rate Base × Total Hours
Allowance = Position Allowance / 160 × Total Hours
Total = Base Salary + Allowance
```

## Troubleshooting

- Database errors: Check `.env` credentials
- Missing tables: Run `php artisan migrate`
- Cache issues: Run `php artisan optimize:clear`

---

**Version:** 1.0.0
**Updated:** December 6, 2025
