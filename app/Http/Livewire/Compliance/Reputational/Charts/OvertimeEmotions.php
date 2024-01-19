<?php

namespace App\Http\Livewire\Compliance\Reputational\Charts;

use App\Models\Enums\ChartRange;
use App\Models\Tenant\Compliance\Reputational\AnalysisEmotionsDaily;
use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class OvertimeEmotions extends Component
{
    public ?string $elemId;

    public array $labels;

    public array $datasets;

    public ?string $selectedRange;

    public array $selectedRangeArr = [];

    public array $data;

    public $analysisInfo = [];

    protected $listeners = [
        'groupByUpdated' => 'updatedFilters',
    ];

    public function mount(
        ?AnalysisInfo $analysisInfo,
        $selectedRange,
        ?string $elemId = null
    ) {
        $this->analysisInfo = $analysisInfo;
        $this->selectedRange = $selectedRange;
        $this->elemId = $elemId ?? 'chart_emptions_over_time';
        $this->selectedRangeArr = explode(' to ', $this->selectedRange);
        $this->calculateData();
        $this->labels = $this->getLabels();
        $this->datasets = $this->getDatasets();
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.compliance.reputational.charts.overtime-emotions'
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
            $this->emit('updateOEChart', [
                'datasets' => $this->getDatasets(),
                'labels' => $this->getLabels(),
            ]);
        }
    }

    private function calculateData()
    {
        $calcDatas = AnalysisEmotionsDaily::where('ainfo_id', $this->analysisInfo->id)
        ->whereBetween(\DB::raw('DATE(extracted_at)'), $this->selectedRangeArr)
        ->orderBy('extracted_at', 'asc')
        ->get();

        $labels = [];
        $data = [
            'anger' => [],
            'disgust' => [],
            'joy' => [],
            'fear' => [],
            'neutral' => [],
            'sadness' => [],
            'surprise' => [],
        ];

        if ($calcDatas) {
            foreach ($calcDatas as $key => $calcData) {
                if (isset($calcData->data['anger_percent'])) {
                    $labels[] = $calcData->extracted_at->format('Y-m-d');
                    $data['anger'][] = $calcData->data['anger_percent'];
                    $data['disgust'][] = $calcData->data['disgust_percent'];
                    $data['joy'][] = $calcData->data['joy_percent'];
                    $data['fear'][] = $calcData->data['fear_percent'];
                    $data['neutral'][] = $calcData->data['neutral_percent'];
                    $data['sadness'][] = $calcData->data['sadness_percent'];
                    $data['surprise'][] = $calcData->data['surprise_percent'];
                }
            }
        }

        $data['anger'] = addMissingDates($this->selectedRangeArr[1], $this->selectedRangeArr[0], $labels, $data['anger'], true);
        $data['disgust'] = addMissingDates($this->selectedRangeArr[1], $this->selectedRangeArr[0], $labels, $data['disgust'], true);
        $data['joy'] = addMissingDates($this->selectedRangeArr[1], $this->selectedRangeArr[0], $labels, $data['joy'], true);
        $data['fear'] = addMissingDates($this->selectedRangeArr[1], $this->selectedRangeArr[0], $labels, $data['fear'], true);
        $data['neutral'] = addMissingDates($this->selectedRangeArr[1], $this->selectedRangeArr[0], $labels, $data['neutral'], true);
        $data['sadness'] = addMissingDates($this->selectedRangeArr[1], $this->selectedRangeArr[0], $labels, $data['sadness'], true);
        $surpriseData = addMissingDates($this->selectedRangeArr[1], $this->selectedRangeArr[0], $labels, $data['surprise']);
        $data['surprise'] = $surpriseData['data'];

        $this->data['labels'] = $surpriseData['labels'];
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
                'label' => \Lang::get('Anger'),
                'backgroundColor' => '#F44336',
                'borderColor' => '#F44336',
                'borderWidth' => 1,
                'data' => $this->data['data']['anger'],
            ],
            [
                'label' => \Lang::get('Disgust'),
                'backgroundColor' => '#FA9805',
                'borderColor' => '#FA9805',
                'borderWidth' => 1,
                'data' => $this->data['data']['disgust'],
            ],
            [
                'label' => \Lang::get('Fear'),
                'backgroundColor' => '#E20392',
                'borderColor' => '#E20392',
                'borderWidth' => 1,
                'data' => $this->data['data']['fear'],
            ],
            [
                'label' => \Lang::get('Joy'),
                'backgroundColor' => '#02C6A1',
                'borderColor' => '#02C6A1',
                'borderWidth' => 1,
                'data' => $this->data['data']['joy'],
            ],
            [
                'label' => \Lang::get('Neutral'),
                'backgroundColor' => '#21A6E8',
                'borderColor' => '#21A6E8',
                'borderWidth' => 1,
                'data' => $this->data['data']['neutral'],
            ],
            [
                'label' => \Lang::get('Sadness'),
                'backgroundColor' => '#C5A8FF',
                'borderColor' => '#C5A8FF',
                'borderWidth' => 1,
                'data' => $this->data['data']['sadness'],
            ],
            [
                'label' => \Lang::get('Surprise'),
                'backgroundColor' => '#FBC02D',
                'borderColor' => '#FBC02D',
                'borderWidth' => 1,
                'data' => $this->data['data']['surprise'],
            ],
        ];
    }
}
