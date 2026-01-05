<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    use HasFactory;

    protected $fillable = ['payroll_id', 'type', 'category', 'name', 'amount'];

    protected $casts = [
        'amount' => 'float',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    /**
     * Scope untuk filter allowances
     */
    public function scopeAllowances($query)
    {
        return $query->where('type', 'ALLOWANCE');
    }

    /**
     * Scope untuk filter deductions
     */
    public function scopeDeductions($query)
    {
        return $query->where('type', 'DEDUCTION');
    }

    /**
     * Scope untuk filter fixed items
     */
    public function scopeFixed($query)
    {
        return $query->where('category', 'FIXED');
    }

    /**
     * Scope untuk filter variable items
     */
    public function scopeVariable($query)
    {
        return $query->where('category', 'VARIABLE');
    }
}
