<?php

use App\Models\Tenant\QuestionnaireType;
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
        $questionnaires = QuestionnaireType::all();
        $questionnaireToEnable = [10, 12, 15];
        foreach ($questionnaires as $questionnaire) {
            if (in_array($questionnaire->id, $questionnaireToEnable)) {
                $questionnaire->dashboardMini = true;
            } else {
                $questionnaire->dashboardMini = false;
            }
            $questionnaire->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $questionnaires = QuestionnaireType::all();
        foreach ($questionnaires as $questionnaire) {
            $questionnaire->dashboardMini = false;
            $questionnaire->save();
        }
    }
};
