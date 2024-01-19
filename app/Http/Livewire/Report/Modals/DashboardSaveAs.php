<?php

namespace App\Http\Livewire\Report\Modals;

use App\Models\Tenant\Dashboard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use LivewireUI\Modal\ModalComponent;

class DashboardSaveAs extends ModalComponent
{
    use AuthorizesRequests;

    public $dashboard;
    public $name;
    public $description;

    public function mount(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;

        $this->name = $dashboard->name;
        $this->description = $dashboard->description;
    }

    public function render()
    {
        return view('livewire.tenant.dynamic-dashboard.modals.dashboard-save-as');
    }

    public function create($dashboard)
    {
        $dashboard = Dashboard::find($dashboard);

        Dashboard::create([
            'name' => $this->name,
            'description' => $this->description,
            'layout' => $dashboard->layout,
            'filters' => json_encode(Session::get('searchDashboard')),
        ]);

        $this->closeModal();
    }
}
