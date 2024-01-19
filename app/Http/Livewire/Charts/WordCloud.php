<?php

namespace App\Http\Livewire\Charts;

use App\Models\Tenant\Analysis\TermsFrequency;
use Illuminate\View\View;
use Livewire\Component;

class WordCloud extends Component
{
    public ?string $elemId;

    public ?string $shape;

    public ?string $backgroundColor;

    public ?string $color;

    public ?string $from;

    public ?string $to;

    public ?array $list;

    public array $datasets;

    protected $listeners = [
        'filtersChanged' => '$refresh',
    ];

    public function mount(
        ?string $elemId = null,
        ?string $shape = null,
        ?string $backgroundColor = null,
        ?string $color = null,
        ?string $from = null,
        ?string $to = null,
        ?array $list = null
    ) {
        $this->elemId = $elemId ?? 'word_cloud';
        $this->shape = $shape ?? 'ellipticity';
        $this->backgroundColor = $backgroundColor ?? color(6);
        $this->color = $color ?? color(4);
        $this->from = $from;
        $this->to = $to;
        $this->list = $list ?? TermsFrequency::wordCloud($this->from, $this->to);
        $this->datasets = $this->getListWords();
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.charts.word-cloud'
        );
    }

    public function updatedFilters()
    {
        $this->emit('updateChart', [
            'datasets' => $this->getDatasets(),
            'labels' => $this->getLabels(),
        ]);
    }

    private function getListWords()
    {
        $words = ['Emissions', 'Information', 'GRI', 'Artificial', 'intelligence', 'Climate', 'Social', 'Governance', 'Business', 'Analysis'];

        $data = array_map(function ($ar) {
            return [$ar, rand(10, 50)];
        }, $words);

        return $data;
    }
}
