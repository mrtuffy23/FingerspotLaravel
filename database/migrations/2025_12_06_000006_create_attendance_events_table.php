<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('attendance_events', function (Blueprint $table) {
            $table->id();
            $table->string('employee_pin',50);
            $table->dateTime('event_time');
            $table->string('device_id',100)->nullable();
            $table->longText('raw_data')->nullable();
            $table->timestamps();
            $table->index(['employee_pin','event_time']);
        });
    }
    public function down() { Schema::dropIfExists('attendance_events'); }
};
