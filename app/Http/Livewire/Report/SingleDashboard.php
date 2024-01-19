<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\DynamicReportsTrait;
use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Company;
use App\Models\Tenant\Dashboard;
use App\Models\Tenant\Indicator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SingleDashboard extends Component
{
    use AuthorizesRequests;
    use BreadcrumbsTrait;
    use DynamicReportsTrait;

    public $name;
    public $dashboard;

    protected $countriesList;
    protected $companiesList;
    protected $businessSectorsList;
    protected $indicatorsList;
    protected $groupByList;
    public array $search;

    public $revenueMin;
    public $revenueMax;
    public $yMin;
    public $yMax;
    public array $charts;

    /**
     * Returns an array of validation rules.
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
     * Mounts the component with the given dashboard and initializes its properties.
     *
     * @param Dashboard $dashboard The dashboard to be mounted.
     * @return void
     */
    public function mount(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;

        $this->companiesList = Company::list()->get();
        $this->countriesList = getCountriesForSelect();
        $this->businessSectorsList = BusinessSector::list()->get();
        $this->indicatorsList = Indicator::list()->where('has_benchmarking', true)->get();
        $this->groupByList = [
            ['id' => 'year', 'name' => __('Year')],
            ['id' => 'month', 'name' => __('Month')],
        ];

        $this->revenueMin = 0;
        $this->revenueMax = 1000000;

        // Default filters
        $this->search = $this->dashboard->filters ? json_decode($this->dashboard->filters, true) : [
            'companies' => [],
            'businessSectors' => [],
            'countries' => [],
            'company' => [],
        ];

        Session::put('searchDashboard', $this->search);

        $this->addBreadcrumb(__('Dashboards'), route('tenant.dynamic-dashboard.index'));
        $this->addBreadcrumb($this->dashboard->name);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(Dashboard $dashboard)
    {
        return view(
            'livewire.tenant.dynamic-dashboard.single-dashboard',
            [
                "dashboard" => $this->dashboard,
                'companiesList' => parseDataForSelect($this->companiesList, 'id', 'name'),
                'businessSectorsList' => parseDataForSelect($this->businessSectorsList, 'id', 'name'),
                'countriesList' => $this->countriesList
            ]
        )->layoutData(['mainBgColor' => 'bg-esg4',]);
    }

    /**
     * Saves the report to the database.
     */
    public function save()
    {
        $this->search["companies"] = $this->search["company"];
        $this->dashboard->filters = json_encode($this->search);
        $this->dashboard->save();
    }

    /**
     * Redirects the user to the dynamic dashboard index page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        return redirect()->to(route('tenant.dynamic-dashboard.index'));
    }

    /**
     * Filters the dashboard based on the selected search criteria and emits a refresh event to update the component.
     * @return void
     */
    public function filter()
    {
        Session::put('searchDashboard', $this->search);
        $this->emit('refreshComponent');
    }
}
