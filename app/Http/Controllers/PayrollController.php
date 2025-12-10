<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollPeriod;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index()
    {
        $periods = PayrollPeriod::with('payrolls')->paginate(10);
        return view('admin.payroll.index', compact('periods'));
    }

    public function create()
    {
        return view('admin.payroll.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string',
        ]);

        $period = PayrollPeriod::create(array_merge($validated, ['status' => 'draft']));
        $this->payrollService->generateForPeriod($period);

        return redirect()->route('payroll.show', $period)->with('success', 'Payroll period created and generated');
    }

    public function show($id)
    {
        $period = PayrollPeriod::with(['payrolls.employee'])->findOrFail($id);
        $payrolls = $period->payrolls()->paginate(15);
        return view('admin.payroll.show', compact('period', 'payrolls'));
    }

    public function finalize($id)
    {
        $period = PayrollPeriod::findOrFail($id);
        $period->update(['status' => 'finalized']);

        return redirect()->route('payroll.show', $period)->with('success', 'Payroll period finalized');
    }
}
