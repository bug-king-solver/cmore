<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('benchmark_reports', function (Blueprint $table) {
            $table->string('revenue')->nullable()->after('currency');
            $table->string('employees')->nullable()->after('revenue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('benchmark_reports', function (Blueprint $table) {
            $table->dropColumn('revenue');
            $table->dropColumn('employees');
        });
    }
};
