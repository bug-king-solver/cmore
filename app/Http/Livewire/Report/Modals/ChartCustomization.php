<?php

namespace App\Http\Livewire\Report\Modals;

use App\Models\Chart;
use App\Models\Enums\ChartTypesEnum;
use App\Models\Tenant\Dashboard;
use App\Models\Tenant\Indicator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class ChartCustomization extends ModalComponent
{
    use AuthorizesRequests;

    public Dashboard $dashboard;
    public Chart $chart;
    public int $rowIndex;
    public int $colIndex;

    public $chartsList;

    public string $chartTitlte;
    public string $chartType;
    public array $chartIndicators;
    public array $chartColors;

    /**
     * Modal max width.
     */
    public static function modalMaxWidth(): string
    {
        return '5xl';
    }

    /**
     * Indicates if the modal should close when the escape key is pressed.
     */
    public static function closeModalOnEscape(): bool
    {
        return false;
    }

    /**
     * Indicates if the modal should close when the backdrop is clicked.
     */
    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    /**
     * The validation rules.
     */
    public function rules()
    {
        return [
            'chartTitlte' => 'required',
            'chartType' => 'required',
            'chartIndicators' => 'required',
            'chartColors' => 'required'
        ];
    }

    /**
     * Mount the component.
     */
    public function mount(Dashboard $dashboard, int $rowIndex, int $colIndex)
    {
        $this->dashboard = $dashboard;
        $this->rowIndex = $rowIndex;
        $this->colIndex = $colIndex;

        $this->chartsList = parseKeyValueForSelect(ChartTypesEnum::toArray());

        $dasbhboardLayout = parseStringToArray($dashboard->layout);
        $dasbhboardLayout = $dasbhboardLayout[$rowIndex][$colIndex] ?? null;
        $chartSlug = $dasbhboardLayout['value'] ?? null;

        if (!$dasbhboardLayout || !$chartSlug) {
            $this->closeModal();
            $this->emit('openModal', 'modals.notification', ['data' => [
                'type' => 'error',
                'message' => __('The chart does not exist')
            ]]);
            return;
        }

        $this->chart = Chart::where('slug', $chartSlug)->first();
        $chartStructure = parseStringToArray($this->chart->structure);
        $chartIndicators = parseStringToArray($this->chart->indicators);

        $this->chartType = $dasbhboardLayout['structure']['type'] ?? $chartStructure['type'];
        $this->chartTitlte = $dasbhboardLayout['structure']['title'] ?? $this->chart->name;

        if (empty($dasbhboardLayout['structure']) || $dasbhboardLayout['structure'] === "") {
            $indicators = Indicator::whereIn('id', $chartIndicators)->get();
            $colors = $chartStructure['data']['datasets'][0]['backgroundColor'] ?? [];
            $this->chartIndicators = $indicators->pluck('name', 'id')->toArray();

            foreach ($indicators as $key => $value) {
                $this->chartColors[$value->id] = isset($colors[$key])
                    ? color($colors[$key])
                    : null;
            }
        } else {
            $this->chartIndicators = $dasbhboardLayout['structure']['data']['datasets'][0]['label'];
            $this->chartColors = $dasbhboardLayout['structure']['data']['datasets'][0]['backgroundColor'];
        }
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.tenant.dynamic-dashboard.modals.chart-customization');
    }

    /**
     * Save the chart.
     */
    public function save()
    {
        $data = $this->validate();

        $structure = parseStringToArray($this->chart->structure);

        $structure['type'] = $data['chartType'];
        $structure['title'] = $data['chartTitlte'];
        $structure['data']['datasets'][0]['backgroundColor'] = $data['chartColors'];
        $structure['data']['datasets'][0]['label'] = $data['chartIndicators'];

        $dashboardLayout = parseStringToArray($this->dashboard->layout);

        $dashboardLayout[$this->rowIndex][$this->colIndex] = array_merge(
            $dashboardLayout[$this->rowIndex][$this->colIndex],
            [
                'structure' => $structure,
            ]
        );

        $this->dashboard->layout = json_encode($dashboardLayout);
        $this->dashboard->update();
        $this->closeModal();
        $this->emit('refresh');
        $this->dispatchBrowserEvent(
            'dashboardChartSaved',
            [
                'structure' => $structure,
                'rowIndex' => $this->rowIndex,
                'colIndex' => $this->colIndex,
            ]
        );
    }
}
