<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'divisions';
    
    protected $fillable = ['nama', 'code', 'description'];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'division_id');
    }
}
