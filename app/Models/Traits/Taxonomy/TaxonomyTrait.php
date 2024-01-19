<?php

namespace App\Models\Traits\Taxonomy;

use App\Models\Tenant\BusinessActivities;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use DB;
use Illuminate\Support\Str;

trait TaxonomyTrait
{
    protected Taxonomy | int $taxonomy;

    /**
     * Calculate the taxonomy values based on the given activity.
     *
     * @param \App\Models\Tenant\Taxonomy $taxonomy
     * @return void
     */
    public static function calcTaxonomyValues($taxonomy)
    {
        $data = $taxonomy->summary;
        $defaultArray = [
            "value" => 0,
            "percentage" => 0,
        ];

        $activities = [
            'volume' => $defaultArray,
            'capex' => $defaultArray,
            'opex' => $defaultArray,
        ];

        // reset the values
        $data['notEligible'] = $activities;
        $data['eligible'] = $activities;
        $data['eligibleAligned'] = $activities;
        $data['eligibleNotAligned'] = $activities;

        $safeguard = parseStringToArray($taxonomy->safeguard);

        if (!empty($safeguard) && $safeguard['verified'] !== null) {
            $alignedPercentage = $taxonomy->activities->map(function ($activity) use (&$data) {
                $constributeSubstantital = parseStringToArray($activity->contribute);
                $percentage = 0;

                if ($activity->activityIsAligned === false || $activity->npsIsAnswered === false) {
                    return false;
                }

                foreach ($constributeSubstantital['data'] ?? [] as $objective) {
                    if ($objective['verified'] === 1) {
                        $temp = isset($objective['percentage'])
                            ? (int)$objective['percentage']
                            : 0;
                        $percentage += $temp;
                    }
                }

                if ($percentage > 0) {
                    $volume = $activity->summary['volume']['value'] ?? 0;
                    $capex = $activity->summary['capex']['value'] ?? 0;
                    $opex = $activity->summary['opex']['value'] ?? 0;

                    $data['eligibleAligned']['volume']['value'] += $volume * ($percentage / 100);
                    $data['eligibleAligned']['capex']['value'] += $capex * ($percentage / 100);
                    $data['eligibleAligned']['opex']['value'] += $opex * ($percentage / 100);
                    return [
                        'activityId' => $activity->id,
                        'percentage' => $percentage,
                    ];
                }
            });

            $taxonomy->activities->map(function ($activity) use (&$data, $alignedPercentage) {

                if ($activity->npsIsAnswered === false) {
                    return;
                }

                $constributeSubstantital = parseStringToArray($activity->contribute);
                $percentage = 0;

                foreach ($constributeSubstantital['data'] ?? [] as $objective) {
                    $temp = 0;
                    $objectivePercentage = $objective['percentage'] ?? 0;

                    if ($activity->activityIsAligned === true) {
                        if ($objective['verified'] === 0) {
                            $temp = (int)$objectivePercentage;
                        }
                    } else {
                        $temp = 100;
                    }

                    $percentage += $temp;
                }

                if ($percentage > 100) {
                    $percentage = 100;
                }

                $alignedPercentageFiltered = $alignedPercentage->filter(function ($item) use ($activity) {
                    if (!$item) {
                        return false;
                    }
                    return $item['activityId'] === $activity->id;
                })->first();

                if ($percentage === 0 && $alignedPercentageFiltered) {
                    $percentage = 100 - $alignedPercentageFiltered['percentage'];
                }

                $volume = $activity->summary['volume']['value'] ?? 0;
                $capex = $activity->summary['capex']['value'] ?? 0;
                $opex = $activity->summary['opex']['value'] ?? 0;

                $data['eligibleNotAligned']['volume']['value'] += roundValues($volume * ($percentage / 100), 2);
                $data['eligibleNotAligned']['capex']['value'] += roundValues($capex * ($percentage / 100), 2);
                $data['eligibleNotAligned']['opex']['value'] += roundValues($opex * ($percentage / 100), 2);
            });
        }

        $data['eligible']['volume']['value'] = $taxonomy->activities->sum('summary.volume.value');
        $data['eligible']['capex']['value'] = $taxonomy->activities->sum('summary.capex.value');
        $data['eligible']['opex']['value'] = $taxonomy->activities->sum('summary.opex.value');


        $data['total']['volume']['value'] = empty($data['total']['volume']['value'])
            ? 0
            : floatval($data['total']['volume']['value']);

        $data['total']['capex']['value'] = empty($data['total']['capex']['value'])
            ? 0
            : floatval($data['total']['capex']['value']);

        $data['total']['opex']['value'] = empty($data['total']['opex']['value'])
            ? 0
            : floatval($data['total']['opex']['value']);

        $data['notEligible']['volume']['value'] = $data['total']['volume']['value'] - $data['eligible']['volume']['value'];
        $data['notEligible']['capex']['value'] = $data['total']['capex']['value'] - $data['eligible']['capex']['value'];
        $data['notEligible']['opex']['value'] = $data['total']['opex']['value'] - $data['eligible']['opex']['value'];

        foreach ($data as $key => $value) {
            if (in_array($key, ['total', 'dnsh', 'contribute'])) {
                continue;
            }
            $data[$key]['volume']['percentage'] = calculatePercentage($data[$key]['volume']['value'], $data['total']['volume']['value'], 2);
            $data[$key]['capex']['percentage'] = calculatePercentage($data[$key]['capex']['value'], $data['total']['capex']['value'], 2);
            $data[$key]['opex']['percentage'] = calculatePercentage($data[$key]['opex']['value'], $data['total']['opex']['value'], 2);
        }

        DB::table('taxonomies')->where('id', $taxonomy->id)->update([
            'summary' => $data,
        ]);
    }

    /**
     * Copy questions from JSON files to database.
     *
     * @param int $businessActivityId
     * @return array
     */
    public static function getQuestions($businessActivityId): ?array
    {
        $businessActivityId = "$businessActivityId";

        $baseDir = base_path() . "/database/data/taxonomy/questionnaires/";
        $businessActivity = BusinessActivities::whereCode($businessActivityId)->first();
        $sector = Str::slug($businessActivity->parent->name);

        // remove numbers and - from sector
        $sector = $sector = ltrim(str_replace([' '], '', $sector), '0123456789-');
        $pathFiles = $baseDir . $sector . "/$businessActivityId";
        $cs = $pathFiles . "/cs";
        $dnsh = $pathFiles . "/dnsh";

        // combine all json from cs into one array
        $csFiles = glob($cs . "/*.json");
        $npsFiles = glob($dnsh . "/*.json");

        $csQuestions = [];
        $npsQuestions = [];

        foreach ($csFiles as $i => $file) {
            $json = file_get_contents($file);
            $json = json_decode($json, true);
            $csQuestions[] = [
                'arrayPosition' => $i,
                'percentage' => 0,
                'verified' => null,
                'transition_enabling' => null,
                'name' => [
                    'en' => $json[0]['objective'] ?? '',
                    'es' => $json[0]['objective'] ?? '',
                    'fr' => $json[0]['objective'] ?? '',
                    'pt-BR' => $json[0]['objective'] ?? '',
                    'pt-PT' => $json[0]['objective'] ?? '',
                ],
                'questions' => $json,
            ];
        }

        foreach ($npsFiles as $i => $file) {
            $json = file_get_contents($file);
            $json = json_decode($json, true);
            $npsQuestions[] = [
                'arrayPosition' => $i,
                'percentage' => 0,
                'verified' => null,
                'transition_enabling' => null,
                'name' => [
                    'en' => $json[0]['objective'] ?? '',
                    'es' => $json[0]['objective'] ?? '',
                    'fr' => $json[0]['objective'] ?? '',
                    'pt-BR' => $json[0]['objective'] ?? '',
                    'pt-PT' => $json[0]['objective'] ?? '',
                ],
                'questions' => $json,
            ];
        }

        $defaultArray = [
            'arrayPosition' => null,
            'percentage' => 0,
            'verified' => null,
            'objective' => null,
            'questions' => null,
        ];

        if (count($csQuestions) == 0) {
            $csQuestions = [];
        }

        if (count($npsQuestions) == 0) {
            $npsQuestions = [];
        }

        return [
            'cs' => $csQuestions,
            'dnsh' => $npsQuestions,
        ];
    }

    /**
     * Copy questions from JSON files to database.
     *
     * @param int|null $companySize - 1, 2, 3, 4, 5
     * @return void
     */
    public static function getSafeguardsQuestion($companySize)
    {
        if (!$companySize) {
            return [];
        }

        $baseDir = base_path() . "/database/data/taxonomy/safeguards/";
        $files = glob($baseDir . "/*.json");

        $questions = [];

        foreach ($files as $i => $file) {
            $json = file_get_contents($file);
            $json = collect(json_decode($json, true))
                ->filter(function ($item) use ($companySize) {
                    return in_array($companySize, $item['size']);
                })->toArray();

            foreach ($json as $key => $item) {
                return [
                    'arrayPosition' => $key,
                    'questions' => $item['questions'],
                ];
            }
        }
        return $questions;
    }
}
