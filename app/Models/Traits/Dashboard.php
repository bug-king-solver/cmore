<?php

namespace App\Models\Traits;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Category;
use App\Models\Tenant\Initiative;
use App\Models\Tenant\Question;
use App\Models\Tenant\Questionnaire;
use Illuminate\Support\Facades\DB;

trait Dashboard
{
    protected Questionnaire | int $questionnaire;

    protected $mainCategories;

    protected $subCategories;

    protected $initiatives;

    protected $enabledQuestions;

    protected $weightableQuestions;

    protected $answers;

    // public function __construct($questionnaire)
    // {
    //     $this->questionnaire = $questionnaire instanceof Questionnaire
    //         ? $questionnaire
    //         : Questionnaire::find($questionnaire);
    //     $this->parseCategories();
    //     $this->parseAnswers();
    //     $this->parseInitiatives();
    // }

    public function setQuestionnaire($questionnaire)
    {
        $this->questionnaire = $questionnaire instanceof Questionnaire
            ? $questionnaire
            : Questionnaire::find($questionnaire);
        $this->parseCategories();
        $this->parseQuestions();
        $this->parseAnswers();
        $this->parseInitiatives();
    }

    abstract public function view($questionnaire);

    protected function parseCategories()
    {
        // We don't need to parse the categories multiple times
        if ($this->mainCategories) {
            return;
        }

        // TODO :: This can be dynamic, but for now  we have no reason to do it
        $mainCategoriesIds = [1, 2, 3];
        $mainCategories = [];
        $subCategories = [];

        array_map(
            function ($category) use ($mainCategoriesIds, &$mainCategories, &$subCategories) {
                $name = json_decode($category['name'], true);

                $category = (new Category())->forceFill($category);
                $category->setTranslations('name', $name);

                if (in_array($category->id, $mainCategoriesIds, false)) {
                    $mainCategories[] = $category;
                } else {
                    $subCategories[] = $category;
                }
            },
            $this->questionnaire->categories()->toArray()
        );

        $this->mainCategories = $mainCategories;
        $this->subCategories = $subCategories;
    }

    protected function parseInitiatives()
    {
        // We don't need to parse the initiatives multiple times
        if ($this->initiatives || !$this->questionnaire->initiatives) {
            return;
        }

        $this->initiatives = Initiative::limit(10)
            ->orderBy('impact', 'desc')
            ->with('category')
            ->find($this->questionnaire->initiatives);
    }

    protected function parseQuestions()
    {
        $allQuestions = collect($this->questionnaire->questions())
            ->keyBy('id');

        // Enabled questions
        $this->enabledQuestions = $allQuestions->where('enabled', true);
    }

    protected function parseAnswers()
    {
        $this->answers = $this->questionnaire->finalAnswers->keyBy('question_id');
    }

    /**
     * Action Plan
     */
    protected function parseDataForChartActionPlan()
    {
        $businessSectorId = $this->questionnaire->company->business_sector_id;

        if (!$this->initiatives) {
            return null;
        }

        $i = 0;
        $xsMax = 0;
        $ysMax = 0;

        $initiatives = $this->initiatives->map(function ($initiative) use ($businessSectorId, &$i, &$xsMax, &$ysMax) {
            $i++;
            $indexes = array_column($this->questionnaire->categories, 'id');
            $key = array_search($initiative->category_id, $indexes, false);

            $questionnaireTypeId = $this->questionnaire->questionnaire_type_id;
            $categoryId = $initiative->category->parent_id ?? $initiative->category_id;

            $x = round(Category::ponderation($questionnaireTypeId, $categoryId, $businessSectorId));

            $xsMax = $xsMax > $x
                ? $xsMax
                : $x;

            $y = round(100 - ($this->questionnaire->categories[$key]['maturity'] ?? 0)) + (10 - $i) * 10;
            $ysMax = $ysMax > $y
                ? $ysMax
                : $y;

            return [
                'name' => $i,
                'data' => [
                    [$x, $y, 15],
                ],
            ];
        });

        // Convert to percentages to work as expected
        $initiatives->transform(function ($initiative) use ($ysMax, $xsMax) {
            $x = round($initiative['data'][0][0] * 100 / ($xsMax ?: 1), 3) - 6;
            $y = round($initiative['data'][0][1] * 100 / ($ysMax ?: 1), 3) - 6;
            $initiative['data'][0][0] = $x > 6
                ? $x
                : 6;
            $initiative['data'][0][1] = $y > 6
                ? $y
                : 6;

            return $initiative;
        });

        return [
            'series' => json_encode(array_values($initiatives->toArray())),
            'xaxis' => [
                'max' => 100,
                'min' => 0,
            ],
            'yaxis' => [
                'max' => 100,
                'min' => 0,
            ],
        ];
    }

    /**
     * Action Plan Table
     */
    protected function parseDataForChartActionPlanTable()
    {
        return $this->initiatives;
    }

    /**
     * Total Gender Equility
     */
    protected function parseDataForChartGenderEquilityTotal($questionId = 192)
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, $questionId);

        if (!$answer || is_null($answer->value)) {
            return null;
        }

        $labels = [
            __('Female'),
            __('Male'),
            __('Other'),
        ];

        $values = json_decode($answer->value, true)[2];
        $total = array_sum(array_map(fn ($value) => array_sum($value), $values));

        $series = [
            isset($values['Female']) ? round(array_sum($values['Female']) * 100 / $total, 2) : 0,
            isset($values['Male']) ? round(array_sum($values['Male']) * 100 / $total, 2) : 0,
            isset($values['Other']) ? round(array_sum($values['Other']) * 100 / $total, 2) : 0,
        ];

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    /**
     * Total Gender Equility  >> screen
     */
    protected function parseDataForChartGenderEquilityTotalForScreen($questionId = 432)
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, $questionId);

        if (!$answer || is_null($answer->value)) {
            return null;
        }

        $labels = [
            __('Female'),
            __('Male'),
            __('Other'),
        ];

        $values = json_decode($answer->value, true);
        $total = array_sum($values);

        $series = [
            isset($values[151]) ? round($values[151] * 100 / $total, 2) : 0,
            isset($values[152]) ? round($values[152] * 100 / $total, 2) : 0,
            isset($values[8]) ? round($values[8] * 100 / $total, 2) : 0,
        ];

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    /**
     * Total Gender salary  >> screen
     */
    protected function parseDataForChartGenderScreen($questionId = 441)
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, $questionId);

        if (!$answer || is_null($answer->value)) {
            return null;
        }

        $labels = [
            __('Female'),
            __('Male'),
            __('Other'),
        ];

        $values = json_decode($answer->value, true);

        if ($values == null) {
            return null;
        }

        $total = array_sum($values);

        $series = [
            isset($values[151]) && $values[151] && $total ? round($values[151] * 100 / $total, 2) : 0,
            isset($values[152]) && $values[152] && $total ? round($values[152] * 100 / $total, 2) : 0,
            isset($values[8]) && $values[8] && $total ? round($values[8] * 100 / $total, 2) : 0,
        ];

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    /**
     * Gender equility » employees
     */
    protected function parseDataForChartGenderEquilityEmployees($questionId = 192)
    {
        // TODO :: Fix question id
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, $questionId);

        if (!$answer || is_null($answer->value)) {
            return null;
        }

        $labels = [
            __('Female'),
            __('Male'),
            __('Other'),
        ];

        $values = json_decode($answer->value, true)[2];
        $total = array_sum(array_map(fn ($value) => array_sum($value), $values));

        $series = [
            isset($values['Female']) && $values['Female'] && $total
                ? round(array_sum($values['Female']) * 100 / $total, 2)
                : 0,
            isset($values['Male']) && $values['Male'] && $total
                ? round(array_sum($values['Male']) * 100 / $total, 2)
                : 0,
            isset($values['Other']) && $values['Other'] && $total
                ? round(array_sum($values['Other']) * 100 / $total, 2)
                : 0,
        ];

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    /**
     * Gender equility » employees  >> screen
     */
    protected function parseDataForChartGenderEquilityEmployeesForScreen($questionId = 435)
    {
        // TODO :: Fix question id
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, $questionId);

        if (!$answer || is_null($answer->value)) {
            return null;
        }

        $labels = [
            __('Female'),
            __('Male'),
            __('Other'),
        ];

        $values = json_decode($answer->value, true);
        $total = array_sum($values);

        $series = [
            isset($values[151]) && $values[151] && $total ? round($values[151] * 100 / $total, 2) : 0,
            isset($values[152]) && $values[152] && $total ? round($values[152] * 100 / $total, 2) : 0,
            isset($values[8]) && $values[8] && $total ? round($values[8] * 100 / $total, 2) : 0,
        ];

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    /**
     * Gender equility » management
     */
    protected function parseDataForChartGenderEquilityManagement($questionId = 201)
    {
        return $this->parseDataForChartGenderEquilityEmployees($questionId);
    }

    /**
     * Gender equility » management  >> screen
     */
    protected function parseDataForChartGenderEquilityManagementForScreen($questionId = 435)
    {
        return $this->parseDataForChartGenderEquilityEmployeesForScreen($questionId);
    }

    /**
     * Co2 Emissions
     */
    protected function parseDataForChartGenderEquilityCLevel($questionId = 201)
    {
        return $this->parseDataForChartGenderEquilityEmployees($questionId);
    }

    /**
     * Co2 Emissions >> screen
     */
    protected function parseDataForChartGenderEquilityCLevelForScreen($questionId = 466)
    {
        return $this->parseDataForChartGenderEquilityEmployeesForScreen($questionId);
    }

    /**
     * Co2 Emissions
     */
    protected function parseDataForChartCo2Emissions($questionId = 351)
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, $questionId);
        $value = isset($answer->value)
            ? json_decode($answer->value, true)[1]
            : null;

        return is_numeric($value)
            ? $value
            : false;
    }

    /**
     * Selected sdg's
     */
    protected function parseDataForChartSdgsTop5($questionId = 51)
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, $questionId);

        return isset($answer->value)
            ? json_decode($answer->value, true)
            : [];
    }

    /**
     * Turnover Rate
     */
    protected function parseDataForChartTurnoverRate($questionId = 196)
    {
        return $this->parseDataForChartCo2Emissions($questionId);
    }

    protected function getQuestionnaireName($questionnaireId)
    {
        return Questionnaire::questionnaireListByQuestionId([$questionnaireId])->first();
    }

    /**
     * Get data from data table by questionnaire Id
     */
    protected function getDataByQuestionnaire($questionnaire)
    {
        $this->setQuestionnaire($questionnaire);
        $this->answers = $this->questionnaire->dataList()->get();
    }

    /**
     * Get data from data table by Indicator Id
     */
    protected function getDataByindicator($indicatorId)
    {
        $result = $this->answers->where('indicator_id', $indicatorId)
            ->first();

        return $result ?? null;
    }

    /**
     * Get value of specific indicator
     * Return value or null
     */
    protected function getValueByIndicatorId($indicatorId, $type = null)
    {
        $result = $this->getDataByindicator($indicatorId);

        if ($type != null) {
            return $result->value ?? 0;
        } else {
            return [
                'value' => $result->value ?? 0,
                'unit' => $result->indicator->unit_default ?? null
            ];
        }
    }

    /**
     * Get charts data for multiple IndicatorIds
     */
    protected function getDataForChartsByIndicatorIds($indicatorId)
    {
        $data = [];

        if (!is_array($indicatorId)) {
            return [];
        }

        foreach ($indicatorId as $indicator) {
            $result = $this->getDataByindicator($indicator);

            if (isset($result->value) && ($result->value == '' || $result->value == null)) {
                array_push($data, 0);
            } else {
                array_push($data, $result->value ?? 0);
            }
        }

        return $data;
    }

    /**
     * Get Unit name by Indicator Ids
     */
    protected function getUnitByIndicatorIds($indicatorId)
    {
        $data = [];

        if (!is_array($indicatorId)) {
            return [];
        }

        foreach ($indicatorId as $indicator) {
            $result = $this->getDataByindicator($indicator);

            if (isset($result) && ($result->indicator->unit_default ?? null) != null) {
                array_push($data, $result->indicator->unit_default);
            }
        }

        return $data[0] ?? null;
    }

    /**
     * Get Unit name by Question Id
     */
    protected function getUnitByQuestionId(int $question_id): int | null
    {
        $question = Question::find($question_id)->with('questionOptions');

        if (isset($question) && isset($question->questionOptions) && count($question->questionOptions)) {
            return $question->questionOptions[0]->indicator->unit_default ?? null;
        }

        return null;
    }

    /**
     * Create array for charts with the data
     */
    protected function parseDataForCharts($labels, $indicatorids, $defaultYear = false)
    {
        $labels = $this->getDataForChartsByIndicatorIds($labels);
        $data = $this->getDataForChartsByIndicatorIds($indicatorids);

        // TODO: Refactor this functionality. For now we use questionnaire column `from` to display year
        if ($defaultYear) {
            array_push($labels, $this->questionnaire->from->format('Y'));
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'unit' => $this->getUnitByIndicatorIds($indicatorids)
        ];
    }

    /**
     * Create array for charts with the data with static labels
     */
    protected function parseDataForChartsWithStaticLabels($labels, $indicatorids)
    {
        $data = $this->getDataForChartsByIndicatorIds($indicatorids);

        $output = removeNullDataFromArray($labels, $data);

        if ($output == null) {
            return null;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'unit' => $this->getUnitByIndicatorIds($indicatorids)
        ];
    }

    /**
     * Calculate value based on percentage for charts
     */
    protected function parseDataForCalculateValueByPercentage($valueIndicatorId, $percentageIndicatorIds)
    {
        $data = [];
        $value = $this->getDataByindicator($valueIndicatorId)->value ?? null;

        if ($value == null) {
            return null;
        }

        $percentageValues = $this->getDataForChartsByIndicatorIds($percentageIndicatorIds);

        foreach ($percentageValues as $row) {
            $percentage = $row->value ?? 0;

            if ($percentage > 0) {
                array_push($data, intval(($value * $percentage) / 100));
            } else {
                array_push($data, $percentage);
            }
        }

        return $data;
    }

    /**
     * Parse data for multi bar chart TODO://
     */
    protected function parseDataForMultibarChart($labels, $data, $defaultYear = false)
    {
        $labels = $this->getDataForChartsByIndicatorIds($labels);
    }

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
        return $indicators
            ? $indicators->toArray()
            : [];
    }

    /**
     * Get value of answer
     */
    protected function getDataByQuestionIds($questionIds)
    {
        return $this->questionnaire->answers()
            ->select(DB::raw("REGEXP_REPLACE(value, '[^0-9]', '') as value"), 'id', 'question_id')
            ->whereIn('question_id', $questionIds)->get();
    }
}
