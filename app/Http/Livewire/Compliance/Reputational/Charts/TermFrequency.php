<?php

namespace App\Http\Livewire\Compliance\Reputational\Charts;

use App\Models\Enums\ChartRange;
use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use App\Models\Tenant\Compliance\Reputational\AnalysisKeywordsFrequencyDaily;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class TermFrequency extends Component
{
    public ?string $elemId;

    public array $terms;

    public array $labels;

    public array $datasets;

    public ?string $selectedRange;

    public array $selectedRangeArr = [];

    public array $data;

    public $filters;

    public $analysisInfo = [];

    protected $listeners = [
        'groupByUpdated' => 'updatedFilters',
    ];

    public function mount(
        ?AnalysisInfo $analysisInfo,
        $selectedRange,
        ?string $elemId = null,
        ?array $filters = null
    ) {
        $this->analysisInfo = $analysisInfo;
        $this->selectedRange = $selectedRange;
        $this->elemId = $elemId ?? 'chart_term_frequency';
        $this->selectedRangeArr = explode(' to ', $this->selectedRange);
        $this->filters = $filters;
        $this->terms = $this->getWordList();
        if (! $this->filters) {
            if ($this->terms) {
                $this->filters['term'] = key($this->terms);
            }
        }
        $this->calculateData();
        $this->labels = $this->getLabels();
        $this->datasets = $this->getDatasets();
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.compliance.reputational.charts.term-frequency'
        );
    }

    public function updatedFilters()
    {
        $this->filters();
    }

    public function filters()
    {
        $this->selectedRangeArr = explode(' to ', $this->selectedRange);
        if (count($this->selectedRangeArr) == 2) {
            $this->calculateData();
            $this->emit('updateTFChart', [
                'datasets' => $this->getDatasets(),
                'labels' => $this->getLabels(),
            ]);
        }
    }

    private function getLabels()
    {
        return $this->data['labels'];
    }

    private function getDatasets()
    {
        return [
            [
                'label' => $this->filters['term'] ?? '',
                'backgroundColor' => '#E86321',
                'borderColor' => '#E86321',
                'borderWidth' => 1,
                'data' => $this->data['data'],
            ],
        ];
    }

    private function calculateData()
    {
        $labels = [];
        $data = [];
        if (isset($this->filters['term']) && $this->filters['term'] != '') {
            $calcDatas = AnalysisKeywordsFrequencyDaily::where('ainfo_id', $this->analysisInfo->id)
                ->whereBetween(\DB::raw('DATE(extracted_at)'), $this->selectedRangeArr)
                ->whereNotNull('data->kw_weights->' . $this->filters['term'])
                ->orderBy('extracted_at', 'asc')
                ->get();

            if ($calcDatas) {
                foreach ($calcDatas as $key => $calcData) {
                    $labels[] = $calcData->extracted_at->format('Y-m-d');
                    $data[] = $calcData->data['kw_weights'][$this->filters['term']];
                }
            }
        }
        $finalData = addMissingDates($this->selectedRangeArr[1], $this->selectedRangeArr[0], $labels, $data);
        $this->data['labels'] = $finalData['labels'];
        $this->data['data'] = $finalData['data'];
    }

    private function getWordList()
    {
        $calcDatas = AnalysisKeywordsFrequencyDaily::where('ainfo_id', $this->analysisInfo->id)
            ->whereBetween(\DB::raw('DATE(extracted_at)'), $this->selectedRangeArr)
            ->orderBy('extracted_at', 'asc')
            ->pluck('data');

        $data = [];
        foreach ($calcDatas as $key => $calcData) {
            foreach ($calcData['kw_weights'] as $word => $wordWeight) {
                if (! isset($data[$word])) {
                    $data[$word] = $word;
                }
            }
        }

        return $data;
    }
}
