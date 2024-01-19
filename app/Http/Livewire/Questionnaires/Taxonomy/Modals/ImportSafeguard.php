<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy\Modals;

use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class ImportSafeguard extends ModalComponent
{
    use AuthorizesRequests;

    public Taxonomy | int $taxonomy;

    public $safeguardAligned;

    protected $rules = [
        'safeguardAligned' => 'required|boolean',
    ];

    /**
     * Mount the component.
     */
    public function mount(Taxonomy $taxonomy)
    {
        $this->taxonomy = $taxonomy;

        $this->safeguardAligned = $this->taxonomy->safeguard['verified'] ?? null;
        $this->authorize("questionnaires.review.{$this->taxonomy->questionnaire_id}");
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.tenant.taxonomy.modals.import-safeguard');
    }

    /**
     * Review the questionnaire.
     */
    public function save()
    {
        $this->authorize("questionnaires.review.{$this->taxonomy->questionnaire_id}");
        $data = $this->validate();

        $safeguard = $this->taxonomy->safeguard;

        $safeguard['imported'] = true;
        $safeguard['verified'] = $data['safeguardAligned']
            ? 1
            : 0;

        $this->taxonomy->update(['safeguard' => $safeguard]);

        $this->taxonomy->refresh();
        $this->emit('taxonomyUpdated');

        $this->closeModal();
    }
}
