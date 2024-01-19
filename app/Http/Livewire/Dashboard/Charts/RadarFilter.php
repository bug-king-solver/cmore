<?php

namespace App\Http\Livewire\Dashboard\Charts;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Category;
use App\Models\Tenant\InternalTag;
use App\Models\Tenant\Question;
use App\Models\Tenant\Questionnaire;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class RadarFilter extends Component
{
    public ?string $elemId;

    public array $optionsList1;

    public array $optionsList2;

    public array $data;

    public array $dataFiltered;

    public array $filterLists;

    public int|Questionnaire $questionnaire;

    public array $labels;

    public string $title;

    public string|null $radarFilterOpt1 = null;

    public string|null $radarFilterOpt2 = null;

    public array $configs = [];

    protected $listeners = [
        'updatedFilters' => 'updatedFilters',
    ];

    /** @var array */
    public array $chartData;

    /**
     * @param Questionnaire $questionnaire
     * @param string $title
     * @param string|null $elemId
     * @param array|null $filterLists
     * @param array|null $configs
     * @return void
     */
    public function mount(
        Questionnaire $questionnaire,
        string $title,
        string $elemId = null,
        ?array $filterLists = [],
        ?array $configs = []
    ) {
        $this->questionnaire = $questionnaire;
        $this->title = $title;
        $this->elemId = $elemId;
        $this->filterLists = $filterLists;
        $this->configs = $configs;

        if (!empty($this->filterLists)) {
            $this->optionsList1 = InternalTag::whereIn('slug', $this->filterLists['options_1'])
                ->orderBy('name', 'asc')
                ->pluck('name', 'slug')->toArray();

            $this->optionsList2 = InternalTag::whereIn('slug', $this->filterLists['options_2'])
                ->orderBy('id', 'asc')
                ->pluck('name', 'slug')->toArray();

            $this->radarFilterOpt1 = array_key_first($this->optionsList1) ?? null;
            $this->radarFilterOpt2 = array_key_first($this->optionsList2) ?? null;
        }

        $this->calculateData();
    }

    /**
     * @return View
     */
    public function render(): View
    {
        if (empty($this->data)) {
            return view('livewire.tenant.dashboard.charts.no-data');
        }

        return view('livewire.tenant.dashboard.charts.radarfilter');
    }


    /**
     * @return void
     */
    public function updatedRadarFilterOpt1(): void
    {
        $this->filter();
    }

    /**
     * @return void
     */
    public function updatedRadarFilterOpt2(): void
    {
        $this->filter();
    }

    /**
     * @return void
     */
    public function filter()
    {
        $this->calculateData();

        $this->emit('updateRadarChart', [
            'elemId' => $this->elemId,
            'datasets' => $this->data['data'],
            'labels' => $this->data['labels'],
        ]);
    }

    /**
     * Calculate data for chart
     * @return void
     */
    private function calculateData(): void
    {
        $configs = $this->configs;
        $dashboardData = $this->questionnaire->dashboardData;

        if(empty($dashboardData)) {
            $this->data = [];
            return;
        }

        $allTags = InternalTag::pluck('name', 'slug')->toArray();

        if (($configs['type'] ?? 'simple') == 'simple') {
            $this->data = createQuestionnaireSpiderChartWithCategories($dashboardData['simple']);
            foreach ($this->data['labels'] as $key => &$label) {
                $label = $allTags[$label];
            }


            foreach ($this->data['data'] as $key => &$data) {
                $data['label'] = $allTags[$data['label']] ?? $data['label'];
            }

            return;
        } else {
            $data = $dashboardData['complete'][$this->radarFilterOpt1][$this->radarFilterOpt2];
            $this->data = createQuestionnaireSpiderChartWithCategories($data);
            foreach ($this->data['labels'] as $key => &$label) {
                $label = $allTags[$label];
            }
            foreach ($this->data['data'] as $key => &$data) {
                $data['label'] = $allTags[$data['label']];
            }
            return;
        }
    }
}
