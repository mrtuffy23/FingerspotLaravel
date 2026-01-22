<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $leave->update([
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // 🔄 AUTO-UPDATE ATTENDANCE UNTUK SEMUA HARI CUTI
        $startDate = Carbon::parse($leave->start_date);
        $endDate = Carbon::parse($leave->end_date);
        
        // Map leave type ke attendance status (sesuai config/discipline.php)
        $statusMap = [
            'izin' => 'izin',
            'sakit' => 'sakit',
            'sakit_sabtu' => 'sakit',
            'kecelakaan' => 'kecelakaan',
            'cuti' => 'cuti',
            'izin_keluar' => 'izin',
            'libur' => 'cuti',
        ];

        // Map leave type ke point delta (sesuai config/discipline.php)
        $pointDeltaMap = [
            'izin' => -20,
            'sakit' => -5,
            'sakit_sabtu' => -5,
            'kecelakaan' => -1,
            'cuti' => 0,
            'izin_keluar' => -5,
            'libur' => 0,
        ];
        
        $attendanceStatus = $statusMap[$leave->type] ?? 'cuti';
        $pointDelta = $pointDeltaMap[$leave->type] ?? 0;
        
        // Update attendance untuk tiap hari dalam range cuti
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Calculate leave compensation based on type
            $leaveCompensation = 0;
            if ($leave->type === 'sakit' && $date->isSaturday()) {
                $leaveCompensation = 5; // Sakit di Sabtu = 5 jam
            } elseif ($leave->type === 'sakit' || $leave->type === 'cuti' || $leave->type === 'kecelakaan') {
                $leaveCompensation = 7; // Sakit/Cuti/Kecelakaan = 7 jam
            }
            // izin dan izin_keluar tidak dapat compensation
            
            Attendance::updateOrCreate(
                [
                    'employee_id' => $leave->employee_id,
                    'date' => $date->toDateString(),
                ],
                [
                    'status' => $attendanceStatus,
                    'point_delta' => $pointDelta,
                    'work_hours' => 0,
                    'compensated_hours' => $leaveCompensation,
                    'notes' => "✅ Approved {$leave->type}: {$leave->reason}",
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]
            );
        }

        return redirect()->route('leave.index')->with('success', 'Cuti berhasil disetujui & Attendance sudah di-update otomatis untuk ' . $leave->duration . ' hari (Kompensasi: ' . ($leaveCompensation > 0 ? $leaveCompensation . ' jam' : 'tidak ada') . ')');
    }

    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update([
            'approved_at' => null,
            'approved_by' => null,
        ]);

        // 🔄 HAPUS/RESET ATTENDANCE YANG DI-CREATE SAAT APPROVE
        $startDate = Carbon::parse($leave->start_date);
        $endDate = Carbon::parse($leave->end_date);
        
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Cari attendance yang dibuat untuk cuti ini
            $attendance = Attendance::where('employee_id', $leave->employee_id)
                                    ->where('date', $date->toDateString())
                                    ->first();
            
            if ($attendance) {
                // Delete the attendance record (since leave is not approved)
                $attendance->delete();
            }
        }

        return redirect()->route('leave.index')->with('success', 'Cuti berhasil ditolak & Attendance sudah dihapus');
    }

    /**
     * Delete a leave request
     */
    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);

        // If leave is approved, delete related attendance records and reverse points
        if ($leave->approved_at) {
            $startDate = Carbon::parse($leave->start_date);
            $endDate = Carbon::parse($leave->end_date);
            
            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                // Cari attendance yang dibuat untuk cuti ini
                $attendance = Attendance::where('employee_id', $leave->employee_id)
                                        ->where('date', $date->toDateString())
                                        ->first();
                
                if ($attendance) {
                    // Delete the attendance record (observer akan automatically reverse points)
                    $attendance->delete();
                }
            }

            $deleteMessage = ' & Attendance sudah dihapus (poin sudah di-reverse otomatis)';
        } else {
            $deleteMessage = '';
        }

        // Delete the leave record
        $leave->delete();

        return redirect()->route('leave.index')->with('success', 'Pengajuan cuti berhasil dihapus' . $deleteMessage);
    }
}
