<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDivision extends Model
{
    protected $table = 'subdivisions';
    
    protected $fillable = ['nama', 'code', 'description'];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'subdivision_id');
    }
}
