<?php

use App\Models\Tenant\Questionnaire;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $questionnaires = Questionnaire::whereNotNull('submitted_at')
            ->where('questionnaire_type_id', 10)
            ->orderBy('submitted_at', 'DESC')
            ->get();

        foreach ($questionnaires as $questionnaire) {
            $questionnaire->review();
            $questionnaire->submit();
        }
    }
};
