<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimePermit extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'date', 'overtime_end_time', 'reason', 'approved_by', 'approved_at'
    ];

    protected $casts = [
        'date' => 'date',
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

    /**
     * Check if overtime permit is approved
     */
    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }
}
