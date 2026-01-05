<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariableAllowance extends Model
{
    use HasFactory;

    protected $fillable = ['classification_id', 'name', 'code', 'amount_per_day'];

    protected $casts = [
        'amount_per_day' => 'float',
    ];

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }
}
