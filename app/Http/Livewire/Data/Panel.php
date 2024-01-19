<?php

namespace App\Http\Livewire\Data;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Category;
use App\Models\Tenant\Company;
use App\Models\Tenant\Data;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Tenant\Validator as logValidator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;


class Panel extends Component
{
    use BreadcrumbsTrait;
    use WithPagination;

    protected $listeners = [
        'questionnairesChanged' => '$refresh',
    ];

    protected $timeReport = ['quarterly' => 4, 'biannual' => 6, 'annual' => 12];

    public $companiesList;
    public $search;

    public $model;
    public $company;
    public $categoriesParent;

    public $modelCategory;
    public $categories;

    public $pendingByCategories;

    public $companyId = false;

    /**
     * Mount the component.
     */
    public function mount()
    {
        $this->model = new Indicator();
        $this->company = new Company();
        $this->categories = array();
        $this->modelCategory = new Category();
        $this->companiesList = parseDataForSelect($this->company->list()->get(), 'id', 'name');
        $this->search ='';
        $this->filter();
        
        $this->addBreadcrumb(__('Report'));
        $this->addBreadcrumb(__('Monitoring'));
        $this->addBreadcrumb(__('Panel'));
    }

    /**
     * Render the component.
     */
    public function render()
    {
        $total = $this->total();
        $totalByCompany = $this->totalIndicatorByCompany();
        $indicadorsPending = $this->getPendingVerification();
        $indicadorsNeedReport = $this->getReportVerification();
        $categoriesIndicatorsCount = $this->indicatorsByCategory();
        $indicatorsDeadlines = $this->calculateApproachingDeadlines();

        $types = $this->totalTypeData();

        $frequency = $this->calculateFrequencyFlag();

        return view(
            'livewire.tenant.data.panel',
            [
                'totalIndicators' => $total,
                'totalByCompany' => $totalByCompany,
                'categoriesIndicatorsCount' => $categoriesIndicatorsCount,
                'indicadorsPending' => $indicadorsPending,
                'indicadorsNeedReport' => $indicadorsNeedReport,
                'types' => $types,
                'frequency' => $frequency,
                'indicatorsDeadline' => $indicatorsDeadlines,
            ]
        );
    }

    public function filter()
    {
        $this->companyId = $this->search;
    }

    public function total()
    {
        // TODO :: Add enabled scope
        return Indicator::whereEnabled(true)->count();
    }

    /**
     * Count and group Category parent names
     */
    private function indicatorsByCategory()
    {
        // TODO :: Add enabled scope
        $categories = Category::
            whereEnabled(true)
            ->whereModelType(Indicator::class)
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        $categories = $categories->map(function($category) {
            $subCategoriesIds = $category->children->pluck('id')->filter()->toArray();
            $categoriesIds = array_merge([$category->id], $subCategoriesIds);

            $category->indicators_count = Indicator::whereIn('category_id', $categoriesIds)
                ->count();

            return $category;
        })
        ->filter(fn($category) => $category->indicators_count > 0);

        $this->categories = $categories->pluck('indicators_count', 'name')->toArray();

        return $this->categories;
    }

    /**
     * Calculate total Type Data is Manual or Not.
     */
    private function totalTypeData()
    {
        $totalManual =  Data::where("company_id", $this->companyId)->whereNull("questionnaire_id")->count();
        $totalQuestionnaire =  Data::where("company_id", $this->companyId)->whereNotNull("questionnaire_id")->count();

        $types = array(
            array(
                'type' => 'Questionnaire',
                'total' => $totalQuestionnaire,
            ),
            array(
                'type' => 'Manual',
                'total' => $totalManual,
            )
        );
        return $types;
    }

    /**
     * Calculate total indicator by company.
     */
    public function totalIndicatorByCompany()
    {
        $dataIndicator = Data::where('company_id', $this->companyId)
            ->selectRaw('MAX(id) as id')
            ->groupBy('indicator_id')
            ->get();
        return count($dataIndicator);
    }

    /**
     * Calculate on data need report.
     */
    public function getReportVerification()
    {
        $validators = Data::where('validator_status', 0)
            ->where('company_id', $this->companyId)
            ->whereHas('indicator.category')
            ->with('indicator.category.parent.parent')
            ->whereHas('indicator.validator')
            ->with('indicator.validator')
            ->get();


        $filteredData = $validators->filter(function ($item) {
            $frequency = $item->indicator->validator->first()->frequency;
            $updatedAt = $item->updated_at;
            return $updatedAt->diffInMonths(now()) >= $this->timeReport[$frequency];
        });

        $groupedData = $filteredData->groupBy(function ($item) {
            return $item->indicator->category->name;
        });


        $result = [];

        $groupedData->each(function ($items, $categoryName) use (&$result) {
            $count = count($items);

            $result[] = [
                'category_name' => $categoryName,
                'qtd' => $count,
            ];
        });

        return $result;
    }

    /**
     * Calculate on peding verification.
     */
    public function getPendingVerification()
    {
        $validators = Data::where('validator_status', 0)
            ->whereHas('indicator.category')
            ->with('indicator.category.parent.parent')
            ->whereHas('indicator.validator')
            ->with('indicator.validator')
            ->get();

        $groupedData = $validators->groupBy(function ($item) {
            return $item->indicator->category->name;
        });

        $result = [];

        $groupedData->each(function ($items, $categoryName) use (&$result) {
            $count = count($items);

            $result[] = [
                'category_name' => $categoryName,
                'qtd' => $count,
            ];
        });

        return $result;
    }

    /**
     * Calculate on Frequency.
     */
    private function calculateFrequencyFlag()
    {
        return logValidator::where("company_id", $this->companyId)->groupBy('frequency')
            ->select('frequency', DB::raw('count(*) as qty'))->get()->map(function ($item) {
                return [
                    'frequency' => $item->frequency,
                    'qty' => $item->qty,
                ];
            })->toarray();
    }

    /**
     * Calculate approaching deadlines
     */
    private function calculateApproachingDeadlines()
    {
        $indicators = Data::where('validator_status', 0)
            ->where('company_id', $this->companyId)
            ->whereHas('indicator.validator')
            ->with('indicator.validator')
            ->with('indicator')
            ->selectRaw('MAX(data.id) as id,indicator_id,MAX(data.updated_at) as updated_at')
            ->groupBy('data.indicator_id')
            ->get();
        $indicatorsMapping = [];

        $indicators->map(function ($item) use (&$indicatorsMapping) {
            $frequency = $item->indicator->validator->first()->frequency;
            $indicator = Indicator::where("id", $item->indicator_id)->get()->first();

            $indicatorsMapping[] = [
                "deadline" => $item->updated_at->addMonths($this->timeReport[$frequency])->toDateString(),
                "name" => $indicator->name,
                "id" => $item->indicator_id
            ];
        });

        $data = collect($indicatorsMapping);
        return $data->where('deadline', '>=', now())->toArray();
    }
}
