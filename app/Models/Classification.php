<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'level'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function fixedAllowances()
    {
        return $this->hasMany(FixedAllowance::class);
    }

    public function variableAllowances()
    {
        return $this->hasMany(VariableAllowance::class);
    }

    public function fixedDeductions()
    {
        return $this->hasMany(FixedDeduction::class);
    }

    public function variableDeductions()
    {
        return $this->hasMany(VariableDeduction::class);
    }
}
