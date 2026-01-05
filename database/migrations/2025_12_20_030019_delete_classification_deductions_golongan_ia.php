<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Find the classification "Golongan I A"
        $classification = DB::table('classifications')
            ->where('name', 'Golongan I A')
            ->first();

        if ($classification) {
            // Delete all fixed deductions for this classification
            DB::table('fixed_deductions')
                ->where('classification_id', $classification->id)
                ->delete();

            // Delete all variable deductions for this classification
            DB::table('variable_deductions')
                ->where('classification_id', $classification->id)
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse operation needed - this is data deletion
    }
};
