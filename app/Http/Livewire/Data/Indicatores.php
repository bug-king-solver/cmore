<?php

namespace App\Http\Livewire\Data;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Tenant\Company;
use App\Models\Tenant\Data;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Task;
use Illuminate\View\View;
use Livewire\Component;

class Indicatores extends Component
{
    use BreadcrumbsTrait;
    use CustomPagination;

    public $search;

    public Indicator | int $indicator;

    public $company;

    public $companiesList;

    public $tab = 'information';

    public float $totalData;

    public $graphsData;

    protected $listeners = [
        'dataIndicatorChanged' => '$refresh',
        'updateCompanyId'
    ];

    /**
     * Mount the component.
     */
    public function mount(Indicator $indicator)
    {
        $this->indicator = $indicator;
        $this->companiesList = parseDataForSelect(Company::list()->get(), 'id', 'name');
        $this->company = session('companyId');
        
        $this->addBreadcrumb(__('Report'));
        $this->addBreadcrumb(__('Monitoring'));
        $this->addBreadcrumb(__('Indicators'), route('tenant.data.index'));
        $this->addBreadcrumb($indicator->name);
    }

    /**
     * Render view
     */
    public function render(): View
    {
        $data = Data::where('indicator_id', $this->indicator->id)
            ->with('company')
            ->with('indicator')
            ->whereHas('indicator')
            ->with('user')
            ->where('company_id', $this->company);

        if($this->company){
            $company = Company::where('id', $this->company)->first();
            $companyTasks = $company->tasks()
            ->paginate($this->selectedItemsPerPage);
        }

        $this->graphsData = $data->get()->groupBy(function ($item) {
            return $item->reported_at->format('Ym');
        })->map(function ($item, $key) {
            return [
                'value' => $item->last()->value,
                'count' => $item->count(),
                'label' => $item->last()->reported_at->format('Y-m')
            ];
        })
        ->sortBy(function ($item, $key) {
            return $key;
        })
        ->toArray();

        // total data = === last value of the data
        $value = array_last($this->graphsData)['value'] ?? 0;

        if(!is_string($value)){
            $this->totalData = $value;
        }

        $data = $data->paginate($this->selectedItemsPerPage);

        return view(
            'livewire.tenant.data.show',
            [
                'data' => $data,
                'companyTasks' => $companyTasks ?? null
            ]
        );
    }

    /**
     * Tab change
     */
    public function tabChnage($value)
    {
        $this->tab = $value;
    }

    public function updateCompanyId($value)
    {
        session(['companyId' => $value]);
    }
}
