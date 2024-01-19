<?php

namespace App\Http\Livewire\Questionnaires\Modals;

use App\Models\Tenant\Questionnaire;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Submit extends ModalComponent
{
    use AuthorizesRequests;

    public Questionnaire | int $questionnaire;

    public $redirectToDashboard;

    /**
     * Mount the component.
     */
    public function mount(Questionnaire $questionnaire, $redirectToDashboard = true)
    {
        $this->questionnaire = $questionnaire;
        $this->redirectToDashboard = $redirectToDashboard;

        $this->authorize("questionnaires.submit.{$this->questionnaire->id}");
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.tenant.questionnaires.submit');
    }

    /**
     * Save the questionnaire.
     */
    public function save(Questionnaire $questionnaire)
    {
        abort_if(! $this->questionnaire->isCompleted(), 403);

        $this->authorize("questionnaires.submit.{$this->questionnaire->id}");

        $this->questionnaire->submit();

        if ($this->redirectToDashboard) {
            if ($this->questionnaire->questionnaire_type_id == 10) {
                return redirect(route('tenant.taxonomy.report', ['questionnaire' => $this->questionnaire->id]));
            } else {
                return redirect(route('tenant.dashboard', ['questionnaire' => $this->questionnaire->id]));
            }
        }

        $this->emit('questionnairesChanged');

        $this->closeModal();
    }
}
