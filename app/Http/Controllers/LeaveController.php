<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('employee')->paginate(15);
        return view('admin.leave.index', compact('leaves'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('admin.leave.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:izin,sakit,sakit_sabtu,kecelakaan,cuti,izin_keluar,libur',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $duration = $endDate->diffInDays($startDate) + 1;

        Leave::create(array_merge($validated, [
            'duration' => $duration,
        ]));

        return redirect()->route('leave.index')->with('success', 'Pengajuan cuti berhasil dibuat');
    }

    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['approved_at' => now()]);

        return redirect()->route('leave.index')->with('success', 'Cuti berhasil disetujui');
    }

    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['approved_at' => null]);

        return redirect()->route('leave.index')->with('success', 'Cuti berhasil ditolak');
    }
}
