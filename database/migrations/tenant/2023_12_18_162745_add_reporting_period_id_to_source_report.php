<?php

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
        if (!Schema::hasColumn('source_reports', 'reporting_period_id')) {
            Schema::table('source_reports', function (Blueprint $table) {
                $table->foreignId('reporting_period_id')->nullable()->after('id')->constrained('reporting_periods');
            });
        }

        if (Schema::hasColumn('source_reports', 'reporting_period_id')) {
            DB::table('source_reports')->update(['reporting_period_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('source_reports', 'reporting_period_id')) {
            Schema::table('source_reports', function (Blueprint $table) {
                $table->dropForeign(['reporting_period_id']);
                $table->dropColumn('reporting_period_id');
            });
        }
    }
};
