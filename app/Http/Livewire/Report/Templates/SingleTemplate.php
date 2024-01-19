<?php

namespace App\Http\Livewire\Report\Templates;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\DynamicReportsTrait;
use App\Models\Tenant\DashboardTemplate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SingleTemplate extends Component
{
    use AuthorizesRequests;
    use BreadcrumbsTrait;
    use DynamicReportsTrait;

    public $name;
    public $dashboardTemplate;

    /**
     * Returns an array of validation rules for the component's properties.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => ['required']
        ];
    }

    /**
     * Mount the component.
     *
     * @param DashboardTemplate $dashboardTemplate
     * @return void
     */
    public function mount(DashboardTemplate $dashboardTemplate)
    {
        $this->dashboardTemplate = $dashboardTemplate;
        Session::forget('searchDashboard');

        $this->addBreadcrumb(__('Dashboards'), route('tenant.dynamic-dashboard.index'));
        $this->addBreadcrumb(__('Preview'));
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view(
            'livewire.tenant.dynamic-dashboard.templates.single-template',
            [
                "dashboardTemplate" => $this->dashboardTemplate,
            ]
        )
        ->layoutData(
            [
                'mainBgColor' => 'bg-esg4',
            ]
        );
    }
}
