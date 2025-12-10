<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('payroll_periods', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('name')->nullable();
            $table->integer('starting_points')->default(100);
            $table->timestamps();
            $table->unique(['start_date','end_date']);
        });
    }
    public function down() { Schema::dropIfExists('payroll_periods'); }
};
