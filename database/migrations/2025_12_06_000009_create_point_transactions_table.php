<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->unsignedBigInteger('payroll_period_id')->nullable();
            $table->date('date');
            $table->integer('delta');
            $table->string('reason',255);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('point_transactions'); }
};
