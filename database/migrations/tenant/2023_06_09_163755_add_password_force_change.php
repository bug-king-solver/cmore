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
        Schema::table('users', function (Blueprint $table) {
            // Force to change the password in the next login
            $table->boolean('password_force_change')->default(false)->after('password');
            $table->renameColumn('password_expiration', 'password_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_force_change');
            $table->renameColumn('password_expires_at', 'password_expiration');
        });
    }
};
