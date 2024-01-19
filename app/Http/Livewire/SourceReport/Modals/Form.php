<?php

namespace App\Http\Livewire\SourceReport\Modals;

use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Source;
use App\Models\Tenant\SourceReport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use AuthorizesRequests;

    public Questionnaire | int $questionnaire;

    public $frameworkList;
    public $framework;
    public $force;
    public $setChangedFrameworkId = null;
    public $setChangedCompanyId = null;
    public $taggableList;

    public $taggableIds = [];

    protected $rules = [
        'framework' => 'required|exists:sources,id',
    ];

    protected function rules()
    {
        $rules = [
            'framework' => [
                'required',
                'exists:sources,id',
                Rule::unique(app(SourceReport::class)->getTable(), 'source_id')->where(function ($query) {
                    return $query->whereNull('deleted_at')
                        ->where('source_id', $this->framework)
                        ->where('company_id', $this->questionnaire->company_id);
                })
            ],
        ];

        return $rules;
    }

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
        $this->authorize("questionnaires.review.{$this->questionnaire->id}");
        $this->force = false;
        $this->frameworkList = parseDataForSelect(Source::get(), 'id', 'name');
        $this->taggableList = getTagsForSelect();
        if ($this->questionnaire->exists) {
            $this->taggableIds = $this->questionnaire->tags ? $this->questionnaire->tags->pluck('id', null)->toArray() : [];
        }
    }

    public function render(): View
    {
        return view('livewire.tenant.source-reports.form');
    }

    public function save()
    {
        $this->authorize("questionnaires.review.{$this->questionnaire->id}");
        if ($this->force) {
            SourceReport::where('source_id', $this->framework)->where('company_id', $this->questionnaire->company_id)->delete();
        }
        $data = $this->validate();

        $data['source_id'] = $data['framework'];
        $data['company_id'] = $this->questionnaire->company_id;
        $data['questionnaire_id'] = $this->questionnaire->id;
        $data['reporting_period_id'] = $this->questionnaire->reporting_period_id;

        $report = SourceReport::create($data);

        $this->emit('updatedData');

        return redirect()->route('tenant.exports.show', ['id' => $report->id, 'action' => 'view']);
    }
}
