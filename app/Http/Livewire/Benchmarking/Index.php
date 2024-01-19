<?php

namespace App\Http\Livewire\Benchmarking;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\BenchmarkReport;
use App\Models\Benchmarking;
use App\Models\BusinessSector;
use App\Models\Enums\LineFilters;
use App\Models\Tenant\Company;
use App\Models\Tenant\Indicator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use BreadcrumbsTrait;
    use WithPagination;

    protected $countriesList;

    protected $companiesList;

    protected $lineBy;

    protected $businessSectorsList;

    protected $indicatorsList;

    protected $yearsList;

    public array $search;

    public $revenueMin;

    public $revenueMax;

    public $revenueMaxStr;

    public $employeeMin;

    public $employeeMax;

    public $yMin;

    public $yMax;

    public $companyType;

    public $compareWith;

    public array $charts;

    protected $listeners = [
        'dataChanged' => '$refresh',
    ];

    public function mount()
    {
        $this->companyType = 'all';
        $this->compareWith = 'global';
        if ($this->companyType == 'all') {
            $this->companiesList = Company::list()->get();
        } else {
            $this->companiesList =  Company::whereRaw("find_in_set('".$this->companyType."', type)")->get();
        }

        $this->countriesList = getCountriesForSelect();
        $this->businessSectorsList = BusinessSector::list()->get();
        $this->indicatorsList = Indicator::list()->where('has_benchmarking', true)->get();
        $this->lineBy = parseKeyValueForSelect(LineFilters::toArray());
        $currentYear = date('Y') - 1;
        $firstYear = 2020;
        for ($i = $firstYear; $i <= $currentYear; $i++) {
            $this->yearsList[$i]['id'] = $i;
        }
        $this->revenueMin = 0;
        $this->revenueMax = BenchmarkReport::max('revenue');
        $this->revenueMaxStr = formatToCurrency($this->revenueMax, false, 'USD');

        $this->employeeMin = 0;
        $this->employeeMax = BenchmarkReport::max('employees');


        $this->search = [
            'companies' => $this->companiesList->pluck('id')->toArray(),
            'businessSectors' => [],
            'countries' => [],
            'years' => array_column($this->yearsList, 'id'),
            'revenue' => ['min' => $this->revenueMin, 'max' => $this->revenueMax],
            'employee' => ['min' => $this->employeeMin, 'max' => $this->employeeMax],
            'y' => ['min' => $this->yMin, 'max' => $this->yMax],
            'groupBy' => 'year',
            'indicator' => $this->indicatorsList->first()->id,
            'lineBy' => $this->lineBy[0]['id'],
            'companyType' => $this->companyType,
            'compareWith' => $this->compareWith,
        ];

        $this->filter();

        $this->addBreadcrumb(__('Benchmark'));
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.benchmarking.index',
            [
                'companiesList' => parseDataForSelect($this->companiesList, 'id', 'name'),
                'businessSectorsList' => parseDataForSelect($this->businessSectorsList, 'id', 'name'),
                'countriesList' => $this->countriesList,
                'indicatorsList' => parseDataForSelect($this->indicatorsList, 'id', 'name'),
                'yearsList' => parseDataForSelect($this->yearsList, 'id'),
                'lineBy' => $this->lineBy,
                'companyType' => $this->companyType,
                'compareWith' => $this->compareWith,

            ]
        );
    }

    public function updatedSearchYears($years)
    {
        sort($years);
        $this->search['years'] = $years;
    }

    public function updatedSearchRevenueMin($revenue)
    {
        if ($revenue > $this->revenueMin) {
            $this->search['revenue']['min'] = $this->revenueMin;
        } else {
            $this->search['revenue']['min'] = $revenue;
        }
    }

    public function updatedSearchRevenueMax($revenue)
    {
        if ($revenue > $this->revenueMax) {
            $this->search['revenue']['max'] = $this->revenueMax;
        } else {
            $this->search['revenue']['max'] = $revenue;
        }
    }

    public function updatedSearchEmployeeMin($employee)
    {
        if ($employee > $this->employeeMin) {
            $this->search['employee']['min'] = $this->employeeMin;
        } else {
            $this->search['employee']['min'] = $employee;
        }
    }

    public function updatedSearchEmployeeMax($employee)
    {
        if ($employee > $this->employeeMax) {
            $this->search['employee']['max'] = $this->employeeMax;
        } else {
            $this->search['employee']['max'] = $employee;
        }
    }

    public function updatedSearchYMin($y)
    {
        if ($y > $this->yMin) {
            $this->search['y']['min'] = $this->yMin;
        } else {
            $this->search['y']['min'] = $y;
        }
    }

    public function updatedSearchYMax($y)
    {
        if ($y > $this->yMax) {
            $this->search['y']['max'] = $this->yMax;
        } else {
            $this->search['y']['max'] = $y;
        }
    }

    public function updatedSearchBusinessSectors($businessSectorsIds)
    {
        $this->search['businessSectors'] = array_values(array_map(fn ($id) => (int) $id, $businessSectorsIds));
    }

    public function updatedCountries($countriesIds)
    {
        $this->search['businessSectors'] = array_values(array_map(fn ($id) => (int) $id, $countriesIds));
    }

    public function updatedSearchIndicator($indicator)
    {
        $this->search['indicator'] = (int) $indicator;
    }

    public function updatedSearchLineBy($lineBy)
    {
        $this->search['lineBy'] = $lineBy;
    }

    public function updatedSearchCompanyType($companyType)
    {
        $this->search['companyType'] = $companyType;
    }

    public function updatedSearchCompareWith($compareWith)
    {
        $this->search['compareWith'] = $compareWith;
    }

    public function updateCompaniesList() {

        $companyType = $this->search['companyType'];
        if ($companyType == 'all') {
            $this->companiesList =  Company::list()->get();
        } else {
            $this->companiesList =  Company::whereRaw("find_in_set('".$companyType."', type)")->get();
        }

        $this->search['companies'] = $this->companiesList->pluck('id')->toArray();
        $this->filter();
    }
    public function filter()
    {
        $companyType = $this->search['companyType'];
        if ($companyType == 'all') {
            $this->companiesList =  Company::list()->get();
        } else {
            $this->companiesList =  Company::whereRaw("find_in_set('".$companyType."', type)")->get();
        }
        extract($this->search);
        if (empty($companies) || empty($years) || empty($indicator)) {
            return $this->render();
        }

        $dataDistribution = Benchmarking::dataForDistribution($companies, $this->search);
        $this->yMin = $dataDistribution['y']['min'];
        $this->yMax = $dataDistribution['y']['max'];

        $this->charts = [
            'main' => [
                'labels' => $years,
                'data' => Benchmarking::dataForMain($companies, $this->search, 'year'),
            ],
            'distribution' => [
                'data' => $dataDistribution,
            ],
        ];

        return $this->render();
    }
}
