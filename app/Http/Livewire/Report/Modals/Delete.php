<?php

namespace App\Http\Livewire\Report\Modals;

use App\Models\Tenant\Dashboard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public Dashboard | int $dashboard;

    public function mount(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
        //$this->authorize("targets.delete.{$this->dashboard->id}");
    }

    public function render()
    {
        return view('livewire.tenant.dynamic-dashboard.modals.delete');
    }

    public function delete()
    {
        $this->dashboard->delete();

        $this->emit('dashboardChanged');

        $this->closeModal();
    }
}
