<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Answer;
use App\Models\Tenant\QuestionOptions\Simple;
use App\Models\Tenant\Sdg;
use App\Models\Traits\Dashboard;

class Dashboard7
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
            'sdgs_top5' => array_keys($this->parseDataForChartSdgsTop5(31085)),
            'badges' => $this->parseDataForBadgesForScreen(),
            'checkboxs' => $this->parseDataForcheckboxForScreen(),
            'energy_consumption' => $this->parseDataForEnergyConsumption(),
            'water_consumption' => $this->parseDataForWaterConsumption(),
            'waste_generated' => $this->parseDataForWasteGenerated(),
            'recycled_waste' => $this->parseDataForRecycledWaste(),
            'hazardous_waste' => $this->parseDataForHazardousWaste(),
            'gender_equility_employees' => $this->parseDataForChartGenderChart(30989),
            'outsourced_workers' => $this->parseDataForChartGenderChart(30986),
            'gender_equality_executives' => $this->parseDataForChartGenderChart(30990),
            'gender_equality_leadership' => $this->parseDataForChartGenderChart(30991),
            'gender_high_governance_body' => $this->parseDataForChartGenderChart(30983),
            'layoffs' => $this->parseDataForLayoffs(),
            'work_days_lost' => $this->parseDataForWorkDaysLost(),
            'annual_reporting' => $this->parseDataForAnnualReporting(),
        ];

        $data = $this->getQuestionnaireName($this->questionnaire['id']);

        return tenantView(
            request()->print == 'true' ? 'tenant.dashboards.reports.7' : 'tenant.dashboards.7',
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
                'environmental_policy' => $this->parseDataForCheckbox(31061),
                'green_bonds' => $this->parseDataForCheckbox(31077),
                'impact_strategy' => $this->parseDataForCheckbox(31080),
                'monitoring' => $this->parseDataForCheckbox(31031),
                'adherence' => $this->parseDataForCheckbox(31073),
                'atmospheric_pollutants' => $this->parseDataForAtmosphericPollutants(),
                'ozone_depleting' => $this->parseDataForOzoneDepleting(),
                'ghg_emissions' => $this->parseDataForCheckbox(31040),
                'climate_impact' => $this->parseDataForCheckbox(31065),
                'fossil_fuel' => $this->parseDataForCheckbox(31066),
                'reduce_energy' => $this->parseDataForCheckbox(31046),
                'energy_consumption_sources' => $this->parseDataForEnergyConsumptionSources(),
                'non_renewable' => $this->parseDataForNonRenewable(),
                'waste_treated' => $this->parseDataForWasteTreated(),
                'water_discharge' => $this->parseDataForCheckbox(31058),
                'water_emissions' => $this->parseDataForCheckbox(31059),
                'water_emissions_type' => $this->parseDataForWaterEmissionsType(),
                'impact_biodiversity' => $this->parseDataForCheckbox(31063),
                'organization_sensitive_area' => $this->parseDataForCheckbox(31064),
                'manufacture_sale_control' => $this->parseDataForCheckbox(31067),
                'manufacture_pesticides' => $this->parseDataForCheckbox(31068),
                'contribute_soil_degradation' => $this->parseDataForCheckbox(31069),
                'soil_use_practices' => $this->parseDataForCheckbox(31070),
                'forestry' => $this->parseDataForCheckbox(31075),
                'new_construction' => $this->parseDataForCheckbox(31078),
                'construction_renovation' => $this->parseDataForConstructionRenovation(),
                'remuneration_policy' => $this->parseDataForCheckbox(30997),
                'provision_data_salary' => $this->parseDataForCheckbox(31001),
                'osh' => $this->parseDataForCheckbox(31006),
                'gender_pay_gap' => $this->parseDataForGenderPayGap(),
                'contract_workers' => $this->parseDataForContractWorkers(),
                'code_ethics_conduct' => $this->parseDataForCheckbox(31011),
                'code_conduct_suppliers' => $this->parseDataForCheckbox(31018),
                'training_code_ethics' => $this->parseDataForCheckbox(31013),
                'organization_salary' => $this->parseDataForCheckbox(30987),
                'occupational_safety' => $this->parseDataForCheckbox(30988),
                'distribution_gender' => $this->parseDataForDistributionGender(),
                'customer_data_privacy' => $this->parseDataForCheckbox(31015),
                'supplier_selection_policy' => $this->parseDataForCheckbox(31016),
                'incidents_discrimination' => $this->parseDataForIncidentsDiscrimination(31029),
                'case_violation_human' => $this->parseDataForCheckbox(31026),
                'diligence_process_identify' => $this->parseDataForCheckbox(31027),
                'web_site' =>  $this->parseDataForCheckbox(30979),
                'institutional_presentation' =>  $this->parseDataForCheckbox(30980),
                'organizational_structure' => $this->parseDataForCheckbox(30981),
                'presentation_annual_reports' => $this->parseDataForCheckbox(31086),
                'constitution' => $this->parseDataForConstitution(),
                'president_ceo' => $this->parseDataForCheckbox(30984),
                'corruption_risk' => $this->parseDataForCheckbox(31019),
                'policy_to_prevent' => $this->parseDataForCheckbox(31020),
                'complaints_from_workers' => $this->parseDataForCheckbox(31021),
                'complaints_from_customers' => $this->parseDataForCheckbox(31022),
                'complaints_from_suppliers' => $this->parseDataForCheckbox(31023),
                'accidents_at_work_during' => $this->parseDataForAccidentsAtWorkDuring(),
                'statement_responsability' =>  $this->parseDataForStatementResponsability(),
                'high_water_stress' => $this->parseDataForCheckbox(31062),
                'exploration_seas' => $this->parseDataForCheckbox(31071),
                'recycled_water' => $this->parseDataForRecycledWater(),
                'color_leadership_roles' => $this->parseDataForColorLeadershipRoles(),
                'minority_groups' => $this->parseDataForMinorityGroups(),
                'opportunity_targets_minority' => $this->parseDataForOpportunityTargetsMinority(),
                'diversity_literacy' => $this->parseDataForCheckbox(30996),
            ];
        }

        return [];
    }

    public function parsePosition()
    {
        $questionIds = array_unique([
            31021, 31022, 31023, 31006, 31015, 31011, 31016, 31018, 31020, 31019, 31061,
            31040, 31046, 30997, 31086, 31061, 31032, 31041, 31080, 31082, 31022, 31015,
            30989, 30991, 30983, 30990, 31016, 31032, 31041, 31083, 31084, 31040, 31046,
            31074, 30979, 30980, 30981, 31006, 31015, 31018, 31021, 31022, 31023, 31016,
            31011, 31061, 31019, 31020, 31040, 31046, 30997, 31037, 31038, 31039, 31047,
            31055, 31049, 31049, 31051, 31085, 30989, 30986, 30990, 30991, 30993, 31010,
            30513, 30983, 31002, 31003, 31057, 30982, 31061, 31077, 31080, 31031, 31073,
            31058, 31059, 31063, 31064, 31067, 31068, 31069, 31070, 31075, 31040, 31065,
            31078, 30997, 31001, 31006, 31011, 31018, 31013, 30987, 30988, 31066, 31046,
            31015, 31016, 31029, 31026, 31027, 30979, 30980, 30981, 31086, 30984, 31019,
            31020, 31021, 31022, 31023, 31062, 31071, 31000, 30999, 30992, 30994, 30996,
            30995, 31009, 31107, 31087,
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

        $answer = $this->answers[31021] ?? null;
        $badge3Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31022] ?? null;
        $badge3Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31023] ?? null;
        $badge3Validate3 = isset($answer->value) ? $answer->value : null;

        $badges[3] = $badge3Validate1 == 'yes' && $badge3Validate2 == 'yes' && $badge3Validate3 == 'yes';

        $answer = $this->answers[31006] ?? null;
        $badge4Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31015] ?? null;
        $badge4Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31011] ?? null;
        $badge4Validate3 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31016] ?? null;
        $badge4Validate4 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31018] ?? null;
        $badge4Validate5 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31020] ?? null;
        $badge4Validate6 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31019] ?? null;
        $badge4Validate7 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31061] ?? null;
        $badge4Validate8 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31040] ?? null;
        $badge4Validate9 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31046] ?? null;
        $badge4Validate10 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[30997] ?? null;
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
        $answer = $this->answers[31086] ?? null;
        $badges[5] = isset($answer->value) && $answer->value === 'yes' ? true : false;

        $answer = $this->answers[31061] ?? null;
        $badge6Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31032] ?? null;
        $badge6Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31041] ?? null;
        $badge6Validate3 = isset($answer->value) ? $answer->value : null;

        $badges[6] = $badge6Validate1 == 'yes'
            && $badge6Validate2 == 'yes'
            && $badge6Validate3 == 'yes';

        $answer = $this->answers[31080] ?? null;
        $badge7Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31082] ?? null;
        $badge7Validate2 = isset($answer->value) ? $answer->value : null;

        $badges[7] = $badge7Validate1 == 'yes' && $badge7Validate2 == 'yes' ? true : false;

        $answer = $this->answers[31022] ?? null;
        $badge8Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31015] ?? null;
        $badge8Validate2 = isset($answer->value) ? $answer->value : null;

        $badges[8] = $badge8Validate1 == 'yes' && $badge8Validate2 == 'yes' ? true : false;

        $answer = $this->answers[30989] ?? null;
        $badge9Validate1 = isset($answer->value) ? json_decode($answer->value, true) : null;

        $answer = $this->answers[30991] ?? null;
        $badge9Validate2 = isset($answer->value) ? json_decode($answer->value, true) : null;

        $answer = $this->answers[30983] ?? null;
        $badge9Validate3 = isset($answer->value) ? json_decode($answer->value, true) : null;

        $answer = $this->answers[30990] ?? null;
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

        $answer = $this->answers[31016] ?? null;
        $badge10Validate1 = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $badges[10] = $badge10Validate1 == 'yes' ? true : false;

        $answer = $this->answers[31032] ?? null;
        $badge11Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31041] ?? null;
        $badge11Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31083] ?? null;
        $badge11Validate3 = isset($answer->value) ? $answer->value : null;

        $badges[11] = $badge11Validate1 == 'yes'
            && $badge11Validate2 == 'yes'
            && $badge11Validate3 == 'yes';

        $answer = $this->answers[31084] ?? null;
        $badges[12] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31040] ?? null;
        $badges[13] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31046] ?? null;
        $badges[14] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31074] ?? null;
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
        $answer = $this->answers[30979] ?? null;
        $checkbox['website'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[30980] ?? null;
        $checkbox['institutional'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[30981] ?? null;
        $checkbox['chart'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31006] ?? null;
        $checkbox['health_and_safety_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31015] ?? null;
        $checkbox['customer_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31018] ?? null;
        $checkbox['code_of_ethics_supplier'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31021] ?? null;
        $chk4Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31022] ?? null;
        $chk4Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = $this->answers[31023] ?? null;
        $chk4Validate3 = isset($answer->value) ? $answer->value : null;

        $checkbox['complaint_mechanisms'] = $chk4Validate1 == 'yes'
            && $chk4Validate2 == 'yes'
            && $chk4Validate3 == 'yes';

        $answer = $this->answers[31016] ?? null;
        $checkbox['supplier_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31011] ?? null;
        $checkbox['code_of_ethics_conduct'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31061] ?? null;
        $checkbox['environmental_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31019] ?? null;
        $checkbox['corruption_risk'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31020] ?? null;
        $checkbox['policy_to_prevent'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31040] ?? null;
        $checkbox['reducing_emissions'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[31046] ?? null;
        $checkbox['energy_consumption'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = $this->answers[30997] ?? null;
        $checkbox['remuneration_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        return $checkbox;
    }

    /**
     * Employee avg salary >> screen NOT USED.
     */
    protected function parseDataForChartEmployeeSalaryAvg()
    {
        $answer = $this->answers[31002];
        $salary1 = isset($answer->value) ? json_decode($answer->value, true)[1] : 0;

        $answer = $this->answers[31003];
        $salary2 = isset($answer->value) ? json_decode($answer->value, true)[1] : 0;

        return $salary1 && $salary2 ? round($salary1 / $salary2, 2) : '-';
    }

    /**
     * Co2 Emissions
     */
    protected function parseDataForChartCo2EmissionsForScreen()
    {
        $answer1 = $this->answers[31037] ?? null;
        $answer2 = $this->answers[31038] ?? null;
        $answer3 = $this->answers[31039] ?? null;

        $value1 = isset($answer1->value) ? json_decode($answer1->value, true) : null;
        $value2 = isset($answer2->value) ? json_decode($answer2->value, true) : null;
        $value3 = isset($answer3->value) ? json_decode($answer3->value, true) : null;

        $labels = [__('Scope 1'), __('Scope 2'), __('Scope 3')];
        $data = [$value1[1] ?? null, $value2[1] ?? null, $value3[1] ?? null];

        $parseData = removeNullDataFromArray($labels, $data);

        if ($parseData == null) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(31037);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
            'unit' => $unit ?? 't CO2 eq'
        ];
    }

    protected function parseDataForEnergyConsumption()
    {
        $answers1 = $this->answers['31047'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(31047);

        return ["value" => $value1, "unit" => $unit ?? "KWh"];
    }

    protected function parseDataForWaterConsumption()
    {
        $answers1 = $this->answers['31055'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(31055);

        return ["value" => $value1, "unit" => $unit ?? "m3"];
    }

    protected function parseDataForWasteGenerated()
    {
        $answers1 = $this->answers['31049'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if (! isset($value1['343']) || is_numeric($value1['343']) == false) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(31049);

        return ["value" => $value1['343'], "unit" => $unit ?? "t"];
    }

    protected function parseDataForRecycledWaste()
    {
        $answers1 = $this->answers['31049'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if (! isset($value1['1290']) || is_numeric($value1['1290']) == false) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(31049);

        return ["value" => $value1['1290'] ?? null, "unit" => $unit ?? "t"];
    }

    protected function parseDataForRecycledWater()
    {
        $answers1 = $this->answers[31057] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(31057);

        return ["value" => $value1 ?? null, "unit" => $unit ?? "t"];
    }

    protected function parseDataForHazardousWaste()
    {
        $answers1 = $this->answers['31051'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(31051);

        return ["value" => array_sum(removeBooleanFromArray($value1)), "unit" => $unit ?? "t"];
    }

    protected function parseDataForChartGenderChart($questionId = 30989)
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

        $unit = $this->getUnitByQuestionId($questionId);

        return [
            'labels' => $labels,
            'series' => $series,
            'unit' => $unit ?? '%'
        ];
    }

    protected function parseDataForLayoffs()
    {
        $answers1 = $this->answers['30993'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return $value1;
    }

    protected function parseDataForWorkDaysLost()
    {
        $answers1 = $this->answers['31010'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(31010);

        return ["value" => $value1, "unit" => $unit ?? "days"];
    }

    protected function parseDataForAnnualReporting()
    {
        $answers1 = $this->answers['31087'] ?? null;

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
        $answers1 = $this->answers[31034] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $labels = [__('Atmospheric pollutants')];
        $data = [$value1[1288] ?? null];

        $parseData = removeNullDataFromArray($labels, $data);

        if ($parseData == null) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(31034);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
            'unit' => $unit ?? "t eq pollutants",
        ];
    }

    protected function parseDataForOzoneDepleting()
    {
        $answers1 = $this->answers[31034] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $labels = [__('Ozone depleting substances')];
        $data = [$value1[1289] ?? null];

        $parseData = removeNullDataFromArray($labels, $data);

        if ($parseData == null) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(31034);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
            'unit' => $unit ?? "t eq ODS",
        ];
    }

    protected function parseDataForEnergyConsumptionSources()
    {
        $answers1 = $this->answers[31043] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers[31044] ?? null;
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

        $unit = $this->getUnitByQuestionId(31043);

        return [
            'labels' => $labels,
            'series' => $data,
            'unit' => $unit ?? "%",
        ];
    }

    protected function parseDataForNonRenewable()
    {
        $answers1 = $this->answers[31045] ?? null;

        return isset($answers1->value) ? json_decode($answers1->value, true) : [];
    }

    protected function parseDataForWasteTreated()
    {
        $answers1 = $this->answers[31049] ?? null;
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

        $unit = $this->getUnitByQuestionId(31049);

        return [
            'labels' => $labels,
            'series' => $data,
            'unit' => $unit ?? "%",
        ];
    }

    protected function parseDataForWaterEmissionsType()
    {
        $answers1 = $this->answers[31060] ?? null;
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

        $unit = $this->getUnitByQuestionId(31060);

        return [
            'labels' => $labels,
            'series' => $data,
            'unit' => $unit ?? "%",
        ];
    }

    protected function parseDataForConstructionRenovation()
    {
        $answers1 = $this->answers[31079] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        return calculatePercentage($value1[1327] ?? 0, $value1[1326] ?? 0);
    }

    protected function parseDataForGenderPayGap() // query
    {
        $answers1 = $this->answers[31000] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers[30999] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(31000); // get the first question unit always

        return ["value" => calculatePercentage((($value1 ?? 0) - ($value2 ?? 0)), $value1 ?? 0), "unit" => $unit ?? "%"];
    }

    protected function parseDataForDistributionGender()
    {
        $answers1 = $this->answers[30986] ?? null;
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

        $unit = $this->getUnitByQuestionId(30986);

        return [
            'labels' => $labels,
            'series' => $data,
            'unit' => $unit ?? '%',
        ];
    }

    protected function parseDataForContractWorkers()
    {
        $answers1 = $this->answers[31005] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        return array_sum(removeBooleanFromArray($value1));
    }

    protected function parseDataForIncidentsDiscrimination()
    {
        $answers1 = $this->answers[31029] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return $value1;
    }

    protected function parseDataForConstitution()
    {
        $answers1 = $this->answers[30982] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        if ($value1 == null) {
            return null;
        }

        return str_replace('-', ' ', $value1);
    }

    protected function parseDataForAccidentsAtWorkDuring()
    {
        $answers1 = $this->answers[31009] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if (is_numeric($value1) == false) {
            return null;
        }

        return $value1;
    }

    protected function parseDataForStatementResponsability()
    {
        $answers1 = $this->answers[31107] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $labels = [];
        foreach ($value1 ?? [] as $key => $value) {
            $label = Simple::find($key)->label;
            array_push($labels, $label);
        }

        return $labels;
    }

    protected function parseDataForColorLeadershipRoles()
    {
        $answers1 = $this->answers[30992] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return null;
        }

        $labels = [__('Leadership roles'), __('Non')];
        $data = [
            $value1 ?? 0,
            100 - $value1 ?? 0,
        ];

        if (empty(array_filter($data))) {
            return null;
        }

        $unit = $this->getUnitByQuestionId(30992);

        return [
            'labels' => $labels,
            'series' => $data,
            'unit' => $unit ?? '%'
        ];
    }

    protected function parseDataForMinorityGroups()
    {
        $answers1 = $this->answers[30994] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        return ($value1 != null) ? array_keys($value1) : [];
    }

    protected function parseDataForOpportunityTargetsMinority()
    {
        $answers1 = $this->answers[30995] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        return ($value1 != null) ? $value1 : null;
    }
}
