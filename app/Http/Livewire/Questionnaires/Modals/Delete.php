<?php

namespace App\Http\Livewire\Questionnaires\Modals;

use App\Models\Tenant\Questionnaire;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public Questionnaire | int $questionnaire;

    /**
     * Mount the component.
     */
    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
        $this->authorize("questionnaires.delete.{$this->questionnaire->id}");
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.tenant.questionnaires.delete');
    }

    /**
     * Delete the questionnaire.
     */
    public function delete()
    {

        dispatch(function () {
              /** detach all users attached to the company */
            $this->questionnaire->users()->sync([]);
            $this->questionnaire->tags()->sync([]);

            if ($this->questionnaire->taxonomy) {
                $this->questionnaire->taxonomy->activities()->delete();
                $this->questionnaire->taxonomy()->delete();
            }

            $this->questionnaire->delete();
        });


        $this->emit('questionnairesChanged');

        $this->closeModal();
    }
}
