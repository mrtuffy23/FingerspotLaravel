<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('total_fixed_allowance', 12, 2)->default(0)->after('base_salary');
            $table->decimal('total_variable_allowance', 12, 2)->default(0)->after('total_fixed_allowance');
            $table->dropColumn(['rate_allowance', 'allowance_amount']);
        });
    }

    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['total_fixed_allowance', 'total_variable_allowance']);
            $table->decimal('rate_allowance', 12, 4)->default(0)->after('rate_base');
            $table->decimal('allowance_amount', 12, 2)->default(0)->after('base_salary');
        });
    }
};
