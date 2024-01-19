<?php

namespace App\Http\Livewire\Dashboard\Charts;

use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;
use App\Models\Tenant\Questionnaire;

class BarFilter extends Component
{
    public ?string $elemId;

    public array $chartData;
    
    public array $datasets;

    public array $optionsList;

    public ?string $selectedOption;

    public array $data;

    public $filters;

    public $questionnaire = [];

    public array $labels;

    public array $subPoint = [];

    public array $subInfo = [];

    public string $title;

    protected $listeners = [
        'updatedFilters' => 'updatedFilters',
    ];

    public function mount(
        ?string $title,
        ?array $chartData,
        ?string $elemId = null,
        ?array $filters = null
    ) {
        $this->title = $title;
        $this->elemId = $elemId;
        $this->chartData = $chartData;
        $this->filters = $filters;
        $this->optionsList = $this->chartData['optionsList'] ?? [];

        if (! $this->filters) {
            if ($this->optionsList) {
                $this->filters['filterby'] = key($this->optionsList);
            }
        }
        $this->calculateData();
        $this->labels = $this->getLabels();
        $this->datasets = $this->getDatasets();
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.dashboard.charts.barfilter'
        );
    }

    public function updatedFilters()
    {
        $this->filters();
    }

    public function filters()
    {
        $this->calculateData();
        $this->emit('updatePollutionChart'.$this->elemId, [
            'datasets' => $this->getDatasets(),
            'labels' => $this->getLabels(),
        ]);

    }

    private function calculateData()
    {
        $filterbyKey = $this->filters['filterby'];
        
        $this->data['labels'] = [$this->chartData['baseYears'][$filterbyKey], $this->chartData['reportPeriodYear']];
        $this->data['data'] = [$this->chartData['baseYearValues'][$filterbyKey] ?? 0, $this->chartData['reportYearValues'][$filterbyKey] ?? 0];
    }

    private function getLabels()
    {
        $labeles = $this->data['labels'];
       
        $this->subPoint = [
            [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: '. $labeles[0]) ],
            [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: '. $labeles[1]) ]
        ];
        
        return $labeles;
    }

    private function getDatasets()
    {
        $this->subInfo = [
            ['value' => array_sum($this->data['data']), 'unit' => $this->chartData['unit']],
        ];
        return $this->data['data'];
    }
}
