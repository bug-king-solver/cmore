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
        if (!Schema::hasTable('source_reports')) {
            Schema::create('source_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('source_id')->constrained('sources')->cascadeOnDelete();
                $table->bigInteger('company_id');
                $table->foreignId('questionnaire_id')->nullable()->constrained('questionnaires')->cascadeOnDelete();
                $table->json('data')->nullable();
                $table->date('from');
                $table->date('to');
                $table->softDeletes();
                $table->timestamps();
            });
        } else {
            // Check if every column below exists , if not , create
            if (!Schema::hasColumn('source_reports', 'company_id')) {
                Schema::table('source_reports', function (Blueprint $table) {
                    $table->bigInteger('company_id');
                });
            }

            if (!Schema::hasColumn('source_reports', 'questionnaire_id')) {
                Schema::table('source_reports', function (Blueprint $table) {
                    $table->foreignId('questionnaire_id')->nullable()->constrained('questionnaires')->cascadeOnDelete();
                });
            }

            if (!Schema::hasColumn('source_reports', 'data')) {
                Schema::table('source_reports', function (Blueprint $table) {
                    $table->json('data')->nullable();
                });
            }

            if (!Schema::hasColumn('source_reports', 'from')) {
                Schema::table('source_reports', function (Blueprint $table) {
                    $table->date('from');
                });
            }

            if (!Schema::hasColumn('source_reports', 'to')) {
                Schema::table('source_reports', function (Blueprint $table) {
                    $table->date('to');
                });
            }

            if (!Schema::hasColumn('source_reports', 'deleted_at')) {
                Schema::table('source_reports', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }

            if (!Schema::hasColumn('source_reports', 'created_at')) {
                Schema::table('source_reports', function (Blueprint $table) {
                    $table->timestamps();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('source_reports');
    }
};
