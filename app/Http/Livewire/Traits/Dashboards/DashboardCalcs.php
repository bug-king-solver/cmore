<?php

namespace App\Http\Livewire\Traits\Dashboards;

use App\Models\Tenant\Data;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Questionnaire;
use Illuminate\Support\Collection;

trait DashboardCalcs
{
    public $questionnaireIdLists = [];
    public $questionnaireList;
    public $questionnaireId;

    protected $indicatorsData;
    protected $indicators;

    protected $shouldDivideBy1000;

    /**
     * Mount the component trait
     */
    public function mountDashboardCalcs()
    {
        $this->setIndicators();

        $questionnaires = Questionnaire::whereIn('id', $this->questionnaireIdLists)->get();

        $this->questionnaireList = $questionnaires->filter(function ($questionnaire) {
            return $questionnaire->questionnaire_type_id === $this->typeId
                && $questionnaire->submitted_at != null;
        });

        if (!$this->questionnaireId) {
            $this->questionnaireId = $this->questionnaireList->first()->id ?? null;
        }

        $this->shouldDivideBy1000 = false;
    }

    /**
     * Hydrate the component trait
     */
    public function hydrateDashboardCalcs()
    {
        $this->setIndicators();
    }

    /**
     * Set the indicators data
     * @param Questionnaire questionnaire - the questionnaire to search
     * @return Collection
     */
    public function setIndicatorsData(Questionnaire $questionnaire): Collection
    {
        $this->indicatorsData = $this->fetchAllQuestionnaireData($questionnaire);
        return $this->indicatorsData;
    }

    /**
     * Set the indicators id's
     * @return void
     */
    public function setIndicators(): void
    {
        $this->indicators = Indicator::all()->pluck('name', 'id')->toArray();
    }

    /**
     * Execute a search into the data table, using the indicators ID as params
     * @param int|Questionnaire questionnaire - the questionnaire to search
     * @return Collection|
     */
    public function fetchAllQuestionnaireData(Questionnaire $questionnaire): Collection
    {
        return $this->fetchData(
            $questionnaire->id,
            $questionnaire->dataList()->pluck('indicator_id')->toArray(),
            'value'
        );
    }

    /**
     * Get Unit name by Indicator Ids
     */
    protected function getUnitByIndicatorIdsCalcs($indicatorIds)
    {
        $data = [];

        if (!is_array($indicatorIds)) {
            return [];
        }

        foreach ($indicatorIds as $indicator) {
            $result = $this->getDataByIndicatorCalcs($indicator);

            if (isset($result) && $result->indicator->unit_default != null) {
                array_push($data, $result->indicator->unit_default);
            }
        }

        return $data[0] ?? null;
    }

    /**
     * Get data from data table by Indicator Id
     */
    protected function getDataByIndicatorCalcs($indicatorId)
    {
        $result = $this->questionnaire->finalAnswers->keyBy('question_id')->where('indicator_id', $indicatorId)
            ->first();

        return $result ?? null;
    }

    /**
     * Execute a search into the data table, using the indicators ID as params
     * @param int questionnaireId - the id of the questionnaire to search
     * @param array indicatorIds - the indicators to search
     * @param string valueColumn - the column name to calc the result
     * @return Collection
     */
    public function fetchData($questionnaireId, $indicatorIds = [], $valueColumn = 'value'): Collection
    {
        return Data::whereIn('indicator_id', $indicatorIds)
            ->where('questionnaire_id', $questionnaireId)
            ->with(['indicator' => function ($indicator) {
                return $indicator->withoutGlobalScopes()->with('internalTags');
            }])
            ->get()
            ->map(function ($data) use ($valueColumn) {
                $value = $data->$valueColumn ?? 0;
                if (is_numeric($value) && $this->shouldDivideBy1000) {
                    // check if the indcator has any tag with the slug : notdivideby1000
                    $notDivideBy1000 = $data->indicator->internalTags->where('slug', 'notdivideby1000')->first();
                    if (!$notDivideBy1000) {
                        $value = (float)$value / 1000;
                    }

                    $value = roundValues((float)$value, 2);
                }

                return [
                    'indicator_name' => $data->indicator->name,
                    'indicator_id' => $data->indicator_id,
                    'value' => $value,
                    'unit_default' => $data->indicator->unit_default,
                ];
            });
    }

    /**
     * Map the chart structure to mount the chart
     * @param array charts - the chart structure to search
     * @return array
     */
    public function parseChartsStructureCategories(array $charts): array
    {
        foreach ($charts as &$chart) {
            $chart['categories'] = $this->parseCategoriesStructure($chart['categories']);
            $chart['total'] = collect($chart['categories'])->sum('total');
        }

        return $charts;
    }

    /**
     * Recursive function to recive an array of chart and group the categories
     * @param array categories - the categories structure to search
     * @return array
     */
    public function parseCategoriesStructure($categories)
    {
        foreach ($categories as $key => &$category) {
            if (isset($category['categories'])) {
                // recursive call to handle subcategories
                $category['categories'] = $this->parseCategoriesStructure($category['categories']);
            }

            if (isset($category['indicators'])) {
                if ($category['indicators'] == 'all') {
                    $category['indicators'] = $this->indicatorsData->pluck('indicator_id')->toArray();
                }

                $category['total'] = 0;

                if (isset($category['custom_column_value'])) {
                    $category['indicators'] = $this->fetchData(
                        $this->questionnaire->id,
                        array_keys($this->indicators),
                        $category['custom_column_value']
                    );
                    $category['total'] = $category['indicators']->sum('value');
                } else {
                    $category['indicators'] = $this->filterIndicatorFromList($category);
                    if (isset($category['math']) && $category['math'] !== null) {
                        $category['total'] = $this->calcIndicatorValues($category['math'], $category['indicators']);
                    }
                }

                $category['unit'] = $this->getUnitByIndicatorIdsCalcs($category['indicators']);
            }
        }

        return $categories;
    }

    /**
     * Calc the indicator values based on the expression and the indicators values
     * @param string expression - the expression to calc
     * @param array indicators - the indicators values
     */
    public function calcIndicatorValues($expression, $indicators): ?float
    {
        foreach ($indicators as $indicator) {
            $id = $indicator["indicator_id"];
            $value = $indicator["value"];
            $expression = str_replace($id, $value, $expression);
        }

        $result = null;

        try {
            @eval('$result = ' . $expression . ';');
        } catch (\Throwable $th) {
            return null;
        }

        return roundValues($result, 2);
    }

    /**
     * Filter the indicators from the list of indicators
     * @param array category - the category to filter
     */
    public function filterIndicatorFromList($category): array
    {
        //filter the indicators from the list of indicators
        $filteredIndicator = collect($this->indicatorsData)->filter(function ($indicator) use ($category) {
            return in_array($indicator['indicator_id'], $category['indicators']);
        })
            ->values()
            ->toArray();

        foreach ($category['indicators'] as $indicator) {
            //check if the indicator does not exists in the filtered list
            if (!in_array($indicator, array_column($filteredIndicator, 'indicator_id'))) {
                if (isset($this->indicators[$indicator])) {
                    $filteredIndicator[] = [
                        'indicator_name' => $this->indicators[$indicator],
                        'indicator_id' => $indicator,
                        'value' => $category['math'] !== null ? 0 : null,
                    ];
                }
            }
        }

        return $filteredIndicator;
    }
}
