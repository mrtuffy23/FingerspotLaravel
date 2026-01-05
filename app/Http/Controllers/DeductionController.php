<?php

namespace App\Http\Controllers;

use App\Models\FixedDeduction;
use App\Models\VariableDeduction;
use App\Models\Classification;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    /**
     * Tampilkan daftar deductions untuk classification tertentu
     */
    public function index(Classification $classification)
    {
        $fixedDeductions = $classification->fixedDeductions;
        $variableDeductions = $classification->variableDeductions;

        return response()->json([
            'classification_id' => $classification->id,
            'classification_name' => $classification->name,
            'fixed_deductions' => $fixedDeductions,
            'variable_deductions' => $variableDeductions,
        ]);
    }

    /**
     * Tambah fixed deduction
     */
    public function storeFixedDeduction(Request $request, Classification $classification)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'amount' => 'required|numeric|min:0',
        ]);

        $deduction = FixedDeduction::create([
            'classification_id' => $classification->id,
            ...$validated
        ]);

        return response()->json([
            'message' => 'Fixed deduction berhasil ditambahkan',
            'data' => $deduction
        ], 201);
    }

    /**
     * Tambah variable deduction
     */
    public function storeVariableDeduction(Request $request, Classification $classification)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'amount_per_day' => 'required|numeric|min:0',
        ]);

        $deduction = VariableDeduction::create([
            'classification_id' => $classification->id,
            ...$validated
        ]);

        return response()->json([
            'message' => 'Variable deduction berhasil ditambahkan',
            'data' => $deduction
        ], 201);
    }

    /**
     * Update fixed deduction
     */
    public function updateFixedDeduction(Request $request, FixedDeduction $deduction)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'code' => 'sometimes|nullable|string|max:50',
            'amount' => 'sometimes|numeric|min:0',
        ]);

        $deduction->update($validated);

        return response()->json([
            'message' => 'Fixed deduction berhasil diupdate',
            'data' => $deduction
        ]);
    }

    /**
     * Update variable deduction
     */
    public function updateVariableDeduction(Request $request, VariableDeduction $deduction)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'code' => 'sometimes|nullable|string|max:50',
            'amount_per_day' => 'sometimes|numeric|min:0',
        ]);

        $deduction->update($validated);

        return response()->json([
            'message' => 'Variable deduction berhasil diupdate',
            'data' => $deduction
        ]);
    }

    /**
     * Hapus fixed deduction
     */
    public function destroyFixedDeduction(FixedDeduction $deduction)
    {
        $deduction->delete();

        return response()->json([
            'message' => 'Fixed deduction berhasil dihapus'
        ]);
    }

    /**
     * Hapus variable deduction
     */
    public function destroyVariableDeduction(VariableDeduction $deduction)
    {
        $deduction->delete();

        return response()->json([
            'message' => 'Variable deduction berhasil dihapus'
        ]);
    }

    /**
     * Tampilkan ringkasan semua deductions
     */
    public function summary(Classification $classification)
    {
        $fixedDeductions = $classification->fixedDeductions;
        $variableDeductions = $classification->variableDeductions;

        $totalFixedAmount = $fixedDeductions->sum('amount');
        $totalVariableAmount = $variableDeductions->sum('amount_per_day');

        return response()->json([
            'classification_id' => $classification->id,
            'classification_name' => $classification->name,
            'fixed_deductions' => [
                'count' => $fixedDeductions->count(),
                'total_amount' => $totalFixedAmount,
                'items' => $fixedDeductions
            ],
            'variable_deductions' => [
                'count' => $variableDeductions->count(),
                'total_amount_per_day' => $totalVariableAmount,
                'items' => $variableDeductions
            ]
        ]);
    }
}
