<?php

use App\Models\Tenant\Company;
use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
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
        Schema::create('reputation_analysis_info', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class, 'company_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->json('alternative_names');
            $table->json('handles');
            $table->string('language')->nullable();
            $table->json('search_terms');
            $table->json('competitors');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_raw_data', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AnalysisInfo::class, 'reputation_analysis_info_id');
            $table->string('language')->nullable();
            $table->json('data');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_keywords_frequency', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_keywords_frequency_daily', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->string('month');
            $table->string('week_of_year');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_keywords_frequency_weekly', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->string('month');
            $table->string('week_of_year');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_keywords_frequency_monthly', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->string('month');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_keywords_frequency_yearly', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_sentiments', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_sentiments_daily', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->string('month');
            $table->string('week_of_year');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_sentiments_weekly', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->string('month');
            $table->string('week_of_year');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_sentiments_monthly', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->string('month');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_sentiments_yearly', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_emotions', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_emotions_daily', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->string('month');
            $table->string('week_of_year');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_emotions_weekly', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->string('month');
            $table->string('week_of_year');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_emotions_monthly', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->string('month');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reputation_analysis_emotions_yearly', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->string('year');
            $table->timestamp('extracted_at', $precision = 0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reputation_analysis_info');
        Schema::dropIfExists('reputation_analysis_raw_data');
        Schema::dropIfExists('reputation_analysis_keywords_frequency');
        Schema::dropIfExists('reputation_analysis_keywords_frequency_daily');
        Schema::dropIfExists('reputation_analysis_keywords_frequency_weekly');
        Schema::dropIfExists('reputation_analysis_keywords_frequency_monthly');
        Schema::dropIfExists('reputation_analysis_keywords_frequency_yearly');
        Schema::dropIfExists('reputation_analysis_sentiments');
        Schema::dropIfExists('reputation_analysis_sentiments_daily');
        Schema::dropIfExists('reputation_analysis_sentiments_weekly');
        Schema::dropIfExists('reputation_analysis_sentiments_monthly');
        Schema::dropIfExists('reputation_analysis_sentiments_yearly');
        Schema::dropIfExists('reputation_analysis_emotions');
        Schema::dropIfExists('reputation_analysis_emotions_daily');
        Schema::dropIfExists('reputation_analysis_emotions_weekly');
        Schema::dropIfExists('reputation_analysis_emotions_monthly');
        Schema::dropIfExists('reputation_analysis_emotions_yearly');
    }
};
