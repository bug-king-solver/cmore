<?php

namespace App\Http\Livewire\Companies\Modals\Ratios;

use App\Models\Tenant\GarBtar\BankSimulationAssets;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public BankSimulationAssets | int $bankSimulation;

    public function mount(BankSimulationAssets $asset)
    {
        $this->bankSimulation = $asset;
    }

    public function render()
    {
        return view('livewire.tenant.companies.modals.ratios.delete');
    }

    public function delete()
    {
        /** detach all users attached to the bankSimulation */
        $this->bankSimulation->delete();

        $this->emit('companyRefresh');
        $this->emit('refreshComponent');

        $this->closeModal();
    }
}
