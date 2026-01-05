<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            // Add category column to differentiate ALLOWANCE vs DEDUCTION
            $table->string('category', 20)->after('payroll_id')->default('ALLOWANCE');
            
            // Add new columns with updated names
            $table->string('type', 50)->after('category')->nullable();
            $table->string('name', 100)->after('type')->nullable();
        });
        
        // Copy data from old columns to new columns
        DB::statement('UPDATE payroll_details SET type = allowance_type, name = allowance_name');
        
        // Drop old columns
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->dropColumn(['allowance_type', 'allowance_name']);
        });
        
        // Make new columns non-nullable
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->string('type', 50)->nullable(false)->change();
            $table->string('name', 100)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            // Add back old columns
            $table->string('allowance_type', 50)->after('payroll_id')->nullable();
            $table->string('allowance_name', 100)->after('allowance_type')->nullable();
        });
        
        // Copy data back
        DB::statement('UPDATE payroll_details SET allowance_type = type, allowance_name = name');
        
        // Drop new columns
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->dropColumn(['category', 'type', 'name']);
        });
        
        // Make old columns non-nullable
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->string('allowance_type', 50)->nullable(false)->change();
            $table->string('allowance_name', 100)->nullable(false)->change();
        });
    }
};
