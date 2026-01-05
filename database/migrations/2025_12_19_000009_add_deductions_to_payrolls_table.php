<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('total_fixed_deduction', 12, 2)->default(0)->after('total_variable_allowance');
            $table->decimal('total_variable_deduction', 12, 2)->default(0)->after('total_fixed_deduction');
            $table->decimal('total_deduction', 12, 2)->default(0)->after('total_variable_deduction');
            $table->decimal('net_salary', 12, 2)->default(0)->after('total_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'total_fixed_deduction',
                'total_variable_deduction',
                'total_deduction',
                'net_salary'
            ]);
        });
    }
};
