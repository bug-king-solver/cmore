<?php

namespace App\Http\Livewire\Report;

use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Company;
use App\Models\Tenant\Dashboard;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    protected $companiesList;

    protected $countriesList;

    protected $businessSectorsList;

    protected $dashboardList;

    public $year;

    public array $search;

    protected $listeners = [
        'dashboard' => '$refresh',
    ];

    public function mount()
    {
        $this->companiesList = Company::list()->get();
        $this->countriesList = getCountriesForSelect();
        $this->businessSectorsList = BusinessSector::list()->get();
        $this->dashboardList = Dashboard::list()->get();

        $this->search = [
            'companies' => [],
            'countries' => [],
            'businessSectors' => [],
        ];
    }

    public function render(): View
    {

        return view(
            'livewire.tenant.dynamic-dashboard.index',
            [
                'companiesList' => parseDataForSelect($this->companiesList, 'id', 'name'),
                'countriesList' => $this->countriesList,
                'businessSectorsList' => parseDataForSelect($this->businessSectorsList, 'id', 'name'),
                'dashboardList' => parseDataForSelect($this->dashboardList, 'id', 'name'),
            ]
        )
            ->layoutData(
                [
                    'mainBgColor' => 'bg-esg15',
                ]
            );
    }

    public function filter()
    {
    }
}
