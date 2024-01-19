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
        $this->toogleHasScore(1);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->toogleHasScore(0);
    }


    public function toogleHasScore($action)
    {
        // $questionnaireTypes = [1, 2, 3, 4, 7, 18, 9, 8, 16, 19];
        // $questionnaires = QuestionnaireType::whereIn('id', $questionnaireTypes)
        //     ->withoutGlobalScopes()
        //     ->get();

        // if (Schema::hasColumn('questionnaire_types', 'has_score')) {
        //     if ($questionnaires->count() > 0) {
        //         QuestionnaireType::whereIn('id', $questionnaireTypes)
        //             ->withoutGlobalScopes()
        //             ->update(['has_score' => $action]);
        //     }
        // }
    }
};
