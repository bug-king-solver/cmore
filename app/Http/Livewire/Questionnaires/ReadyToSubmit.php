<?php

namespace App\Http\Livewire\Questionnaires;

use App\Models\Tenant\Questionnaire;
use Livewire\Component;

class ReadyToSubmit extends Component
{
    public int $questionnaire;

    public $isCompleted;

    public $isSubmitted;

    protected $listeners = [
        '$refresh',
    ];

    public function mount($questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    public function showSubmitForm()
    {
        $questionnaire = Questionnaire::find($this->questionnaire);
        // TO DO :: Company registration is not working properly | seem it is marking the questionnaires as complete when they are not
        // to immediatly fix it, I added the validation of the progress
        $this->isCompleted = $questionnaire->isCompleted();
        $this->isSubmitted = $questionnaire->isSubmitted();
    }

    public function render()
    {
        $this->showSubmitForm();
        return view('livewire.tenant.questionnaires.ready-to-submit');
    }
}
