<?php

namespace Database\Migrations;

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
            $table->boolean('validated')->default(false)->after('reporter_id');
        });
        Schema::table('benchmark_reports', function (Blueprint $table) {
            $table->boolean('validated')->default(false)->after('reporter_id');
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
            $table->dropColumn('validated');
        });
        Schema::table('benchmark_reports', function (Blueprint $table) {
            $table->dropColumn('validated');
        });
    }
};
