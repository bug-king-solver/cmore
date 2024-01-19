<?php

namespace App\Http\Livewire\Questionnaires\Modals;

use App\Models\Tenant\Questionnaire;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Duplicate extends ModalComponent
{
    use AuthorizesRequests;

    public Questionnaire | int $questionnaire;

    public $redirectToDashboard;

    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
        $this->authorize("questionnaires.create");
    }

    public function render()
    {
        return view('livewire.tenant.questionnaires.duplicate');
    }

    public function save(Questionnaire $questionnaire)
    {
        $this->authorize("questionnaires.create.{$this->questionnaire->id}");
        $newQuestionnaire = $this->questionnaire->replicateQuestionnaire();

        $redirect = $newQuestionnaire->questionnaireWelcome();
        return redirect($redirect);
    }
}
