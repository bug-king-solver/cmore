<?php

use App\Models\Tenant\BusinessSector;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        //disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table("questionnaire_physicalrisks")->truncate();

        //create the business_sector_id if not exists
        if (!Schema::hasColumn('questionnaire_physicalrisks', 'business_sector_id')) {
            Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
                $table->foreignIdFor(BusinessSector::class, 'business_sector_id')
                    ->constrained('business_sectors')
                    ->cascadeOnDelete()
                    ->after('id');
            });
        }

        //check if the FK questionnaire_physicalrisks_business_activity_id_foreign exists , if do , delete
        if (hasForeignKey('questionnaire_physicalrisks', 'business_activity_id')) {
            Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
                $table->dropForeign('questionnaire_physicalrisks_business_activity_id_foreign');
            });
        }

        // drop the business_activity_id if exists
        if (Schema::hasColumn('questionnaire_physicalrisks', 'business_activity_id')) {
            Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
                $table->dropColumn('business_activity_id');
            });
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // add the business_activity_id if not exists
        if (!Schema::hasColumn('questionnaire_physicalrisks', 'business_activity_id')) {
            Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
                $table->foreignId('business_activity_id')->nullable()
                    ->constrained('business_activities')
                    ->cascadeOnDelete()
                    ->after('id');
            });
        }

        if (hasForeignKey('questionnaire_physicalrisks', 'business_sector_id')) {
            Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
                $table->dropForeign('questionnaire_physicalrisks_business_sector_id_foreign');
            });
        }


        // drop the business_sector_id if exists
        if (Schema::hasColumn('questionnaire_physicalrisks', 'business_sector_id')) {
            Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
                $table->dropColumn('business_sector_id');
            });
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
