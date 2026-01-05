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
        Schema::table('payroll_details', function (Blueprint $table) {
            // Tambah kolom baru jika belum ada
            if (!Schema::hasColumn('payroll_details', 'type')) {
                $table->string('type', 50)->comment('ALLOWANCE atau DEDUCTION')->after('payroll_id');
            }
            if (!Schema::hasColumn('payroll_details', 'category')) {
                $table->string('category', 50)->comment('FIXED atau VARIABLE')->after('type');
            }
            if (!Schema::hasColumn('payroll_details', 'name')) {
                $table->string('name', 100)->after('category');
            }
            
            // Drop kolom lama jika ada
            if (Schema::hasColumn('payroll_details', 'allowance_type')) {
                $table->dropColumn('allowance_type');
            }
            if (Schema::hasColumn('payroll_details', 'allowance_name')) {
                $table->dropColumn('allowance_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            if (Schema::hasColumn('payroll_details', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('payroll_details', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('payroll_details', 'name')) {
                $table->dropColumn('name');
            }
            
            // Restore kolom lama
            $table->string('allowance_type', 50)->nullable()->after('payroll_id');
            $table->string('allowance_name', 100)->nullable()->after('allowance_type');
        });
    }
};
