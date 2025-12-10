<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('payroll_period_id')->constrained('payroll_periods')->cascadeOnDelete();
            $table->decimal('total_actual_hours',7,2)->default(0);
            $table->decimal('total_compensated_hours',7,2)->default(0);
            $table->decimal('total_hours',7,2)->default(0);
            $table->decimal('rate_base',12,4)->default(0);
            $table->decimal('rate_allowance',12,4)->default(0);
            $table->decimal('base_salary',12,2)->default(0);
            $table->decimal('allowance_amount',12,2)->default(0);
            $table->decimal('total_salary',14,2)->default(0);
            $table->timestamps();
            $table->unique(['employee_id','payroll_period_id']);
        });
    }
    public function down() { Schema::dropIfExists('payrolls'); }
};
