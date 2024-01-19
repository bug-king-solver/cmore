<?php

namespace App\Http\Livewire\Charts;

use App\Models\Tenant\Analysis\TermsFrequency;
use Illuminate\View\View;
use Livewire\Component;

class EmotionAverage extends Component
{
    public ?string $elemId;

    public array $terms;

    public ?array $filters;

    public ?string $groupBy;

    public string $from;

    public string $to;

    public array $labels;

    public array $datasets;

    protected $listeners = [
        'groupByUpdated' => 'filtersChanged',
    ];

    public function mount(
        $from,
        $to,
        ?string $elemId = null
    ) {
        $this->from = $from;
        $this->to = $to;
        $this->elemId = $elemId ?? 'chart_average_emotion';
        $this->labels = $labels ?? ['Happy', 'Sad', "Don't Care", 'Annoyed', 'Angry', 'Amused', 'Inspired'];
        $this->datasets = $this->getData();
    }

    public function render()
    {
        return view('livewire.tenant.charts.emotion-average');
    }

    public function updatedFilters()
    {
        $this->emit('updateChart', [
            'datasets' => $this->getDatasets(),
            'labels' => $this->getLabels(),
        ]);
    }

    private function getData()
    {
        $data = [];
        for ($i = 0; $i < count($this->labels); $i++) {
            $data[] = rand(10, 100);
        }

        return $data;
    }
}
