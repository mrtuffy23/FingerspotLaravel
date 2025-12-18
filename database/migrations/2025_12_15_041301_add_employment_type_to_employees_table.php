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
        Schema::table('employees', function (Blueprint $table) {
            // Tambah employment_type: monthly atau daily
            if (!Schema::hasColumn('employees', 'employment_type')) {
                $table->enum('employment_type', ['monthly', 'daily'])->default('monthly');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'employment_type')) {
                $table->dropColumn('employment_type');
            }
        });
    }
};
