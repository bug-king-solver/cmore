<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Sdg;
use App\Models\Traits\Dashboard;

class Dashboard2old
{
    use Dashboard;

    public function view($questionnaireId)
    {
        $this->setQuestionnaire($questionnaireId);
        $sdgs = Sdg::all();

        $charts = [
            'gender_minimum_wage' => $this->parseDataForChartGenderScreen(441),
            'gender_executives' => $this->parseDataForChartGenderScreen(492),
            'gender_high_governance_body' => $this->parseDataForChartGenderScreen(466),
            'gender_collaborators' => $this->parseDataForChartGenderScreen(432),
            'leadership_genre' => $this->parseDataForChartGenderScreen(435),
            'action_plan' => $this->parseDataForChartActionPlan(),
            'action_plan_table' => $this->parseDataForChartActionPlanTable(),
            'co2_emissions' => $this->parseDataForChartCo2EmissionsForScreen(),
            'employees_salary' => $this->parseDataForChartEmployeeSalaryAvg(),
            'sdgs_top5' => $this->parseDataForChartSdgsTop5(460),
            'badges' => $this->parseDataForBadgesForScreen(),
            'checkboxs' => $this->parseDataForcheckboxForScreen(),
            //'employee_by_nationality' => $this->parseDataForEmployeeByNationality()
        ];

        return tenantView(
            'tenant.dashboards.2-10032023084300',
            [
                'questionnaire' => $this->questionnaire,
                'charts' => $charts,
                'sdgs' => $sdgs,
            ]
        );
    }

    /**
     * Badges >> screen
     */
    protected function parseDataForBadgesForScreen()
    {
        $badges = [];
        $badges[1] = true;
        $badges[2] = true;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 445);
        $badge3Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 446);
        $badge3Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 447);
        $badge3Validate3 = isset($answer->value) ? $answer->value : null;

        $badges[3] = $badge3Validate1 == 'yes' && $badge3Validate2 == 'yes' && $badge3Validate3 == 'yes';

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 442);
        $badge4Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 443);
        $badge4Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 444);
        $badge4Validate3 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 448);
        $badge4Validate4 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 449);
        $badge4Validate5 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 456);
        $badge4Validate6 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 457);
        $badge4Validate7 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 1262);
        $badge4Validate8 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 477);
        $badge4Validate9 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 479);
        $badge4Validate10 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 493);
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
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 496);
        $badges[5] = isset($answer->value) && $answer->value === 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 1262);
        $badge6Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 451);
        $badge6Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 452);
        $badge6Validate3 = isset($answer->value) ? $answer->value : null;

        $badges[6] = $badge6Validate1 == 'yes'
            && $badge6Validate2 == 'yes'
            && $badge6Validate3 == 'yes';

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 472);
        $badge7Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 474);
        $badge7Validate2 = isset($answer->value) ? $answer->value : null;

        $badges[7] = $badge7Validate1 == 'yes' && $badge7Validate2 == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 446);
        $badge8Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 443);
        $badge8Validate2 = isset($answer->value) ? $answer->value : null;

        $badges[8] = $badge8Validate1 == 'yes' && $badge8Validate2 == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 432);
        $badge9Validate1 = isset($answer->value) ? json_decode($answer->value, true) : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 435);
        $badge9Validate2 = isset($answer->value) ? json_decode($answer->value, true) : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 466);
        $badge9Validate3 = isset($answer->value) ? json_decode($answer->value, true) : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 492);
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

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 448);
        $badge10Validate1 = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $badges[10] = $badge10Validate1 == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 451);
        $badge11Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 452);
        $badge11Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 471);
        $badge11Validate3 = isset($answer->value) ? $answer->value : null;

        $badges[11] = $badge11Validate1 == 'yes'
            && $badge11Validate2 == 'yes'
            && $badge11Validate3 == 'yes';

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 459);
        $badges[12] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 477);
        $badges[13] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 479);
        $badges[14] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 753);
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
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 428);
        $checkbox['website'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 467);
        $checkbox['institutional'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 429);
        $checkbox['chart'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 442);
        $checkbox['health_and_safety_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 443);
        $checkbox['customer_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 444);
        $checkbox['code_of_ethics'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 445);
        $chk4Validate1 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 446);
        $chk4Validate2 = isset($answer->value) ? $answer->value : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 447);
        $chk4Validate3 = isset($answer->value) ? $answer->value : null;

        $checkbox['complaint_mechanisms'] = $chk4Validate1 == 'yes'
            && $chk4Validate2 == 'yes'
            && $chk4Validate3 == 'yes';

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 448);
        $checkbox['supplier_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 449);
        $checkbox['code_of_ethics_conduct'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 1262);
        $checkbox['environmental_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 457);
        $checkbox['corruption_risk'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 456);
        $checkbox['policy_to_prevent'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 477);
        $checkbox['reducing_emissions'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 479);
        $checkbox['energy_consumption'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 493);
        $checkbox['remuneration_policy'] = isset($answer->value) && $answer->value == 'yes' ? true : false;

        return $checkbox;
    }

    /**
     * Employee avg salary >> screen
     */
    protected function parseDataForChartEmployeeSalaryAvg()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 436);
        $salary1 = isset($answer->value) ? json_decode($answer->value, true)[1] : 0;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 437);
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

        return [
            [
                'name' => __('Scope 1 Emissions'),
                'value' => $value1[1] ?? null,
            ],
            [
                'name' => __('Scope 2 Emissions'),
                'value' => $value2[1] ?? null,
            ],
            [
                'name' => __('Scope 3 Emissions'),
                'value' => $value3[1] ?? null,
            ],
        ];
    }
}
