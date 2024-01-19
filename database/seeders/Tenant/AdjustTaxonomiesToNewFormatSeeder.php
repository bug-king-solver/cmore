<?php

namespace Database\Seeders\Tenant;

use App\Models\Enums\Taxonomy\AcronymForObjectives;
use App\Models\Tenant\Questionnaire;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdjustTaxonomiesToNewFormatSeeder extends Seeder
{

    /**
     * php artisan tenants:seed --class=Database\\Seeders\\Tenant\\AdjustTaxonomiesToNewFormatSeeder
     * Run the database seeds.
     */
    public function run(): void
    {
        $questionnaires = Questionnaire::where('questionnaire_type_id', 10)
        ->withoutGlobalScopes()->get();
        $taxonomyCT = file_get_contents(base_path() . "/database/data/taxonomy/c_t/c_t_data.json");
        $taxonomyCT = collect(json_decode($taxonomyCT, true));
        $taxonomyCT['climate_mitigation'] = collect($taxonomyCT['climate_mitigation']);
        $taxonomyCT['climate_adaptation'] = collect($taxonomyCT['climate_adaptation']);

        foreach ($questionnaires as $questionnaire) {

            $taxonomy = $questionnaire->taxonomy;

            if (!$taxonomy) {
                continue;
            }

            $listObjectives = [
                'contribute' => [],
                'dnsh' => [],
            ];

            $defaultArray = [
                "volume" => [
                    "value" => 0,
                    "percentage" => 0,
                ],
                "capex" => [
                    "value" => 0,
                    "percentage" => 0,
                ],
                "opex" => [
                    "value" => 0,
                    "percentage" => 0,
                ]
            ];

            $totalVolume = $taxonomy->summary['total']['volume'];
            $totalCapex = $taxonomy->summary['total']['capex'];
            $totalOpex = $taxonomy->summary['total']['opex'];

            foreach ($taxonomy->activities as $item) {
                $resume = $item->summary;

                if (isset($resume['contribute']) && isset($resume['dnsh'])) {
                    continue;
                }

                foreach ($item->contribute['data'] as $objetive) {
                    $acronymObjetive = AcronymForObjectives::fromValue($objetive['objective']);
                    $percentage = (float) $objetive['percentage'];
                    $resume['contribute']['objectives'][$acronymObjetive]['active'] = true;
                    $resume['contribute']['objectives'][$acronymObjetive]['percentage'] = $percentage;
                    $resume['contribute']['objectives'][$acronymObjetive]['opex'] = calculateDivision(($resume['opex'] * $percentage), 100);
                    $resume['contribute']['objectives'][$acronymObjetive]['capex'] = calculateDivision(($resume['capex'] * $percentage), 100);
                    $resume['contribute']['objectives'][$acronymObjetive]['volume'] = calculateDivision(($resume['volume'] * $percentage), 100);
                    $resume['contribute']['objectives'][$acronymObjetive]['enabling']['percentage'] = 0;
                    $resume['contribute']['objectives'][$acronymObjetive]['transition']['percentage'] = 0;

                    if (!isset($listObjectives['contribute']['objectives'][$acronymObjetive])) {
                        $listObjectives['contribute']['objectives'][$acronymObjetive] = $defaultArray;
                    }
                    $listObjectives['contribute']['objectives'][$acronymObjetive]['opex']['value'] += $resume['contribute']['objectives'][$acronymObjetive]['opex'];
                    $listObjectives['contribute']['objectives'][$acronymObjetive]['capex']['value'] += $resume['contribute']['objectives'][$acronymObjetive]['capex'];
                    $listObjectives['contribute']['objectives'][$acronymObjetive]['volume']['value'] += $resume['contribute']['objectives'][$acronymObjetive]['volume'];

                    $listObjectives['contribute']['objectives'][$acronymObjetive]['opex']['percentage'] = calculatePercentage($listObjectives['contribute']['objectives'][$acronymObjetive]['opex']['value'], $taxonomy->summary['total']['opex']);
                    $listObjectives['contribute']['objectives'][$acronymObjetive]['capex']['percentage'] = calculatePercentage($listObjectives['contribute']['objectives'][$acronymObjetive]['capex']['value'], $taxonomy->summary['total']['capex']);
                    $listObjectives['contribute']['objectives'][$acronymObjetive]['volume']['percentage'] = calculatePercentage($listObjectives['contribute']['objectives'][$acronymObjetive]['volume']['value'], $taxonomy->summary['total']['volume']);

                    $taxonomyContribution = $taxonomyCT['climate_mitigation']->where('activity_Code', "$item->code")->first();
                    if ($taxonomyContribution == "") {
                        $taxonomyContribution = $taxonomyCT['climate_adaptation']->where('activity_Code', "$item->code")->first();
                    }
                    if (isset($taxonomyContribution['contribution'])) {
                        if ($item->AlignedTaxonomyPercentage > 0 && $taxonomyContribution['contribution'] == "C") {
                            $resume['contribute']['objectives'][$acronymObjetive]['enabling']['percentage'] = 100;
                        } elseif ($item->AlignedTaxonomyPercentage > 0 && $taxonomyContribution['contribution'] == "T") {
                            $resume['contribute']['objectives'][$acronymObjetive]['transition']['percentage'] = 100;
                        }
                    }
                }

                foreach ($item->dnsh['data'] as $objetive) {
                    $acronymObjetive = AcronymForObjectives::fromValue($objetive['objective']);
                    $percentage = (float) $objetive['percentage'];
                    $resume['dnsh']['objectives'][$acronymObjetive]['active'] = true;
                    $resume['dnsh']['objectives'][$acronymObjetive]['percentage'] = $percentage;
                    $resume['dnsh']['objectives'][$acronymObjetive]['opex'] = calculateDivision(($resume['opex'] * $percentage), 100);
                    $resume['dnsh']['objectives'][$acronymObjetive]['capex'] = calculateDivision(($resume['capex'] * $percentage), 100);
                    $resume['dnsh']['objectives'][$acronymObjetive]['volume'] = calculateDivision(($resume['volume'] * $percentage), 100);
                    $resume['dnsh']['objectives'][$acronymObjetive]['enabling']['percentage'] = 0;
                    $resume['dnsh']['objectives'][$acronymObjetive]['transition']['percentage'] = 0;

                    if (!isset($listObjectives['dnsh']['objectives'][$acronymObjetive])) {
                        $listObjectives['dnsh']['objectives'][$acronymObjetive] = $defaultArray;
                    }

                    $listObjectives['dnsh']['objectives'][$acronymObjetive]['opex']['value'] += $resume['dnsh']['objectives'][$acronymObjetive]['opex'];
                    $listObjectives['dnsh']['objectives'][$acronymObjetive]['capex']['value'] += $resume['dnsh']['objectives'][$acronymObjetive]['capex'];
                    $listObjectives['dnsh']['objectives'][$acronymObjetive]['volume']['value'] += $resume['dnsh']['objectives'][$acronymObjetive]['volume'];

                    $listObjectives['dnsh']['objectives'][$acronymObjetive]['opex']['percentage'] = calculatePercentage($listObjectives['dnsh']['objectives'][$acronymObjetive]['opex']['value'],  $taxonomy->summary['total']['opex']);
                    $listObjectives['dnsh']['objectives'][$acronymObjetive]['capex']['percentage'] = calculatePercentage($listObjectives['dnsh']['objectives'][$acronymObjetive]['capex']['value'],  $taxonomy->summary['total']['capex']);
                    $listObjectives['dnsh']['objectives'][$acronymObjetive]['volume']['percentage'] = calculatePercentage($listObjectives['dnsh']['objectives'][$acronymObjetive]['volume']['value'],  $taxonomy->summary['total']['volume']);

                    $taxonomyContribution = $taxonomyCT['climate_mitigation']->where('activity_Code', "$item->code")->first();
                    if ($taxonomyContribution == "") {
                        $taxonomyContribution = $taxonomyCT['climate_adaptation']->where('activity_Code', "$item->code")->first();
                    }
                    if (isset($taxonomyContribution['contribution'])) {
                        if ($item->AlignedTaxonomyPercentage > 0 && $taxonomyContribution['contribution'] == "C") {
                            $resume['dnsh']['objectives'][$acronymObjetive]['enabling']['percentage'] = 100;
                        } elseif ($item->AlignedTaxonomyPercentage > 0 && $taxonomyContribution['contribution'] == "T") {
                            $resume['dnsh']['objectives'][$acronymObjetive]['transition']['percentage'] = 100;
                        }
                    }
                }
                $resume['opex'] = [
                    "value" => $resume['opex'],
                    "percentage" => calculatePercentage($resume['opex'], $totalOpex, 2),
                ];
                $resume['capex'] = [
                    "value" => $resume['capex'],
                    "percentage" => calculatePercentage($resume['capex'], $totalCapex, 2),
                ];
                $resume['volume'] = [
                    "value" => $resume['volume'],
                    "percentage" => calculatePercentage($resume['volume'], $totalVolume, 2),
                ];

                $item->summary = $resume;
                $item->save();
            };

            $resume = array_merge($taxonomy->summary, $listObjectives);

            foreach ($taxonomy->summary as $key => $value) {
                if (in_array($key, ['total', 'proportions', 'dnsh', 'contribute'])) {
                    continue;
                }
                $resume['proportions']['volume'][$key] = calculatePercentage($resume[$key]['volume'], $resume['total']['volume']);
                $resume['proportions']['capex'][$key] = calculatePercentage($resume[$key]['capex'], $resume['total']['capex']);
                $resume['proportions']['opex'][$key] = calculatePercentage($resume[$key]['opex'], $resume['total']['opex']);
            }

            $taxonomy->summary = $resume;
            $taxonomy->save();
        }
    }
}
