<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Questionnaire;
use App\Models\Traits\Dashboard;
use Illuminate\Support\Collection;

class Dashboard20
{
    public $questionnaire;
    protected $categories;

    public $categoriesFirstLevel;

    public $categoriesSecondLevel;

    public $categoriesThirtyLevel;


    /**
     * Rules
     * For this dashboard , we need to create a buble or scatter chart.
     * The chart will have the 3 main categories .
     * For the Second level categories , we will use on the chart as the labels.
     * For the axis X , we need to sum all values ( sum all answer from the Impact category ) and apply the rule
     *   >= 12: value === 5
     *   10 and 11: value === 4
     *   8 and 9: value === 3
     *   6 and 7: value === 2
     *   <= 5: value === 1
     *  For the axis Y , we need to use the biggest value from the Financial category answers.
     */
    public function view($questionnaireId)
    {
        $this->questionnaire = Questionnaire::findOrFail($questionnaireId);

        $categories = $this->questionnaire->getCategoriesRecursive();
        return tenantView(
            "tenant.dashboards.20",
            [
                'charts' => $this->parseDoubleMaterialityMatrixChart($categories),
            ]
        );
    }


    /**
     * @return array<int|string, array{label: mixed, color: string, backgroundColor: mixed, radius: int, data: array<int, array{x: mixed, y: float, labelFull: mixed, labelShort: mixed, fullName: string}>}>
     */
    public function parseDoubleMaterialityMatrixChart($categories): array
    {
        $chart = [];

        $count = 0;
        foreach ($categories as $i => $category) {
            $chart[$i] = [
                'label' => $category['name'],
                'color' => $i === 0 ? "bg-esg2" : ($i === 1 ? "bg-esg1" : "bg-esg" . ($i + 1)),
                'backgroundColor' => $i === 0 ? color(2) : ($i === 1 ? color(1) : color($i + 1)),
                'radius' => 10,
                'data' => []
            ];

            foreach ($category['childrens'] as $secondCategory) {
                $lastCategories = $secondCategory['childrens'];

                $calcImpactQuestionsIds = [];

                if (isset($lastCategories[0])) {
                    $calcImpactQuestionsIds = array_column($lastCategories[0]['questions'], 'id');
                }

                if (isset($lastCategories[1])) {
                    $financeImpactQuestionsIds = array_column($lastCategories[1]['questions'], 'id');
                }

                $chart[$i]['data'][] = [
                    'x' => $this->calcImpact($lastCategories[0], $calcImpactQuestionsIds),
                    'y' => $this->calcFinance($lastCategories[1], $financeImpactQuestionsIds),
                    'labelFull' => $secondCategory['name'],
                    'labelShort' => getLetterByIndex($count),
                    'fullName' => getLetterByIndex($count) . '. ' . $secondCategory['name']
                ];
                ++$count;
            }
        }

        return $chart;
    }


    /**
     * Get all questions from category
     * @param array|Collection $category
     * @return mixed[]
     */
    public function parseQuestionsByCategory(array|Collection $category): array
    {
        return collect($category['questions'])
            ->filter(static fn ($item): bool => $item['answer_type'] == 'binary-colorfull')
            ->values()->all();

    }

    /**
     * Get value of answer
     * @param array|Collection $category
     * @param array|Collection $questions
     * @return int
     */
    public function calcImpact(array|Collection $category, array|Collection $questions = []): int
    {
        $filteredQuestions = $this->parseQuestionsByCategory($category);

        //answer is category childrens questions where question id in $question
        $filteredQuestions = collect($filteredQuestions)->filter(static fn ($item): bool => in_array($item['id'], $questions));

        $answers = $this->getAnswerFromQuestion($filteredQuestions);
        $result = $answers->sum('value');
        switch ($result) {
            case ($result >= 12):
                return 5;
            case ($result == 10 || $result == 11):
                return 4;
            case ($result == 8 || $result == 9):
                return 3;
            case ($result == 6 || $result == 7):
                return 2;
            default:
                return 1;
        }
    }

    /**
     * Get value of answer
     * @param $category
     * @param $questions
     * @return int
     */
    public function calcFinance(array|Collection $category, array|Collection $questions = []): int
    {
        $filteredQuestions = $this->parseQuestionsByCategory($category);
        //answer is category childrens questions where question id in $question
        $filteredQuestions = collect($filteredQuestions)->filter(static fn ($item): bool => in_array($item['id'], $questions));
        $answers = $this->getAnswerFromQuestion($filteredQuestions);
        return  (int)$answers->max('value');
    }

    /**
     * Get value of answer
     * @param array|Collection $questions
     * @return Collection
     */
    protected function getAnswerFromQuestion(array|Collection $questions): Collection
    {
        $answer = collect($questions)->map(fn ($item) => $item['answer'])->map(function ($item) {
            // remove all non number character from answerr value
            $item['value'] = (int)preg_replace('#[^0-9]#', '', (string) $item['value']);
            return $item;
        });

        return collect($answer);
    }
}
