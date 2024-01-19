<?php

namespace App\Http\Livewire\Compliance\Reputational\Charts;

use App\Models\Enums\ChartRange;
use App\Models\Tenant\Compliance\Reputational\AnalysisEmotionsDaily;
use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use Carbon\Carbon;
use Livewire\Component;

class EmotionAverage extends Component
{
    public ?string $elemId;

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
        $this->elemId = $elemId ?? 'chart_average_emotion';
        $this->selectedRangeArr = explode(' to ', $this->selectedRange);
        $this->calculateData();
        $this->labels = $this->getLabels();
        $this->datasets = $this->getDatasets();
    }

    public function render()
    {
        return view('livewire.tenant.compliance.reputational.charts.emotion-average');
    }

    public function filters()
    {
        $this->selectedRangeArr = explode(' to ', $this->selectedRange);
        if (count($this->selectedRangeArr) == 2) {
            $this->calculateData();
            $this->emit('updateEAChart', [
                'datasets' => $this->getDatasets(),
                'labels' => $this->getLabels(),
            ]);
        }
    }

    private function calculateData()
    {
        $calcDatas = AnalysisEmotionsDaily::where('ainfo_id', $this->analysisInfo->id)->whereBetween(\DB::raw('DATE(extracted_at)'), $this->selectedRangeArr)->orderBy('extracted_at', 'asc')->pluck('data');
        $startDate = Carbon::parse($this->selectedRangeArr[0]);
        $endDate = Carbon::parse($this->selectedRangeArr[1]);
        $totalDays = $startDate->diffInDays($endDate);
        $data = [];
        if ($calcDatas) {
            $data[] = calculatePercentage($calcDatas->sum('anger_percent'), $totalDays);
            $data[] = calculatePercentage($calcDatas->sum('disgust_percent'), $totalDays);
            $data[] = calculatePercentage($calcDatas->sum('joy_percent'), $totalDays);
            $data[] = calculatePercentage($calcDatas->sum('fear_percent'), $totalDays);
            $data[] = calculatePercentage($calcDatas->sum('neutral_percent'), $totalDays);
            $data[] = calculatePercentage($calcDatas->sum('sadness_percent'), $totalDays);
            $data[] = calculatePercentage($calcDatas->sum('surprise_percent'), $totalDays);
        }
        $this->data['data'] = $data;
    }

    private function getLabels()
    {
        return [
            \Lang::get('Anger'),
            \Lang::get('Disgust'),
            \Lang::get('Joy'),
            \Lang::get('Fear'),
            \Lang::get('Neutral'),
            \Lang::get('Sadness'),
            \Lang::get('Surprise'),
        ];
    }

    private function getDatasets()
    {
        return [
            [
                'label' => '',
                'data' => $this->data['data'],
                'fill' => true,
                'backgroundColor' => 'rgba(232, 99, 33, 0.2)',
                'borderColor' => '#E86321',
            ],
        ];
    }
}
