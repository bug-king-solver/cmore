<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Traits\Dashboard;

class Dashboard6
{
    use Dashboard;

    public function view($questionnaireId)
    {
    }

    public function chartForOneQuestionnaire($questionnaireId)
    {
        if ($questionnaireId != null) {
            $this->setQuestionnaire($questionnaireId);
            $this->parsePosition();

            $charts = [
                'global_esg' => $this->parseDataForChartGlobalEsg(),
                'main_categories_esg' => $this->parseDataForChartMainCategoriesEsg(),
                'category1' => $this->parseDatacategory1(),
                'category2' => $this->parseDatacategory2(),
                'category3' => $this->parseDatacategory3(),
            ];
        }

        return [
            'mainCategories' => $this->mainCategories,
            'charts' => $charts ?? null,
        ];
    }

    public function parsePosition()
    {
        $questionIds = array_unique([
            1306, 1307, 1308, 1309, 1310, 1311, 1312, 1313, 1314, 1315, // category 93
            1316, 1317, 1318, 1319, 1320, 1321, 1322, 1323, 1324, 1325, // category 94
            1326, 1327, 1328, 1329, 1330, 1331, 1332, 1333, 1334, 1335, // category 95
        ]);

        $this->answers = $this->answers->whereIn('question_id', $questionIds)->sortBy('question_id');
    }

    /**
     * ESG Global
     */
    protected function parseDataForChartGlobalEsg()
    {
        $category1 = $this->category1();
        $category2 = $this->category2();
        $category3 = $this->category3();

        $result = ($category1 + $category2 + $category3) / 3;

        return [
            round($result),
            100 - round($result),
        ];
    }

    protected function parseDataForChartMainCategoriesEsg()
    {
        $category1 = $this->category1();
        $category2 = $this->category2();
        $category3 = $this->category3();

        return [
            [round($category1),  100 - round($category1)],
            [round($category2),  100 - round($category2)],
            [round($category3),  100 - round($category3)],
        ];
    }

    protected function parseDatacategory1()
    {
        $category1 = $this->category1();

        return [round($category1),  100 - round($category1)];
    }

    protected function parseDatacategory2()
    {
        $category2 = $this->category2();

        return [round($category2),  100 - round($category2)];
    }

    protected function parseDatacategory3()
    {
        $category3 = $this->category3();

        return [round($category3),  100 - round($category3)];
    }

    protected function category1()
    {
        $weight = 0;
        $answers1 = $this->answers['1306'] ?? null;
        $answers2 = $this->answers['1307'] ?? null;
        $answers3 = $this->answers['1308'] ?? null;
        $answers4 = $this->answers['1309'] ?? null;
        $answers5 = $this->answers['1310'] ?? null;
        $answers6 = $this->answers['1311'] ?? null;
        $answers7 = $this->answers['1312'] ?? null;
        $answers8 = $this->answers['1313'] ?? null;
        $answers9 = $this->answers['1314'] ?? null;
        $answers10 = $this->answers['1315'] ?? null;

        if ($answers1 == null && $answers2 == null && $answers3 == null && $answers4 == null && $answers5 == null && $answers6 == null && $answers7 == null && $answers8 == null && $answers9 == null && $answers10 == null) {
            return 0;
        }

        if ($answers1 != null && $answers1['value'] == 'yes') {
            $weight += $answers1['weight'];
        }

        if ($answers2 != null && $answers2['value'] == 'yes') {
            $weight += $answers2['weight'];
        }

        if ($answers3 != null && $answers3['value'] == 'yes') {
            $weight += $answers3['weight'];
        }

        if ($answers4 != null && $answers4['value'] == 'yes') {
            $weight += $answers4['weight'];
        }

        if ($answers5 != null && $answers5['value'] == 'yes') {
            $weight += $answers5['weight'];
        }

        if ($answers6 != null && $answers6['value'] == 'yes') {
            $weight += $answers6['weight'];
        }

        if ($answers7 != null && $answers7['value'] == 'yes') {
            $weight += $answers7['weight'];
        }

        if ($answers8 != null && $answers8['value'] == 'yes') {
            $weight += $answers8['weight'];
        }

        if ($answers9 != null && $answers9['value'] == 'yes') {
            $weight += $answers9['weight'];
        }

        if ($answers10 != null && $answers10['value'] == 'yes') {
            $weight += $answers10['weight'];
        }

        return $weight;
    }

    protected function category2()
    {
        $weight = 0;
        $answers1 = $this->answers['1316'] ?? null;
        $answers2 = $this->answers['1317'] ?? null;
        $answers3 = $this->answers['1318'] ?? null;
        $answers4 = $this->answers['1319'] ?? null;
        $answers5 = $this->answers['1320'] ?? null;
        $answers6 = $this->answers['1321'] ?? null;
        $answers7 = $this->answers['1322'] ?? null;
        $answers8 = $this->answers['1323'] ?? null;
        $answers9 = $this->answers['1324'] ?? null;
        $answers10 = $this->answers['1325'] ?? null;

        if ($answers1 == null && $answers2 == null && $answers3 == null && $answers4 == null && $answers5 == null && $answers6 == null && $answers7 == null && $answers8 == null && $answers9 == null && $answers10 == null) {
            return 0;
        }

        if ($answers1 != null && $answers1['value'] == 'yes') {
            $weight += $answers1['weight'];
        }

        if ($answers2 != null && $answers2['value'] == 'yes') {
            $weight += $answers2['weight'];
        }

        if ($answers3 != null && $answers3['value'] == 'yes') {
            $weight += $answers3['weight'];
        }

        if ($answers4 != null && $answers4['value'] == 'yes') {
            $weight += $answers4['weight'];
        }

        if ($answers5 != null && $answers5['value'] == 'yes') {
            $weight += $answers5['weight'];
        }

        if ($answers6 != null && $answers6['value'] == 'yes') {
            $weight += $answers6['weight'];
        }

        if ($answers7 != null && $answers7['value'] == 'yes') {
            $weight += $answers7['weight'];
        }

        if ($answers8 != null && $answers8['value'] == 'yes') {
            $weight += $answers8['weight'];
        }

        if ($answers9 != null && $answers9['value'] == 'yes') {
            $weight += $answers9['weight'];
        }

        if ($answers10 != null && $answers10['value'] == 'yes') {
            $weight += $answers10['weight'];
        }

        return $weight;
    }

    protected function category3()
    {
        $weight = 0;
        $answers1 = $this->answers['1326'] ?? null;
        $answers2 = $this->answers['1327'] ?? null;
        $answers3 = $this->answers['1328'] ?? null;
        $answers4 = $this->answers['1329'] ?? null;
        $answers5 = $this->answers['1330'] ?? null;
        $answers6 = $this->answers['1331'] ?? null;
        $answers7 = $this->answers['1332'] ?? null;
        $answers8 = $this->answers['1333'] ?? null;
        $answers9 = $this->answers['1334'] ?? null;
        $answers10 = $this->answers['1335'] ?? null;

        if ($answers1 == null && $answers2 == null && $answers3 == null && $answers4 == null && $answers5 == null && $answers6 == null && $answers7 == null && $answers8 == null && $answers9 == null && $answers10 == null) {
            return 0;
        }

        if ($answers1 != null && $answers1['value'] == 'yes') {
            $weight += $answers1['weight'];
        }

        if ($answers2 != null && $answers2['value'] == 'yes') {
            $weight += $answers2['weight'];
        }

        if ($answers3 != null && $answers3['value'] == 'yes') {
            $weight += $answers3['weight'];
        }

        if ($answers4 != null && $answers4['value'] == 'yes') {
            $weight += $answers4['weight'];
        }

        if ($answers5 != null && $answers5['value'] == 'yes') {
            $weight += $answers5['weight'];
        }

        if ($answers6 != null && $answers6['value'] == 'yes') {
            $weight += $answers6['weight'];
        }

        if ($answers7 != null && $answers7['value'] == 'yes') {
            $weight += $answers7['weight'];
        }

        if ($answers8 != null && $answers8['value'] == 'yes') {
            $weight += $answers8['weight'];
        }

        if ($answers9 != null && $answers9['value'] == 'yes') {
            $weight += $answers9['weight'];
        }

        if ($answers10 != null && $answers10['value'] == 'yes') {
            $weight += $answers10['weight'];
        }

        return $weight;
    }
}
