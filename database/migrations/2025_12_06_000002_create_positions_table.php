<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name',150);
            $table->decimal('allowance',12,2)->default(0);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('positions'); }
};
