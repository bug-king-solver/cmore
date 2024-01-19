<?php

namespace App\Http\Livewire\Questionnaires\Physicalrisks\Modals;

use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\PhysicalRisks\PhysicalRisks;
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

        $physicalRisks = $questionnaire->physicalRisks;

        PhysicalRisks::whereIn('id', $this->questionnaire->physicalRisks->pluck('id')->toArray())
        ->update([
            'completed_at' => null,
            'completed' => false
        ]);

        $this->emit('questionnairesChanged');
        $this->closeModal();
    }
}
