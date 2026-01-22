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
        Schema::table('employees', function (Blueprint $table) {
            $table->integer('current_points')->default(100)->after('umk');
            $table->integer('initial_points')->default(100)->after('current_points');
            $table->foreignId('current_payroll_period_id')->nullable()->after('initial_points')->constrained('payroll_periods')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['current_points', 'initial_points', 'current_payroll_period_id']);
        });
    }
};
