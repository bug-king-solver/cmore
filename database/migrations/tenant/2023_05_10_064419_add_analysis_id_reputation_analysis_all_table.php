<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // due to 64 char limit of foreign key identifier we have to use ainfo_id instead of reputation_analysis_info_id
        $tables = [
            'reputation_analysis_emotions',
            'reputation_analysis_emotions_daily',
            'reputation_analysis_emotions_monthly',
            'reputation_analysis_emotions_weekly',
            'reputation_analysis_emotions_yearly',
            'reputation_analysis_keywords_frequency',
            'reputation_analysis_keywords_frequency_daily',
            'reputation_analysis_keywords_frequency_monthly',
            'reputation_analysis_keywords_frequency_weekly',
            'reputation_analysis_keywords_frequency_yearly',
            'reputation_analysis_sentiments',
            'reputation_analysis_sentiments_daily',
            'reputation_analysis_sentiments_monthly',
            'reputation_analysis_sentiments_weekly',
            'reputation_analysis_sentiments_yearly',
        ];

        foreach ($tables as $tableToUp) {
            Schema::table($tableToUp, function (Blueprint $table) {
                $table->foreignId('ainfo_id')->after('id')->nullable()->constrained('reputation_analysis_info')->onDelete('cascade');
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
