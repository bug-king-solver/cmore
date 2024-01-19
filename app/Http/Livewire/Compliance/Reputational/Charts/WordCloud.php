<?php

namespace App\Http\Livewire\Compliance\Reputational\Charts;

use App\Models\Enums\ChartRange;
use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use App\Models\Tenant\Compliance\Reputational\AnalysisKeywordsFrequencyDaily;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class WordCloud extends Component
{
    public ?string $elemId;

    public array $datasets;

    public ?string $selectedRange;
    
    public array $selectedRangeArr = [];
    
    public array $data;

    public $analysisInfo = [];

    protected $listeners = [
        'filtersChanged' => '$refresh',
    ];

    public function mount(
        ?AnalysisInfo $analysisInfo,
        $selectedRange,
        ?string $elemId = null
    ) {
        $this->analysisInfo = $analysisInfo;
        $this->selectedRange = $selectedRange;
        $this->elemId = $elemId ?? 'word_cloud';
        $this->selectedRangeArr = explode(' to ', $this->selectedRange);
        $this->calculateData();
        $this->datasets = $this->data;
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.compliance.reputational.charts.word-cloud'
        );
    }

    public function filters()
    {
        $this->selectedRangeArr = explode(' to ', $this->selectedRange);
        if (count($this->selectedRangeArr) == 2) {
            $this->calculateData();
            $this->emit('updateWc', [
                'datasets' => $this->data,
            ]);
        }
    }

    private function calculateData()
    {
        $calcDatas = AnalysisKeywordsFrequencyDaily::where('ainfo_id', $this->analysisInfo->id)
            ->whereBetween(\DB::raw('DATE(extracted_at)'), $this->selectedRangeArr)
            ->orderBy('extracted_at', 'asc')
            ->pluck('data');

        $data = [];
        foreach ($calcDatas as $key => $calcData) {
            if (isset($calcData['kw_weights']) && ! empty($calcData['kw_weights'])) {
                foreach ($calcData['kw_weights'] as $word => $wordWeight) {
                    if (isset($data[$word])) {
                        $data[$word] = $data[$word] + $wordWeight;
                    } else {
                        $data[$word] = $wordWeight;
                    }
                }
            }
        }
        $returnData = array_map(function ($word, $wht) {
            return [$word, $wht];
        }, array_keys($data), array_values($data));
        $this->data = $returnData;
    }
}
