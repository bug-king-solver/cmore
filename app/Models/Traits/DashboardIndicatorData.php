<?php

namespace App\Models\Traits;

use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Initiative;
use App\Models\Tenant\Category;

trait DashboardIndicatorData
{
    protected Questionnaire | int $questionnaire;

    protected $indicatorValues;

    protected $indicatorValuesArr;

    protected $initiatives;


    public function setQuestionnaire($questionnaire)
    {
        $this->questionnaire = $questionnaire instanceof Questionnaire
            ? $questionnaire
            : Questionnaire::find($questionnaire);
        $this->parseIndicatorValues();
        $this->parseInitiatives();
    }

    protected function parseInitiatives()
    {
        // We don't need to parse the initiatives multiple times
        if ($this->initiatives || ! $this->questionnaire->initiatives) {
            return;
        }

        $this->initiatives = Initiative::limit(10)
            ->orderBy('impact', 'desc')
            ->with('category')
            ->find($this->questionnaire->initiatives);
    }

    protected function getQuestionnaireName($questionnaireId)
    {
        return Questionnaire::questionnaireListByQuestionId([$questionnaireId])->first();
    }

    abstract public function view($questionnaire);

    // from data table indicator values
    protected function parseIndicatorValues()
    {
        $indicatorsData = $this->questionnaire->dataList()->get();
        $this->indicatorValues = $indicatorsData->pluck('value', 'indicator_id')->toArray();
        $this->indicatorValuesArr = $indicatorsData->keyBy('indicator_id');
    }

    // getIndicatorDetailsArray
    protected function getIndicatorArr($indicatorId)
    {
        $indicatorId = (array) $indicatorId;
        $indicators = $this->indicatorValuesArr->whereIn('indicator_id', $indicatorId);
        return $indicators ? $indicators->toArray() : [];

    }

    protected function parseListType($indicators)
    {
        $indicatorValuesArr = arrReplaceValueFromArray(array_keys($indicators), $this->indicatorValues);
        $values = array_map(function ($value, $key) use($indicatorValuesArr) {
            $value['value'] = $indicatorValuesArr[$key];
            return $value;
        }, $indicators, array_keys($indicatorValuesArr));
        return $values;
    }

    protected function parseCheckboxList($indicators, $returnType = 'string')
    {
        $indicatorValuesArr = arrReplaceValueFromArray(array_keys($indicators), $this->indicatorValues);

        $values = $returnType == 'string' ? '-' : [];
        if( count ($indicatorValuesArr) > 0 ) {
            $checkedValues = array_intersect_key($indicators, array_filter($indicatorValuesArr));
            $values = $returnType=="string" ?
            implode(', ', array_column($checkedValues, 'label')) :
              array_column($checkedValues, 'label');
        }
        return $values;
    }

    protected function parseMaturityLevelChart()
    {
        return [
            "unit" => "%",
            "values" => [
                round($this->questionnaire->maturity),
                100 - round($this->questionnaire->maturity),
            ]
        ];
    }

    protected function parseMaturityLevelCategoryChart()
    {
        $mainCategories = $this->questionnaire->categoriesMainList()->toArray();
        if( count($mainCategories)) {
            foreach($mainCategories as &$category) {
                $maturityFinal = $category['maturity_final'] ?? 0;
                $category['dataset'] = [
                    $maturityFinal,
                    100-$maturityFinal,
                ];
                $category['unit'] = '%';
            }
        }
        return $mainCategories;
    }

    protected function dataLabels($color)
    {
        return [
            'anchor' => 'end',
            'color' => $color,
            'backgroundColor'  => hex2rgba($color, '0.1'),
            'padding'  => 4,
            'padding'  => 4,
            'borderRadius'  => 4,
            'font' => [
                'weight' => 'bold'
            ],
            'align' => 'top',
        ];
    }

     /**
     * Get Unit name by Indicator Ids
     */
    protected function getUnitByIndicatorIds($indicatorIds)
    {
        $data = [];

        if (! is_array($indicatorIds)) {
            return [];
        }

        foreach ($indicatorIds as $indicator) {
            $result = $this->getDataByindicator($indicator);

            if (isset($result) && $result->indicator->unit_default != null) {
                array_push($data, $result->indicator->unit_default);
            }
        }

        return $data[0] ?? null;
    }

    /**
     * Get Unit name by Question Id
     */
    protected function getUnitByQuestionId(int $question_id)
    {
        $question = Question::find($question_id)->with('questionOptions');

        if (isset($question) && isset($question->questionOptions) && count($question->questionOptions)) {
            return $question->questionOptions[0]->indicator->unit_default ?? null;
        }

        return null;
    }

    /**
     * Get data from data table by Indicator Id
     */
    protected function getDataByindicator($indicatorId)
    {
        $result = $this->questionnaire->finalAnswers->keyBy('question_id')->where('indicator_id', $indicatorId)
            ->first();

        return $result ?? null;
    }
}
