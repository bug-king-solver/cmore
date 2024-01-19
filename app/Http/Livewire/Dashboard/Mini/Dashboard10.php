<?php

namespace App\Http\Livewire\Dashboard\Mini;

use App\Http\Livewire\Traits\Dashboards\DashboardCalcs;
use App\Models\Enums\Taxonomy\AcronymForObjectives;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard10 extends Component
{
    use DashboardCalcs;

    public $questionnaire;
    public $data;
    public $activities;
    public $taxonomy;
    public $rowActive;
    protected $typeId = 10;

    /**
     * Mount the component
     * @param $questionnaires
     */
    public function mount($questionnaires)
    {
        $this->questionnaireIdLists = $questionnaires;
    }

    /**
     * Render the component
     */
    public function render(): View
    {
        $this->questionnaire = $this->questionnaireList->where('id', $this->questionnaireId)->first();

        $this->taxonomy = $this->questionnaire->taxonomy;
        $values = $this->taxonomy->summary;

        $this->data = [
            "volume" => [
                'label' => 'Volume',
                "icon" => "progress",
                "total" => $values['total']['volume'] ?? 0,
                "notEligible" => $values['notEligible']['volume'] ?? 0,
                "eligibleAndNotAligned" => $values['eligibleNotAligned']['volume'] ?? 0,
                "aligned" => $values['eligibleAligned']['volume'] ?? 0,
            ],
            "CAPEX" => [
                'label' => 'CAPEX',
                "icon" => "money-v2",
                "total" => $values['total']['capex'] ?? 0,
                "notEligible" => $values['notEligible']['capex'] ?? 0,
                "eligibleAndNotAligned" => $values['eligibleNotAligned']['capex'] ?? 0,
                "aligned" => $values['eligibleAligned']['capex'] ?? 0,
            ],
            "OPEX" => [
                'label' => 'OPEX',
                "icon" => "money-v2",
                "total" => $values['total']['opex'] ?? 0,
                "notEligible" => $values['notEligible']['opex'] ?? 0,
                "eligibleAndNotAligned" => $values['eligibleNotAligned']['opex'] ?? 0,
                "aligned" => $values['eligibleAligned']['opex'] ?? 0,
            ]
        ];

        $this->activities = [];
        $items = [
            [
                'text' => __('Climate change mitigation'),
                'name' => AcronymForObjectives::CLIMATE_CHANGE_MITIGATION->value,
            ],
            [
                'text' => __('Climate change adaptation'),
                'name' => AcronymForObjectives::CLIMATE_CHANGE_ADAPTATION->value,
            ],
            [
                'text' => __('Sustainable use and protection of water and marine resources'),
                'name' => AcronymForObjectives::WATER_MARINE_RESOURCES->value,
            ],
            [
                'text' => __('Transition to a circular economy'),
                'name' => AcronymForObjectives::CIRCULAR_ECONOMY->value,
            ],
            [
                'text' => __('Pollution prevention and control'),
                'name' => AcronymForObjectives::POLLUTION->value,
            ],
            [
                'text' => __('Protecting and restoring biodiversity and ecosystems'),
                'name' => AcronymForObjectives::BIODIVERSITY_AND_ECOSYSTEMS->value,
            ],

        ];
        foreach ($this->taxonomy->activities as $activity) {
            $type = __("Projected");
            $resume = parseStringToArray($activity->summary);

            if ($resume['volume'] > 0 || $resume['capex'] > 0 || $resume['opex'] > 0) {
                $type = __('Real');
            }
            $itemsTable = [];

            foreach ($items as $index => $item) {
                if (array_key_exists($item['name'], $resume['contribute']['objectives'])) {
                    $dataObjetive = $resume['contribute']['objectives'][$item['name']]['volume'];
                } else {
                    $dataObjetive = ['value' => 0, 'percentage' => 0];
                }

                $itemsTable[] = [
                    'index' => $index + 1,
                    'text' => $item['text'],
                    'value' => $dataObjetive['value'],
                    'percentage' => $dataObjetive['percentage'],
                ];
            }

            $this->activities[] = [
                'id' => $activity->id,
                'sector' => $activity->sector->name,
                'name' => $activity->name,
                'type' => $type,
                'aligned' => $activity->getActivityIsAlignedAttribute(),
                'taxonomy' => $activity->getAlignedTaxonomyPercentageAttribute(),
                'vn' => $resume['volume']['percentage'],
                'items' => $itemsTable,
            ];
        }

        return view('livewire.tenant.dashboard.mini.taxonomy');
    }

    public function showInformationRow($value = null)
    {
        if (isset($this->rowActive) && $this->rowActive === $value) {
            $this->rowActive = null;
        } else {
            $this->rowActive = $value;
        }
        $this->emit('updateRow');
    }
}
