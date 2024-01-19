<?php

namespace App\Http\Livewire\Questionnaires\Physicalrisks\Modals;

use App\Models\Enums\Risk;
use App\Models\Tenant\BusinessActivities;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\PhysicalRisks\PhysicalRisks;
use App\Services\ThinkHazard\ThinkHazard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class DeleteGeography extends ModalComponent
{
    use AuthorizesRequests;

    public PhysicalRisks $physicalRisks;

    /**
     * Get the maximum width of the modal.
     *
     * @return string
     */
    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount(PhysicalRisks $physicalRisks)
    {
        $this->physicalRisk = $physicalRisks;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return void
     */
    public function render(): View
    {
        return view('livewire.tenant.physicalrisks.modals.delete-geography');
    }

    /**
     * Save the activity.
     *
     * @return void
     */
    public function delete()
    {
        $this->authorize('questionnaires.delete', $this->physicalRisk->questionnaire->id);
        $this->physicalRisk->delete();

        $this->closeModal();
        $this->emit('physicalRiskRefresh');
    }
}
