<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'end_date', 'name', 'starting_points'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}
