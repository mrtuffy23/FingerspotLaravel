<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Employee extends Model {
    use HasFactory;
    protected $fillable = [
        'pin', 'nik', 'name', 'birth_place', 'birth_date',
        'employment_type', 'department_id', 'division_id', 'subdivision_id',
        'position_id', 'status', 'join_year', 'umk', 'photo', 'classification_id',
        'current_points', 'initial_points', 'current_payroll_period_id'
    ];
    protected $casts = ['birth_date' => 'date'];
    public function position() { return $this->belongsTo(Position::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function classification() { return $this->belongsTo(Classification::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function leaves() { return $this->hasMany(Leave::class); }
    public function payrolls() { return $this->hasMany(Payroll::class); }
    public function shiftAssignments() { return $this->hasMany(ShiftAssignment::class); }
    public function shifts() { return $this->belongsToMany(Shift::class, 'shift_assignments'); }
    public function deductions() { return $this->hasMany(EmployeeDeduction::class); }
    public function division() { return $this->belongsTo(Division::class); }
    public function subDivision() { return $this->belongsTo(SubDivision::class, 'subdivision_id'); }
    public function pointTransactions() { return $this->hasMany(PointTransaction::class); }
    public function currentPayrollPeriod() { return $this->belongsTo(PayrollPeriod::class, 'current_payroll_period_id'); }
}
