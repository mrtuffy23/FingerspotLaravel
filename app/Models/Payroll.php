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
        'rate_allowance', 'base_salary', 'allowance_amount', 'total_salary'
    ];

    protected $casts = [
        'total_actual_hours' => 'float',
        'total_compensated_hours' => 'float',
        'total_hours' => 'float',
        'rate_base' => 'float',
        'rate_allowance' => 'float',
        'base_salary' => 'float',
        'allowance_amount' => 'float',
        'total_salary' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }
}
