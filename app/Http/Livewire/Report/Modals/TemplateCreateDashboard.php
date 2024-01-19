<?php

namespace App\Http\Livewire\Report\Modals;

use App\Models\Tenant\Dashboard;
use App\Models\Tenant\DashboardTemplate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class TemplateCreateDashboard extends ModalComponent
{
    use AuthorizesRequests;

    public DashboardTemplate | int $dashboardTemplate;
    public $name;
    public $description;

    /**
     * mount the component.
     * @param DashboardTemplate $dashboardTemplate
     */
    public function mount(DashboardTemplate $dashboardTemplate)
    {
        $this->dashboardTemplate = $dashboardTemplate;

        if ($this->dashboardTemplate) {
            $this->name = $this->dashboardTemplate->name;
            $this->description = $this->dashboardTemplate->description;
        }
    }

    /**
     * Render the component modal.
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.tenant.dynamic-dashboard.modals.template-create-dashboard');
    }

    /**
     * Create a new dashboard from a template.
     */
    public function createFromTemplate($template)
    {
        $template = DashboardTemplate::find($template);

        Dashboard::create([
            'name' => $this->name,
            'description' => $this->description,
            'layout' => $template->layout
        ]);

        $this->emit('dashboardChanged');
        $this->closeModal();
    }
}
