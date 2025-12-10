<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('pin',50)->unique();
            $table->string('nik',50)->unique();
            $table->string('name',200);
            $table->string('birth_place',100)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('status',['aktif','nonaktif','kontrak','resign'])->default('aktif');
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->year('join_year')->nullable();
            $table->decimal('umk',12,2)->default(0);
            $table->timestamps();
            $table->index('pin');
            $table->index('nik');
        });
    }
    public function down() { Schema::dropIfExists('employees'); }
};
