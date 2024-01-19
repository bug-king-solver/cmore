<?php

namespace App\Http\Livewire\Questionnaires\AnswerTypes\Modals;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionOption;
use App\Models\Tenant\QuestionOptions\Simple;
use App\Services\Questionnaires\Co2CalculatorService;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;

class Co2Calculator extends ModalComponent
{
    public Question $question;
    public QuestionOption $questionOption;
    public Simple $questionOptionSimple;
    public Indicator $indicator;
    public Answer $answer;

    /** form */
    public $emissionsFactors = [];
    public $emission_factor;
    public $result_emission_factor;
    public $value;


    /**
     * mount the component
     */
    public function mount(QuestionOption $questionOption, $value, Answer $answer)
    {
        $this->questionOption = $questionOption;
        $this->indicator = $questionOption->indicator;
        $this->value = $value;
        $this->answer = $answer;

        $unity = $this->indicator->unit_qty;
        $default = $this->indicator->unit_default;


        $answerHasCo2 = $this->answer->data['co2_equiv'] ?? null;

        if (!$answerHasCo2) {
            $this->emission_factor = getEmissionFactor($unity, $default);
            $this->emission_factor = 0.24106;
        } else {
            $this->emission_factor = $answerHasCo2['emission_factor_used'];
        }

        $this->result_emission_factor = (float)$value * $this->emission_factor;
    }

    /**
     * render the component
     */
    public function render()
    {
        return tenantView('livewire.tenant.questionnaires.co2equiv.calc-modal');
    }

    /**
     * updated emission factor
     */
    public function updatedEmissionFactor()
    {
        if ((float)$this->emission_factor == 0) {
            $this->result_emission_factor = 0;
            return;
        }

        $this->result_emission_factor = (float)$this->value * $this->emission_factor;
    }

    /**
     * Save the answer
     */
    public function save()
    {

        $question = $this->questionOption->question;

        $data = [
            'co2_equiv' =>
            [
                'conversions' => [],
                'emission_factor' => $this->emissionsFactors,
                'emission_factor_default' => $this->indicator->unit_default,
                'emission_factor_used' => $this->emission_factor,
            ]
        ];

        DB::table((new Answer())->getTable())
            ->where('id', $this->answer->id)
            ->update([
                'data' => $data
            ]);

        $this->dispatchBrowserEvent('co2-calculator', [
            'questionOption' => $this->questionOption->id,
            'emissionFactor' => $this->result_emission_factor
        ]);

        $this->closeModal();
    }
}
