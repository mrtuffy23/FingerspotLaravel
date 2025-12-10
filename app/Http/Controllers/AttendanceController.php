<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('employee')->paginate(15);
        return view('admin.attendance.index', compact('attendances'));
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
            'first_in' => 'nullable|datetime',
            'last_out' => 'nullable|datetime',
            'work_hours' => 'nullable|numeric',
            'compensated_hours' => 'nullable|numeric',
            'status' => 'required|in:present,absent,late,sick,on_leave,early_leave,accident,holiday,permission,out_permission',
            'point_delta' => 'nullable|integer',
            'note' => 'nullable|string',
        ]);

        Attendance::create($validated);

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
            'first_in' => 'nullable|datetime',
            'last_out' => 'nullable|datetime',
            'work_hours' => 'nullable|numeric',
            'compensated_hours' => 'nullable|numeric',
            'status' => 'required|in:present,absent,late,sick,on_leave,early_leave,accident,holiday,permission,out_permission',
            'point_delta' => 'nullable|integer',
            'note' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully');
    }
}
