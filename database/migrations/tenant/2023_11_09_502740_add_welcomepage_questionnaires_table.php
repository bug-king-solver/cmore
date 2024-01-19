<?php

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
        if (!Schema::hasColumn('questionnaires', 'welcomepage_enable')) {
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->boolean('welcomepage_enable')->default(0)->after('maturity');
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
        if (Schema::hasColumn('questionnaires', 'welcomepage_enable')) {
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->dropColumn('welcomepage_enable');
            });
        }
    }
};
