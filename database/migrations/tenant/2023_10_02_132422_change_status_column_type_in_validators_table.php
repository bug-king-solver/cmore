<?php

namespace Database\Migrations\Tenant;

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

        //check if status column exists, if not, create
        if (!Schema::hasColumn('validators', 'status')) {
            Schema::table('validators', function (Blueprint $table) {
                $table->boolean('status')->default(true)->after('id');
            });
        } else {
            Schema::table('validators', function (Blueprint $table) {
                $table->boolean('status')->default(true)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('validators', function (Blueprint $table) {
            //
        });
    }
};
