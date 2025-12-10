<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->dateTime('first_in')->nullable();
            $table->dateTime('last_out')->nullable();
            $table->decimal('work_hours',6,2)->default(0);
            $table->decimal('compensated_hours',6,2)->default(0);
            $table->enum('status',['present','late','early_leave','absent','on_leave','sick','accident','holiday','permission','out_permission'])->default('present');
            $table->integer('point_delta')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['employee_id','date']);
        });
    }
    public function down() { Schema::dropIfExists('attendances'); }
};
