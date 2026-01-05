<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeduction extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'name', 'code', 'type', 'amount', 'start_date', 'end_date', 'notes'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Check if deduction is active on a given date
     */
    public function isActiveOn($date)
    {
        $date = \Carbon\Carbon::parse($date);
        
        $isAfterStart = is_null($this->start_date) || $date->gte($this->start_date);
        $isBeforeEnd = is_null($this->end_date) || $date->lte($this->end_date);
        
        return $isAfterStart && $isBeforeEnd;
    }
}