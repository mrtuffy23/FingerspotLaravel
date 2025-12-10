<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkCalendar extends Model
{
    protected $fillable = ['date','description','type'];
    protected $dates = ['date'];
}
