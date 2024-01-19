<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy\Modals;

use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use App\Models\Tenant\Questionnaires\Taxonomy\TaxonomyActivities;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class DeleteActivity extends ModalComponent
{
    use AuthorizesRequests;

    public TaxonomyActivities | int $activity;

    /**
     * Render the component.
     * @return void
     */
    public function mount(TaxonomyActivities $activity)
    {
        $this->activity = $activity;
        $this->authorize("questionnaires.delete.{$this->activity->id}");
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.tenant.taxonomy.modals.delete');
    }

    /**
     * Delete the taxonomy.
     * @return void
     */
    public function delete()
    {
        $this->activity->delete();

        $this->emit('taxonomyUpdated');

        $this->closeModal();
    }
}
