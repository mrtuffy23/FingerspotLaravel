<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Employee extends Model {
    use HasFactory;
    protected $fillable = ['pin','nik','name','birth_place','birth_date','status','position_id','department_id','join_year','umk'];
    public function position() { return $this->belongsTo(Position::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function leaves() { return $this->hasMany(Leave::class); }
    public function payrolls() { return $this->hasMany(Payroll::class); }
}
