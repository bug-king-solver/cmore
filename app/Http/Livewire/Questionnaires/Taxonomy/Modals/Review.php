<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy\Modals;

use App\Models\Tenant\Questionnaire;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Review extends ModalComponent
{
    use AuthorizesRequests;

    public Questionnaire | int $questionnaire;

    /**
     * Mount the component.
     */
    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
        $this->authorize("questionnaires.review.{$this->questionnaire->id}");
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.tenant.questionnaires.review');
    }

    /**
     * Review the questionnaire.
     */
    public function review()
    {
        $this->authorize("questionnaires.review.{$this->questionnaire->id}");

        $questionnaire = $this->questionnaire;
        $questionnaire->completed_at = null;
        $questionnaire->submitted_at = null;
        $questionnaire->save();

        $taxonomy = $questionnaire->taxonomy;

        $taxonomy->completed = false;
        $taxonomy->completed_at = null;
        foreach ($taxonomy->activities as $activity) {
            $activity->resetDNSHAndContributeSummary();
        }
        $taxonomy->save();

        $this->emit('questionnairesChanged');
        $this->closeModal();
    }
}
