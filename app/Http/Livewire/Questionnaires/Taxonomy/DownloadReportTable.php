<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy;

use App\Models\Enums\Taxonomy\AcronymForObjectives;
use App\Models\Enums\Taxonomy\ShortNameForObjectives;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use App\Models\Tenant\Questionnaires\Taxonomy\TaxonomyActivities;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class DownloadReportTable extends Component
{
    use AuthorizesRequests;

    public int|Questionnaire $questionnaire;
    public Taxonomy $taxonomy;
    public $activities;
    public $objectives;
    public $businessVolume;

    public $proportionEligibleAndAligned;


    public $volumeOptionsToShow = [];
    public $valueToShow = 1;
    public $textToShow;
    public $columnToIndex;

    protected $listeners = [
        'taxonomyUpdated' => '$refresh',
    ];

    /**
     * Mount the component.
     *
     * @param  \App\Models\Tenant\Questionnaire  $questionnaire
     * @return void
     */
    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
        $this->authorize("questionnaires.view.{$this->questionnaire->id}");
        $this->taxonomy = $this->questionnaire->taxonomy()->with('activities.sector')->first();

        $this->businessVolume = parseStringToArray($this->taxonomy->summary);

        $this->volumeOptionsToShow = parseKeyValueForSelect([
            1 => __('Business volume'),
            2 => __('CAPEX'),
            3 => __('OPEX'),
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        $this->proportionEligibleAndAligned = 0;
        $businessVolume = $this->businessVolume;

        $this->textToShow = collect($this->volumeOptionsToShow)->filter(function ($item, $key) {
            return $item['id'] == $this->valueToShow;
        })->first()['title'];

        switch ($this->valueToShow) {
            case 1:
                $columnToIndex = "volume";
                break;
            case 2:
                $columnToIndex = "capex";
                break;
            case 3:
                $columnToIndex = "opex";
                break;
            default:
                $columnToIndex = "volume";
                break;
        }
        $this->columnToIndex = $columnToIndex;

        $objectives = ShortNameForObjectives::toArray();
        $acronymObjetiveCCM = AcronymForObjectives::fromValue('Mitigação das alterações climáticas');

        $this->activities = $this->taxonomy->activities->groupBy('GetReportTableIndex')
            ->map(function ($activities, $key) use ($objectives, $columnToIndex, $acronymObjetiveCCM) {

                $data = $activities->map(function ($item) use ($objectives, $columnToIndex, $acronymObjetiveCCM) {

                    $contribute = $item->filterContributeObjectivesData();
                    $dnsh = $item->filterNpsObjectivesData();
                    $resume = parseStringToArray($item->summary);

                    $data = [
                        'name' => $item->name,
                        'code' => $item->code,
                        'volume' => formatToCurrency($resume[$columnToIndex]['value'], false, '€'),
                        'proportion' => $resume[$columnToIndex]['percentage'] . "%",
                    ];

                    foreach ($objectives as $objective) {
                        $objective = ShortNameForObjectives::fromValue($objective);
                        $data[$objective] = "NA";
                    }

                    foreach ($objectives as $objective) {
                        $objective = ShortNameForObjectives::fromValue($objective);
                        $data["NPS-" . $objective] = "NA";
                    }

                    foreach ($contribute as $objectiveArr) {
                        $objective = ShortNameForObjectives::fromValue(translateJson($objectiveArr['name']));
                        $data[$objective] = $objectiveArr['percentage'] . "%";
                    }

                    foreach ($dnsh as $npsArr) {
                        $name = translateJson($npsArr['name']);
                        if ($name !== null) {
                            $objective = ShortNameForObjectives::fromValue($name);
                            $data["NPS-" . $objective] = $npsArr['verified'] === 1
                                ? "Y"
                                : "N";
                        }
                    }

                    if ($this->taxonomy->safeguard['verified'] === 1) {
                        $data['safeguard'] = "Y";
                    } else {
                        $data['safeguard'] = "N";
                    }

                    // $data['proporção_do_volume_de_negócios_alinhada'] = $item->AlignedTaxonomyPercentage . "%";
                    $data['proporção_do_volume_de_negócios_alinhada'] = $data['proportion'];
                    $data['proporção_do_volume_de_negócios_alinhada_last'] = "NA";

                    $data['activity_cap'] = "";
                    $data['activity_tra'] = "";
                    if (isset($resume['contribute']['objectives'][$acronymObjetiveCCM]) && $resume['contribute']['objectives'][$acronymObjetiveCCM]['transition']['percentage'] === 100) {
                        $data['activity_tra'] = 'T';
                    } elseif (isset($resume['contribute']['objectives'][$acronymObjetiveCCM]) && $resume['contribute']['objectives'][$acronymObjetiveCCM]['enabling']['percentage'] === 100) {
                        $data['activity_cap'] = 'C';
                    }

                    return $data;
                });

                return [
                    'table' => $key,
                    'data' => $data
                ];
            })->sortBy('table')
            ->toArray();


        if (isset($this->activities[1]['data'])) {
            foreach ($this->activities[1]['data'] as $a) {
                $temp = str_replace("%", "", $a['proportion']);
                $this->proportionEligibleAndAligned += (int)$temp;
            }
        }

        $this->proportionEligibleAndAligned = $this->proportionEligibleAndAligned . "%";

        if (!isset($this->activities[2])) {
            $this->activities[2] = [
                'table' => 2,
                'data' => []
            ];
        }

        $this->activities[3] = [
            'table' => 3,
            'data' => [
                [
                    'name' => __(':text of activities eligible for taxonomy but not sustainable from an environmental point of view ( A.2 ) ', ['text' => $this->textToShow]),
                    'code' => "",
                    'eligible_not_aligned' => formatToCurrency($businessVolume['eligibleNotAligned'][$columnToIndex]['value'], false, '€'),
                    'proportion' => $this->taxonomy->summary['eligibleNotAligned'][$columnToIndex]['percentage'] . "%",
                ],
                [
                    'name' => __('Total A1 + A2'),
                    'code' => "",
                    'eligible' => formatToCurrency($businessVolume['eligible'][$columnToIndex]['value'], false, '€'),
                    'proportion' => $this->taxonomy->summary['eligible'][$columnToIndex]['percentage'] . "%",
                ],
                [
                    'name' => __(':text of activities not eligible for taxonomy', ['text' => $this->textToShow]),
                    'code' => "",
                    'not_eligible' => formatToCurrency($businessVolume['notEligible'][$columnToIndex]['value'], false, '€'),
                    'proportion' => $this->taxonomy->summary['notEligible'][$columnToIndex]['percentage'] . "%",
                ],
                [
                    'name' => __('Total A + B'),
                    'code' => "",
                    'total' => formatToCurrency($businessVolume['total'][$columnToIndex]['value'], false, '€'),
                    'proportion' => "100 %",
                ],
            ]
        ];

        $this->objectives['cs'] = array_keys($objectives);
        $this->objectives['dnsh'] = array_keys($objectives);

        return view('livewire.tenant.taxonomy.report.table');
    }
}
