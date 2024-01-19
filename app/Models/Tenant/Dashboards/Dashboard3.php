<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Company;
use App\Models\Tenant\Initiative;
use App\Models\Tenant\QuestionOptions\Simple;
use App\Models\Traits\Dashboard;

class Dashboard3
{
    use Dashboard;

    protected $position;

    protected $goals = [];

    protected $answers;

    protected function parseInitiatives()
    {
        // We don't need to parse the initiatives multiple times
        if ($this->initiatives) {
            return;
        } elseif (!$this->questionnaire->initiatives) {
            $this->initiatives = [];

            return;
        }

        $this->parseGoals();

        $initiativesByGoals = [
            0 => [1, 2, 6, 15, 32, 63, 64, 65, 66, 69, 71, 74, 76, 77, 78, 79, 85],
            845 => [8, 9, 13, 33, 37, 38, 81, 82, 83, 84],
            846 => [8, 9, 13, 14, 36, 86],
            847 => [8, 9, 13, 14, 34, 35, 39, 40, 41, 53, 87],
            848 => [5, 27, 28, 29, 48, 88, 89],
            849 => [48, 90, 91, 92],
            850 => [22, 23, 60],
            851 => [13, 48, 49, 54, 68],
            852 => [4, 13, 28, 42],
            853 => [8, 9, 13, 30, 50, 59],
            854 => [8, 9, 13, 42, 70, 72], 93,
            855 => [8, 9, 10, 13, 40, 51],
            856 => [7, 10, 44, 45, 47, 54, 55, 73],
            857 => [9, 12, 13, 27, 67, 75, 104],
            858 => [3, 12],
            859 => [7],
            860 => [11, 16, 17, 18, 19, 20, 21, 22, 24, 25, 26, 47, 52, 54, 55, 56, 57, 58, 61, 80, 93, 94, 95],
            861 => [43, 46, 96, 97, 98, 100],
            862 => [24, 31, 56, 61, 62, 80, 99, 101, 102, 103],
            863 => [],
            864 => [56, 61, 80],
        ];

        // 0 is a special one
        $goals = array_merge($this->goals ?: array_keys($initiativesByGoals), [0]);

        // Filter initiatives by chosen goals
        $initiativesByGoals = array_filter(
            $initiativesByGoals,
            fn ($key) => in_array($key, $goals, true),
            ARRAY_FILTER_USE_KEY
        );

        $initiativesByGoals = array_unique(array_merge(...$initiativesByGoals));

        $initiativesIds = array_filter(
            $this->questionnaire->initiatives,
            fn ($initiative) => in_array($initiative, $initiativesByGoals, false)
        );

        $this->initiatives = Initiative::limit(10)
            ->orderBy('impact', 'desc')
            ->with('category')
            ->find($initiativesIds);
    }

    public function view($questionnaireId)
    {
        $this->setQuestionnaire($questionnaireId);
        $Ids = [
            524, 526, 528, 529, 530, 533, 536, 539, 542, 544,
            546, 547, 549, 550, 758, 553, 554, 555, 556, 558, 560, 562, 757,
        ];
        $this->parsePosition($Ids);
        $this->parsePositionIds();

        $charts = [
            'emission_scope' => $this->parseDataForChartEmission(),
            'employee_satisfaction_rate' => $this->parseDataForChartEmployeeSatisfactionRate(),
            'employee_satisfaction_rate_measures' => $this->parseDataForChartEmployeeSatisfactionRateMeasures(),
            'human_rights_complaints' => $this->parseDataForChartHumanRightsComplaints(),
            'whistleblowing_channels_complaints' => $this->parseDataForChartWhistleblowingChannelsComplaints(),
            'attacks_and_breaches_of_information' => $this->parseDataForAttacksAndBreachesOfInformation(),
            'emission_balance' => $this->parseDataForChartEmissionBalance(),
            'voluntary_contributions' => $this->parseDataForChartContributionsToBiodiversityProjects(),
            'materials_acquired' => $this->parseDataForChartMaterialsAcquired(),
            'generated_waste' => $this->parseDataForChartGeneratedWaste(),
            'employee_training_sustainability' => $this->parseDataForChartEmployeeTrainingSustainability(),
            'labor_agreements' => $this->parseDataForChartLaborAgreements(),
            'gender_total' => $this->parseDataForChartGenderEqualityTotal(),
            'gender_collaborators' => $this->parseDataForChartGenderCollaborators(),
            'gender_top_management' => $this->parseDataForChartGenderTopManagement(),
            'disparities_top_management' => $this->parseDataForDisparitiesTopManagement(),
            'employees_disabilities' => $this->parseDataForChartEmployeesDisabilities(),
            'occupational_accidents' => $this->parseDataForChartOccupationalAccidents(),
            'collaborators_volunteer_actions' => $this->parseDataForChartCollaboratorsVolunteerActions(),
            'collaborators_environmental_projects' => $this->parseDataForChartCollaboratorsEnvironmentalProjects(),
            'average_satisfaction_rate_stakeholders' => $this->parseDataForChartAverageSatisfactionRateStakeholders(),
            'sustainability_performance' => $this->parseDataForChartSustainabilityPerformance(),
            'socialenvironmental_information' => $this->parseDataForChartSocialenvironmentalInformation(),
            'sustainability_criteria' => $this->parseDataForChartSustainabilityCriteria(),
            'turnover_in_portugal' => $this->parseDataForChartTurnoverInPortugal(),
            'action_plan' => $this->parseDataForChartActionPlan(),
            'action_plan_table' => $this->parseDataForChartActionPlanTable(),
            'goal' => $this->parseDataForChartGoal(),
            'goals' => $this->goals,
            'management_certificate' => $this->parseDataForChartCertificateManagement(),
        ];

        return tenantView(
            request()->print == 'true' ? 'tenant.dashboards.reports.3' : 'tenant.dashboards.3',
            [
                'questionnaire' => $this->questionnaire,
                'mainCategories' => $this->mainCategories,
                'charts' => $charts,
                'position' => $this->position,
                'report' => request()->print == 'true' ? $this->parseDataForReport($this->questionnaire) : null,
            ]
        );
    }

    protected function parseDataForReport($questionnaire)
    {
        $company = Company::where('id', $questionnaire->company_id)
            ->with('business_sector')
            ->first();

        return [
            'compnay' => $company ?? [],
            'management_message' => $this->parseDataForText(1300),
            'cae' => $this->parseDataForText(767) . ' ' . $this->parseDataForText(516),
            'dimension' => $this->parseDataForDimension(),
            'nature_of_company' => $this->parseDataForNatureOfCompany(),
            'acting_geography' => $this->parseDataForQuestionOptionTextForCheckbox(1132), // QUERY HERE (Has multiple options selection)
            'scope_of_activity' => $this->parseDataForScopeOfActivity(),
            'mission_vision' => $this->parseDataForText(1301),
            'sustainability_governance' => $this->parseDataForText(1303),
            'sustainability_strategy' => $this->parseDataForText(1302),
            'priority_stakeholders' => $this->parseDataForQuestionOptionTextForCheckbox(674),
            'stakeholder_expectations' => $this->parseDataForText(676),
            'methodology_for_stakeholder' => $this->parseDataForText(678),
            'methodology_used_to_determine_material_topic' => $this->parseDataForText(693),
            'stakeholder_consultation_process' => $this->parseDataForText(695),
            'material_themes' => $this->parseDataForText(696),
            'journey_2030_materials' => $this->parseDataForQuestionOptionTextForCheckbox(754),
            'ods' => $this->parseDataForODS(),
            'ghg_emissions' => $this->parseDataForText(570),
            'operations_direct_impact_biodiversity' => $this->parseDataForText(599),
            'measures_implemented_biodiversity' => $this->parseDataForText(601),
            'voluntary_contributions_biodiversity' => $this->parseDataForText(604),
            'materials_acquired' => $this->parseDataForText(606),
            'main_waste_generated' => $this->parseDataForText(610),
            'employee_satisfaction_survey' => $this->parseDataForText(614),
            'universe_questionnaire' => $this->parseDataForText(615),
            'questionnaire_response_rate' => $this->parseDataForText(616),
            'main_results_obtained' => $this->parseDataForText(618),
            'measures_implemented_results_questionnaire' => $this->parseDataForText(620),
            'satisfaction_survey_reconciliation' => $this->parseDataForText(623),
            'universe_questionnaire_social' => $this->parseDataForText(624),
            'questionnaire_response_rate_social' => $this->parseDataForText(625),
            'main_results_obtained_social' => $this->parseDataForText(627),
            'measures_implemented_questionnaire_social' => $this->parseDataForText(629),
            'priority_sustainability_training_topics' => $this->parseDataForText(631),
            'labor_relations_management_strategy' => $this->parseDataForText(635),
            'equality_diversity_promotion_strategy' => $this->parseDataForText(638),
            'type_work_accidents' => $this->parseDataForText(650),
            'implemented_improvement_plan' => $this->parseDataForText(652),
            'human_rights_violation_risks' => $this->parseDataForText(658),
            'human_rights_risk_management_approach' => $this->parseDataForText(660),
            'complaints_human_rights_received' => $this->parseDataForText(667),
            'stakeholder_satisfaction_assessment' => $this->parseDataForText(680),
            'examples_socio_environmental' => $this->parseDataForText(691),
            'material_topics_covered_management' => $this->parseDataForText(699),
            'how_management_systems_are_organized' => $this->parseDataForText(701),
            'description_the_ethics_management' => $this->parseDataForText(704),
            'results_external_verification' => $this->parseDataForText(706),
            'recipients_complaint' => $this->parseDataForText(709),
            'complaints_received_interested_parties' => $this->parseDataForText(713),
            'information_integrity_management_system' => $this->parseDataForText(715),
            'certifications_related_information_integrity_management_system' => $this->parseDataForText(717),
            'risks_misuse_information_systems' => $this->parseDataForText(719),
            'measures_taken_ensure_integrity_information_systems' => $this->parseDataForText(721),
            'resilience_information_integrity_management_system' => $this->parseDataForText(723),
            'negative_incidences_occurred' => $this->parseDataForText(725),
            'improvement_measures_implemented_negative_incidences' => $this->parseDataForText(727),
            'resilience_tests_carried_out' => $this->parseDataForText(728),
            'selection_criteria_suppliers_technologies' => $this->parseDataForText(730),
            'logo' => $this->parseDataForCompanyLogo(),
        ];
    }

    public function parsePositionIds()
    {
        $questionIds = array_unique([
            1300, 767, 516, 517, 641, 518, 1132, 520, 1301, 1303, 1302,
            674, 676, 678, 693, 695, 696, 754, 570, 599, 601, 604, 606,
            610, 614, 615, 616, 618, 620, 623, 625, 622, 627, 629, 631,
            635, 638, 650, 652, 658, 660, 667, 680, 691, 699, 701, 704,
            706, 709, 713, 715, 717, 719, 721, 723, 725, 727, 728, 730,
            1304, 765, 766,
        ]);
        $this->answers = $this->answers->whereIn('question_id', $questionIds)->sortBy('question_id');
    }

    protected function parsePosition($questionIds)
    {
        //TODO - refactor to function
        $answers = $this->answers->whereIn('question_id', $questionIds)->sortBy('question_id');

        $conhecerCounter = 0;
        $construirCounter = 0;
        $comunicarCounter = 0;
        $consolidarCounter = 0;
        $coliderarCounter = 0;

        foreach ($answers as $answer) {
            if ($answer->question_id == 524 || $answer->question_id == 778) {
                if (isset($answer->parsed_value[719])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[720])) {
                    $construirCounter++;
                }
            } elseif ($answer->question_id == 526 || $answer->question_id == 780) {
                if (isset($answer->parsed_value[721])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[722])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[723])) {
                    $consolidarCounter++;
                }

                if (isset($answer->parsed_value[724])) {
                    $consolidarCounter++;
                }

                if (isset($answer->parsed_value[725])) {
                    $coliderarCounter++;
                }
            } elseif ($answer->question_id == 528 || $answer->question_id == 782) {
                if (isset($answer->parsed_value[726])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[727])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[728])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[729])) {
                    $consolidarCounter++;
                }

                if (isset($answer->parsed_value[730])) {
                    $consolidarCounter++;
                }

                if (isset($answer->parsed_value[731])) {
                    $coliderarCounter++;
                }

                if (isset($answer->parsed_value[732])) {
                    $coliderarCounter++;
                }
            } elseif ($answer->question_id == 529 || $answer->question_id == 757 || $answer->question_id == 783 || $answer->question_id == 824) {
                if ($answer->value === 'yes') {
                    $conhecerCounter++;
                }
            } elseif ($answer->question_id == 530 || $answer->question_id == 784) {
                if ($answer->value === 'yes') {
                    $construirCounter++;
                }
            } elseif ($answer->question_id == 533 || $answer->question_id == 787) {
                if (isset($answer->parsed_value[734])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[735])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[736])) {
                    $consolidarCounter++;
                }
            } elseif ($answer->question_id == 536 || $answer->question_id == 790) {
                if (isset($answer->parsed_value[737])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[738])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[739])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[740])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[741])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[742])) {
                    $consolidarCounter++;
                }
            } elseif ($answer->question_id == 539 || $answer->question_id == 793) {
                if (isset($answer->parsed_value[743])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[744])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[745])) {
                    $consolidarCounter++;
                }
            } elseif ($answer->question_id == 542 || $answer->question_id == 796) {
                if (isset($answer->parsed_value[746])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[747])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[748])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[749])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[750])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[751])) {
                    $consolidarCounter++;
                }
            } elseif ($answer->question_id == 544 || $answer->question_id == 798) {
                if (isset($answer->parsed_value[753])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[754])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[755])) {
                    $consolidarCounter++;
                }
            } elseif ($answer->question_id == 546 || $answer->question_id == 800) {
                if (isset($answer->parsed_value[757])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[758])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[759])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[760])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[761])) {
                    $consolidarCounter++;
                }
            } elseif ($answer->question_id == 547 || $answer->question_id == 801) {
                if (isset($answer->parsed_value[763])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[764])) {
                    $coliderarCounter++;
                }
            } elseif ($answer->question_id == 549 || $answer->question_id == 803) {
                if (isset($answer->parsed_value[765])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[766])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[767])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[768])) {
                    $consolidarCounter++;
                }
            } elseif ($answer->question_id == 550 || $answer->question_id == 804) {
                if (isset($answer->parsed_value[770])) {
                    $conhecerCounter++;
                }

                if (isset($answer->parsed_value[771])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[772])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[773])) {
                    $construirCounter++;
                }
                if (isset($answer->parsed_value[774])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[775])) {
                    $consolidarCounter++;
                }

                if (isset($answer->parsed_value[776])) {
                    $consolidarCounter++;
                }
            } elseif ($answer->question_id == 758 || $answer->question_id == 825) {
                if (isset($answer->parsed_value[779])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[780])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[781])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[782])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[783])) {
                    $comunicarCounter++;
                }
            } elseif ($answer->question_id == 553 || $answer->question_id == 554 || $answer->question_id == 806 || $answer->question_id == 807) {
                if ($answer->value === 'yes') {
                    $comunicarCounter++;
                }
            } elseif ($answer->question_id == 555 || $answer->question_id == 556 || $answer->question_id == 808 || $answer->question_id == 809) {
                if ($answer->value === 'yes') {
                    $consolidarCounter++;
                }
            } elseif ($answer->question_id == 558 || $answer->question_id == 811) {
                if (isset($answer->parsed_value[784])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[785])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[786])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[787])) {
                    $construirCounter++;
                }

                if (isset($answer->parsed_value[788])) {
                    $consolidarCounter++;
                }
            } elseif ($answer->question_id == 560 || $answer->question_id == 813) {
                if (isset($answer->parsed_value[789])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[790])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[791])) {
                    $consolidarCounter++;
                }
            } elseif ($answer->question_id == 562 || $answer->question_id == 815) {
                if (isset($answer->parsed_value[792])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[793])) {
                    $comunicarCounter++;
                }

                if (isset($answer->parsed_value[794])) {
                    $consolidarCounter++;
                }

                if (isset($answer->parsed_value[795])) {
                    $consolidarCounter++;
                }

                if (isset($answer->parsed_value[796])) {
                    $consolidarCounter++;
                }
            }
        }

        $position = 'despertar';
        if ($consolidarCounter >= 18 && $comunicarCounter >= 13 && $construirCounter >= 20 && $conhecerCounter >= 12) {
            $position = 'coliderar';
        } elseif ($comunicarCounter >= 13 && $construirCounter >= 20 && $conhecerCounter >= 12) {
            $position = 'consolidar';
        } elseif ($construirCounter >= 20 && $conhecerCounter >= 12) {
            $position = 'comunicar';
        } elseif ($conhecerCounter >= 12) {
            $position = 'construir';
        } elseif ($conhecerCounter >= 5) {
            $position = 'conhecer';
        }

        return $this->position = $position;
    }

    /**
     * Emission scope
     */
    protected function parseDataForChartEmission()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 579);
        $scope1 = isset($answer->value) ? json_decode($answer->value, true)[1] : 0;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 585);
        $scope2 = isset($answer->value) ? json_decode($answer->value, true)[1] : 0;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 591);
        $scope3 = isset($answer->value) ? json_decode($answer->value, true)[1] : 0;

        if ($scope1 == null && $scope2 == null && $scope3 == null) {
            return null;
        }

        $labels = [
            __('Âmbito 1'),
            __('Âmbito 2'),
            __('Âmbito 3'),
        ];

        $series = [
            round($scope1, 1),
            round($scope2, 1),
            round($scope3, 1),
        ];

        return [
            'labels' => $labels,
            'series' => $series,
            'total' => round(array_sum($series), 1),
        ];
    }

    /**
     * Balance of GHG / GVA emissions
     */
    protected function parseDataForChartEmissionBalance()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 579);
        $scope1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 585);
        $scope2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 591);
        $scope3 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 597);
        $values1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 521);
        $values2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if ($scope1 == null && $scope2 == null && $scope3 == null && $values1 == null && $values2 == null) {
            return null;
        }

        return calculateDivision((($scope1 + $scope2 + $scope3) - $values1), $values2);
    }

    /**
     * Voluntary contributions to biodiversity projects / net income
     */
    protected function parseDataForChartContributionsToBiodiversityProjects()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 603);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 672);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $percentage = calculatePercentage($value1, $value2);

        return [
            'value' => $value1,
            'percentage' => [$percentage, 100 - $percentage],
        ];
    }

    /**
     * Generated waste / VAB
     */
    protected function parseDataForChartGeneratedWaste()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 747);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 521);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        return calculateDivision($value1, $value2);
    }

    /**
     * Materials acquired or extracted / VAB
     */
    protected function parseDataForChartMaterialsAcquired()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 748);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 521);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        return calculateDivision($value1, $value2);
    }

    /**
     * Employee satisfaction rate
     */
    protected function parseDataForChartEmployeeSatisfactionRate($questionId = 617)
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, $questionId);
        $values = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if (!is_numeric($values)) {
            return null;
        }

        return [
            $values,
            100 - $values,
        ];
    }

    /**
     * Employee satisfaction rate on conciliation measures
     */
    protected function parseDataForChartEmployeeSatisfactionRateMeasures($questionId = 626)
    {
        return $this->parseDataForChartEmployeeSatisfactionRate($questionId);
    }

    /**
     * Employees in training on sustainability
     */
    protected function parseDataForChartEmployeeTrainingSustainability()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 632);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 641);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $percentage = calculatePercentage($value1, $value2);

        return [
            'value' => $value1 ?: 0,
            'percentage' => [$percentage, 100 - $percentage],
        ];
    }

    /**
     * degree of coverage of labor agreements
     */
    protected function parseDataForChartLaborAgreements()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 636);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 641);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $percentage = calculatePercentage($value1, $value2);

        return [
            'value' => $value1 ?: 0,
            'percentage' => [$percentage, 100 - $percentage],
        ];
    }

    /**
     *  Gender total
     */
    protected function parseDataForChartGenderEqualityTotal()
    {
        $answer1 = $this->answers[765] ?? null;
        $answer2 = $this->answers[766] ?? null;

        $value = isset($answer1->value) ? json_decode($answer1->value, true) : null;
        $value2 = isset($answer2->value) ? json_decode($answer2->value, true) : null;

        $labels = [
            __('female'),
            __('male'),
            __('other'),
        ];

        $series = [
            ($value[151] ?? 0) + ($value2[151] ?? 0),
            ($value[152] ?? 0) + ($value2[152] ?? 0),
            ($value[8] ?? 0) + ($value2[8] ?? 0),
        ];

        $filterData = array_filter($series); // Validate series array has value > 0 or not.

        if (empty($filterData)) { // if filter data is empty means series array dont have any values.
            return null;
        }

        return [
            'labels' => $labels,
            'series' => $series,
            'total' => array_sum($series),
        ];
    }

    /**
     * Gender Collaborators
     */
    protected function parseDataForChartGenderCollaborators()
    {
        $answer = $this->answers[766] ?? null;
        $value = isset($answer->value) ? json_decode($answer->value, true) : [];

        $labels = [
            __('female'),
            __('male'),
            __('other'),
        ];

        $series = [
            $value[151] ?? 0,
            $value[152] ?? 0,
            $value[8] ?? 0,
        ];

        $filterData = array_filter($series); // Validate series array has value > 0 or not.

        if (empty($filterData)) { // if filter data is empty means series array dont have any values.
            return null;
        }

        return [
            'labels' => $labels,
            'series' => $series,
            'total' => array_sum($series),
        ];
    }

    /**
     * Gender Top management
     */
    protected function parseDataForChartGenderTopManagement()
    {
        $answer = $this->answers[765] ?? null;
        $value = isset($answer->value) ? json_decode($answer->value, true) : [];

        $labels = [
            __('female'),
            __('male'),
            __('other'),
        ];

        $series = [
            $value[151] ?? 0,
            $value[152] ?? 0,
            $value[8] ?? 0,
        ];

        $filterData = array_filter($series); // Validate series array has value > 0 or not.

        if (empty($filterData)) { // if filter data is empty means series array dont have any values.
            return null;
        }

        return [
            'labels' => $labels,
            'series' => $series,
            'total' => array_sum($series),
        ];
    }

    /**
     * Pay disparities in top management
     */
    protected function parseDataForDisparitiesTopManagement()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 643);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 644);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $percentage = calculatePercentage(($value1 - $value2), $value1);

        return [
            'value' => $value1 - $value2,
            'percentage' => [$percentage, 100 - $percentage],
        ];
    }

    /**
     * Employees with disabilities
     */
    protected function parseDataForChartEmployeesDisabilities()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 647);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 641);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $percentage = calculatePercentage($value1, $value2);

        return [
            'value' => $value1,
            'percentage' => [$percentage, 100 - $percentage],
        ];
    }

    /**
     * Frequency of occupational accidents
     */
    protected function parseDataForChartOccupationalAccidents()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 653);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 654);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        return calculateDivision($value2, $value1) * 1000000;
    }

    /**
     * Complaints about human rights received
     */
    protected function parseDataForChartHumanRightsComplaints($questionId = 664)
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, $questionId);
        $values = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if (!is_numeric($values)) {
            return null;
        }

        return $values;
    }

    /**
     * Complaints received in the complaint and whistleblowing channels
     */
    protected function parseDataForChartWhistleblowingChannelsComplaints($questionId = 710)
    {
        return $this->parseDataForChartHumanRightsComplaints($questionId);
    }

    /**
     * Attacks and breaches of information systems
     */
    protected function parseDataForAttacksAndBreachesOfInformation($questionId = 732)
    {
        return $this->parseDataForChartHumanRightsComplaints($questionId);
    }

    /**
     * Collaborators in volunteer actions
     */
    protected function parseDataForChartCollaboratorsVolunteerActions()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 641);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 670);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $percentage = calculatePercentage($value2, $value1);

        return [
            'value' => $value2,
            'percentage' => [$percentage, 100 - $percentage],
        ];
    }

    /**
     * Voluntary contributions to social/environmental projects / net income
     */
    protected function parseDataForChartCollaboratorsEnvironmentalProjects()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 671);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 672);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $percentage = calculatePercentage($value1, $value2);

        return [
            'value' => $value1,
            'percentage' => [$percentage, 100 - $percentage],
        ];
    }

    /**
     * Average satisfaction rate of priority stakeholders
     */
    protected function parseDataForChartAverageSatisfactionRateStakeholders()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 1259);
        $values = isset($answer->value) ? json_decode($answer->value, true) : null;

        if ($values == null) {
            return null;
        }

        $count = count($values);
        $sum = array_sum($values);
        $avg = round(calculateDivision($sum, $count));

        return [
            $avg,
            100 - $avg,
        ];
    }

    /**
     * Disclosure of sustainability performance
     */
    protected function parseDataForChartSustainabilityPerformance()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 684);
        $value1 = isset($answer->value) && $answer->value == 'yes' ? 1 : 0;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 687);
        $value2 = isset($answer->value) && $answer->value == 'yes' ? 1 : 0;

        return [
            $value1,
            $value2,
        ];
    }

    /**
     * Products / Services with socio-environmental information
     */
    protected function parseDataForChartSocialenvironmentalInformation()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 689);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 690);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if (!$value1 || $value2 == null) {
            return 0;
        }

        $percentage = calculatePercentage($value2, $value1);

        return [
            'value' => $value2,
            'percentage' => [$percentage, 100 - $percentage],
        ];
    }

    /**
     * Purchases covered by sustainability criteria
     */
    protected function parseDataForChartSustainabilityCriteria()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 740);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 741);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if (!$value1 || $value2 == null) {
            return 0;
        }

        $percentage = calculatePercentage($value1, $value2);

        return [
            'value' => $value1,
            'percentage' => [$percentage, 100 - $percentage],
        ];
    }

    /**
     * Turnover in Portugal corresponding to subscribers to the Charter
     */
    protected function parseDataForChartTurnoverInPortugal()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 744);
        $value1 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 745);
        $value2 = isset($answer->value) ? json_decode($answer->value, true)[1] : null;

        if (!$value1 || $value2 == null) {
            return 0;
        }

        $percentage = calculatePercentage($value2, $value1);

        return [
            'value' => $value2,
            'percentage' => [$percentage, 100 - $percentage],
        ];
    }

    /**
     * Goals
     */
    protected function parseDataForChartGoal()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 742);
        $value = isset($answer->value) ? json_decode($answer->value, true) : null;

        if ($value == null) {
            return null;
        }

        return $value;
    }

    /**
     * Material Objectives of the Company
     */
    protected function parseGoals()
    {
        $answer = $this->answers[754] ?? false;
        $value = $answer && isset($answer->value) ? json_decode($answer->value, true) : [];
        $this->goals = array_keys($value);
    }

    /**
     * Certified management systems
     */
    protected function parseDataForChartCertificateManagement()
    {
        $answer = Answer::whereQuestionnaireIdAndQuestionId($this->questionnaire->id, 700);
        $value = isset($answer->value) ? json_decode($answer->value, true) : null;

        if ($value == null) {
            return null;
        }

        return $value;
    }

    protected function parseDataForText($quetionId)
    {
        $answers1 = $this->answers[$quetionId] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        return $value1 != null ? $value1 : null;
    }

    protected function parseDataForDimension()
    {
        $answers1 = $this->answers[517] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers[641] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $lable = null;
        if ($value1 != null) {
            $rowData = Simple::where('value', $value1)->first()->label;
            $result = explode('(', $rowData);

            $lable = $result[0] ?? null;
        }

        if ($value2 != null) {
            $lable .= '(' . $value2 . ' ' . __('Collaborators') . ')';
        }

        return $lable != null ? $lable : null;
    }

    protected function parseDataForNatureOfCompany()
    {
        $answers1 = $this->answers[518] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        if ($value1 == null) {
            return null;
        }

        $label = '';

        if ($value1 == 'no') {
            $label = __('Não');
        } elseif ($value1 == 'yes-of-portuguese-origin') {
            $label = __('Multinacional de origem portuguesa');
        } elseif ($value1 == 'yes-its-a-subsidiary-of-a-multinational-of-foreign-origin') {
            $label = __('Filial /concessionária de uma multinacional de origem estrangeira');
        }

        return $label;
    }

    /**
     * Get Label name from the question id for checkbox
     * @return string
     */
    protected function parseDataForQuestionOptionTextForCheckbox($questionId = null)
    {
        $answers1 = $this->answers[$questionId] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $label = '';
        if (is_array($value1)) {
            foreach ($value1 as $key => $value) {
                $label .= Simple::find($key)->label . ' / ';
            }
        }

        return $label;
    }

    /**
     * Get Label name from the question id for radio
     * @return string
     */
    protected function parseDataForQuestionOptionTextForRadio($questionId = null)
    {
        $answers1 = $this->answers[$questionId] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return $value1 != null ? Simple::where('value', $value1)->first()->label : null;
    }

    protected function parseDataForScopeOfActivity()
    {
        $answers1 = $this->answers[520] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        if ($value1 == null) {
            return null;
        }

        $label = '';

        if ($value1 == 'yes') {
            $label = __('Exportadora');
        } elseif ($value1 == 'no') {
            $label = __('Não exportadora');
        }

        return $label;
    }

    protected function parseDataForODS()
    {
        $answers1 = $this->answers[754] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        return $value1 != null ? array_keys($value1) : [];
    }

    protected function parseDataForCompanyLogo()
    {
        $answers1 = $this->answers[1304] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $logo = null;
        if ($value1 == 'yes') {
            $attachments = $answers1->attachments()->first();
            if ($attachments) {
                $logo = tenantPrivateAsset($attachments->id, 'attachments');
            }
        }

        return $logo;
    }
}
