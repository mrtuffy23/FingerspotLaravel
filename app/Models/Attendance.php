<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'date', 'first_in', 'last_out', 
        'work_hours', 'compensated_hours', 'status', 'point_delta', 'note'
    ];

    protected $casts = [
        'date' => 'date',
        'first_in' => 'datetime',
        'last_out' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
