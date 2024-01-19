<?php

namespace App\Models\Traits;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Category;
use App\Models\Tenant\Initiative;
use App\Models\Tenant\Questionnaire;

trait DashboardData
{
    /**
     * Gender Charts
     */
    protected function parseDataForChartGenderScreen($data)
    {
        $labels = [
            __('Female'),
            __('Male'),
            __('Other'),
        ];

        $series = [
            $data["female"],
            $data["male"],
            $data["other"],
        ];

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    protected function parseDataPercentageForChartGenderScreen($data)
    {

        $labels = [
            __('Female'),
            __('Male'),
            __('Other'),
        ];

        $total = array_sum($data);

        $series = [
            round($data["female"] * 100 / $total, 2),
            round($data["male"] * 100 / $total, 2),
            round($data["other"] * 100 / $total, 2),
        ];

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    protected function parseDataForChartCo2EmissionsForScreen($data)
    {
        return [
            [
                'name' => __('Scope 1 Emissions'),
                'value' => $data['s1'] ?? null,
            ],
            [
                'name' => __('Scope 2 Emissions'),
                'value' => $data['s2'] ?? null,
            ],
            [
                'name' => __('Scope 3 Emissions'),
                'value' => $data['s3'] ?? null,
            ],
        ];
    }

    protected function parseDataForChartEmployeeSalaryAvg($data)
    {
        return $data['salary1'] && $data['salary2']
            ? round($data['salary1'] / $data['salary2'], 2)
            : null;
    }
}
