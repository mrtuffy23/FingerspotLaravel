<?php
namespace App\Services;
use App\Models\PayrollPeriod;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\EmployeeDeduction;
use Carbon\Carbon;

class PayrollService {
    public function generateForPeriod(PayrollPeriod $period) {
        $employees = Employee::with([
            'classification.fixedAllowances', 
            'classification.variableAllowances',
            'classification.fixedDeductions',
            'classification.variableDeductions'
        ])->get();
        foreach ($employees as $emp) {
            if (!$emp->classification_id) {
                continue;
            }

            $att = Attendance::where('employee_id',$emp->id)
                ->whereBetween('date', [$period->start_date, $period->end_date])
                ->get();
            
            $totalActual = $att->sum('work_hours');
            $totalComp = $att->sum(function($record) {
                return $record->calculateTotalCompensation();
            });
            $total = round($totalActual + $totalComp, 2);
            
            $umk = $emp->umk ?: config('company.umk', 0);
            $rateBase = $umk / 160;
            $baseSalary = round($rateBase * $total, 2);
            
            // Calculate fixed allowances
            $totalFixedAllowance = 0;
            $fixedAllowanceDetails = [];
            foreach ($emp->classification->fixedAllowances as $allowance) {
                $amount = $allowance->amount;
                $totalFixedAllowance += $amount;
                $fixedAllowanceDetails[] = [
                    'type' => 'ALLOWANCE',
                    'category' => 'FIXED',
                    'name' => $allowance->name,
                    'amount' => $amount
                ];
            }
            
            // Calculate variable allowances (per working day)
            $workingDays = $att->count();
            $totalVariableAllowance = 0;
            $variableAllowanceDetails = [];
            foreach ($emp->classification->variableAllowances as $allowance) {
                $amount = round($allowance->amount_per_day * $workingDays, 2);
                $totalVariableAllowance += $amount;
                $variableAllowanceDetails[] = [
                    'type' => 'ALLOWANCE',
                    'category' => 'VARIABLE',
                    'name' => $allowance->name,
                    'amount' => $amount
                ];
            }
            
            // Calculate fixed deductions
            $totalFixedDeduction = 0;
            $fixedDeductionDetails = [];
            foreach ($emp->classification->fixedDeductions as $deduction) {
                $amount = $deduction->amount;
                $totalFixedDeduction += $amount;
                $fixedDeductionDetails[] = [
                    'type' => 'DEDUCTION',
                    'category' => 'FIXED',
                    'name' => $deduction->name,
                    'amount' => $amount
                ];
            }
            
            // Calculate variable deductions (per working day)
            $totalVariableDeduction = 0;
            $variableDeductionDetails = [];
            foreach ($emp->classification->variableDeductions as $deduction) {
                $amount = round($deduction->amount_per_day * $workingDays, 2);
                $totalVariableDeduction += $amount;
                $variableDeductionDetails[] = [
                    'type' => 'DEDUCTION',
                    'category' => 'VARIABLE',
                    'name' => $deduction->name,
                    'amount' => $amount
                ];
            }

            // Add employee-specific deductions (EmployeeDeduction)
            $employeeDeductions = EmployeeDeduction::where('employee_id', $emp->id)->get();
            $employeeFixedDeductionDetails = [];
            $employeeVariableDeductionDetails = [];
            
            $periodStart = Carbon::parse($period->start_date);
            $periodEnd = Carbon::parse($period->end_date);

            foreach ($employeeDeductions as $empDeduction) {
                // Check if deduction is active during this period
                if ($empDeduction->isActiveOn($periodStart) || $empDeduction->isActiveOn($periodEnd)) {
                    if ($empDeduction->type === 'fixed') {
                        $amount = $empDeduction->amount;
                        $totalFixedDeduction += $amount;
                        $employeeFixedDeductionDetails[] = [
                            'type' => 'DEDUCTION',
                            'category' => 'FIXED',
                            'name' => $empDeduction->name . ' (Pribadi)',
                            'amount' => $amount
                        ];
                    } else {
                        $amount = round($empDeduction->amount * $workingDays, 2);
                        $totalVariableDeduction += $amount;
                        $employeeVariableDeductionDetails[] = [
                            'type' => 'DEDUCTION',
                            'category' => 'VARIABLE',
                            'name' => $empDeduction->name . ' (Pribadi)',
                            'amount' => $amount
                        ];
                    }
                }
            }
            
            $totalSalary = $baseSalary + $totalFixedAllowance + $totalVariableAllowance;
            $totalDeduction = $totalFixedDeduction + $totalVariableDeduction;
            $netSalary = $totalSalary - $totalDeduction;
            
            // Create or update payroll record
            $payroll = Payroll::updateOrCreate([
                'employee_id' => $emp->id,
                'payroll_period_id' => $period->id
            ],[
                'total_actual_hours' => $totalActual,
                'total_compensated_hours' => $totalComp,
                'total_hours' => $total,
                'rate_base' => $rateBase,
                'base_salary' => $baseSalary,
                'total_fixed_allowance' => $totalFixedAllowance,
                'total_variable_allowance' => $totalVariableAllowance,
                'total_salary' => $totalSalary,
                'total_fixed_deduction' => $totalFixedDeduction,
                'total_variable_deduction' => $totalVariableDeduction,
                'total_deduction' => $totalDeduction,
                'net_salary' => $netSalary
            ]);
            
            // Delete old details and create new ones
            $payroll->payrollDetails()->delete();
            $allDetails = array_merge(
                $fixedAllowanceDetails, 
                $variableAllowanceDetails,
                $fixedDeductionDetails,
                $variableDeductionDetails,
                $employeeFixedDeductionDetails,
                $employeeVariableDeductionDetails
            );
            foreach ($allDetails as $detail) {
                PayrollDetail::create(array_merge(['payroll_id' => $payroll->id], $detail));
            }
        }
    }
}
