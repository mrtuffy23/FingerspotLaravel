<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MariaDB doesn't support RENAME COLUMN, use CHANGE instead
        DB::statement('ALTER TABLE attendances CHANGE COLUMN note notes LONGTEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE attendances CHANGE COLUMN notes note LONGTEXT NULL');
    }
};
