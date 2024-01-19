<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\ReportingPeriod;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportingPeriodSeeder extends Seeder
{
    //  php artisan tenant:seed --class=Database\\Seeders\\Tenant\\ReportingPeriodSeeder

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hasReportingPeriodForLastYear = ReportingPeriod::where('type', 'year')
            ->where('name', carbon()->subYears(1)->year)
            ->count();

        if ($hasReportingPeriodForLastYear > 0) {
            return;
        }

        $year = carbon()->subYears(1)->year;

        $yearData = [
            'type' => 'year',
            'name' => $year,
            "year" => $year, // "year" is required for the "ReportingPeriodsTrait.php
            'custom_name' => $year,
            'id_year' => null,
            'id_semester' => null,
            'id_quarter' => null,
            'typeOrder' => 1,
            'enabled_questionnaires_filter' => true,
            'enabled_questionnaires_reporting' => true,
            'enabled_monitoring_filter' => true,
            'enabled_monitoring_reporting' => true,
        ];

        $yearCreated = ReportingPeriod::create($yearData);

        for ($i = 0; $i < 2; $i++) {
            $semesterData = [
                'type' => 'semester',
                'name' => "{$year} - " . ($i + 1) . "st semester",
                "year" => $year,
                'custom_name' => "{$year} - " . ($i + 1) . "st semester",
                'id_year' => $yearCreated->id,
                'id_semester' => null,
                'id_quarter' => null,
                'typeOrder' => $i + 1,
            ];

            $semesterCreated = ReportingPeriod::create($semesterData);
            $quarterInit = ReportingPeriod::where('type', 'quarter')->orderBy('id', 'desc')->count();

            for ($j = $quarterInit; $j < ($quarterInit + 2); $j++) {
                $quarterData = [
                    'type' => 'quarter',
                    'name' => "{$year} - " . ($j + 1) . "st quarter",
                    "year" => $year,
                    'custom_name' => "{$year} - " . ($j + 1) . "st quarter",
                    'id_year' => $yearCreated->id,
                    'id_semester' => $semesterCreated->id,
                    'id_quarter' => null,
                    'typeOrder' => $j + 1,
                ];

                $quarterCreated = ReportingPeriod::create($quarterData);
                $monthInit = ReportingPeriod::where('type', 'month')->orderBy('id', 'desc')->count();

                for ($k = $monthInit; $k < ($monthInit + 3); $k++) {
                    $monthData = [
                        'type' => 'month',
                        "year" => $year,
                        'id_year' => $yearCreated->id,
                        'id_semester' => $semesterCreated->id,
                        'id_quarter' => $quarterCreated->id,
                        'name' => $year . ' - ' . carbon()->startOfYear()->addMonths($k)->format('F'),
                        'custom_name' => $year . ' - ' . carbon()->startOfYear()->addMonths($k)->format('F'),
                        'typeOrder' => $k + 1,
                    ];

                    ReportingPeriod::create($monthData);
                }
            }
        }
    }
}
