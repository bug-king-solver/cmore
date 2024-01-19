<?php

use App\Models\Tenant\Answer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionOption;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $questions = Question::where('questionnaire_type_id', 15)->where('answer_type', 'checkbox-obs-decimal')->get();

        if ($questions) {
            foreach ($questions as $question) {
                $questionOptionWithNextOption = [];
                foreach ($question->questionOptions as $i => $question_option) {
                    if (!$question_option->option->is_co2_equivalent) {
                        $nextOptionIsCo2equiv = isset($question->questionOptions[$i + 1]) ? $question->questionOptions[$i + 1]->option->is_co2_equivalent : false;
                        if ($nextOptionIsCo2equiv) {
                            $questionOptionWithNextOption[$question_option->option->id] = $question->questionOptions[$i + 1]->question_option_id;
                        }
                    }
                }

                if (!empty($questionOptionWithNextOption)) {
                    $answers = Answer::where('question_id', $question->id)->get();
                    if (count($answers) > 0 ) {
                        foreach ($answers as $answer) {
                            $jsonValue = json_decode($answer->value, true);
                            foreach ($questionOptionWithNextOption as $optionId => $nextOptionId) {
                                if (!isset($jsonValue[$optionId])) {
                                    if (isset($jsonValue[$nextOptionId])) {
                                        unset($jsonValue[$nextOptionId]);
                                    }
                                }
                            }
                            // save method has been overrideden and has different conditions in answer model, so it will not work here.
                            Answer::where('id', $answer->id)->update(['value'=> json_encode($jsonValue)]);
                        }
                    }
                }
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
        //
    }
};
