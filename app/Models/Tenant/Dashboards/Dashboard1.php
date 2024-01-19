<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Sdg;
use App\Models\Traits\Dashboard;

class Dashboard1
{
    use Dashboard;

    public function view($questionnaireId)
    {
        $this->setQuestionnaire($questionnaireId);
        $sdgs = Sdg::all();

        $charts = [
            'global_esg' => $this->parseDataForChartGlobalEsg(),
            'main_categories_esg' => $this->parseDataForChartMainCategoriesEsg(),
            'main_categories_table' => $this->parseDataForChartMainCategoriesTable(),
            'subcategories1_table' => $this->parseDataForChartSubcategories1Table(),
            'subcategories2_table' => $this->parseDataForChartSubcategories2Table(),
            'subcategories3_table' => $this->parseDataForChartSubcategories3Table(),
            'action_plan' => $this->parseDataForChartActionPlan(),
            'action_plan_table' => $this->parseDataForChartActionPlanTable(),
            'gender_equility_total' => $this->parseDataForChartGenderEquilityTotal(),
            'gender_equility_employees' => $this->parseDataForChartGenderEquilityEmployees(),
            'gender_equility_management' => $this->parseDataForChartGenderEquilityManagement(),
            'gender_equility_c_level' => $this->parseDataForChartGenderEquilityCLevel(),
            'co2_emissions' => $this->parseDataForChartCo2Emissions(),
            'water_withdrawal' => $this->parseDataForChartWaterWithdrawal(),
            'turnover_rate' => $this->parseDataForChartTurnoverRate(),
            'trained_employees' => $this->parseDataForChartTrainedEmployees(),
            'sdgs_top5' => $this->parseDataForChartSdgsTop5(),
        ];

        return tenantView(
            'tenant.dashboards.1',
            [
                'questionnaire' => $this->questionnaire,
                'mainCategories' => $this->mainCategories,
                'sdgs' => $sdgs,
                'charts' => $charts,
            ]
        );
    }

    /**
     * ESG Global
     */
    protected function parseDataForChartGlobalEsg()
    {
        return [
            $this->questionnaire->maturity,
            100 - $this->questionnaire->maturity,
        ];
    }

    /**
     * ESG by category
     */
    protected function parseDataForChartMainCategoriesEsg()
    {
        $mainCategories = array_pluck($this->mainCategories, 'maturity');
        $mainCategories = array_map(fn ($maturity) => [round($maturity), 100 - round($maturity)], $mainCategories);

        return $mainCategories;
    }

    /**
     * Table ESG by category
     */
    protected function parseDataForChartMainCategoriesTable()
    {
        return $this->mainCategories;
    }

    /**
     * Table ESG by subcategory // Category = Environment (1)
     */
    protected function parseDataForChartSubcategories1Table()
    {
        $categories = array_filter($this->subCategories, fn ($category) => (int) $category->parent_id === 1);
        $categories = array_map(
            function ($category) {
                if (! $category->questions_sum_weight) {
                    $category->maturity = '-';
                }

                return $category;
            },
            $categories
        );

        return $categories;
    }

    /**
     * Table ESG by subcategory // Social = Environment (2)
     */
    protected function parseDataForChartSubcategories2Table()
    {
        $categories = array_filter($this->subCategories, fn ($category) => (int) $category->parent_id === 2);
        $categories = array_map(
            function ($category) {
                if (! $category->questions_sum_weight) {
                    $category->maturity = '-';
                }

                return $category;
            },
            $categories
        );

        return $categories;
    }

    /**
     * Table ESG by subcategory // Category = Governance (3)
     */
    protected function parseDataForChartSubcategories3Table()
    {
        $categories = array_filter($this->subCategories, fn ($category) => (int) $category->parent_id === 3);
        $categories = array_map(
            function ($category) {
                if (! $category->questions_sum_weight) {
                    $category->maturity = '-';
                }

                return $category;
            },
            $categories
        );

        return $categories;
    }

    /**
     * Water Withdrawal
     */
    protected function parseDataForChartWaterWithdrawal()
    {
        // TODO :: Fix question id
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 351);

        $value = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        return is_numeric($value) ? $value : false;
    }

    /**
     * Trained Employees
     */
    protected function parseDataForChartTrainedEmployees()
    {
        return $this->parseDataForChartWaterWithdrawal();
    }

    /**
     * Emission Revenue >> screen
     */
    protected function parseDataForChartEmissionRevenue()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 461);
        $value = isset($answer->value) ? json_decode($answer->value, true) : [];
        $emission1 = array_sum($value);

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 423);
        $emission2 = isset($answer->value) ? json_decode($answer->value, true)[1] : [];

        return $emission1 && $emission2 ? round($emission1 / $emission2, 2) : false;
    }

    /**
     * Employee by nationality >> screen
     */
    protected function parseDataForEmployeeByNationality()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 433);

        if (! $answer || is_null($answer->value)) {
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
}
