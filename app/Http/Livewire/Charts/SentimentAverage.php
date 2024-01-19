<?php

namespace App\Http\Livewire\Charts;

use Livewire\Component;

class SentimentAverage extends Component
{
    public string $avg_sentiment;

    public string $from;

    public string $to;

    public function mount(
        $from,
        $to
    ) {
        $this->from = $from;
        $this->to = $to;
        $this->avg_sentiment = $this->averageSentiment();
    }

    public function render()
    {
        return view('livewire.tenant.charts.sentiment-average');
    }

    public function updatedFilters()
    {
        $this->emit('updateChart', [
            'datasets' => $this->getDatasets(),
            'labels' => $this->getLabels(),
        ]);
    }

    private function averageSentiment()
    {
        $finalData = [
            'positive' => rand(10, 100),
            'negative' => rand(10, 100),
            'neutral' => rand(10, 100),
        ];

        return array_search(max($finalData), $finalData);
    }
}
