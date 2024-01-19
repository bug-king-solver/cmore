<?php

namespace App\Http\Livewire\Companies;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Http\Livewire\Traits\TabsTrait;
use App\Models\Enums\Regulamentation\AssetTypeEnum;
use App\Models\Enums\Regulamentation\EntityTypeEnum;
use App\Models\Tenant\Company;
use App\Models\Tenant\Data;
use App\Models\Tenant\GarBtar\BankAssets;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\SharingOption;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;
    use CustomPagination;
    use TabsTrait;
    use BreadcrumbsTrait;

    public Company|int $company;

    protected $users;
    protected $notes;
    protected $questionnaireList; // used when the questionnaire dont have enabled the dashboard mini;
    public $questionnaires;
    public $listSimulations;
    public $listBankEcoSystems;
    public $simulation;
    public $simulationIndex;

    public $kpi = 'real';
    public $business = 'volum';
    public $stockflow = 'stock';

    public $assetTypeList;
    public $entityTypeList;

    public $denominatorGAR;
    public $denominatorBTAR;

    public $rowActive;

    protected $listeners = [
        'companyRefresh' => '$refresh',
        'addedSimulation' => 'addedSimulation',
        'refreshComponent' => 'refreshGraph'
    ];

    public function mount(int $company)
    {
        $this->assetTypeList = AssetTypeEnum::toArray();

        $this->entityTypeList = EntityTypeEnum::toArray();

        $this->company = Company::onlyOwnData()
            ->where('id', $company)
            ->with('business_sector')
            ->with('owner.roles')
            ->with('sharingOptions')
            ->firstOrFail();

        $this->listSimulations = $this->company->bankSimulations;
        $this->simulationIndex = 0;
        $this->simulation = $this->listSimulations->get($this->simulationIndex);
        $bankAssets = new BankAssets();

        $this->denominatorGAR = $bankAssets->getDenominatorGAR();
        $this->denominatorBTAR = $bankAssets->getDenominatorBTAR();

        $this->tabList = [
            [
                'label' => __('General ESG Information'),
                'icon' => 'plant',
                'slug' => str_replace(" ", "_", strtolower('General ESG Information')),
                'order' => 1,
            ],
            [
                'label' => __('Additional Info'),
                'icon' => 'questionnaire',
                'slug' => str_replace(" ", "_", strtolower('info')),
                'order' => 3,
            ]
        ];

        if (tenant()->hasGarBtarFeature) {
            $this->tabList[] = [
                'label' => __('Ratios and Impacts'),
                'icon' => 'ratios',
                'slug' => str_replace(" ", "_", strtolower('Ratios and Impacts')),
                'order' => 2,
            ];
        }

        if (tenant()->hasSharingEnabled) {

            $this->listBankEcoSystems = SharingOption::all();

            $this->tabList[] = [
                'label' => __('Sharing Consent'),
                'icon' => 'share',
                'slug' => str_replace(" ", "_", strtolower('Sharing Consent')),
                'order' => 4,
            ];
        }

        //sort by order column
        $this->tabList = collect($this->tabList)->sortBy('order')->values()->toArray();
        $this->activeTab = str_replace(" ", "_", strtolower('General ESG Information'));
        $this->authorize("companies.view.{$this->company->id}");

        $this->addBreadcrumb(__('Companies'), route('tenant.companies.list'));
        $this->addBreadcrumb($this->company->name);
    }

    public function render(): View
    {
        $this->questionnaires = Questionnaire::list()
            ->where("company_id", $this->company->id)
            ->select("id", "questionnaire_type_id")
            ->where("submitted_at", "!=", null)
            ->with(["type" => function ($type) {
                return $type->HasDashboardMini();
            }])
            ->whereHas("type", function ($type) {
                return $type->HasDashboardMini();
            })
            ->get()
            ->sortBy('questionnaire.questionnaire_type_id')
            ->groupBy("questionnaire_type_id")
            ->toArray() ?? [];

        $this->users = $this->company->users()->paginate($this->selectedItemsPerPage);

        $this->notes = $this->company->notes()->paginate($this->selectedItemsPerPage);

        $this->questionnaireList = Questionnaire::list()
            ->where("company_id", $this->company->id)
            ->with('type')
            ->paginate($this->selectedItemsPerPage);

        return view('livewire.tenant.companies.show');
    }

    /**
     * Update kpi
     */
    public function changeKpi($value)
    {
        $this->kpi = $value;
        $this->emit('refreshComponent');
    }

    /**
     * Update Business
     */
    public function changeBusiness($value)
    {
        $this->business = $value;
        $this->emit('refreshComponent');
    }

    /**
     * Update stock/flow
     */
    public function changeStockFlow($value)
    {
        $this->stockflow = $value;
        $this->emit('refreshComponent');
    }

    /**
     * Dispatch event to refresh the charts
     */
    public function refreshGraph()
    {
        $this->dispatchBrowserEvent('chartUpdated');
    }

    public function addedSimulation()
    {
        $this->listSimulations = $this->company->bankSimulations;
        $this->simulation = $this->listSimulations->first();
        $this->kpi = 'simulation';
        $this->emit('refreshComponent');
        $this->emit('companyRefresh');
    }

    public function updatedSimulationIndex()
    {
        $this->simulation = $this->listSimulations->get($this->simulationIndex);
        $this->emit('companyRefresh');
        $this->emit('refreshComponent');
    }

    /**
     * Update row detail section
     */
    public function showInformationRow($value = null)
    {
        if (isset($this->rowActive) && $this->rowActive === $value) {
            $this->rowActive = null;
        } else {
            $this->rowActive = $value;
        }
        $this->emit('updateRow');
    }




}
