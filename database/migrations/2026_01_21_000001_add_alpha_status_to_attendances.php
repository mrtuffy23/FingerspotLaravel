<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'alpha' and 'absent' to the enum if not already there
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status', [
                'present',
                'late',
                'early_leave',
                'absent',
                'alpha',
                'on_leave',
                'sick',
                'accident',
                'holiday',
                'permission',
                'out_permission',
                'izin',
                'sakit',
                'sakit_sabtu',
                'kecelakaan',
                'cuti',
                'terlambat',
                'pulang_cepat',
                'izin_keluar',
                'libur'
            ])->change()->default('present');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status', [
                'present',
                'late',
                'early_leave',
                'absent',
                'on_leave',
                'sick',
                'accident',
                'holiday',
                'permission',
                'out_permission'
            ])->change()->default('present');
        });
    }
};
