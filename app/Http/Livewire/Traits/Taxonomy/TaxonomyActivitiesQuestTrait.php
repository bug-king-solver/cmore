<?php

namespace App\Http\Livewire\Traits\Taxonomy;

trait TaxonomyActivitiesQuestTrait
{
    public $questionnaireProgress;

    /**
     * Get the taxonomy activities quest trait listener.
     */
    public function bootTaxonomyActivitiesQuestTrait()
    {
        return $this->listeners += [
            'confirmResetQuestions' => 'confirmResetQuestions',
        ];
    }

    /**
     * Calc questionnaire progress.
     */
    public function calcQuestionnaireProgress($questions)
    {
        $answered = collect($questions)->filter(function ($question) {
            return $question['answered'] == 1;
        })->count();

        $total = collect($questions)->filter(function ($question) {
            return $question['enabled'] == 1;
        })->count();

        $this->questionnaireProgress = calculatePercentage($answered, $total);
        return $this->questionnaireProgress;
    }

    /**
     * Handle updated questions.
     *
     * @param  mixed  $nested
     * @return void
     */
    public function updatedQuestions($value, $nested)
    {
        $arr = explode('.', $nested);
        $questionId = $arr[0];
        $answerId = $arr[2];

        $column = $this->questionnaireColumn;
        $arr = parseStringToArray($this->activity->$column);
        $arr['verified'] = null; // reset verified

        $question = $this->objectives['questions'][$questionId];

        foreach ($question['options'] as $answerIndex => $a) {
            $question['options'][$answerIndex]['selected'] = null;
        }

        $question['options'][$answerId]['selected'] = $value;
        $question['answered'] = 1;
        $question['answered_value'] = $value;
        $this->objectives['questions'][$questionId] = $question;

        $this->parseActionQuestion($arr, $this->objectives, $question, $answerId);
    }

    /**
     * Parse the action question.
     *
     * @param  mixed  $arr
     * @param  mixed  $data
     * @param  mixed  $question
     * @param  mixed  $questionId
     * @param  mixed  $answerId
     * @return void
     */
    public function parseActionQuestion($arr, $data, $question, $answerId)
    {
        $column = $this->questionnaireColumn;
        $action = $question['options'][$answerId]['action'] ?? null;
        $transition_enabling = $question['options'][$answerId]['transition_enabling'] ?? null;
        $action = strtolower(trim(str_replace("..", '.', $action)));

        $data['verified'] = null;
        $data['transition_enabling'] = $transition_enabling;

        if ($action == 'não alinhada' || $action == null) {
            $data['verified'] = 0;
        } elseif ($action == 'alinhada') {
            $data['verified'] = 1;
        }

        $currentQuestionIndex = null;
        $action = "$action";
        foreach ($data['questions'] as $i => $q) {
            $id = $q['id'];
            $id = "$id";
            if ($id === $action) {
                if ($data['questions'][$i]['enabled'] !== 1) {
                    $data['questions'][$i]['enabled'] = 1;
                }
            }

            if ($id === (string)$question['id']) {
                $currentQuestionIndex = $i;
            }

            if ($currentQuestionIndex !== null) {
                if ($i > $currentQuestionIndex && $id != $action) {
                    $data['questions'][$i]['enabled'] = 0;
                    foreach ($data['questions'][$i]['options'] as $answerIndex => $answer) {
                        $data['questions'][$i]['options'][$answerIndex]['selected'] = null;
                    }
                }
            }
        }

        $newData = parseStringToArray($arr['data'] ?? '');
        $newData[$this->objective] = $data;
        $this->objectives = $data;
        $arr['data'] = $newData;

        $this->activity->$column = $arr;
        $this->activity->update();
    }

    /**
     * Show the modal to confirm reset questions.
     */
    public function resetarQuestionnaire()
    {
        $data = [
            'title' => __('Warning'),
            'message' => __('Are you sure you want to reset the questionnaire?'),
            'cancel' => true,
            'confirm' => 'confirmResetQuestions',
        ];

        $this->emit('openModal', 'modals.notification', ['data' => $data]);
        return;
    }

    /**
     * Confirm reset questions.
     * @return void
     */
    public function confirmResetQuestions()
    {
        $column = $this->questionnaireColumn;
        $arr = parseStringToArray($this->activity->$column);

        $this->objectives['verified'] = null;

        $questions = $this->objectives['questions'];
        foreach ($questions as $questionIndex => $question) {
            $questions[$questionIndex]['answered'] = false;
            $questions[$questionIndex]['answered_value'] = null;

            if ($questionIndex != 0) {
                $questions[$questionIndex]['enabled'] = 0;
            }

            foreach ($question['options'] as $answerIndex => $answer) {
                $questions[$questionIndex]['options'][$answerIndex]['selected'] = null;
            }
        }

        $this->objectives['questions'] = $questions;
        $this->objectives['transition_enabling'] = null;

        $arr['data'][$this->objective] = $this->objectives;


        $this->activity->$column = $arr;
        $this->activity->update();
        $this->emit('taxonomyActivityUpdated');
        $this->emit('closeModal');
    }
}
