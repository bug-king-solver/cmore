<?php

namespace Database\Migrations\Tenant;

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
        // I used DB::statement to have effect in soft deletes

        // Business sectors
        Schema::table('business_sectors', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Indicators
        Schema::table('indicators', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Initiative
        Schema::table('initiatives', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_sectors', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('indicators', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
