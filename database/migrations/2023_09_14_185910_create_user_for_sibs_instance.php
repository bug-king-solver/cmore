<?php

use App\Models\Admin;
use Database\Seeders\AdminSeeder;
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
        $needle = 'sibs';
        if (str_contains(config('app.instance'), $needle)) {
            Artisan::call('db:seed', [
                '--class' => AdminSeeder::class,
                '--force' => true
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            //
        });
    }
};
