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
        if (! Schema::hasColumn('answers', 'not_reported')) {
            Schema::table('answers', function (Blueprint $table) {
                $table->boolean('not_reported')->after('value')->default(false);
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
        if (Schema::hasColumn('answers', 'not_reported')) {
            Schema::table('answers', function (Blueprint $table) {
                $table->dropColumn('not_reported');
            });
        }
    }
};
