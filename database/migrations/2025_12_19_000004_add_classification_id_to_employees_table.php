<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('classification_id')->nullable()->after('employment_type')->constrained('classifications')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['classification_id']);
            $table->dropColumn('classification_id');
        });
    }
};
