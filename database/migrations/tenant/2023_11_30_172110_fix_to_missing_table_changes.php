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
        // check if exists 'country_iso', 'country_code', 'region_code', 'city_code' and drop
        if (Schema::hasColumn('questionnaire_physicalrisks', 'country_iso')) {
            Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
                $table->dropColumn(['country_iso']);
            });
        }

        if (Schema::hasColumn('questionnaire_physicalrisks', 'country_code')) {
            Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
                $table->dropColumn(['country_code']);
            });
        }

        if (Schema::hasColumn('questionnaire_physicalrisks', 'region_code')) {
            Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
                $table->dropColumn(['region_code']);
            });
        }

        if (Schema::hasColumn('questionnaire_physicalrisks', 'city_code')) {
            Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
                $table->dropColumn(['city_code']);
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
    }
};
