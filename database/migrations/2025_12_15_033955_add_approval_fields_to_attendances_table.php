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
        Schema::table('attendances', function (Blueprint $table) {
            // Tambah approval fields
            if (!Schema::hasColumn('attendances', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable();
            }
            if (!Schema::hasColumn('attendances', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            
            // Tambah notes jika belum ada
            if (!Schema::hasColumn('attendances', 'notes') && !Schema::hasColumn('attendances', 'note')) {
                $table->longText('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'approved_by')) {
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('attendances', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
        });
    }
};
