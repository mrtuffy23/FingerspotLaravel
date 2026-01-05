<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'payroll_period_id', 'total_actual_hours',
        'total_compensated_hours', 'total_hours', 'rate_base',
        'base_salary', 'total_fixed_allowance', 'total_variable_allowance', 'total_salary',
        'total_fixed_deduction', 'total_variable_deduction', 'total_deduction', 'net_salary'
    ];

    protected $casts = [
        'total_actual_hours' => 'float',
        'total_compensated_hours' => 'float',
        'total_hours' => 'float',
        'rate_base' => 'float',
        'base_salary' => 'float',
        'total_fixed_allowance' => 'float',
        'total_variable_allowance' => 'float',
        'total_salary' => 'float',
        'total_fixed_deduction' => 'float',
        'total_variable_deduction' => 'float',
        'total_deduction' => 'float',
        'net_salary' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function payrollDetails()
    {
        return $this->hasMany(PayrollDetail::class);
    }
}
