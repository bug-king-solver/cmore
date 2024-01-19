<?php

namespace App\Http\Livewire\ReportingPeriods;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\HasCustomColumns;
use App\Models\Tenant\ReportingPeriod;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Redirector;

class Form extends Component
{
    use AuthorizesRequests;
    use HasCustomColumns;
    use BreadcrumbsTrait;

    protected string $feature = 'reportingPeriods';

    public ReportingPeriod | int $resource;


    public string $name;
    public string $custom_name;
    public bool $enabled_questionnaires_filter;
    public bool $enabled_questionnaires_reporting;
    public bool $enabled_monitoring_filter;
    public bool $enabled_monitoring_reporting;

    /**
     * Get the validation rules.
     * @return array<mixed>
     */
    protected function rules(): array
    {
        return  [
            'name' => ['required', 'string', 'max:255'],
            'custom_name' => ['required', 'string', 'max:255'],
            'enabled_questionnaires_filter' => ['boolean'],
            'enabled_questionnaires_reporting' => ['boolean'],
            'enabled_monitoring_filter' => ['boolean'],
            'enabled_monitoring_reporting' => ['boolean'],
        ];
    }

    /**
     * Mount the component.
     * @return void
     */
    public function mount(?ReportingPeriod $reportingPeriod): void
    {
        $this->addBreadcrumb(__('Reporting Periods'));
        $this->addBreadcrumb(__('Edit'));
        $this->addBreadcrumb($reportingPeriod->custom_name);
        $this->resource = $reportingPeriod;

        $this->name = $reportingPeriod->name;
        $this->custom_name = $reportingPeriod->custom_name;
        $this->enabled_questionnaires_filter = $reportingPeriod->enabled_questionnaires_filter;
        $this->enabled_questionnaires_reporting = $reportingPeriod->enabled_questionnaires_reporting;
        $this->enabled_monitoring_filter = $reportingPeriod->enabled_monitoring_filter;
        $this->enabled_monitoring_reporting = $reportingPeriod->enabled_monitoring_reporting;
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.tenant.reporting-periods.form');
    }

    /**
     * Save the resource.
     * @return RedirectResponse|Redirector
     */
    public function save(): RedirectResponse|Redirector
    {
        $data = $this->validate();
        $this->resource->update($data);

        return redirect()->route('tenant.reporting-periods.index');
    }
}
