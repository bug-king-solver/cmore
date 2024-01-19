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
        //check if the column exists
        if (Schema::hasColumn('taxonomies', 'business_resume')) {
            Schema::table('taxonomies', function (Blueprint $table) {
                $table->renameColumn('business_resume', 'summary');
            });
        }

        if (Schema::hasColumn('taxonomy_activities', 'activity_resume')) {
            Schema::table('taxonomy_activities', function (Blueprint $table) {
                $table->renameColumn('activity_resume', 'summary');
            });
        }

        if (Schema::hasColumn('taxonomy_activities', 'substancial_contribute')) {
            Schema::table('taxonomy_activities', function (Blueprint $table) {
                $table->renameColumn('substancial_contribute', 'contribute');
            });
        }

        if (Schema::hasColumn('taxonomy_activities', 'nps')) {
            Schema::table('taxonomy_activities', function (Blueprint $table) {
                $table->renameColumn('nps', 'dnsh');
            });
        }

        if (Schema::hasColumn('company_addresses', 'country')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->renameColumn('country', 'country_code');
            });
        }

        if (Schema::hasColumn('company_addresses', 'region')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->renameColumn('region', 'region_code');
            });
        }

        if (Schema::hasColumn('company_addresses', 'city')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->renameColumn('city', 'city_code');
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
        if (Schema::hasColumn('taxonomies', 'summary')) {
            Schema::table('taxonomies', function (Blueprint $table) {
                $table->renameColumn('summary', 'business_resume');
            });
        }

        if (Schema::hasColumn('taxonomy_activities', 'summary')) {
            Schema::table('taxonomy_activities', function (Blueprint $table) {
                $table->renameColumn('summary', 'activity_resume');
            });
        }

        if (Schema::hasColumn('taxonomy_activities', 'dnsh')) {
            Schema::table('taxonomy_activities', function (Blueprint $table) {
                $table->renameColumn('dnsh', 'nps');
            });
        }

        if (Schema::hasColumn('taxonomy_activities', 'contribute')) {
            Schema::table('taxonomy_activities', function (Blueprint $table) {
                $table->renameColumn('contribute', 'substancial_contribute');
            });
        }

        if (Schema::hasColumn('company_addresses', 'country_code')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->renameColumn('country_code', 'country');
            });
        }

        if (Schema::hasColumn('company_addresses', 'region_code')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->renameColumn('region_code', 'region');
            });
        }

        if (Schema::hasColumn('company_addresses', 'city_code')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->renameColumn('city_code', 'city');
            });
        }
    }
};
