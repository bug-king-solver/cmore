<?php

namespace App\Jobs\Tenants\Questionnaires;

use App\Models\Enums\Taxonomy\AcronymForObjectives;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Submit10 implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var Questionnaire */
    protected Questionnaire $questionnaire;
    protected Taxonomy $taxonomy;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Questionnaire $questionnaire)
    {
        $this->onQueue('questionnaires');
        $this->questionnaire = $questionnaire;
        $this->taxonomy = $questionnaire->taxonomy;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $taxonomy = $this->taxonomy;
        $taxonomyCT = file_get_contents(base_path() . "/database/data/taxonomy/c_t/c_t_data.json");
        $taxonomyCT = collect(json_decode($taxonomyCT, true));
        $taxonomyCT['climate_mitigation'] = collect($taxonomyCT['climate_mitigation']);
        $taxonomyCT['climate_adaptation'] = collect($taxonomyCT['climate_adaptation']);
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

        foreach ($taxonomy->activities as $item) {
            $resume = $item->summary;
            foreach ($item->contribute['data'] as $objetive) {
                $transition_enabling = $objetive['transition_enabling'] ?? null;
                $acronymObjetive = AcronymForObjectives::fromValue($objetive['name']['pt-PT']);
                $percentage = (float) $objetive['percentage'];
                $resume['contribute']['objectives'][$acronymObjetive]['percentage'] = $percentage;

                $resume['contribute']['objectives'][$acronymObjetive]['opex']['value'] = roundValues(($resume['opex']['value'] * $percentage) / 100);
                $resume['contribute']['objectives'][$acronymObjetive]['capex']['value'] = roundValues(($resume['capex']['value'] * $percentage) / 100);
                $resume['contribute']['objectives'][$acronymObjetive]['volume']['value'] = roundValues(($resume['volume']['value'] * $percentage) / 100);

                $resume['contribute']['objectives'][$acronymObjetive]['opex']['percentage'] = calculatePercentage($resume['contribute']['objectives'][$acronymObjetive]['opex']['value'], $taxonomy->summary['total']['opex']['value'], 2);
                $resume['contribute']['objectives'][$acronymObjetive]['capex']['percentage'] = calculatePercentage($resume['contribute']['objectives'][$acronymObjetive]['capex']['value'], $taxonomy->summary['total']['capex']['value'], 2);
                $resume['contribute']['objectives'][$acronymObjetive]['volume']['percentage'] = calculatePercentage($resume['contribute']['objectives'][$acronymObjetive]['volume']['value'], $taxonomy->summary['total']['volume']['value'], 2);

                $resume['contribute']['objectives'][$acronymObjetive]['enabling']['percentage'] = 0;
                $resume['contribute']['objectives'][$acronymObjetive]['transition']['percentage'] = 0;

                if (!isset($listObjectives['contribute']['objectives'][$acronymObjetive])) {
                    $listObjectives['contribute']['objectives'][$acronymObjetive] = $defaultArray;
                }
                $listObjectives['contribute']['objectives'][$acronymObjetive]['opex']['value'] += $resume['contribute']['objectives'][$acronymObjetive]['opex']['value'];
                $listObjectives['contribute']['objectives'][$acronymObjetive]['capex']['value'] += $resume['contribute']['objectives'][$acronymObjetive]['capex']['value'];
                $listObjectives['contribute']['objectives'][$acronymObjetive]['volume']['value'] += $resume['contribute']['objectives'][$acronymObjetive]['volume']['value'];

                $listObjectives['contribute']['objectives'][$acronymObjetive]['opex']['percentage'] = calculatePercentage($listObjectives['contribute']['objectives'][$acronymObjetive]['opex']['value'], $taxonomy->summary['total']['opex']['value'], 2);
                $listObjectives['contribute']['objectives'][$acronymObjetive]['capex']['percentage'] = calculatePercentage($listObjectives['contribute']['objectives'][$acronymObjetive]['capex']['value'], $taxonomy->summary['total']['capex']['value'], 2);
                $listObjectives['contribute']['objectives'][$acronymObjetive]['volume']['percentage'] = calculatePercentage($listObjectives['contribute']['objectives'][$acronymObjetive]['volume']['value'], $taxonomy->summary['total']['volume']['value'], 2);

                $resume['contribute']['objectives'][$acronymObjetive]['transition_enabling'] = $transition_enabling;
                if (isset($transition_enabling)) {
                    if ($transition_enabling == "C") {
                        $resume['contribute']['objectives'][$acronymObjetive]['enabling']['percentage'] = $percentage;
                    } elseif ($transition_enabling == "T") {
                        $resume['contribute']['objectives'][$acronymObjetive]['transition']['percentage'] = $percentage;
                    }
                } else {
                    $taxonomyContribution = $taxonomyCT['climate_mitigation']->where('activity_Code', "$item->code")->first();
                    if ($taxonomyContribution == "") {
                        $taxonomyContribution = $taxonomyCT['climate_adaptation']->where('activity_Code', "$item->code")->first();
                    }
                    if (isset($taxonomyContribution['contribution'])) {
                        if ($item->AlignedTaxonomyPercentage > 0 && $taxonomyContribution['contribution'] == "C") {
                            $resume['contribute']['objectives'][$acronymObjetive]['enabling']['percentage'] = $percentage;
                        } elseif ($item->AlignedTaxonomyPercentage > 0 && $taxonomyContribution['contribution'] == "T") {
                            $resume['contribute']['objectives'][$acronymObjetive]['transition']['percentage'] = $percentage;
                        }
                    }
                }
            }

            foreach ($item->dnsh['data'] as $objetive) {
                $acronymObjetive = AcronymForObjectives::fromValue($objetive['name']['pt-PT']);
                $transition_enabling = $objetive['transition_enabling'] ?? null;
                $percentage = (float) $objetive['percentage'];
                $resume['dnsh']['objectives'][$acronymObjetive]['percentage'] = $percentage;

                $resume['dnsh']['objectives'][$acronymObjetive]['opex']['value'] = roundValues(($resume['opex']['value'] * $percentage) / 100);
                $resume['dnsh']['objectives'][$acronymObjetive]['capex']['value'] = roundValues(($resume['capex']['value'] * $percentage) / 100);
                $resume['dnsh']['objectives'][$acronymObjetive]['volume']['value'] = roundValues(($resume['volume']['value'] * $percentage) / 100);

                $resume['dnsh']['objectives'][$acronymObjetive]['opex']['percentage'] = calculatePercentage($resume['dnsh']['objectives'][$acronymObjetive]['opex']['value'], $taxonomy->summary['total']['opex']['value'], 2);
                $resume['dnsh']['objectives'][$acronymObjetive]['capex']['percentage'] = calculatePercentage($resume['dnsh']['objectives'][$acronymObjetive]['capex']['value'], $taxonomy->summary['total']['capex']['value'], 2);
                $resume['dnsh']['objectives'][$acronymObjetive]['volume']['percentage'] = calculatePercentage($resume['dnsh']['objectives'][$acronymObjetive]['volume']['value'], $taxonomy->summary['total']['volume']['value'], 2);

                $resume['dnsh']['objectives'][$acronymObjetive]['enabling']['percentage'] = 0;
                $resume['dnsh']['objectives'][$acronymObjetive]['transition']['percentage'] = 0;

                if (!isset($listObjectives['dnsh']['objectives'][$acronymObjetive])) {
                    $listObjectives['dnsh']['objectives'][$acronymObjetive] = $defaultArray;
                }

                $listObjectives['dnsh']['objectives'][$acronymObjetive]['opex']['value'] += $resume['dnsh']['objectives'][$acronymObjetive]['opex']['value'];
                $listObjectives['dnsh']['objectives'][$acronymObjetive]['capex']['value'] += $resume['dnsh']['objectives'][$acronymObjetive]['capex']['value'];
                $listObjectives['dnsh']['objectives'][$acronymObjetive]['volume']['value'] += $resume['dnsh']['objectives'][$acronymObjetive]['volume']['value'];

                $listObjectives['dnsh']['objectives'][$acronymObjetive]['opex']['percentage'] = calculatePercentage($listObjectives['dnsh']['objectives'][$acronymObjetive]['opex']['value'], $taxonomy->summary['total']['opex']['value'], 2);
                $listObjectives['dnsh']['objectives'][$acronymObjetive]['capex']['percentage'] = calculatePercentage($listObjectives['dnsh']['objectives'][$acronymObjetive]['capex']['value'], $taxonomy->summary['total']['capex']['value'], 2);
                $listObjectives['dnsh']['objectives'][$acronymObjetive]['volume']['percentage'] = calculatePercentage($listObjectives['dnsh']['objectives'][$acronymObjetive]['volume']['value'], $taxonomy->summary['total']['volume']['value'], 2);

                $resume['dnsh']['objectives'][$acronymObjetive]['transition_enabling'] = $transition_enabling;
                if (isset($transition_enabling)) {
                    if ($transition_enabling == "C") {
                        $resume['dnsh']['objectives'][$acronymObjetive]['enabling']['percentage'] = $percentage;
                    } elseif ($transition_enabling == "T") {
                        $resume['dnsh']['objectives'][$acronymObjetive]['transition']['percentage'] = $percentage;
                    }
                } else {
                    $taxonomyContribution = $taxonomyCT['climate_mitigation']->where('activity_Code', "$item->code")->first();
                    if ($taxonomyContribution == "") {
                        $taxonomyContribution = $taxonomyCT['climate_adaptation']->where('activity_Code', "$item->code")->first();
                    }
                    if (isset($taxonomyContribution['contribution'])) {
                        if ($item->AlignedTaxonomyPercentage > 0 && $taxonomyContribution['contribution'] == "C") {
                            $resume['dnsh']['objectives'][$acronymObjetive]['enabling']['percentage'] = $percentage;
                        } elseif ($item->AlignedTaxonomyPercentage > 0 && $taxonomyContribution['contribution'] == "T") {
                            $resume['dnsh']['objectives'][$acronymObjetive]['transition']['percentage'] = $percentage;
                        }
                    }
                }
            }
            $item->summary = $resume;
            $item->save();
        };

        $resume = array_merge($taxonomy->summary, $listObjectives);
        foreach ($taxonomy->summary as $key => $value) {
            if (in_array($key, ['total', 'dnsh', 'contribute'])) {
                continue;
            }
            $resume[$key]['volume']['percentage'] = calculatePercentage($resume[$key]['volume']['value'], $resume['total']['volume']['value'], 2);
            $resume[$key]['capex']['percentage'] = calculatePercentage($resume[$key]['capex']['value'], $resume['total']['capex']['value'], 2);
            $resume[$key]['opex']['percentage'] = calculatePercentage($resume[$key]['opex']['value'], $resume['total']['opex']['value'], 2);
        }
        $taxonomy->summary = $resume;
        $taxonomy->save();
    }
}
