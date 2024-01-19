<?php

use App\Models\Admin;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            Admin::create([
                'name' => 'Portal',
                'email' => 'admin@sibsesg.com',
                'password' => bcrypt('Kjdahd^2382Ljans!2jkm'),
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
