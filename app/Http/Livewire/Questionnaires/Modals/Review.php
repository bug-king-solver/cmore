<?php

namespace App\Http\Livewire\Questionnaires\Modals;

use App\Models\Tenant\Questionnaire;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Review extends ModalComponent
{
    use AuthorizesRequests;

    public Questionnaire | int $questionnaire;

    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
        $this->authorize("questionnaires.review.{$this->questionnaire->id}");
    }

    public function render()
    {
        return view('livewire.tenant.questionnaires.review');
    }

    public function review()
    {
        $this->authorize("questionnaires.review.{$this->questionnaire->id}");

        $this->questionnaire->review();

        $this->emit('questionnairesChanged');

        $this->closeModal();
    }
}
