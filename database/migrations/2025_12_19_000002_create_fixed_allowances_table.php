<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fixed_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classification_id')->constrained('classifications')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('code', 50);
            $table->decimal('amount', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fixed_allowances');
    }
};
