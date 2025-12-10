<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('type',['izin','sakit','sakit_sabtu','kecelakaan','cuti','izin_keluar','libur']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration')->nullable()->comment('Jumlah hari cuti');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down() { 
        Schema::dropIfExists('leaves'); 
    }
};