<?php

namespace App\Http\Controllers;

use App\Models\OvertimePermit;
use App\Models\Employee;
use Illuminate\Http\Request;

class OvertimePermitController extends Controller
{
    /**
     * Display overtime permits for an employee
     */
    public function index(Request $request)
    {
        $query = OvertimePermit::with('employee');

        if ($request->has('employee_id') && !empty($request->get('employee_id'))) {
            $query->where('employee_id', $request->get('employee_id'));
        }

        if ($request->has('date') && !empty($request->get('date'))) {
            $query->whereDate('date', $request->get('date'));
        }

        $overtimePermits = $query->paginate(15);
        $employees = Employee::all();

        return view('admin.overtime-permit.index', compact('overtimePermits', 'employees'));
    }

    /**
     * Show form to create new overtime permit
     */
    public function create()
    {
        $employees = Employee::all();
        return view('admin.overtime-permit.create', compact('employees'));
    }

    /**
     * Store new overtime permit
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'overtime_end_time' => 'required|date_format:H:i',
            'reason' => 'nullable|string',
        ]);

        OvertimePermit::create($validated);

        return redirect()->route('overtime-permit.index')
            ->with('success', 'Overtime permit created successfully');
    }

    /**
     * Show overtime permit details
     */
    public function show($id)
    {
        $overtimePermit = OvertimePermit::with('employee')->findOrFail($id);
        return view('admin.overtime-permit.show', compact('overtimePermit'));
    }

    /**
     * Show form to edit overtime permit
     */
    public function edit($id)
    {
        $overtimePermit = OvertimePermit::findOrFail($id);
        $employees = Employee::all();
        return view('admin.overtime-permit.edit', compact('overtimePermit', 'employees'));
    }

    /**
     * Update overtime permit
     */
    public function update(Request $request, $id)
    {
        $overtimePermit = OvertimePermit::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'overtime_end_time' => 'required|date_format:H:i',
            'reason' => 'nullable|string',
        ]);

        $overtimePermit->update($validated);

        return redirect()->route('overtime-permit.index')
            ->with('success', 'Overtime permit updated successfully');
    }

    /**
     * Approve overtime permit
     */
    public function approve($id)
    {
        $overtimePermit = OvertimePermit::findOrFail($id);
        $overtimePermit->update([
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Overtime permit approved');
    }

    /**
     * Reject overtime permit
     */
    public function reject($id)
    {
        $overtimePermit = OvertimePermit::findOrFail($id);
        $overtimePermit->update([
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return back()->with('success', 'Overtime permit approval reset');
    }

    /**
     * Delete overtime permit
     */
    public function destroy($id)
    {
        $overtimePermit = OvertimePermit::findOrFail($id);
        $overtimePermit->delete();

        return back()->with('success', 'Overtime permit deleted');
    }
}
