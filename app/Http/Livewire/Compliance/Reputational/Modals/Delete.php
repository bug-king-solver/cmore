<?php

namespace App\Http\Livewire\Compliance\Reputational\Modals;

use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public AnalysisInfo | int $analysisInfo;

    public function mount(AnalysisInfo $analysisInfo)
    {
        $this->analysisInfo = $analysisInfo;
        $this->authorize("reputation.delete.{$this->analysisInfo->id}");
    }

    public function render()
    {
        return view('livewire.tenant.compliance.reputational.modal.delete');
    }

    public function delete()
    {
        $this->analysisInfo->delete();
        $this->closeModal();
        return redirect(route('tenant.reputation.index'));
    }
}
