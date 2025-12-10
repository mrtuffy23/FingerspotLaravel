<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'type', 'points', 'reason', 'reference_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
