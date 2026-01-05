<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDeduction;
use Illuminate\Http\Request;

class EmployeeDeductionController extends Controller
{
    /**
     * Display a listing of deductions for an employee
     */
    public function index(Employee $employee)
    {
        $deductions = $employee->deductions()->latest()->paginate(10);
        return view('admin.employee-deductions.index', compact('employee', 'deductions'));
    }

    /**
     * Show the form for creating a new deduction
     */
    public function create(Employee $employee)
    {
        return view('admin.employee-deductions.create', compact('employee'));
    }

    /**
     * Store a newly created deduction in storage
     */
    public function store(Employee $employee, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'type' => 'required|in:fixed,variable',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        $validated['employee_id'] = $employee->id;

        EmployeeDeduction::create($validated);

        return redirect()->route('employees.deductions.index', $employee)
            ->with('success', 'Potongan gaji berhasil ditambahkan');
    }

    /**
     * Show the form for editing a deduction
     */
    public function edit(Employee $employee, EmployeeDeduction $deduction)
    {
        if ($deduction->employee_id !== $employee->id) {
            abort(403);
        }

        return view('admin.employee-deductions.edit', compact('employee', 'deduction'));
    }

    /**
     * Update the deduction in storage
     */
    public function update(Employee $employee, EmployeeDeduction $deduction, Request $request)
    {
        if ($deduction->employee_id !== $employee->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'type' => 'required|in:fixed,variable',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        $deduction->update($validated);

        return redirect()->route('employees.deductions.index', $employee)
            ->with('success', 'Potongan gaji berhasil diperbarui');
    }

    /**
     * Remove a deduction from storage
     */
    public function destroy(Employee $employee, EmployeeDeduction $deduction)
    {
        if ($deduction->employee_id !== $employee->id) {
            abort(403);
        }

        $deduction->delete();

        return redirect()->route('employees.deductions.index', $employee)
            ->with('success', 'Potongan gaji berhasil dihapus');
    }
}
