<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Department;
use App\Models\Classification;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['position', 'department', 'classification'])->paginate(15);
        return view('admin.karyawan.index', compact('employees'));
    }

    public function create()
    {
        $positions = Position::all();
        $departments = Department::all();
        $classifications = Classification::all();
        return view('admin.karyawan.create', compact('positions', 'departments', 'classifications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|unique:employees',
            'nik' => 'required|unique:employees',
            'name' => 'required|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'status' => 'required|in:aktif,nonaktif,kontrak,resign',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'classification_id' => 'required|exists:classifications,id',
            'join_year' => 'nullable|integer',
            'umk' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'employment_type' => 'required|in:monthly,daily',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photoFile->getClientOriginalExtension();
            $photoFile->move(public_path('uploads/employees'), $photoName);
            $validated['photo'] = 'uploads/employees/' . $photoName;
        }

        Employee::create($validated);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function show($id)
    {
        $employee = Employee::with(['position', 'department', 'classification', 'attendances', 'payrolls'])->findOrFail($id);
        return view('admin.karyawan.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $positions = Position::all();
        $departments = Department::all();
        $classifications = Classification::all();
        return view('admin.karyawan.edit', compact('employee', 'positions', 'departments', 'classifications'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'pin' => 'required|unique:employees,pin,' . $id,
            'nik' => 'required|unique:employees,nik,' . $id,
            'name' => 'required|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'status' => 'required|in:aktif,nonaktif,kontrak,resign',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'classification_id' => 'required|exists:classifications,id',
            'join_year' => 'nullable|integer',
            'umk' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'employment_type' => 'required|in:monthly,daily',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo && file_exists(public_path($employee->photo))) {
                unlink(public_path($employee->photo));
            }
            $photoFile = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photoFile->getClientOriginalExtension();
            $photoFile->move(public_path('uploads/employees'), $photoName);
            $validated['photo'] = 'uploads/employees/' . $photoName;
        }

        $employee->update($validated);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
