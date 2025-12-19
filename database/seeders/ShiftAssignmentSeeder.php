<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use Carbon\Carbon;

class ShiftAssignmentSeeder extends Seeder
{
    /**
     * Assign shifts to employees: STANDARD for monthly, SHIFT_1 for daily
     */
    public function run(): void
    {
        $standard = Shift::where('code', 'STANDARD')->first();
        $shift1 = Shift::where('code', 'SHIFT_1')->first();

        if (!$standard || !$shift1) {
            $this->command->warn('Shifts not found. Run ShiftSeeder first.');
            return;
        }

        $start = Carbon::create(2025, 11, 1);

        $employees = Employee::all();
        foreach ($employees as $emp) {
            $shift = $emp->employment_type === 'monthly' ? $standard : $shift1;

            ShiftAssignment::updateOrCreate(
                [
                    'employee_id' => $emp->id,
                    'shift_id' => $shift->id,
                    'start_date' => $start->toDateString(),
                ],
                [
                    'end_date' => null,
                ]
            );
        }

        $this->command->info('✅ Shift assignments created: STANDARD for monthly, SHIFT_1 for daily.');
    }
}
