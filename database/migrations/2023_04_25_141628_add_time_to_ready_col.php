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
        Schema::table('benchmark_data', function (Blueprint $table) {
            $table->bigInteger('time_to_ready')->nullable()->after('ready_at');
        });
        Schema::table('benchmark_reports', function (Blueprint $table) {
            $table->bigInteger('time_to_ready')->nullable()->after('ready_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('benchmark_data', function (Blueprint $table) {
            $table->dropColumn('time_to_ready');
        });
        Schema::table('benchmark_reports', function (Blueprint $table) {
            $table->dropColumn('time_to_ready');
        });
    }
};
