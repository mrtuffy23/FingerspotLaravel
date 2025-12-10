<?php
namespace App\Services;
use App\Models\PayrollPeriod;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
class PayrollService {
    public function generateForPeriod(PayrollPeriod $period) {
        $employees = Employee::with('position')->get();
        foreach ($employees as $emp) {
            $att = Attendance::where('employee_id',$emp->id)
                ->whereBetween('date', [$period->start_date, $period->end_date])
                ->get();
            $totalActual = $att->sum('work_hours');
            $totalComp = $att->sum('compensated_hours');
            $total = round($totalActual + $totalComp,2);
            $umk = $emp->umk ?: config('company.umk', 0);
            $rateBase = $umk / 160;
            $rateAllow = ($emp->position->allowance ?? 0) / 160;
            $baseSalary = round($rateBase * $total,2);
            $allowAmount = round($rateAllow * $total,2);
            $totalSalary = $baseSalary + $allowAmount;
            Payroll::updateOrCreate([
                'employee_id' => $emp->id,
                'payroll_period_id' => $period->id
            ],[
                'total_actual_hours' => $totalActual,
                'total_compensated_hours' => $totalComp,
                'total_hours' => $total,
                'rate_base' => $rateBase,
                'rate_allowance' => $rateAllow,
                'base_salary' => $baseSalary,
                'allowance_amount' => $allowAmount,
                'total_salary' => $totalSalary
            ]);
        }
    }
}
