<?php

namespace App\Http\Livewire\Companies\Flow;

use App\Models\Tenant\Questionnaire;
use Livewire\Component;

class NextButton extends Component
{
    public int $questionnaire;

    public $isCompleted;

    public $isSubmitted;

    public $currentStep;


    protected $listeners = [
        '$refresh',
    ];

    public function mount($questionnaire, $currentStep)
    {
        $this->questionnaire = $questionnaire;
        $this->currentStep = $currentStep;
    }

    public function showSubmitForm()
    {
        $questionnaire = Questionnaire::find($this->questionnaire);
        $this->isCompleted = $questionnaire->isCompleted();
        $this->isSubmitted = $questionnaire->isSubmitted();
    }

    public function render()
    {
        $this->showSubmitForm();
        return view('livewire.tenant.companies.flow.next-button');
    }

    // public function nextStep($currentStep)
    // {
    //     $questionnaire = Questionnaire::find($this->questionnaire);
    //     $this->isCompleted = $questionnaire->isCompleted();
    //     $this->isSubmitted = $questionnaire->isSubmitted();
    // }
}
