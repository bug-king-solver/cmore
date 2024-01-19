<?php

use App\Models\Tenant\Questionnaire;
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
        //check if questionnaire has the deleted_at column
        if (Schema::hasColumn('questionnaires', 'deleted_at')) {
            $questionnaires = Questionnaire::where('result_at', null)->where('time_to_complete', '!=', null)->get();
            foreach ($questionnaires as $questionnaire) {
                $questionnaire->result_at = $questionnaire->created_at->addSeconds($questionnaire->time_to_complete);
                $questionnaire->save();
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
    }
};
