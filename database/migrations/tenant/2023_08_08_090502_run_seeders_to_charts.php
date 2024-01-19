<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('charts', function (Blueprint $table) {
        //     Artisan::call('tenants:seed', [
        //         '--class' => "Database\\Seeders\\Tenant\\DashboardTemplateSeeder",
        //         '--force' => true,
        //     ]);

        //     Artisan::call('tenants:seed', [
        //         '--class' => "Database\\Seeders\\Tenant\\DynamicChartDataSeeder",
        //         '--force' => true,
        //     ]);
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charts', function (Blueprint $table) {
            //
        });
    }
};
