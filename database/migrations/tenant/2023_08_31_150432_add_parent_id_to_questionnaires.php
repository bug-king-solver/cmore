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
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->integer('parent_id')->nullable()->default(null)->after('questionnaire_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
};
