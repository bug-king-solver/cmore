<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Dashboard;
use App\Models\Tenant\DashboardTemplate;
use Illuminate\View\View;
use Livewire\Component;

class DashboardList extends Component
{
    use BreadcrumbsTrait;

    protected $listeners = [
        'dashboardChanged' => '$refresh',
    ];

    public function mount()
    {
        $this->addBreadcrumb(__('Dashboards'));
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view(
            'livewire.tenant.dynamic-dashboard.list',
            [
                'list' => Dashboard::list()->get(),
                'templateList' => DashboardTemplate::all(),
            ]
        )->layoutData(['mainBgColor' => 'bg-esg4']);
    }
}
