<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_calendars', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('description');
            $table->enum('type', ['workday','national_holiday','collective_leave','weekend'])->default('workday');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_calendars');
    }
};
