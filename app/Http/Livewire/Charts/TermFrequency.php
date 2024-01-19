<?php

namespace App\Http\Livewire\Charts;

use App\Models\Tenant\Analysis\TermsFrequency;
use Illuminate\View\View;
use Livewire\Component;

class TermFrequency extends Component
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
        ?string $elemId = null,
        ?array $filters = null
    ) {
        $this->from = $from;
        $this->to = $to;
        $this->elemId = $elemId ?? 'term_frequency';
        $this->terms = TermsFrequency::termsBetweenDates($this->from, $this->to)->pluck('term')->toArray();
        $this->filters = $filters;

        if (! $this->filters) {
            $this->filters = ['groupBy' => 'day'];

            if ($this->terms) {
                $this->filters['term'] = 0;
            }
        }

        $this->labels = $this->getLabels();
        $this->datasets = $this->getDatasets();
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.charts.term-frequency'
        );
    }

    public function updatedFilters()
    {
        $this->emit('updateChart', [
            'datasets' => $this->getDatasets(),
            'labels' => $this->getLabels(),
        ]);
    }

    private function getLabels()
    {
        $labels = [];
        for ($i = 0; $i < 4; $i++) {
            $labels[] = now()->subMonths($i)->format('M');
        }

        return $labels;
    }

    private function getDatasets()
    {
        return [
            [
                'label' => 'Logged In',
                'backgroundColor' => color(5),
                'borderColor' => color(5),
                'data' => $this->getData(),
            ],
        ];
    }

    private function getData()
    {
        $data = [];
        for ($i = 0; $i < count($this->getLabels()); $i++) {
            $data[] = rand(10, 100);
        }

        return $data;
    }
}
