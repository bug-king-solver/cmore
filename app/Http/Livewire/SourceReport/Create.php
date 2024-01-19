<?php

namespace App\Http\Livewire\SourceReport;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Company;
use App\Models\Tenant\ReportingPeriod;
use App\Models\Tenant\Source;
use App\Models\Tenant\SourceReport;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use BreadcrumbsTrait;

    public SourceReport | int $sourceReport;

    public $frameworkList;
    public $source_id;
    public $force;
    public $taggableList;
    public $companiesList;
    public $company_id;
    public $taggableIds = [];

    public array $reportingPeriodList;

    public int|null $reporting_period_id;

    protected function rules()
    {
        $rules = [
            'source_id' => [
                'required',
                Rule::unique(app(SourceReport::class)->getTable(), 'source_id')->where(function ($query) {
                    return $query->whereNull('deleted_at')
                        ->where('source_id', $this->source_id)
                        ->where('company_id', $this->company_id);
                })
            ],
            'company_id' => ['required', 'exists:companies,id'],
            'reporting_period_id' => ['required', 'exists:reporting_periods,id'],
        ];

        return $rules;
    }

    public function mount()
    {
        $this->force = false;
        $this->frameworkList = parseDataForSelect(Source::get(), 'id', 'name');
        $this->taggableList = getTagsForSelect();
        $companies = Company::list();
        $this->companiesList = parseDataForSelect($companies->get(), 'id', 'name');

        $this->reportingPeriodList = ReportingPeriod::questionnaireFormList();

        $this->addBreadcrumb(__('Report'));
        $this->addBreadcrumb(__('Reports'));
    }

    public function save()
    {
        if ($this->force) {
            SourceReport::where('source_id', $this->source_id)
                ->where('company_id', $this->company_id)
                ->delete();
        }

        $data = $this->validate();

        $this->sourceReport = SourceReport::create($data);
        return redirect()->route('tenant.exports.show', ['id' => $this->sourceReport->id, 'action' => 'edit']);
    }

    public function render()
    {
        return view('livewire.tenant.source-reports.create');
    }
}
