<?php

namespace App\Http\Livewire\Compliance\Reputational\Charts;

use App\Models\Enums\ChartRange;
use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use App\Models\Tenant\Compliance\Reputational\AnalysisSentimentsDaily;
use Carbon\Carbon;
use Livewire\Component;

class OvertimeSentiment extends Component
{
    public ?string $elemId;

    public array $terms;

    public array $labels;

    public array $datasets;

    public ?string $selectedRange;
    
    public array $selectedRangeArr = [];

    public array $data;

    public $analysisInfo = [];

    public function mount(
        ?AnalysisInfo $analysisInfo,
        $selectedRange,
        ?string $elemId = null
    ) {
        $this->analysisInfo = $analysisInfo;
        $this->selectedRange = $selectedRange;
        $this->elemId = $elemId ?? 'chart_sentiment_over_time';
        $this->selectedRangeArr = explode(' to ', $this->selectedRange);
        $this->calculateData();
        $this->labels = $this->getLabels();
        $this->datasets = $this->getDatasets();
    }

    public function render()
    {
        return view('livewire.tenant.compliance.reputational.charts.overtime-sentiment');
    }

    public function filters()
    {
        $this->selectedRangeArr = explode(' to ', $this->selectedRange);
        if (count($this->selectedRangeArr) == 2) {
            $this->calculateData();
            $this->emit('updateOSChart', [
                'datasets' => $this->getDatasets(),
                'labels' => $this->getLabels(),
            ]);
        }
    }

    private function calculateData()
    {
        $calcDatas = AnalysisSentimentsDaily::where('ainfo_id', $this->analysisInfo->id)->whereBetween(\DB::raw('DATE(extracted_at)'), $this->selectedRangeArr)->orderBy('extracted_at', 'asc')->get();
        $labels = [];
        $data = [
            'positive' => [],
            'negative' => [],
            'neutral' => [],
        ];

        if ($calcDatas) {
            foreach ($calcDatas as $key=>$calcData) {
                $labels[] = $calcData->extracted_at->format('Y-m-d');
                $data['positive'][] = $calcData->data['positive_count'];
                $data['negative'][] = $calcData->data['negative_count'];
                $data['neutral'][] = $calcData->data['neutral_count'];
            }
        }

        $data['positive'] = addMissingDates($this->selectedRangeArr[1], $this->selectedRangeArr[0], $labels, $data['positive'], true);
        $data['negative'] = addMissingDates($this->selectedRangeArr[1], $this->selectedRangeArr[0], $labels, $data['negative'], true);
        $neutralData = addMissingDates($this->selectedRangeArr[1], $this->selectedRangeArr[0], $labels, $data['neutral']);
        $data['neutral'] = $neutralData['data'];
        $this->data['labels'] = $neutralData['labels'];
        $this->data['data'] = $data;
    }

    private function getLabels()
    {
        return $this->data['labels'];
    }

    private function getDatasets()
    {
        return [
            [
                'label' => \Lang::get('Negative'),
                'backgroundColor' => '#F44336',
                'borderColor' => '#F44336',
                'borderWidth' => 1,
                'data' => $this->data['data']['negative'],
            ],
            [
                'label' => \Lang::get('Neutral'),
                'backgroundColor' => '#19A0FD',
                'borderColor' => '#19A0FD',
                'borderWidth' => 1,
                'data' => $this->data['data']['neutral'],
            ],
            [
                'label' => \Lang::get('Positive'),
                'backgroundColor' => '#99CA3C',
                'borderColor' => '#99CA3C',
                'borderWidth' => 1,
                'data' => $this->data['data']['positive'],
            ],
        ];
    }
}
