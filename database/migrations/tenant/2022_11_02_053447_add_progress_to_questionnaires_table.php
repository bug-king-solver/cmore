<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Scopes\EnabledScope;
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
        //check if column exists
        if (!Schema::hasColumn('questionnaires', 'progress')) {
            Schema::table('questionnaires', function (Blueprint $table) {
                $table->tinyInteger('progress')->default(0)->after('is_ready');
            });
        }

        if (Schema::hasColumn('questionnaires', 'deleted_at')) {
            // Set old questionnaires as incompleted and set progress
            collect(Questionnaire::withoutGlobalScopes()->get()->all())->map(function ($questionnaire) {
                $questionnaire->calcProgress();
                $questionnaire->markAsCompleted();
                $questionnaire->save();
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
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};
