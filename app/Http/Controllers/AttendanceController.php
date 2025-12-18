<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Services\LeaveCompensationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee');

        // Filter by date if provided
        if ($request->has('date') && !empty($request->get('date'))) {
            $query->whereDate('date', $request->get('date'));
        }

        // Filter by date range if both start_date and end_date provided
        if ($request->has('start_date') && !empty($request->get('start_date')) && 
            $request->has('end_date') && !empty($request->get('end_date'))) {
            $query->whereBetween('date', [$request->get('start_date'), $request->get('end_date')]);
        }

        // Filter by employee if provided
        if ($request->has('employee_id') && !empty($request->get('employee_id'))) {
            $query->where('employee_id', $request->get('employee_id'));
        }

        // Filter by status if provided
        if ($request->has('status') && !empty($request->get('status'))) {
            $query->where('status', $request->get('status'));
        }

        $attendances = $query->paginate(15);
        $employees = Employee::all();

        return view('admin.attendance.index', compact('attendances', 'employees'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('admin.attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'first_in' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}(:\d{2})?$/',
            'last_out' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}(:\d{2})?$/',
            'work_hours' => 'nullable|numeric',
            'compensated_hours' => 'nullable|numeric',
            'status' => 'required|in:present,absent,late,sick,on_leave,early_leave,accident,holiday,permission,out_permission',
            'point_delta' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::create($validated);

        // Calculate work hours automatically for monthly employees
        $employee = $attendance->employee;
        if ($employee->employment_type === 'monthly' && $attendance->first_in && $attendance->last_out) {
            $workHours = $attendance->calculateMonthlyWorkHours();
            $attendance->update(['work_hours' => round($workHours, 2)]);
        }
        // Calculate work hours automatically for shift/daily employees
        elseif ($employee->employment_type !== 'monthly' && $attendance->first_in && $attendance->last_out) {
            $workHours = $attendance->calculateShiftWorkHours();
            $attendance->update(['work_hours' => round($workHours, 2)]);
        }

        // Apply leave compensation if applicable
        $attendance = $this->applyLeaveCompensation($attendance);

        return redirect()->route('attendance.index')->with('success', 'Attendance created successfully');
    }

    public function show($id)
    {
        $attendance = Attendance::with('employee')->findOrFail($id);
        return view('admin.attendance.show', compact('attendance'));
    }

    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $employees = Employee::all();
        return view('admin.attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'first_in' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}(:\d{2})?$/',
            'last_out' => 'nullable|regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}(:\d{2})?$/',
            'work_hours' => 'nullable|numeric',
            'compensated_hours' => 'nullable|numeric',
            'status' => 'required|in:present,absent,late,sick,on_leave,early_leave,accident,holiday,permission,out_permission',
            'point_delta' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        // Set approved_by dan approved_at jika status sudah confirmed
        if (!$attendance->approved_at) {
            $validated['approved_by'] = auth()->id();
            $validated['approved_at'] = now();
        }

        $attendance->update($validated);

        // Calculate work hours automatically for monthly employees
        $employee = $attendance->employee;
        if ($employee->employment_type === 'monthly' && $attendance->first_in && $attendance->last_out) {
            $workHours = $attendance->calculateMonthlyWorkHours();
            $attendance->update(['work_hours' => round($workHours, 2)]);
        }
        // Calculate work hours automatically for shift/daily employees
        elseif ($employee->employment_type !== 'monthly' && $attendance->first_in && $attendance->last_out) {
            $workHours = $attendance->calculateShiftWorkHours();
            $attendance->update(['work_hours' => round($workHours, 2)]);
        }

        // Apply leave compensation if applicable
        $attendance = $this->applyLeaveCompensation($attendance);

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully');
    }

    public function approve($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update([
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Attendance approved');
    }

    public function reject($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update([
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return back()->with('success', 'Attendance approval reset');
    }

    /**
     * Apply leave compensation if employee is on leave
     */
    private function applyLeaveCompensation(Attendance $attendance): Attendance
    {
        $leave = LeaveCompensationService::getLeaveForDate(
            $attendance->employee_id, 
            Carbon::parse($attendance->date)
        );

        if ($leave) {
            $leaveCompensation = LeaveCompensationService::calculateLeaveCompensation(
                $leave->type, 
                Carbon::parse($attendance->date)
            );

            $attendance->update([
                'compensated_hours' => $leaveCompensation,
                'notes' => ($attendance->notes ? $attendance->notes . ' | ' : '') . 
                           LeaveCompensationService::getCompensationDescription($leave->type, Carbon::parse($attendance->date))
            ]);
        }

        return $attendance;
    }

    /**
     * Check if employee has leave on specific date (AJAX)
     */
    public function checkLeave(Request $request)
    {
        $employeeId = $request->get('employee_id');
        $date = $request->get('date');

        if (!$employeeId || !$date) {
            return response()->json(['has_leave' => false]);
        }

        $leave = LeaveCompensationService::getLeaveForDate($employeeId, Carbon::parse($date));

        if (!$leave) {
            return response()->json(['has_leave' => false]);
        }

        $compensation = LeaveCompensationService::calculateLeaveCompensation($leave->type, Carbon::parse($date));

        return response()->json([
            'has_leave' => true,
            'leave_type' => ucfirst(str_replace('_', ' ', $leave->type)),
            'reason' => $leave->reason ?? '-',
            'compensation' => $compensation,
        ]);
    }
}
