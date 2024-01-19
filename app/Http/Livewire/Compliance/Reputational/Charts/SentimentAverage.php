<?php

namespace App\Http\Livewire\Compliance\Reputational\Charts;

use App\Models\Enums\ChartRange;
use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use App\Models\Tenant\Compliance\Reputational\AnalysisSentimentsDaily;
use Carbon\Carbon;
use Livewire\Component;

class SentimentAverage extends Component
{
    public ?string $elemId;

    public array $datasets;

    public ?string $selectedRange;

    public array $selectedRangeArr = [];

    public $analysisInfo = [];

    public function mount(
        ?AnalysisInfo $analysisInfo,
        $selectedRange,
        ?string $elemId = null
    ) {
        $this->analysisInfo = $analysisInfo;
        $this->selectedRange = $selectedRange;
        $this->selectedRangeArr = explode(' to ', $this->selectedRange);
        $this->elemId = $elemId ?? 'chart_avg_sentiment';
        $this->datasets = $this->averageSentiment();
    }

    public function render()
    {
        return view('livewire.tenant.compliance.reputational.charts.sentiment-average');
    }

    public function filters()
    {
        $this->selectedRangeArr = explode(' to ', $this->selectedRange);
        if (count($this->selectedRangeArr) == 2) {
            $this->emit('updateSAChart', [
                'datasets' => $this->averageSentiment(),
            ]);
        }
    }

    private function averageSentiment()
    {
        $calcDatas = AnalysisSentimentsDaily::where('ainfo_id', $this->analysisInfo->id)->whereBetween(\DB::raw('DATE(extracted_at)'), $this->selectedRangeArr)->orderBy('extracted_at', 'asc')->pluck('data');
        $averageSentiment = 0;
        if (count($calcDatas) > 0) {
            $positive = $calcDatas->sum('positive_count');
            $negative = $calcDatas->sum('negative_count');
            $neutral = $calcDatas->sum('neutral_count');
            $averageSentiment = ($negative * 1 + $neutral * 2 + $neutral *3) / ($negative + $neutral + $neutral) -1;
        }

        return [$averageSentiment, (2 - $averageSentiment)];
    }
}
