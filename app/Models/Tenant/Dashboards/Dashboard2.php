<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Sdg;
use App\Models\Traits\Dashboard;

class Dashboard2
{
    use Dashboard;

    protected $answers;

    public function view($questionnaireId)
    {
        $this->setQuestionnaire($questionnaireId);
        $this->parsePosition();
        $sdgs = Sdg::all();

        $charts = [
            'action_plan' => $this->parseDataForChartActionPlan(),
            'action_plan_table' => $this->parseDataForChartActionPlanTable(),
            'co2_emissions' => $this->parseDataForChartCo2EmissionsForScreen(),
            //'employees_salary' => $this->parseDataForChartEmployeeSalaryAvg(),
            'sdgs_top5' => !empty($this->parseDataForChartSdgsTop5(460)) ? $this->parseDataForChartSdgsTop5(460) : array_keys($this->parseDataForChartSdgsTop5(32162)),
            'badges' => $this->parseDataForBadgesForScreen(),
            'checkboxs' => $this->parseDataForcheckboxForScreen(),
            'energy_consumption' => $this->parseDataForEnergyConsumption(),
            'water_consumption' => $this->parseDataForWaterConsumption(),
            'waste_generated' => $this->parseDataForWasteGenerated(),
            'recycled_waste' => $this->parseDataForRecycledWaste(),
            'hazardous_waste' => $this->parseDataForHazardousWaste(),
            'gender_equility_employees' => $this->parseDataForChartGenderChart(432),
            'outsourced_workers' => $this->parseDataForChartGenderChart(487),
            'gender_equality_executives' => $this->parseDataForChartGenderChart(492),
            'gender_equality_leadership' => $this->parseDataForChartGenderChart(435),
            'gender_high_governance_body' => $this->parseDataForChartGenderChart(466),
            'layoffs' => $this->parseDataForLayoffs(),
            'work_days_lost' => $this->parseDataForWorkDaysLost(),
            'annual_reporting' => $this->parseDataForAnnualReporting(),
        ];

        $data = $this->getQuestionnaireName($this->questionnaire['id']);

        return tenantView(
            request()->print == 'true' ? 'tenant.dashboards.reports.2' : 'tenant.dashboards.2',
            [
                'questionnaire' => $this->questionnaire,
                'charts' => $charts,
                'sdgs' => $sdgs,
                'questionnaireinfo' => $data,
                'business_sector' => $data->company->business_sector()->first(),
                'country' => getCountriesWhereIn([$data->company->country]),
                'report' => $this->getReportData(request()->print),
            ]
        );
    }

    public function getReportData($printView = null)
    {
        if ($printView == 'true') {
            return [
                'environmental_policy' => $this->parseDataForCheckbox(1262),
                'green_bonds' => $this->parseDataForCheckbox(1297),
                'impact_strategy' => $this->parseDataForCheckbox(472),
                'monitoring' => $this->parseDataForCheckbox(1261),
                'adherence' => $this->parseDataForCheckbox(481),
                'atmospheric_pollutants' => $this->parseDataForAtmosphericPollutants(),
                'ozone_depleting' => $this->parseDataForOzoneDepleting(),
                'ghg_emissions' => $this->parseDataForCheckbox(477),
                'climate_impact' => $this->parseDataForCheckbox(1287),
                'fossil_fuel' => $this->parseDataForCheckbox(1288),
                'reduce_energy' => $this->parseDataForCheckbox(479),
                'energy_consumption_sources' => $this->parseDataForEnergyConsumptionSources(),
                'non_renewable' => $this->parseDataForNonRenewable(),
                'waste_treated' => $this->parseDataForWasteTreated(),
                'water_discharge' => $this->parseDataForCheckbox(1281),
                'water_emissions' => $this->parseDataForCheckbox(1282),
                'water_emissions_type' => $this->parseDataForWaterEmissionsType(),
                'impact_biodiversity' => $this->parseDataForCheckbox(1285),
                'organization_sensitive_area' => $this->parseDataForCheckbox(1286),
                'manufacture_sale_control' => $this->parseDataForCheckbox(1289),
                'manufacture_pesticides' => $this->parseDataForCheckbox(1290),
                'contribute_soil_degradation' => $this->parseDataForCheckbox(1291),
                'soil_use_practices' => $this->parseDataForCheckbox(1292),
                'forestry' => $this->parseDataForCheckbox(1295),
                'new_construction' => $this->parseDataForCheckbox(1298),
                'construction_renovation' => $this->parseDataForConstructionRenovation(),
                'remuneration_policy' => $this->parseDataForCheckbox(493),
                'provision_data_salary' => $this->parseDataForCheckbox(494),
                'osh' => $this->parseDataForCheckbox(442),
                'gender_pay_gap' => $this->parseDataForGenderPayGap(),
                'contract_workers' => $this->parseDataForContractWorkers(),
                'code_ethics_conduct' => $this->parseDataForCheckbox(444),
                'code_conduct_suppliers' => $this->parseDataForCheckbox(449),
                'training_code_ethics' => $this->parseDataForCheckbox(464),
                'organization_salary' => $this->parseDataForCheckbox(488),
                'occupational_safety' => $this->parseDataForCheckbox(490),
                'distribution_gender' => $this->parseDataForDistributionGender(),
                'customer_data_privacy' => $this->parseDataForCheckbox(443),
                'supplier_selection_policy' => $this->parseDataForCheckbox(448),
                'incidents_discrimination' => $this->parseDataForIncidentsDiscrimination(1270),
                'case_violation_human' => $this->parseDataForCheckbox(1267),
                'diligence_process_identify' => $this->parseDataForCheckbox(1268),
                'web_site' =>  $this->parseDataForCheckbox(428),
                'institutional_presentation' =>  $this->parseDataForCheckbox(467),
                'organizational_structure' => $this->parseDataForCheckbox(429),
                'presentation_annual_reports' => $this->parseDataForCheckbox(496),
                'constitution' => $this->parseDataForConstitution(),
                'president_ceo' => $this->parseDataForCheckbox(431),
                'corruption_risk' => $this->parseDataForCheckbox(457),
                'policy_to_prevent' => $this->parseDataForCheckbox(456),
                'complaints_from_workers' => $this->parseDataForCheckbox(445),
                'complaints_from_customers' => $this->parseDataForCheckbox(446),
                'complaints_from_suppliers' => $this->parseDataForCheckbox(447),
                'accidents_at_work_during' => $this->parseDataForAccidentsAtWorkDuring(),
                'statement_responsability' =>  $this->parseDataForStatementResponsability(),
                'high_water_stress' => $this->parseDataForCheckbox(1284),
                'exploration_seas' => $this->parseDataForCheckbox(1293),
                'recycled_water' => $this->parseDataForRecycledWater(),
            ];
        }

        return [];
    }

    public function parsePosition()
    {
        $questionIds = array_unique([
            432, 487, 492, 435, 504, 1264, 513, 466, 461,
            509, 510, 1339, 500, 1276, 1276, 1277, 1262, 1297, 472,
            1261, 481, 1272, 477, 1287, 1288, 497, 511, 479, 1274, 1281,
            1282, 1283, 1285, 1286, 1289, 1290, 1291, 1292, 1295, 1298,
            1299, 493, 494, 442, 1337, 1338, 441, 444, 464, 488, 490, 443,
            448, 1270, 1267, 1268, 428, 467, 429, 496, 430, 431, 449, 445,
            446, 447, 457, 456, 31009, 31107, 1284, 1293, 1280,
            445, 446, 442, 447, 443, 444, 448, 449, 456, 457, 1262,
            447, 479, 493, 496, 1262, 451, 452, 472, 474, 446, 443,
            432, 435, 466, 492, 448, 452, 451, 475, 459, 477, 479,
            753, 428, 467, 429, 442, 443, 449, 445, 446, 447, 448, 444, 1262, 457,
            456, 477, 479, 493, 436, 437, 31109, 31111,
        ]);
        $this->answers = $this->answers->whereIn('question_id', $questionIds)->sortBy('question_id');
    }

    /**
     * Badges >> screen
     */
    protected function parseDataForBadgesForScreen()
    {
        $badges = [];
        $badges[1] = true;
        $badges[2] = true;

        $answer = $this->answers[445] ?? null;
        $badge3Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[446] ?? null;
        $badge3Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[447] ?? null;
        $badge3Validate3 = isset($answer->value) ? $answer->value : null;

        $badges[3] = $badge3Validate1 == 'yes' && $badge3Validate2 == 'yes' && $badge3Validate3 == 'yes';

        $answer = $this->answers[442] ?? null;
        $badge4Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[443] ?? null;
        $badge4Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[444] ?? null;
        $badge4Validate3 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[448] ?? null;
        $badge4Validate4 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[449] ?? null;
        $badge4Validate5 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[456] ?? null;
        $badge4Validate6 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[457] ?? null;
        $badge4Validate7 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[1262] ?? null;
        $badge4Validate8 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[477] ?? null;
        $badge4Validate9 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[479] ?? null;
        $badge4Validate10 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[493] ?? null;
        $badge4Validate11 = isset($answer->value) ? $answer->value : null;

        $badges[4] = $badge4Validate1 == 'yes'
            && $badge4Validate2 == 'yes'
            && $badge4Validate3 == 'yes'
            && $badge4Validate4 == 'yes'
            && $badge4Validate5 == 'yes'
            && $badge4Validate6 == 'yes'
            && $badge4Validate7 == 'yes'
            && $badge4Validate8 == 'yes'
            && $badge4Validate9 == 'yes'
            && $badge4Validate10 == 'yes'
            && $badge4Validate11 == 'yes';

        // Query with this question. return true/false insted of number.
        $answer = $this->answers[496] ?? null;
        $badges[5] = isset($answer->value) && $answer->value === 'yes' ? true : false;

        $answer = $this->answers[1262] ?? null;
        $badge6Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[451] ?? null;
        $badge6Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[452] ?? null;
        $badge6Validate3 = isset($answer->value) ? $answer->value : null;

        $badges[6] = $badge6Validate1 == 'yes'
            && $badge6Validate2 == 'yes'
            && $badge6Validate3 == 'yes';

        $answer = $this->answers[472] ?? null;
        $badge7Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[474] ?? null;
        $badge7Validate2 = isset($answer->value) ? $answer->value : null;

        $badges[7] = $badge7Validate1 == 'yes' && $badge7Validate2 == 'yes' ? true : false;

        $answer = $this->answers[446] ?? null;
        $badge8Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[443] ?? null;
        $badge8Validate2 = isset($answer->value) ? $answer->value : null;

        $badges[8] = $badge8Validate1 == 'yes' && $badge8Validate2 == 'yes' ? true : false;

        $answer = $this->answers[432] ?? null;
        $badge9Validate1 = isset($answer->value) ? json_decode($answer->value, true) : null;

        $answer = $this->answers[435] ?? null;
        $badge9Validate2 = isset($answer->value) ? json_decode($answer->value, true) : null;

        $answer = $this->answers[466] ?? null;
        $badge9Validate3 = isset($answer->value) ? json_decode($answer->value, true) : null;

        $answer = $this->answers[492] ?? null;
        $badge9Validate4 = isset($answer->value) ? json_decode($answer->value, true) : null;

        $badge9 = false;

        if ($badge9Validate1) {
            $percentage = calculatePercentage($badge9Validate1[151] ?? 0, array_sum($badge9Validate1));
            $badge9 = $percentage >= 40 && $percentage <= 60;
        }

        if ($badge9Validate2) {
            $percentage = calculatePercentage($badge9Validate2[151] ?? 0, array_sum($badge9Validate2));
            $badge9 = $percentage >= 40 && $percentage <= 60;
        }

        if ($badge9Validate3) {
            $percentage = calculatePercentage($badge9Validate3[151] ?? 0, array_sum($badge9Validate3));
            $badge9 = $percentage >= 40 && $percentage <= 60;
        }

        if ($badge9Validate4) {
            $percentage = calculatePercentage($badge9Validate4[151] ?? 0, array_sum($badge9Validate4));
            $badge9 = $percentage >= 40 && $percentage <= 60;
        }

        $badges[9] = $badge9;

        $answer = $this->answers[448] ?? null;
        $badge10Validate1 = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $badges[10] = $badge10Validate1 == 'yes' ? true : false;

        $answer = $this->answers[451] ?? null;
        $badge11Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[452] ?? null;
        $badge11Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[475] ?? null;
        $badge11Validate3 = isset($answer->value) ? $answer->value : null;

        $badges[11] = $badge11Validate1 == 'yes'
            && $badge11Validate2 == 'yes'
            && $badge11Validate3 == 'yes';

        $answer = $this->answers[459] ?? null;
        $badges[12] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[477] ?? null;
        $badges[13] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[479] ?? null;
        $badges[14] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[753] ?? null;
        $badges[15] = isset($answer->value) && $answer->value >= 1 ? true : false;

        // Query with this question. upload document related to library
        $badges[16] = true;

        return $badges;
    }

    /**
     * Checkbox >> screen
     */
    protected function parseDataForcheckboxForScreen()
    {
        $answer = $this->answers[428] ?? null;
        $checkbox['website'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[467] ?? null;
        $checkbox['institutional'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[429] ?? null;
        $checkbox['chart'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[442] ?? null;
        $checkbox['health_and_safety_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[443] ?? null;
        $checkbox['customer_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[449] ?? null;
        $checkbox['code_of_ethics_supplier'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[445] ?? null;
        $chk4Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[446] ?? null;
        $chk4Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[447] ?? null;
        $chk4Validate3 = isset($answer->value) ? $answer->value : null;

        $checkbox['complaint_mechanisms'] = $chk4Validate1 == 'yes'
            && $chk4Validate2 == 'yes'
            && $chk4Validate3 == 'yes';

        $answer = $this->answers[448] ?? null;
        $checkbox['supplier_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[444] ?? null;
        $checkbox['code_of_ethics_conduct'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[1262] ?? null;
        $checkbox['environmental_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[457] ?? null;
        $checkbox['corruption_risk'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[456] ?? null;
        $checkbox['policy_to_prevent'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[477] ?? null;
        $checkbox['reducing_emissions'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[479] ?? null;
        $checkbox['energy_consumption'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[493] ?? null;
        $checkbox['remuneration_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        return $checkbox;
    }

    /**
     * Employee avg salary >> screen NOT USED.
     */
    protected function parseDataForChartEmployeeSalaryAvg()
    {
        $answer = $this->answers[436];
        $salary1 = isset($answer->value) ? json_decode($answer->value, true)[1] : 0;

        $answer = $this->answers[437];
        $salary2 = isset($answer->value) ? json_decode($answer->value, true)[1] : 0;

        return $salary1 && $salary2 ? round($salary1 / $salary2, 2) : '-';
    }

    /**
     * Co2 Emissions
     */
    protected function parseDataForChartCo2EmissionsForScreen()
    {
        $answer1 = $this->answers[461] ?? null;
        $answer2 = $this->answers[509] ?? null;
        $answer3 = $this->answers[510] ?? null;

        $value1 = isset($answer1->value) ? json_decode($answer1->value, true) : null;
        $value2 = isset($answer2->value) ? json_decode($answer2->value, true) : null;
        $value3 = isset($answer3->value) ? json_decode($answer3->value, true) : null;

        $labels = [__('Scope 1'), __('Scope 2'), __('Scope 3')];
        $data = [$value1[1] ?? null, $value2[1] ?? null, $value3[1] ?? null];

        $parseData = removeNullDataFromArray($labels, $data);

        if ($parseData == null) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    protected function parseDataForEnergyConsumption()
    {
        $answers1 = $this->answers['1339'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return $value1;
    }

    protected function parseDataForWaterConsumption()
    {
        $answers1 = $this->answers['500'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return $value1;
    }

    protected function parseDataForWasteGenerated()
    {
        $answers1 = $this->answers['1276'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if (! isset($value1['343']) || is_numeric($value1['343']) == false) {
            return null;
        }

        return $value1['343'] ?? null;
    }

    protected function parseDataForRecycledWaste()
    {
        $answers1 = $this->answers['1276'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if (! isset($value1['1290']) || is_numeric($value1['1290']) == false) {
            return null;
        }

        return $value1['1290'] ?? null;
    }

    protected function parseDataForRecycledWater()
    {
        $answers1 = $this->answers['1280'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return $value1;
    }

    protected function parseDataForHazardousWaste()
    {
        $answers1 = $this->answers['1277'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return array_sum(removeBooleanFromArray($value1));
    }

    protected function parseDataForChartGenderChart($questionId = 432)
    {
        $answers1 = $this->answers[$questionId] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = [
            __('Women'),
            __('Male'),
            __('Other'),
        ];

        $series = [
            $value1['151'] ?? 0,
            $value1['152'] ?? 0,
            $value1['8'] ?? 0,
        ];

        if (empty(array_filter($series))) {
            return null;
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    protected function parseDataForLayoffs()
    {
        $answers1 = $this->answers['504'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return $value1;
    }

    protected function parseDataForWorkDaysLost()
    {
        $answers1 = $this->answers['1264'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return $value1;
    }

    protected function parseDataForAnnualReporting()
    {
        $answers1 = $this->answers['513'] ?? null;

        return isset($answers1->value) ? json_decode($answers1->value, true) : [];
    }

    protected function parseDataForCheckbox($questionId = 1262)
    {
        $answers1 = $this->answers[$questionId] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1 == 'yes' ? 1 : 0;
    }

    protected function parseDataForAtmosphericPollutants()
    {
        $answers1 = $this->answers[1272] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $labels = [__('Atmospheric pollutants')];
        $data = [$value1[1288] ?? null];

        $parseData = removeNullDataFromArray($labels, $data);

        if ($parseData == null) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    protected function parseDataForOzoneDepleting()
    {
        $answers1 = $this->answers[1272] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $labels = [__('Ozone depleting substances')];
        $data = [$value1[1289] ?? null];

        $parseData = removeNullDataFromArray($labels, $data);

        if ($parseData == null) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    protected function parseDataForEnergyConsumptionSources()
    {
        $answers1 = $this->answers[497] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers[511] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $labels = [__('Renewable'), __('Non-renewable')];
        $data = [
            $value1 ?? 0,
            $value2 ?? 0,
        ];

        if (empty(array_filter($data))) {
            return null;
        }

        return [
            'labels' => $labels,
            'series' => $data,
        ];
    }

    protected function parseDataForNonRenewable()
    {
        $answers1 = $this->answers[1274] ?? null;

        return isset($answers1->value) ? json_decode($answers1->value, true) : [];
    }

    protected function parseDataForWasteTreated()
    {
        $answers1 = $this->answers[1276] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = [__('Treated or recycled'), __('Sent for disposal')];
        $data = [
            isset($value1[1290]) && is_numeric($value1[1290]) ? $value1[1290] : 0,
            isset($value1[1291]) && is_numeric($value1[1291]) ? $value1[1291] : 0,
        ];

        if (empty(array_filter($data))) {
            return null;
        }

        return [
            'labels' => $labels,
            'series' => $data,
        ];
    }

    protected function parseDataForWaterEmissionsType()
    {
        $answers1 = $this->answers[1283] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = [__('Physico-chemical'), __('Undesirable substances'), __('Toxic substances')];
        $data = [
            ($value1[1297] ?? 0) + ($value1[1298] ?? 0) + ($value1[1299] ?? 0) + ($value1[1300] ?? 0) + ($value1[1302] ?? 0) + ($value1[1309] ?? 0),
            (
                ($value1[1294] ?? 0) + ($value1[1301] ?? 0) + ($value1[1303] ?? 0) + ($value1[1304] ?? 0) + ($value1[1305] ?? 0) + ($value1[1306] ?? 0) +
                ($value1[1307] ?? 0) + ($value1[1308] ?? 0) + ($value1[1310] ?? 0) + ($value1[1311] ?? 0) + ($value1[1312] ?? 0) + ($value1[1313] ?? 0) +
                ($value1[1319] ?? 0) + ($value1[1325] ?? 0)
            ),
            (
                ($value1[1314] ?? 0) + ($value1[1315] ?? 0) + ($value1[1316] ?? 0) + ($value1[1317] ?? 0) + ($value1[1318] ?? 0) + ($value1[1320] ?? 0) +
                ($value1[1321] ?? 0) + ($value1[1322] ?? 0) + ($value1[1323] ?? 0) + ($value1[1324] ?? 0)
            ),
        ];

        if (empty(array_filter($data))) {
            return null;
        }

        return [
            'labels' => $labels,
            'series' => $data,
        ];
    }

    protected function parseDataForConstructionRenovation()
    {
        $answers1 = $this->answers[1299] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        return calculatePercentage($value1[1327] ?? 0, $value1[1326] ?? 0);
    }

    protected function parseDataForGenderPayGap()
    {
        $answers1 = $this->answers[1338] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers[1337] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        return calculatePercentage((($value1 ?? 0) - ($value2 ?? 0)), $value1 ?? 0);
    }

    protected function parseDataForDistributionGender()
    {
        $answers1 = $this->answers[487] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = [__('Men'), __('Woman'), __('Other')];
        $data = [
            $value1[151] ?? 0,
            $value1[152] ?? 0,
            $value1[8] ?? 0,
        ];

        if (empty(array_filter($data))) {
            return null;
        }

        return [
            'labels' => $labels,
            'series' => $data,
        ];
    }

    protected function parseDataForContractWorkers()
    {
        $answers1 = $this->answers[441] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        return array_sum(removeBooleanFromArray($value1));
    }

    protected function parseDataForIncidentsDiscrimination()
    {
        $answers1 = $this->answers[1270] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return $value1;
    }

    protected function parseDataForConstitution()
    {
        $answers1 = $this->answers[430] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        if ($value1 == null) {
            return null;
        }

        return str_replace('-', ' ', $value1);
    }

    protected function parseDataForAccidentsAtWorkDuring()
    {
        $answers1 = $this->answers[31109] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return $value1;
    }

    protected function parseDataForStatementResponsability()
    {
        $answers1 = $this->answers[31111] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return $value1;
    }
}
