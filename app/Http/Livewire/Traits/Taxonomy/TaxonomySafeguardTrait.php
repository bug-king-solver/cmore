<?php

namespace App\Http\Livewire\Traits\Taxonomy;

trait TaxonomySafeguardTrait
{
    public $questionnaireProgress;

    /**
     * Get the taxonomy activities quest trait listener.
     */
    public function bootTaxonomySafeguardTrait()
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
            return $question['answered'] == true;
        })->count();

        $total = collect($questions)->filter(function ($question) {
            return $question['enabled'] == true;
        })->count();

        $this->questionnaireProgress = calculatePercentage($answered, $total);
        return $this->questionnaireProgress;
    }

    /**
     * Handle updated safeguard questions.
     */
    public function updatedSafeguardQuestions($value, $nested)
    {
        $arr = explode('.', $nested);
        $questionId = $arr[0];
        $answerId = $arr[2];

        $arr = $this->safeguard;

        $arr['verified'] = null;

        $question = $this->safeguardQuestions[$questionId];
        $question['answered'] = true;
        $question['answered_value'] = $value;

        $action = strtolower($question['options'][$answerId]['action']);

        foreach ($question['options'] as $i => $answer) {
            $question['options'][$i]['selected'] = null;
        }

        $question['options'][$answerId]['selected'] = $value;

        if (in_array($action, ['não cumpre', 'não alinhada', ''])) {
            $arr['verified'] = 0;
        } elseif (in_array($action, ['cumpre', 'alinhada'])) {
            $arr['verified'] = 1;
        }

        if ($arr['verified'] === null) {
            $currentQuestionIndex = null;
            foreach ($this->safeguardQuestions as $i => $q) {
                $id = trim(strtolower((string) $q['id']));
                if (in_array(trim(strtolower((string) $action)), [$id, $id . '.'])) {
                    $this->safeguardQuestions[$i]['enabled'] = 1;
                } elseif ($i >= $questionId) {
                    $this->safeguardQuestions[$i]['enabled'] = 0;
                }
            }
        } else {
            $currentQuestionIndex = null;
            foreach ($this->safeguardQuestions as $i => $q) {
                if ((string)$q['id'] === (string)$question['id']) {
                    $currentQuestionIndex = $i;
                }
                if ($currentQuestionIndex !== null) {
                    if ($i > $currentQuestionIndex) {
                        $this->safeguardQuestions[$i]['enabled'] = 0;
                        foreach ($this->safeguardQuestions[$i]['options'] as $answerIndex => $answer) {
                            $this->safeguardQuestions[$i]['options'][$answerIndex]['selected'] = null;
                        }
                    }
                }
            }
        }

        $this->safeguardQuestions[$questionId] = $question;
        $arr['questions'] = $this->safeguardQuestions;
        $this->safeguard = $arr;

        $this->questionnaire->taxonomy->safeguard = $arr;
        $this->questionnaire->taxonomy->update();
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


    /** * Reset the questionnaire.
     * @return void
     */
    public function confirmResetQuestions()
    {
        $arr = parseStringToArray($this->questionnaire->taxonomy->safeguard);
        $arr['verified'] = null;

        foreach ($arr['questions'] as $questionIndex => $question) {
            $arr['questions'][$questionIndex]['answered'] = false;
            $arr['questions'][$questionIndex]['answered_value'] = null;

            if ($questionIndex != 0) {
                $arr['questions'][$questionIndex]['enabled'] = 0;
            }

            foreach ($question['options'] as $answerIndex => $answer) {
                $arr['questions'][$questionIndex]['options'][$answerIndex]['selected'] = null;
            }
        }

        $this->questionnaire->taxonomy->safeguard = $arr;
        $this->questionnaire->taxonomy->update();

        $this->safeguard = $arr;
        $this->safeguardQuestions = $arr['questions'];

        $this->emit('taxonomyActivityUpdated');
        $this->emit('closeModal');
    }
}
