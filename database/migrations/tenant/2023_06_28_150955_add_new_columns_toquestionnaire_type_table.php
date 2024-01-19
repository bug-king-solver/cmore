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
        Schema::table('questionnaire_types', function (Blueprint $table) {
            //add column hasProgress after name ( boolean default true )
            $table->boolean('hasProgress')->default(true)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_types', function (Blueprint $table) {
            $table->dropColumn('hasProgress');
        });
    }
};
