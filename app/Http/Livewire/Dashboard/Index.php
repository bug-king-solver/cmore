<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Tenant\Questionnaire;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public $selectedQuestionnaire;

    public $dashboardType;

    protected Questionnaire | int $questionnaire;

    public $search;

    public array $chartData;

    public $view;

    public $chart;

    public $period;

    public $periodList;

    public $questionnaireList;

    public $questionnaireListURL;

    public $printView = 0;

    public $management = null;

    protected $listeners = [
        'dataChanged' => 'refeshData',
        'printView' => 'printview',
    ];

    /**
     * Refresh data
     */
    public function refeshData()
    {
        $this->search['questionnaire'] = []; // Empty search result for new period
        $this->printView = 0;
        $this->filter();
    }

    /**
     * Print view
     */
    public function printview()
    {
        $this->printView = 1;
        $this->management = Cookie::get('management');
        $this->filter();
    }

    /**
     * Mount data
     */
    public function mount(Questionnaire $questionnaire)
    {
        $this->selectedQuestionnaire = $questionnaire->exists && $questionnaire->isSubmitted()
            ? $questionnaire
            : Questionnaire::lastSubmitted(auth()->user());

        if (! $this->selectedQuestionnaire) {
            return redirect()->route('tenant.home');
        } elseif (! $this->selectedQuestionnaire->canBeAccessedBy(auth()->user())) {
            abort(403);
        }
        $this->dashboardType = $this->selectedQuestionnaire->questionnaire_type_id;

        $this->search = [
            'questionnaire' => $this->selectedQuestionnaire['id'] == $questionnaire['id'] ? [$this->selectedQuestionnaire['id']] : [], //[$this->selectedQuestionnaire['id']]
        ];

        $this->periodList = [
            '2021' => 2021,
            '2022' => 2022,
            '2023' => 2023
        ];

        $this->questionnaireListURL = route('tenant.questionnairelist');

        $this->filter();
    }

    /**
     * Render the view
     */
    public function render(): View
    {
        $this->chart = $this->chartData['charts'];

        if ($this->dashboardType == 23) {
            if (request()->query('report')) {
                $this->view = 'livewire.tenant.report.' . $this->dashboardType;
            } else {
                $this->view = 'livewire.tenant.dashboard.' . $this->dashboardType;
            }
        } else {
            $this->view = 'livewire.tenant.dashboard.' . $this->dashboardType;
        }

        return tenantView($this->view, $this->chartData);
    }

    /**
     * Update data
     */
    public function updatedSearchQuestionnaire($data)
    {
        $this->search['questionnaire'] = $data;
    }

    /**
     * Abourt print view
     */
    public function abortPrint()
    {
        $this->printView = 0;
        $this->filter();
    }

    /**
     * Filter data
     */
    public function filter()
    {
        if ($this->dashboardType == 5 || $this->dashboardType == 6 || $this->dashboardType == 23) { // This functionality work Only for TDP / Nossa Dashboard
            $dashboard = "\App\Models\Tenant\Dashboards\Dashboard{$this->dashboardType}";
            $dashboardModal = new $dashboard();

            $period = Cookie::get('period');
            $results = null;

            $companyList = (auth()->user()->isOwner())
                ? Questionnaire::submitted()
                : auth()->user()->submittedQuestionnaires()->with('company');

            if ($this->dashboardType == 5 || $this->dashboardType == 23) {
                $companyList = $companyList->whereYear('from', $period ?? 2022);
            }

            $companyList = $companyList->get()->toArray();

            $results = array_map(
                function ($questionnaire) {
                    return ['id' => $questionnaire['id'], 'name' => $questionnaire['company']['name']];
                },
                $companyList
            );

            $this->period = $period ?? 2022;
            $this->questionnaireList = parseDataForSelect($results, 'id', 'name');

            if (count($this->search['questionnaire']) > 1) {
                $dashboard = "\App\Models\Tenant\Dashboards\Dashboard{$this->dashboardType}MultipleQuestionnaire";
                $dashboardModal = new $dashboard();

                $this->chartData = $dashboardModal->chartForMultipleQuestionnaire($this->search['questionnaire'], $this->management);
            } else {
                $this->chartData = $dashboardModal->chartForOneQuestionnaire($this->search['questionnaire'][0] ?? null);
            }

            return $this->render();
        } else {
            return redirect('dashboards/' . $this->selectedQuestionnaire['id']);
        }
    }
}
