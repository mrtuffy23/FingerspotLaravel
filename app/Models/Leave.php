<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'type', 'start_date', 'end_date', 'duration', 'approved_by', 'approved_at', 'reason'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Get status based on approved_at
    public function getStatusAttribute()
    {
        if ($this->approved_at === null) {
            return 'pending';
        }
        return 'approved';
    }

    // Get leave_type alias for type
    public function getLeaveTypeAttribute()
    {
        return $this->type;
    }
}
