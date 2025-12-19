<?php

namespace App\Http\Controllers;

use App\Models\ShiftAssignment;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = ShiftAssignment::with(['employee.department', 'employee.position', 'shift']);

        // Filter by employee
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by shift
        if ($request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
        }

        // Filter by employment type
        if ($request->filled('employment_type')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('employment_type', $request->employment_type);
            });
        }

        // Filter active assignments (no end_date or end_date >= today)
        if ($request->get('active_only', false)) {
            $query->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', Carbon::today());
            });
        }

        $assignments = $query->orderBy('start_date', 'desc')->paginate(20);
        $employees = Employee::orderBy('name')->get();
        $shifts = Shift::orderBy('code')->get();

        return view('admin.shift-assignments.index', compact('assignments', 'employees', 'shifts'));
    }

    public function create()
    {
        $employees = Employee::orderBy('name')->get();
        $shifts = Shift::orderBy('code')->get();

        return view('admin.shift-assignments.create', compact('employees', 'shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Close any existing open assignments for this employee
        ShiftAssignment::where('employee_id', $validated['employee_id'])
            ->whereNull('end_date')
            ->update(['end_date' => Carbon::parse($validated['start_date'])->subDay()]);

        ShiftAssignment::create($validated);

        return redirect()->route('shift-assignments.index')
            ->with('success', 'Penugasan shift berhasil ditambahkan!');
    }

    public function show(ShiftAssignment $shiftAssignment)
    {
        $shiftAssignment->load(['employee.department', 'employee.position', 'shift']);
        return view('admin.shift-assignments.show', compact('shiftAssignment'));
    }

    public function edit(ShiftAssignment $shiftAssignment)
    {
        $employees = Employee::orderBy('name')->get();
        $shifts = Shift::orderBy('code')->get();

        return view('admin.shift-assignments.edit', compact('shiftAssignment', 'employees', 'shifts'));
    }

    public function update(Request $request, ShiftAssignment $shiftAssignment)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $shiftAssignment->update($validated);

        return redirect()->route('shift-assignments.index')
            ->with('success', 'Penugasan shift berhasil diperbarui!');
    }

    public function destroy(ShiftAssignment $shiftAssignment)
    {
        $shiftAssignment->delete();

        return redirect()->route('shift-assignments.index')
            ->with('success', 'Penugasan shift berhasil dihapus!');
    }
}
