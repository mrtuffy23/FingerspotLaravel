<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceEvent extends Model
{
    use HasFactory;

    protected $fillable = ['employee_pin', 'event_time', 'device_id', 'raw_data', 'employee_id'];

    protected $casts = [
        'event_time' => 'datetime',
        'raw_data' => 'json',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
