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
            $table->boolean('ready')->default(false)->after('validator_id');
            $table->dateTime('ready_at')->nullable()->after('ready');
        });
        Schema::table('benchmark_reports', function (Blueprint $table) {
            $table->boolean('ready')->default(false)->after('validator_id');
            $table->dateTime('ready_at')->nullable()->after('ready');
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
            $table->dropColumn('ready');
            $table->dropColumn('ready_at');
        });
        Schema::table('benchmark_reports', function (Blueprint $table) {
            $table->dropColumn('ready');
            $table->dropColumn('ready_at');
        });
    }
};
