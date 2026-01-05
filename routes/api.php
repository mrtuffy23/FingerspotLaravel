<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeductionController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Deduction Management Routes
 */
Route::prefix('classifications/{classification}')->group(function () {
    // Get semua deductions untuk classification
    Route::get('/deductions', [DeductionController::class, 'index'])->name('deductions.index');
    Route::get('/deductions/summary', [DeductionController::class, 'summary'])->name('deductions.summary');
    
    // Fixed Deductions
    Route::post('/deductions/fixed', [DeductionController::class, 'storeFixedDeduction'])->name('fixed_deductions.store');
    Route::put('/fixed-deductions/{deduction}', [DeductionController::class, 'updateFixedDeduction'])->name('fixed_deductions.update');
    Route::delete('/fixed-deductions/{deduction}', [DeductionController::class, 'destroyFixedDeduction'])->name('fixed_deductions.destroy');
    
    // Variable Deductions
    Route::post('/deductions/variable', [DeductionController::class, 'storeVariableDeduction'])->name('variable_deductions.store');
    Route::put('/variable-deductions/{deduction}', [DeductionController::class, 'updateVariableDeduction'])->name('variable_deductions.update');
    Route::delete('/variable-deductions/{deduction}', [DeductionController::class, 'destroyVariableDeduction'])->name('variable_deductions.destroy');
});

