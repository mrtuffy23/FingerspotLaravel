<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('code',50);
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('break_minutes')->default(60);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('shifts'); }
};
