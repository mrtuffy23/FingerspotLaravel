<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\FixedAllowance;
use App\Models\VariableAllowance;
use Illuminate\Http\Request;

class AllowanceConfigController extends Controller
{
    // Show list of classifications with allowances
    public function index()
    {
        $classifications = Classification::with('fixedAllowances', 'variableAllowances')
            ->orderBy('level', 'asc')
            ->get();
        
        return view('admin.allowance.index', compact('classifications'));
    }

    // Show edit form for a specific classification
    public function edit(Classification $classification)
    {
        $classification->load('fixedAllowances', 'variableAllowances');
        return view('admin.allowance.edit', compact('classification'));
    }

    // Update fixed allowance
    public function updateFixedAllowance(Request $request, FixedAllowance $fixedAllowance)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $fixedAllowance->update($validated);

        return redirect()->route('allowance.edit', $fixedAllowance->classification_id)
            ->with('success', 'Tunjangan tetap berhasil diperbarui');
    }

    // Update variable allowance
    public function updateVariableAllowance(Request $request, VariableAllowance $variableAllowance)
    {
        $validated = $request->validate([
            'amount_per_day' => 'required|numeric|min:0',
        ]);

        $variableAllowance->update($validated);

        return redirect()->route('allowance.edit', $variableAllowance->classification_id)
            ->with('success', 'Tunjangan tidak tetap berhasil diperbarui');
    }

    // Batch update all allowances for a classification
    public function batchUpdate(Request $request, Classification $classification)
    {
        $validated = $request->validate([
            'fixed_allowances' => 'array',
            'fixed_allowances.*.id' => 'required|exists:fixed_allowances,id',
            'fixed_allowances.*.amount' => 'required|numeric|min:0',
            'variable_allowances' => 'array',
            'variable_allowances.*.id' => 'required|exists:variable_allowances,id',
            'variable_allowances.*.amount_per_day' => 'required|numeric|min:0',
        ]);

        // Update fixed allowances
        if (isset($validated['fixed_allowances'])) {
            foreach ($validated['fixed_allowances'] as $data) {
                FixedAllowance::where('id', $data['id'])->update(['amount' => $data['amount']]);
            }
        }

        // Update variable allowances
        if (isset($validated['variable_allowances'])) {
            foreach ($validated['variable_allowances'] as $data) {
                VariableAllowance::where('id', $data['id'])->update(['amount_per_day' => $data['amount_per_day']]);
            }
        }

        return redirect()->route('allowance.edit', $classification->id)
            ->with('success', 'Semua tunjangan berhasil diperbarui');
    }
}
