<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Department;
use App\Models\Classification;
use App\Models\Division;
use App\Models\SubDivision;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Employee::with(['position', 'department', 'division', 'subDivision']);
        
        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('nik', 'LIKE', '%' . $search . '%')
                  ->orWhere('pin', 'LIKE', '%' . $search . '%');
        }
        
        $employees = $query->paginate(15)->appends(request()->query());
        return view('admin.karyawan.index', compact('employees', 'search'));
    }

    public function create()
    {
        $departments = Department::all();
        $divisions = Division::all();
        $positions = Position::all();
        $subDivisions = SubDivision::all();
        $classifications = Classification::all();

        return view('admin.karyawan.create', compact('departments', 'divisions', 'positions', 'subDivisions','classifications'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pin' => 'required|unique:employees',
            'nik' => 'required|unique:employees',
            'employee_name' => 'required|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'employment_type' => 'required|in:monthly,daily',
            'department_id' => 'required|exists:departments,id',
            'division_id' => 'required|exists:divisions,id',
            'subdivision_id' => 'nullable|exists:subdivisions,id',
            'position_id' => 'required|exists:positions,id',
            'classification_id' => 'required|exists:classifications,id',
            'status' => 'required|in:aktif,kontrak,nonaktif,resign',
            'join_year' => 'nullable|numeric|min:1990|max:' . date('Y'),
            'umk' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        ]);

        // Map employee_name to name column
        if (isset($data['employee_name'])) {
            $data['name'] = $data['employee_name'];
            unset($data['employee_name']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($data);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil disimpan.');
    }

    public function show($id)
    {
        $employee = Employee::with([
            'department',
            'division',
            'subdivision',
            'position',
            'classification',
            'attendances',
            'payrolls',
            'leaves'
        ])->findOrFail($id);

        return view('admin.karyawan.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Department::all();
        $divisions = Division::all();
        $positions = Position::all();
        $subDivisions = SubDivision::all();
        $classifications = Classification::all(); // Add this line
        
        return view('admin.karyawan.edit', compact('employee', 'departments', 'divisions', 'positions', 'subDivisions', 'classifications'));
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
            'employment_type' => 'required|in:monthly,daily',
            'department_id' => 'required|exists:departments,id',
            'division_id' => 'required|exists:divisions,id',
            'position_id' => 'required|exists:positions,id',
            'subdivision_id' => 'nullable|exists:subdivisions,id',
            'classification_id' => 'required|exists:classifications,id',
            'status' => 'required|in:aktif,kontrak,nonaktif,resign',
            'join_year' => 'nullable|integer',
            'umk' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo) {
                \Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
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