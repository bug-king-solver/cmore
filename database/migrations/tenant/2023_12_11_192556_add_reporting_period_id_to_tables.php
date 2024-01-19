<?php

use App\Models\Tenant\Data;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\QuestionnaireType;
use App\Models\Tenant\ReportingPeriod;
use Database\Seeders\Tenant\ReportingPeriodSeeder;
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
        // check if questionnaires table has reporting_period_id column
        if (!Schema::hasColumn('questionnaires', 'reporting_period_id')) {
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->foreignId('reporting_period_id')->nullable()->after('id')->constrained('reporting_periods');
            });
        }

        //check if data table has reporting_period_id column
        if (!Schema::hasColumn('data', 'reporting_period_id')) {
            Schema::table('data', function (Blueprint $table) {
                $table->foreignId('reporting_period_id')->nullable()->after('id')->constrained('reporting_periods');
            });
        }

        $totalReportingPeriods = ReportingPeriod::count();
        if ($totalReportingPeriods == 0) {
            $seeder = new  ReportingPeriodSeeder();
            $seeder->run();
        }

        // check if has questionnaires created,if do , add reporting period id 1 to all questionnaires
        if (Schema::hasColumn('questionnaires', 'reporting_period_id')) {
            // add the first reporting period id  ( YEAR )
            DB::table('questionnaires')->update(['reporting_period_id' => 1]);
        }

        // check if has data created,if do , add reporting period id 1 to all data
        if (Schema::hasColumn('data', 'reporting_period_id')) {
            // add the first reporting period id  ( YEAR )
            DB::table('data')->update(['reporting_period_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //check if questionnaire table has reporting_period_id column
        if (Schema::hasColumn('questionnaires', 'reporting_period_id')) {
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->dropForeign(['reporting_period_id']);
                $table->dropColumn('reporting_period_id');
            });
        }

        //check if data table has reporting_period_id column
        if (Schema::hasColumn('data', 'reporting_period_id')) {
            Schema::table('data', function (Blueprint $table) {
                $table->dropForeign(['reporting_period_id']);
                $table->dropColumn('reporting_period_id');
            });
        }
    }
};
