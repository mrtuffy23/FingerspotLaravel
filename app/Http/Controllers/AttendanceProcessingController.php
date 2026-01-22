<?php

namespace App\Http\Controllers;

use App\Services\AttendanceProcessingService;
use Illuminate\Http\Request;

class AttendanceProcessingController extends Controller
{
    protected $processingService;

    public function __construct(AttendanceProcessingService $processingService)
    {
        $this->processingService = $processingService;
    }

    /**
     * Show form to process attendance
     */
    public function index()
    {
        return view('admin.absen.process');
    }

    /**
     * Process attendance events into records
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        try {
            $result = $this->processingService->processAttendanceEvents(
                $validated['start_date'],
                $validated['end_date']
            );

            if ($result['success']) {
                return redirect()->route('attendance.index')
                    ->with('success', $result['message']);
            } else {
                return back()->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error processing attendance: ' . $e->getMessage());
        }
    }

    /**
     * Quick process for today
     */
    public function processToday()
    {
        try {
            $result = $this->processingService->processAttendanceEvents();

            return redirect()->route('attendance.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            return back()->with('error', 'Error processing attendance: ' . $e->getMessage());
        }
    }
}
