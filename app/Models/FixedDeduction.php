<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedDeduction extends Model
{
    use HasFactory;

    protected $fillable = ['classification_id', 'name', 'code', 'amount'];

    protected $casts = [
        'amount' => 'float',
    ];

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }
}
