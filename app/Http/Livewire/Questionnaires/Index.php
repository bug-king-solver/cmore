<?php

namespace App\Http\Livewire\Questionnaires;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CustomPagination;
use App\Models\Enums\Questionnaires\QuestionnaireStatusEnum;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\SourceReport;
use Illuminate\View\View;

class Index extends FilterBarComponent
{
    use BreadcrumbsTrait;
    use CustomPagination;

    public bool $isSearchable = false;
    public $status;

    protected $listeners = [
        'questionnairesChanged' => '$refresh',
    ];

    /**
     * Mount the component.
     */
    public function mount()
    {
        parent::initFilters($model = Questionnaire::class);

        $this->model = new Questionnaire();

        $this->status = 'submitted';

        if (!$this->activeFilters) {
            $this->updateAvailableFilter('questionnaire_status');
            $this->updateFiltersValues('questionnaire_status', ['submitted']);
        } else {
            if (isset($this->activeFilters['questionnaire_status'][0]) && $this->activeFilters['questionnaire_status'][0] === 'ongoing') {
                $this->status = 'ongoing';
            }
        }

        $this->addBreadcrumb(__('Report'));
        $this->addBreadcrumb(__('Questionnaires'));

        $current = __('Panel');

        if ($this->activeFilters['questionnaire_status'][0] ?? false) {
            $current = match ($this->activeFilters['questionnaire_status'][0]) {
                QuestionnaireStatusEnum::ONGOING->value => QuestionnaireStatusEnum::ONGOING->labelPlural(),
                QuestionnaireStatusEnum::SUBMITTED->value => QuestionnaireStatusEnum::SUBMITTED->labelPlural(),
                default => '',
            };
        }

        $this->addBreadcrumb($current);
    }

    public function render(): View
    {
        $questionnaires = $this->search($this->model->list()->with('company', 'type'))
            ->paginate($this->selectedItemsPerPage);

        return view(
            'livewire.tenant.questionnaires.index',
            [
                'questionnaires' => $questionnaires,
            ]
        );
    }

    public function sourceReport(Questionnaire $questionnaire)
    {
        // Check Source Report against Questionnaire

        $sourceReport = SourceReport::where('questionnaire_id', $questionnaire->id)->first();
        // If Report exist then redirect else open modal
        if ($sourceReport) {
            return redirect()->route('tenant.exports.show', ['id' => $sourceReport->id, 'action' => 'edit']);
        }

        $this->emit('openModal', 'source-report.modals.form', [$questionnaire]);
    }
}
