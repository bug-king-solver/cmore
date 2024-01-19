<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Enums\NaturalHazard;
use App\Models\Enums\Risk;
use App\Models\Enums\Territory\County;
use App\Models\Enums\Territory\District;
use App\Models\Tenant\Answer;
use App\Models\Tenant\Initiative;
use App\Models\Tenant\QuestionOptions\Simple;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Sdg;
use App\Models\Traits\Dashboard;

class Dashboard5MultipleQuestionnaire
{
    use Dashboard;

    protected $answers;

    protected $reportQuestionnaire;

    protected $questionIds;

    public $questionnaireIds = [];

    public $avg_months_operation;

    public $county;

    public function view($questionnaire = null)
    {
    }

    public function chartForMultipleQuestionnaire($questionnaireIds, $management)
    {
        $this->questionnaireIds = $questionnaireIds;
        $this->parsePosition();

        $this->avg_months_operation = $this->avg_months_operation();
        $this->county = $this->parseDataForMarketsReportingUnitsOperate();

        $charts = [
            // Perfil da entidade
            'total_workers' => $this->parseDataForTotalWorkers(),
            'number_of_business_units' => $this->praseDataForBusinessUnits(),
            'average_months_activity' => $this->parseDataForAverageMonthsActivity(),
            'average_levels_satisfaction_platforms' => $this->parseDataForAverageLevelsSatisfactionPlatforms(),
            'number_of_complaints' => $this->parseDataForNumberOfComplaints(),
            'total_suppliers' => $this->parseDataForTotalSuppliers(),
            'total_reporting_unit_suppliers' => $this->parseDataForTotalReportingUnitSuppliers(),
            'total_reporting_unit_suppliers_child_labor' => $this->parseDataForTotalReportingUnitSuppliersChildLabor(),
            'markets_reporting_units_operate' => $this->parseDataForMarketsReportingUnitsOperate(),

            // Desempenho económico
            'turnover_total_operating_costs' => $this->parseDataForTurnoverTotalOperatingCosts(),
            'total_value_employee_salaries' => $this->parseDataForTotalValueEmployeeSalaries(),
            'total_capital_providers' => $this->parseDataForTotalCapitalProviders(),
            'total_investment_value' => $this->parseDataForTotalInvestmentValue(),
            'total_financial_support_received' => $this->parseDataForTotalFinancialSupportReceived(),
            'turnover_resulting_from_products' => $this->parseDataForTurnoverResultingFromProducts(),
            'withheld_economic_value' => $this->parseDataForWithheldEconomicValue(),
            'turnover_value' => $this->parseDataForTurnoverValue(),
            'total_amount_capex_da' => $this->parseDataForTotalAmountCapexDa(),
            'direct_economic_value' => $this->parseDataForDirectEconomicValue(),
            'taxonomy_turnover' => $this->parseDataForTaxonomyTurnover(),

            // Desempenho social
            'percentage_of_workers_by_contractual_regime' => $this->parseDataForPercentageOfWorkersByContractualRegime(),
            'percentage_workers_by_contract' => $this->parseDataForPercentageWorkersByContract(),
            'total_number_of_hires' => $this->parseDataForTotalNumberOfHires(),
            'percentage_of_hires_by_contractual_regime' => $this->parseDataForPercentageOfHiresByContractualRegime(),
            'percentage_of_hiring_of_workers_by_gender' => $this->parseDataForPercentageOfHiringOfWorkersByGender(),
            'percentage_of_workers_residing_locally' => $this->parseDataForPercentageOfWorkersResidingLocally(),
            'job_creation' => $this->parseDataForJobCreation(),
            'average_turnover' => $this->parseDataForAverageTurnover(),
            'average_turnover_rate' => $this->parseDataForAverageTurnoverRate(),
            'absenteeism_rate' => $this->parseDataForAbsenteeismRate(),
            'sum_of_basic_wages_of_workers_by_gender' => $this->parseDataForSumOfBasicWagesOfWorkersByGender(),
            'base_salary_of_female_workersby_professional' => $this->parseDataForBaseSalaryOfFemaleWorkersByProfessional(),
            'base_salary_of_male_workers_by_professional' => $this->praseDataForBaseSalaryOfmaleWorkersByProfessional(),
            'salary_of_workers_of_other_gender_by_professional' => $this->parseDataForSalaryOfWorkersOfOtherGenderByProfessional(),
            'remuneration_of_employees_by_gender' => $this->parseDataForRemunerationOfEmployeesByGender(),
            'gross_remuneration_of_female_workers_by_professional' => $this->parseDataForGrossRemunerationOfFemaleWorkersByProfessional(),
            'gross_remuneration_of_male_workers_by_professional' => $this->parseDataForGrossRemunerationOfmaleWorkersByProfessional(),
            'gross_remuneration_of_workers_othergender_by_professional' => $this->parseDataForGrossRemunerationOfWorkersOtherGenderByProfessional(),
            'earning_more_than_the_national_minimum_wage_by_gender' => $this->parseDataForEarningMoreThanTheNationalMinimumWageByGender(),
            'percentage_male_workers_professional' => $this->parseDataForPercentageMaleWorkersProfessional(),
            'percentage_female_workers_professional' => $this->parseDataForPercentageFemaleWorkersProfessional(),
            'percentage_othergender_workers_professional' => $this->parseDataForPercentageOtherGenderWorkersProfessional(),
            'percentage_workers_foreign_nationality' => $this->parseDataForPercentageWorkersForeignNationality(),
            'percentage_workers_agegroup' => $this->parseDataForPercentageWorkersAgegroup(),
            //'training_hours_by_gender' => $this->parseDataForTrainingHoursByGender(),
            'avg_hours_training_per_worker' => $this->parseDataForAvgHoursTrainingPerWorker(),
            'number_training_hours' => $this->parseDataForNumberTrainingHours(),
            'workers_training_actions' => $this->parseDataForWorkersTrainingActions(),
            'female_workers_performance_evaluation' => $this->parseDataForFemaleWorkersPerformanceEvaluation(),
            'male_workers_performance_evaluation' => $this->parseDataForMaleWorkersPerformanceEvaluation(),
            'workers_another_gender_performance_evaluation' => $this->parseDataForWorkersAnotherGenderPerformanceEvaluation(),
            'hours_training_occupational_health_safety' => $this->parseDataForHoursTrainingOccupationalHealthSafety(),
            'received_training_occupational_health_safety' => $this->parseDataForReceivedTrainingOccupationalHealthSafety(),
            'accidents_at_work' => $this->parseDataForAccidentsAtWork(),
            'workdays_lost_due_to_accidents' => $this->parseDataForWorkdaysLostDueToAccidents(),
            'number_of_disabled_days' => $this->parseDataForNumberOfDisabledDays(),
            'modalities_schedules_reporting_units_charts' => $this->parseDataForModalitiesSchedulesReportingUnitsCharts(),
            'conciliation_measures_unit_charts' => $this->parseDataForConciliationMeasuresUnitCharts(),
            'workers_initial_parental_leave' => $this->parseDataForWorkersInitialParentalLeave(),
            'workers_return_towork_after_parental_leave' => $this->parseDataForWorkersReturnToworkAfterParentalLeave(),
            'workers_return_towork_after_parental_leave_twelve_month' => $this->parseDataForWorkersReturnToworkAfterParentalLeaveTwelveMonth(),
            //'worker_expect_toreturn_after_leave' => $this->parseDataForWorkerExpectToreturnAfterLeave(),
            'established_by_law_charts' => $this->parseDataForEstablishedByLawCharts(),
            //'policies_progress_social' => $this->praseDataForPoliciesProgress(),
            'return_to_work_rate' => $this->parseDataForReturnToWorkRate(),
            'local_development_programs' => $this->parseDataForLocalDevelopmentPrograms(),
            //'monetary_local_purchases' => $this->parseDataForMonetaryLocalPurchases(),
            //'monetary_amount_spent_purchases' => $this->parseDataForMonetaryAmountSpentPurchases(),
            'monetary_amount_spent_local_products' => $this->parseDataForMonetaryAmountSpentLocalProducts(),
            'participates_local_development_programs_progress' => $this->parseDataForParticipatesLocalDevelopmentProgramsProgress(),

            'number_workers_gender' => $this->parseDataForNumberWorkersGender(),

            // Ambiental
            'total_amount_of_water_consumed' => $this->parseDataForTotalAmountOfWaterConsumed(),
            'reduce_consumption_charts' => $this->parseDataForReduceConsumptionCharts(),
            'reporting_units_wwtp_progress' => $this->parseDataForReportingUnitsWWTP(),
            'emission_value_per_parameter' => $this->parseDataForEmissionValuePerParameter(),
            'electricity_consumed_per_source' => $this->parseDataForElectricityConsumedPerSource(),
            'total_gross_production_value' => $this->parseDataForTotalGrossProductionValue(),
            'total_indirect_costs' => $this->parseDataForTotalIndirectCosts(),
            'gross_added_value_company' => $this->parseDataForGrossAddedValueCompany(),
            'total_production_volume' => $this->parseDataForTotalProductionVolume(),
            'percentage_electricity_consumption' => $this->parseDataForPercentageElectricityConsumption(),
            'ghg_emissions_progress' => $this->parseDataForGhgEmissionsProgress(),
            'travel_in_vehicles_owned_progress' => $this->parseDataForTravelInVehiclesOwnedProgress(),
            'amount_of_non_road_fuel_consumed' => $this->parseDataForAmountOfNonRoadFuelConsumed(),
            'travel_in_vehicles_fuel_consumed' => $this->parseDataForTravelInVehiclesFuelConsumed(),
            'vehicle_and_distance_travelled' => $this->parseDataForVehicleAndDistanceTravelled(),
            'travel_vehicles_reporting_units_donot_own_progress' => $this->parseDataForTravelVehiclesReportingUnitsDonotOwnProgress(),
            'control_or_operate_fuel_consumed' => $this->parseDataForControlOrOperateFuelConsumed(),
            'type_of_transport_vehicle_distance' => $this->parseDataForTypeOfTransportVehicleDistance(),
            'equipment_containing_greebhouse_gas_progress' => $this->parseDataForEquipmentContainingGreebhouseGasProgress(),
            'total_amount_of_leak' => $this->parseDataForTotalAmountOfLeak(),
            'total_amount_of_electricity' => $this->parseDataForTotalAmountOfElectricity(),
            'waste_production_facilities_progress' => $this->praseDataForWasteProductionFacilitiesProgress(),
            'waste_produced_in_ton' => $this->parseDataForWasteProducedInTon(),
            'waste_placed_in_recycling' => $this->parseDataForWastePlacedInRecycling(),
            'water_on_its_premises_progress' => $this->parseDataForWateronItsPremisesProgress(),
            'total_amount_of_water_m3' => $this->parseDataForTotalAmountOfWaterM3(),
            'purchased_goods_during_reporting_period_progress' => $this->parseDataForPurchasedGoodsDuringReportingPeriodProgress(),
            'type_total_quantity_goods_purchased_ton' => $this->parseDataForTypeTotalQuantityGoodsPurchasedTon(),
            'telecommuting_workers_progress' => $this->parseDataForTelecommutingWorkersProgress(),
            'hours_worked_in_telecommuting' => $this->parseDataForHoursWorkedInTelecommuting(),
            'carbon_sequestration_capacity_ghg_progress' => $this->parseDataForcarbonSequestrationCapacityGhgProgress(),
            'ghg_emissions_and_carbon_sequestration' => $this->parseDataForGhgEmissionsAndCarbonSequestration(),
            'emission_air_pollutants_progress' => $this->parseDataForEmissionAirPollutantsProgress(),
            'air_pollutant' => $this->parseDataForAirPollutant(),
            'deplete_the_ozone_layer_progress' => $this->parseDataForDepleteTheOzoneLayerProgress(),
            'depletes_the_ozone_layer_in_tons' => $this->parseDataForDepletesTheOzoneLayerInTons(),
            'emissions' => $this->parseDataForEmissions(),
            'scope' => $this->parseDataForScope(),
            'km_25_km_radius_environmental_protection_area_progress' => $this->parseDataFor25KmRadiusEnvironmentalProtectionAreaProgress(),
            'environmental_impact_studies_progress' => $this->parseDataForEnvironmentalImpactStudiesProgress(),
            'species_affected' => $this->parseDataForSpeciesAffected(),
            'environmental_impact_studies1_progress' => $this->parseDataForEnvironmentalImpactStudies1Progress(),
            'habitats_outside_the_studies_progress' => $this->parseDataForHabitatsOutsideTheStudiesProgress(),

            'energy_costs' => $this->parseDataForEnergyCosts(),
            'hazardous_waste' => $this->parseDataForHazardousWaste(),
            'waste_management_progress' => $this->parseDataForWasteManagementProgress(),
            'radioactive_waste_progress' => $this->parseDataForRadioactiveWastesProgress(),
            //'environmental_policies_progress' => $this->parseDataForEnvironmentalPoliciesProgress(),
            'expenditure_on_innovation' => $this->parseDataForExpenditureOnInnovation(),
            'total_waste_generated' => $this->parseDataForTotalWasteGenerated(),
            'reused_materials' => $this->parseDataForReusedMaterials(),
            'wasted_food' => $this->parseDataForWastedFood(),
            'meals_prepared' => $this->parseDataForMealsPrepared(),
            'cooking_oils_recycled' => $this->parseDataForCookingOilsRecycled(),
            'biodiversity_map' => $this->parseDataForBiodiversityMap(),
            'carbon_intensity' => $this->parseDataForCarbonIntensity(),
            'energy_intensity' => $this->parseDataForEnergyIntensity(),
            'energy_consumption' => $this->parseDataForEnergyConsumption(),
            'energy_efficiency' => $this->parseDataForEnergyEfficiency(),
            'water_consumption_customer' => $this->parseDataForWaterConsumptionCustomer(),
            'energy_consumption_value' => $this->parseDataForEnergyConsumptionValue(),
            'percentage_local_purchases' => $this->parseDataForPercentageLocalPurchases(),

            // Relatório
            //'materiality_matrix' => $this->parseDataForMaterialityMatrix(),
            'sustainable_development_goals' => $this->parseDataForSustainableDevelopmentGoals(),
            'report' => $this->parseReport($management),
            // Governance
            //'legal_requirements_applicable_progress' => $this->parseDataForLegalRequirementsApplicable($management),
            'number_of_hours_of_ethics_training' => $this->parseDataForNumberOfHoursOfEthicsTraining($management),
            'board_of_directors_by_gender' => $this->parseDataForBoardOfDirectorsByGender($management),
            'independent_members_participate_board_of_director' => $this->parseDataForIndependentMembersParticipateBoardOfDirector($management),
            'board_of_directors_by_age_group' => $this->parseDataForBoardOfDirectorsByAgeGroup($management),
            'risks_arising_supply_chain_reporting_units_charts' => $this->parseDataForRisksArisingSupplyChainReportingUnitsCharts($management),
            //'policies_progress' => $this->parseDataForPoliciesProgress($management),
            //'risk_matrix' => $this->parseDataForRiskMatrix(),
            'average_probability_risk_categories' => $this->parseDataForAverageProbabilityRiskCategories($management),
            'average_severity_risk_categories' => $this->parseDataForAverageSeverityRiskCategories($management),
        ];
        if ($management) {
            $charts['environmental_policies'] = $this->parseDataForEnvironmentalPoliciesProgress($management);
            $charts['policies'] = $this->praseDataForPoliciesProgress($management);
            $charts['governance_policies'] = $this->parseDataForPoliciesProgress($management);
            $charts['legal_requirements_applicable'] = $this->parseDataForLegalRequirementsApplicable($management);
            $charts['ethic'] = $this->parseDataForEthic($management);
            $charts['materiality_matrix'] = $this->parseDataForMaterialityMatrix($management);
        } else {
            $charts['environmental_policies_progress'] = $this->parseDataForEnvironmentalPoliciesProgress($management);
            $charts['policies_progress_social'] = $this->praseDataForPoliciesProgress($management);
            $charts['policies_progress'] = $this->parseDataForPoliciesProgress($management);
            $charts['legal_requirements_applicable_progress'] = $this->parseDataForLegalRequirementsApplicable($management);
        }

        $charts['physical_risks'] = $this->parsePhysicalRisks($this->questionnaireIds);

        return [
            'charts' => $charts,
        ];
    }

    public function parsePosition()
    {
        $questionIds = array_unique([
            999, 1000, 1001, 840, 839, 872, 875, 880, 890, 892, 1014, 896, 887, 888, 864, 1010, 1023, 1024,
            1019, 828, 937, 927, 928, 926, 923, 924, 932, 997, 1012, 985, 988,
            989, 987, 1022, 992, 993, 994,
            1002, 1004, 1007, 1008, 1050, 1055, 1056, 1060, 1052, 1054, 1059, 1063, 1025, 1065,
            1068, 1076, 1074, 1075, 962, 1069, 1070, 1071, 1073, 1030, 1031, 1033,
            999, 1101, 1104, 1107, 1110, 1113, 1116, 1119, 1122, 1125, 1000, 1102,
            1105, 1108, 1111, 1114, 1117, 1120, 1123, 1126, 1001, 1103, 1106, 1109, 1112, 1115, 1118, 1121, 1124, 1127, 1147, 1151,
            828, 829, 1044, 831, 832, 833, 836, 1144, 1145, 839, 840, 841, 842, 843, 844,
            845, 846, 847, 848, 849, 850, 851, 852, 853, 854, 855, 856, 857,
            923, 924, 925, 1026, 1027, 929, 930, 931, 1015, 933, 935, 938, 939, 1016, 1029, 1028,
            1032, 940, 941, 1017, 1031, 1018, 942, 943, 946, 947, 948, 949, 950, 951,
            1020, 1034, 1035, 1036, 1021, 958, 959, 961, 962, 963, 964, 965, 1037, 1038, 1039, 969,
            1046, 1068, 1076, 1077, 975, 976, 977, 978, 983, 984, 985, 990, 991, 992, 993, 994, 995,
            1069, 1070, 1071, 1072, 1073, 1048, 979, 862, 1134, 1154, 1151, 866, 867, 868, 869, 871,
            873, 874, 876, 872, 875, 1156, 878, 879, 881, 1157, 883, 884, 885, 889, 891, 893, 895,
            1146, 1051, 1052, 1053, 1054, 1077, 1078, 1079, 1080, 1081, 1083, 1085, 1086, 1087, 1089,
            1090, 1092, 1083, 1084, 1085, 1093, 1155, 1065, 1061, 1062, 1064, 1050, 1055, 1056, 1058,
            901, 1063, 906, 907, 908, 909, 1151, 872, 875, 868, 869, 1159, 1153, 830, 1066,
            1042, 834, 828, 835, 838, 1139, 1137, 1138, 1005, 1006, 1008, 1131, 1141,
            1128, 1129, 1130, 837, 870, 1043,
            858, 1150, 911, 912, 913, 914, 972, 973, 974, 1047, 1152, 1135, 971, 975,
        ]);

        $this->questionIds = $questionIds;

        $collection = collect();

        foreach ($this->questionnaireIds as $id) {
            $this->setQuestionnaire($id);
            $collection->push($this->answers->whereIn('question_id', $questionIds)->sortBy('question_id'));
        }

        $this->answers = $collection;
    }

    public function parseReport($management = null)
    {
        $responsibility = null;
        $company = null;
        $logo = null;

        if ($management != null) {
            $this->setQuestionnaire($management);
            $this->reportQuestionnaire = $this->answers->whereIn('question_id', $this->questionIds)->sortBy('question_id');

            $answers1 = $this->reportQuestionnaire['1002'] ?? null;
            $answers2 = $this->reportQuestionnaire['1004'] ?? null;
            $answers3 = $this->reportQuestionnaire['1007'] ?? null;

            $answers4 = $this->reportQuestionnaire['1042'] ?? null;
            $answers5 = $this->reportQuestionnaire['834'] ?? null;
            $answers6 = $this->reportQuestionnaire['831'] ?? null;
            $answers7 = $this->reportQuestionnaire['832'] ?? null;
            $answers8 = $this->reportQuestionnaire['833'] ?? null;
            $answers9 = $this->reportQuestionnaire['828'] ?? null;
            $answers10 = $this->reportQuestionnaire['829'] ?? null;
            //$answers11 = $this->reportQuestionnaire['1044'] ?? null;
            //$answers12 = $this->reportQuestionnaire['830'] ?? null;

            $answers13 = $this->reportQuestionnaire['836'] ?? null;
            $answers14 = $this->reportQuestionnaire['835'] ?? null;
            $answers15 = $this->reportQuestionnaire['838'] ?? null;
            $answers16 = $this->reportQuestionnaire['1144'] ?? null;
            $answers17 = $this->reportQuestionnaire['1145'] ?? null;
            $answers18 = $this->reportQuestionnaire['1139'] ?? null;
            $answers19 = $this->reportQuestionnaire['1137'] ?? null;
            $answers20 = $this->reportQuestionnaire['1138'] ?? null;
            $answers21 = $this->reportQuestionnaire['1005'] ?? null;
            $answers22 = $this->reportQuestionnaire['1006'] ?? null;
            $answers23 = $this->reportQuestionnaire['1008'] ?? null;
            $answers24 = $this->reportQuestionnaire['1141'] ?? null;

            $answers26 = $this->reportQuestionnaire['1128'] ?? null;
            $answers27 = $this->reportQuestionnaire['1129'] ?? null;
            $answers28 = $this->reportQuestionnaire['1130'] ?? null;

            $answers29 = $this->reportQuestionnaire['835'] ?? null;
            $answers30 = $this->reportQuestionnaire['837'] ?? null;

            $answers31 = $this->reportQuestionnaire['971'] ?? null;
            $answers32 = $this->reportQuestionnaire['975'] ?? null;

            $answers33 = $this->reportQuestionnaire['870'] ?? null;
            $answers34 = $this->reportQuestionnaire['1043'] ?? null;

            $data1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $data2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $data3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

            $data4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;
            $data5 = isset($answers5->value) ? json_decode($answers5->value, true)[1] : null;
            $data6 = isset($answers6->value) ? json_decode($answers6->value, true) : null;
            $data7 = isset($answers7->value) ? json_decode($answers7->value, true)[1] : null;
            $data8 = isset($answers8->value) ? json_decode($answers8->value, true)[1] : null;
            $data9 = isset($answers9->value) ? json_decode($answers9->value, true)[1] : null;
            $data10 = isset($answers10->value) ? json_decode($answers10->value, true)[1] : null;
            //$data11 = isset($answers11->value) ? json_decode($answers11->value, true)[1] : null;
            //$data12 = isset($answers12->value) ? json_decode($answers12->value, true) : null;

            $data13 = isset($answers13->value) ? json_decode($answers13->value, true)[1] : null;
            $data14 = isset($answers14->value) ? json_decode($answers14->value, true)[1] : null;
            $data15 = isset($answers15->value) ? json_decode($answers15->value, true)[1] : null;
            $data16 = isset($answers16->value) ? json_decode($answers16->value, true)[1] : null;
            $data17 = isset($answers17->value) ? json_decode($answers17->value, true)[1] : null;
            $data18 = isset($answers18->value) ? json_decode($answers18->value, true)[1] : null;
            $data19 = isset($answers19->value) ? json_decode($answers19->value, true)[1] : null;
            $data20 = isset($answers20->value) ? json_decode($answers20->value, true)[1] : null;
            $data21 = isset($answers21->value) ? json_decode($answers21->value, true)[1] : null;
            $data22 = isset($answers22->value) ? json_decode($answers22->value, true)[1] : null;
            $data23 = isset($answers23->value) ? json_decode($answers23->value, true) : null;
            $data24 = isset($answers24->value) ? json_decode($answers24->value, true)[1] : null;
            $data26 = isset($answers26->value) ? json_decode($answers26->value, true)[1] : null;
            $data27 = isset($answers27->value) ? json_decode($answers27->value, true)[1] : null;
            $data28 = isset($answers28->value) ? json_decode($answers28->value, true)[1] : null;

            $data29 = isset($answers29->value) ? json_decode($answers29->value, true)[1] : null;
            $data30 = isset($answers30->value) ? json_decode($answers30->value, true)[1] : null;

            $data31 = isset($answers31->value) ? json_decode($answers31->value, true)[1] : null;
            $data32 = isset($answers32->value) ? json_decode($answers32->value, true)[1] : null;

            $data33 = isset($answers33->value) ? json_decode($answers33->value, true)[1] : null;
            $data34 = isset($answers34->value) ? json_decode($answers34->value, true)[1] : null;

            if ($data23) {
                foreach ($data23 as $key => $value) {
                    $label = Simple::find($key)->label;
                    $responsibility = $label;
                }
            }

            $company = Questionnaire::questionnaireListByQuestionId([$management])->toArray();

            $answers24 = $answer['1131'] ?? null;

            if ($answers24) {
                $attachments = $answers24->attachments()->first();
                if ($attachments) {
                    $logo = $attachments->getUrl();
                }
            }
        }

        return [
            'mensagem' => $data1 ?? null,
            'perfil' =>  $data2 ?? null,
            'estrategia' =>  $data3 ?? null,

            'company' => $company,
            'products_services' => $data4 ?? null,
            'characterization' => $data5 ?? null,
            'customer_satisfaction' => $data6 ?? null,
            'claims' => $data7 ?? null,
            'elogios' => $data8 ?? null,
            'employees' => $data9 ?? null,
            'business_units' => $data10 ?? null,
            'number_months' => $this->avg_months_operation ?? null,
            'mercados' => isset($this->county) && $this->county != null ? $this->county : null,
            'numero_fornecedores' => $data13 ?? null,
            'caracterização' => $data14 ?? null,
            'localização_geografica' => $data15 ?? null,
            'moderna' => $data16 ?? null,
            'infantil' => $data17 ?? null,
            'oportunidades' => $data18 ?? null,
            'interessadas_1' => $data19 ?? null,
            'interessadas_2' => $data20 ?? null,
            'prazo' => $data21 ?? null,
            'prazo_long' => $data22 ?? null,
            'ods_text' => $data24 ?? null,
            'ambiental_text' => $data26 ?? null,
            'social_text' => $data27 ?? null,
            'governação_text' => $data28 ?? null,
            'logo' => $logo ?? null,
            'responsibility' => $responsibility ?? null,
            'vat_country' => $company != null ? getCountriesWhereIn([$company[0]['company']['vat_country']]) : null,
            'tipos_de_fornecedores' => $data29 ?? null,
            'fornecedores_de_primeiro' => $data30 ?? null,
            'desenvolvimento' => $data31 ?? $data32 ?? null,
            'measures_promote_energy_efficiency' => $data33 ?? null,
            'nature_ownership' => $data34 ?? null,
        ];
    }

    public function parseDataForMaterialityMatrix($questionnaire = null)
    {
        $answers1 = $this->reportQuestionnaire->whereIn(
            'question_id',
            [999, 1101, 1104, 1107, 1110, 1113, 1116, 1119, 1122, 1125, 1231, 1237]
        )->first();

        $answers2 = $this->reportQuestionnaire->whereIn(
            'question_id',
            [1000, 1102, 1105, 1108, 1111, 1114, 1117, 1120, 1123, 1126, 1232, 1235]
        )->first();

        $answers3 = $this->reportQuestionnaire->whereIn(
            'question_id',
            [1001, 1103, 1106, 1109, 1112, 1115, 1118, 1121, 1124, 1127, 1233, 1236]
        )->first();

        $options1 = $answers1 ? $answers1->question->questionOptions->pluck('option.id')->toArray() : [];
        $options2 = $answers2 ? $answers2->question->questionOptions->pluck('option.id')->toArray() : [];
        $options3 = $answers3 ? $answers3->question->questionOptions->pluck('option.id')->toArray() : [];

        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

        return [
            'environmental_options' => $options1,
            'environmental' => $value1,
            'social_options' => $options2,
            'social' => $value2,
            'governance_options' => $options3,
            'governance' => $value3,
        ];
    }

    public function parseDataForBiodiversityMap()
    {
        $answers = $this->answers['1010'] ?? null;
        $value = isset($answers->value) ? json_decode($answers->value, true) : null;

        if ($value === null) {
            return [];
        }

        $result = [];
        foreach ($value as $key => $value) {
            $label = Simple::find($key)->label;
            $label = explode(' - ', $label);

            $result[] = [
                'type' => $label[0],
                'position' => $label[1],
                'size' => $value,
            ];
        }

        return $result;
    }

    public function parseDataForRiskMatrix()
    {
        $answers1 = $this->answers['997'] ?? null; // Likelihood
        $answers2 = $this->answers['1012'] ?? null; // Impact

        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        $impact = [
            1 => 'Insignificante',
            2 => 'Menor',
            3 => 'Moderado',
            4 => 'Significativo',
            5 => 'Desastroso',
        ];

        $likelihood = [
            1 => 'Muito Improvável',
            2 => 'Improvável',
            3 => 'Pouco Provável',
            4 => 'Provável',
            5 => 'Muito Provável',
        ];

        $datasets = [
            'very_high' => [
                'label' => [],
                'description' => [],
                'data' => [],
            ],
            'high' => [
                'label' => [],
                'description' => [],
                'data' => [],
            ],
            'intermediate' => [
                'label' => [],
                'description' => [],
                'data' => [],
            ],
            'low' => [
                'label' => [],
                'description' => [],
                'data' => [],
            ],
        ];

        if ($value1 == null || $value2 == null) {
            return $datasets;
        }

        $i = 0;
        foreach ($value1 as $key => $likelihoodIndex) {
            $impactIndex = $value2[$key] ?? 5;

            $element = ['x' => $likelihood[$likelihoodIndex], 'y' => $impact[$impactIndex], 'r' => 8];

            if ($impactIndex >= 4 && $likelihoodIndex >= 3) {
                $dataset = 'very_high';
            } elseif (
                ($impactIndex >= 4 && $likelihoodIndex == 2)
                || ($impactIndex == 3 && $likelihoodIndex >= 3)
            ) {
                $dataset = 'high';
            } elseif (
                ($impactIndex >= 3 && $likelihoodIndex == 1)
                || ($impactIndex == 3 && $likelihoodIndex == 2)
                || ($impactIndex == 2 && $likelihoodIndex >= 3)
            ) {
                $dataset = 'intermediate';
            } else {
                $dataset = 'low';
            }

            $datasets[$dataset]['data'][] = $element;
            $datasets[$dataset]['label'][] = ++$i;
            $datasets[$dataset]['description'][] = Simple::find($key)->label;
        }

        return $datasets;
    }

    public function parsePhysicalRisks($questionnaireIds)
    {
        $risksByDistrict = [
            '0' => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::UNKNOWN->color(),
                NaturalHazard::LANDSLIDE->value => Risk::UNKNOWN->color(),
                NaturalHazard::TSUNAMI->value => Risk::UNKNOWN->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::UNKNOWN->color(),
                NaturalHazard::WILDFIRE->value => Risk::UNKNOWN->color(),
            ],
            District::AVEIRO->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::LOW->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::LOW->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::BEJA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::MEDIUM->color(),
                NaturalHazard::LANDSLIDE->value => Risk::LOW->color(),
                NaturalHazard::TSUNAMI->value => Risk::MEDIUM->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::MEDIUM->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::BRAGA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::LOW->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::LOW->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::BRAGANCA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::VERY_LOW->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::MEDIUM->color(),
                NaturalHazard::TSUNAMI->value => Risk::UNKNOWN->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::LOW->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::CASTELO_BRANCO->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::UNKNOWN->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::MEDIUM->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::COIMBRA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::VERY_LOW->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::LOW->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::MEDIUM->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::EVORA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::MEDIUM->color(),
                NaturalHazard::LANDSLIDE->value => Risk::LOW->color(),
                NaturalHazard::TSUNAMI->value => Risk::UNKNOWN->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::MEDIUM->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::FARO->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::MEDIUM->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::MEDIUM->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::MEDIUM->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::GUARDA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::UNKNOWN->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::MEDIUM->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::LEIRIA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::MEDIUM->color(),
                NaturalHazard::LANDSLIDE->value => Risk::MEDIUM->color(),
                NaturalHazard::TSUNAMI->value => Risk::LOW->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::MEDIUM->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::LISBOA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::MEDIUM->color(),
                NaturalHazard::LANDSLIDE->value => Risk::MEDIUM->color(),
                NaturalHazard::TSUNAMI->value => Risk::MEDIUM->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::MEDIUM->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::PORTALEGRE->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::MEDIUM->color(),
                NaturalHazard::LANDSLIDE->value => Risk::LOW->color(),
                NaturalHazard::TSUNAMI->value => Risk::UNKNOWN->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::MEDIUM->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::PORTO->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::MEDIUM->color(),
                NaturalHazard::TSUNAMI->value => Risk::LOW->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::LOW->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::SANTAREM->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::MEDIUM->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::MEDIUM->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::MEDIUM->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::SETUBAL->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::MEDIUM->color(),
                NaturalHazard::LANDSLIDE->value => Risk::MEDIUM->color(),
                NaturalHazard::TSUNAMI->value => Risk::MEDIUM->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::MEDIUM->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::VIANA_DO_CASTELO->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::LOW->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::VERY_LOW->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::LOW->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::VILA_REAL->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::VERY_LOW->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::UNKNOWN->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::LOW->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::VISEU->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::LOW->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::UNKNOWN->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::LOW->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::MEDIUM->color(),
                NaturalHazard::WILDFIRE->value => Risk::HIGH->color(),
            ],
            District::ILHA_DA_MADEIRA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::VERY_LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::LOW->color(),
                NaturalHazard::TSUNAMI->value => Risk::LOW->color(),
                NaturalHazard::VOLCANO->value => Risk::MEDIUM->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::VERY_LOW->color(),
                NaturalHazard::WILDFIRE->value => Risk::VERY_LOW->color(),
            ],
            District::ILHA_DE_PORTO_SANTO->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::VERY_LOW->color(),
                NaturalHazard::LANDSLIDE->value => Risk::LOW->color(),
                NaturalHazard::TSUNAMI->value => Risk::LOW->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::UNKNOWN->color(),
                NaturalHazard::WILDFIRE->value => Risk::VERY_LOW->color(),
            ],
            District::ILHA_DE_SANTA_MARIA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::UNKNOWN->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::LOW->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::VERY_LOW->color(),
                NaturalHazard::WILDFIRE->value => Risk::VERY_LOW->color(),
            ],
            District::ILHA_DE_SAO_MIGUEL->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::UNKNOWN->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::MEDIUM->color(),
                NaturalHazard::VOLCANO->value => Risk::HIGH->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::VERY_LOW->color(),
                NaturalHazard::WILDFIRE->value => Risk::VERY_LOW->color(),
            ],
            District::ILHA_TERCEIRA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::UNKNOWN->color(),
                NaturalHazard::LANDSLIDE->value => Risk::MEDIUM->color(),
                NaturalHazard::TSUNAMI->value => Risk::MEDIUM->color(),
                NaturalHazard::VOLCANO->value => Risk::MEDIUM->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::VERY_LOW->color(),
                NaturalHazard::WILDFIRE->value => Risk::VERY_LOW->color(),
            ],
            District::ILHA_GRACIOSA->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::UNKNOWN->color(),
                NaturalHazard::LANDSLIDE->value => Risk::MEDIUM->color(),
                NaturalHazard::TSUNAMI->value => Risk::LOW->color(),
                NaturalHazard::VOLCANO->value => Risk::UNKNOWN->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::VERY_LOW->color(),
                NaturalHazard::WILDFIRE->value => Risk::VERY_LOW->color(),
            ],
            District::ILHA_DE_SAO_JORGE->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::UNKNOWN->color(),
                NaturalHazard::LANDSLIDE->value => Risk::HIGH->color(),
                NaturalHazard::TSUNAMI->value => Risk::LOW->color(),
                NaturalHazard::VOLCANO->value => Risk::MEDIUM->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::VERY_LOW->color(),
                NaturalHazard::WILDFIRE->value => Risk::VERY_LOW->color(),
            ],
            District::ILHA_DO_PICO->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::HIGH->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::UNKNOWN->color(),
                NaturalHazard::LANDSLIDE->value => Risk::MEDIUM->color(),
                NaturalHazard::TSUNAMI->value => Risk::MEDIUM->color(),
                NaturalHazard::VOLCANO->value => Risk::MEDIUM->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::VERY_LOW->color(),
                NaturalHazard::WILDFIRE->value => Risk::VERY_LOW->color(),
            ],
            District::ILHA_DO_FAIAL->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::UNKNOWN->color(),
                NaturalHazard::LANDSLIDE->value => Risk::UNKNOWN->color(),
                NaturalHazard::TSUNAMI->value => Risk::MEDIUM->color(),
                NaturalHazard::VOLCANO->value => Risk::MEDIUM->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::VERY_LOW->color(),
                NaturalHazard::WILDFIRE->value => Risk::VERY_LOW->color(),
            ],
            District::ILHA_DAS_FLORES->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::UNKNOWN->color(),
                NaturalHazard::LANDSLIDE->value => Risk::MEDIUM->color(),
                NaturalHazard::TSUNAMI->value => Risk::MEDIUM->color(),
                NaturalHazard::VOLCANO->value => Risk::MEDIUM->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::VERY_LOW->color(),
                NaturalHazard::WILDFIRE->value => Risk::VERY_LOW->color(),
            ],
            District::ILHA_DO_CORVO->value => [
                NaturalHazard::RIVER_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::URBAN_FLOOD->value => Risk::UNKNOWN->color(),
                NaturalHazard::COASTAL_FLOOD->value => Risk::MEDIUM->color(),
                NaturalHazard::EARTHQUAKE->value => Risk::UNKNOWN->color(),
                NaturalHazard::LANDSLIDE->value => Risk::MEDIUM->color(),
                NaturalHazard::TSUNAMI->value => Risk::MEDIUM->color(),
                NaturalHazard::VOLCANO->value => Risk::MEDIUM->color(),
                NaturalHazard::CYCLONE->value => Risk::UNKNOWN->color(),
                NaturalHazard::WATER_SCARCITY->value => Risk::UNKNOWN->color(),
                NaturalHazard::EXTREME_HEAT->value => Risk::VERY_LOW->color(),
                NaturalHazard::WILDFIRE->value => Risk::VERY_LOW->color(),
            ],
        ];

        $district = [];
        $data = [];
        foreach ($this->questionnaireIds as $id) {
            $this->setQuestionnaire($id);

            $county = $this->questionnaire->company->cus_county ?? null;
            $district[] = $county ? County::from($county)->district()->value : '0';
        }

        foreach ($district as $row) {
            $data = array_merge($data, $risksByDistrict[$row] ?? $risksByDistrict['0']);
        }

        return $data;
    }

    public function parseDataForEmissions()
    {
        $scope1 = $this->calculateScope1();
        $scope2 = $this->calculateScope2();
        $scope3 = $this->calculateScope3();

        if ($scope1 === 0 && $scope2 === 0 && $scope3 === 0) {
            return null;
        }

        return round($scope1 + $scope2 + $scope3, 2);
    }

    protected function calculateScope1()
    {
        $total = 0;
        foreach ($this->answers as $answer) {
            $scope = $answer['871'] ?? null;
            $scopeStatus = isset($scope->value) ? $scope->value : null;
            if ($scopeStatus == 'yes') {
                $scope = $answer['1147'] ?? null;
                $scopedata = isset($scope->value) ? json_decode($scope->value, true) : null;

                $total += ($scopedata[1270] ?? 0) + ($scopedata[1271] ?? 0) + ($scopedata[1272] ?? 0);
            } else {
                $answers1 = $answer['872'] ?? null;
                $answers2 = $answer['875'] ?? null;
                $answers3 = $answer['887'] ?? null;

                $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
                $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;
                $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

                if ($value1 == null && $value2 == null && $value3 == null) {
                    continue;
                }

                if (isset($value1['946'])) {
                    $total += $value1['946'] * 1.74529;
                }

                if (isset($value1['947'])) {
                    $total += $value1['947'] * 0.44436;
                }

                if (isset($value1['948'])) {
                    $total += $value1['948'] * 1.158;
                }

                if (isset($value1['949'])) {
                    $total += $value1['949'] * 2.01574 / 1000;
                }

                if (isset($value1['950'])) {
                    $total += $value1['950'] * 1.55709;
                }

                if (isset($value1['951'])) {
                    $total += $value1['951'] * 1.54354;
                }

                if (isset($value1['952'])) {
                    $total += $value1['952'] * 2.54514;
                }

                if (isset($value1['953'])) {
                    $total += $value1['953'] * 2.54013;
                }

                if (isset($value1['954'])) {
                    $total += $value1['954'] * 2.55784;
                }

                if (isset($value1['955'])) {
                    $total += $value1['955'] * 2.6988;
                }

                if (isset($value1['956'])) {
                    $total += $value1['956'] * 2.16185;
                }

                if (isset($value1['957'])) {
                    $total += $value1['957'] * 2.3397;
                }

                if (isset($value1['957'])) {
                    $total += $value1['957'] * 2.3397;
                }

                // Value 2
                if (isset($value2['946'])) {
                    $total += $value2['946'] * 1.74529;
                }

                if (isset($value2['947'])) {
                    $total += $value2['947'] * 0.44436;
                }

                if (isset($value2['948'])) {
                    $total += $value2['948'] * 1.158;
                }

                if (isset($value2['949'])) {
                    $total += $value2['949'] * 2.01574 / 1000;
                }

                if (isset($value2['950'])) {
                    $total += $value2['950'] * 1.55709;
                }

                if (isset($value2['951'])) {
                    $total += $value2['951'] * 1.54354;
                }

                if (isset($value2['952'])) {
                    $total += $value2['952'] * 2.54514;
                }

                if (isset($value2['953'])) {
                    $total += $value2['953'] * 2.54013;
                }

                if (isset($value2['954'])) {
                    $total += $value2['954'] * 2.55784;
                }

                if (isset($value2['955'])) {
                    $total += $value2['955'] * 2.6988;
                }

                if (isset($value2['956'])) {
                    $total += $value2['956'] * 2.16185;
                }

                if (isset($value2['957'])) {
                    $total += $value2['957'] * 2.3397;
                }

                // value 3

                if (isset($value3['1009'])) {
                    $total += $value3['1009'] * 22800;
                }

                if (isset($value3['1010'])) {
                    $total += $value3['1010'] * 14800;
                }

                if (isset($value3['1011'])) {
                    $total += $value3['1011'] * 675;
                }

                if (isset($value3['1012'])) {
                    $total += $value3['1012'] * 92;
                }

                if (isset($value3['1013'])) {
                    $total += $value3['1013'] * 3500;
                }

                if (isset($value3['1014'])) {
                    $total += $value3['1014'] * 1100;
                }

                if (isset($value3['1015'])) {
                    $total += $value3['1015'] * 1430;
                }

                if (isset($value3['1016'])) {
                    $total += $value3['1016'] * 353;
                }

                if (isset($value3['1017'])) {
                    $total += $value3['1017'] * 4470;
                }

                if (isset($value3['1018'])) {
                    $total += $value3['1018'] * 53;
                }

                if (isset($value3['1019'])) {
                    $total += $value3['1019'] * 124;
                }

                if (isset($value3['1020'])) {
                    $total += $value3['1020'] * 12;
                }

                if (isset($value3['1021'])) {
                    $total += $value3['1021'] * 3220;
                }

                if (isset($value3['1022'])) {
                    $total += $value3['1022'] * 1340;
                }

                if (isset($value3['1023'])) {
                    $total += $value3['1023'] * 1370;
                }

                if (isset($value3['1024'])) {
                    $total += $value3['1024'] * 9810;
                }

                if (isset($value3['1025'])) {
                    $total += $value3['1025'] * 693;
                }

                if (isset($value3['1026'])) {
                    $total += $value3['1026'] * 1030;
                }

                if (isset($value3['1027'])) {
                    $total += $value3['1027'] * 794;
                }

                if (isset($value3['1028'])) {
                    $total += $value3['1028'] * 1640;
                }

                if (isset($value3['1029'])) {
                    $total += $value3['1029'] * 7390;
                }

                if (isset($value3['1030'])) {
                    $total += $value3['1030'] * 12200;
                }

                if (isset($value3['1031'])) {
                    $total += $value3['1031'] * 8830;
                }

                if (isset($value3['1032'])) {
                    $total += $value3['1032'] * 8860;
                }

                if (isset($value3['1033'])) {
                    $total += $value3['1033'] * 9160;
                }

                if (isset($value3['1034'])) {
                    $total += $value3['1034'] * 9300;
                }

                if (isset($value3['1035'])) {
                    $total += $value3['1035'] * 10300;
                }

                if (isset($value3['1036'])) {
                    $total += $value3['1036'] * 16.12;
                }

                if (isset($value3['1037'])) {
                    $total += $value3['1037'] * 14;
                }

                if (isset($value3['1038'])) {
                    $total += $value3['1038'] * 19;
                }

                if (isset($value3['1039'])) {
                    $total += $value3['1039'] * 2100;
                }

                if (isset($value3['1040'])) {
                    $total += $value3['1040'] * 1330;
                }

                if (isset($value3['1041'])) {
                    $total += $value3['1041'] * 1766;
                }

                if (isset($value3['1042'])) {
                    $total += $value3['1042'] * 3444;
                }

                if (isset($value3['1043'])) {
                    $total += $value3['1043'] * 3922;
                }

                if (isset($value3['1044'])) {
                    $total += $value3['1044'] * 4386;
                }

                if (isset($value3['1045'])) {
                    $total += $value3['1045'] * 2107;
                }

                if (isset($value3['1046'])) {
                    $total += $value3['1046'] * 2804;
                }

                if (isset($value3['1047'])) {
                    $total += $value3['1047'] * 1774;
                }

                if (isset($value3['1048'])) {
                    $total += $value3['1048'] * 1627;
                }

                if (isset($value3['1049'])) {
                    $total += $value3['1049'] * 1552;
                }

                if (isset($value3['1050'])) {
                    $total += $value3['1050'] * 1825;
                }

                if (isset($value3['1051'])) {
                    $total += $value3['1051'] * 1495;
                }

                if (isset($value3['1052'])) {
                    $total += $value3['1052'] * 2088;
                }

                if (isset($value3['1053'])) {
                    $total += $value3['1053'] * 2229;
                }

                if (isset($value3['1054'])) {
                    $total += $value3['1054'] * 14;
                }

                if (isset($value3['1055'])) {
                    $total += $value3['1055'] * 4;
                }

                if (isset($value3['1056'])) {
                    $total += $value3['1056'] * 2053;
                }

                if (isset($value3['1057'])) {
                    $total += $value3['1057'] * 22;
                }

                if (isset($value3['1058'])) {
                    $total += $value3['1058'] * 93;
                }

                if (isset($value3['1059'])) {
                    $total += $value3['1059'] * 844;
                }

                if (isset($value3['1060'])) {
                    $total += $value3['1060'] * 2346;
                }

                if (isset($value3['1061'])) {
                    $total += $value3['1061'] * 3026;
                }

                if (isset($value3['1062'])) {
                    $total += $value3['1062'] * 3;
                }

                if (isset($value3['1063'])) {
                    $total += $value3['1063'] * 2967;
                }

                if (isset($value3['1064'])) {
                    $total += $value3['1064'] * 1258;
                }

                if (isset($value3['1065'])) {
                    $total += $value3['1065'] * 2631;
                }

                if (isset($value3['1066'])) {
                    $total += $value3['1066'] * 3190;
                }

                if (isset($value3['1067'])) {
                    $total += $value3['1067'] * 3143;
                }

                if (isset($value3['1068'])) {
                    $total += $value3['1068'] * 2526;
                }

                if (isset($value3['1069'])) {
                    $total += $value3['1069'] * 3085;
                }

                if (isset($value3['1070'])) {
                    $total += $value3['1070'] * 2729;
                }

                if (isset($value3['1071'])) {
                    $total += $value3['1071'] * 2280;
                }

                if (isset($value3['1072'])) {
                    $total += $value3['1072'] * 2440;
                }

                if (isset($value3['1073'])) {
                    $total += $value3['1073'] * 1505;
                }

                if (isset($value3['1074'])) {
                    $total += $value3['1074'] * 1509;
                }

                if (isset($value3['1075'])) {
                    $total += $value3['1075'] * 2138;
                }

                if (isset($value3['1076'])) {
                    $total += $value3['1076'] * 3607;
                }

                if (isset($value3['1077'])) {
                    $total += $value3['1077'] * 14;
                }

                if (isset($value3['1078'])) {
                    $total += $value3['1078'] * 95;
                }

                if (isset($value3['1079'])) {
                    $total += $value3['1079'] * 38;
                }

                if (isset($value3['1080'])) {
                    $total += $value3['1080'] * 3245;
                }

                if (isset($value3['1081'])) {
                    $total += $value3['1081'] * 26;
                }

                if (isset($value3['1082'])) {
                    $total += $value3['1082'] * 1805;
                }

                if (isset($value3['1083'])) {
                    $total += $value3['1083'] * 3805;
                }

                if (isset($value3['1084'])) {
                    $total += $value3['1084'] * 2265;
                }

                if (isset($value3['1085'])) {
                    $total += $value3['1085'] * 1888;
                }

                if (isset($value3['1086'])) {
                    $total += $value3['1086'] * 296;
                }

                if (isset($value3['1087'])) {
                    $total += $value3['1087'] * 1387;
                }

                if (isset($value3['1088'])) {
                    $total += $value3['1088'] * 1397;
                }

                if (isset($value3['1089'])) {
                    $total += $value3['1089'] * 605;
                }

                if (isset($value3['1090'])) {
                    $total += $value3['1090'] * 2140;
                }

                if (isset($value3['1091'])) {
                    $total += $value3['1091'] * 698;
                }

                if (isset($value3['1092'])) {
                    $total += $value3['1092'] * 1765;
                }

                if (isset($value3['1093'])) {
                    $total += $value3['1093'] * 466;
                }

                if (isset($value3['1094'])) {
                    $total += $value3['1094'] * 1357;
                }

                if (isset($value3['1095'])) {
                    $total += $value3['1095'] * 3985;
                }

                if (isset($value3['1096'])) {
                    $total += $value3['1096'] * 13214;
                }

                if (isset($value3['1097'])) {
                    $total += $value3['1097'] * 13396;
                }

                if (isset($value3['1098'])) {
                    $total += $value3['1098'] * 631;
                }
            }
        }

        return $total ? $total / 1000 : 0;
    }

    protected function calculateScope2()
    {
        $total = 0;
        foreach ($this->answers as $answer) {
            $answers1 = $answer['888'] ?? null;

            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 == null) {
                continue;
            }

            if (isset($value1['1099'])) {
                $total += $value1['1099'] * 0.184;
            }

            if (isset($value1['1100'])) {
                $total += $value1['1100'] * 0.17073;
            }

            if (isset($value1['1101'])) {
                $total += $value1['1101'] * 0.17073;
            }

            if (isset($value1['1102'])) {
                $total += $value1['1102'] * 0.17073;
            }
        }

        return $total ? $total / 1000 : 0;
    }

    protected function calculateScope3()
    {
        $total = 0;
        foreach ($this->answers as $answer) {
            $answers1 = $answer['880'] ?? null;
            $answers2 = $answer['890'] ?? null;
            $answers3 = $answer['892'] ?? null;
            $answers4 = $answer['1014'] ?? null;
            $answers5 = $answer['896'] ?? null;

            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true) : null;
            $value5 = isset($answers5->value) ? json_decode($answers5->value, true) : null;

            if ($value1 == null && $value2 == null && $value3 == null && $value4 == null && $value5 == null) {
                continue;
            }

            if (isset($value1['946'])) {
                $total += $value1['946'] * 1.74529;
            }

            if (isset($value1['947'])) {
                $total += $value1['947'] * 0.44436;
            }

            if (isset($value1['948'])) {
                $total += $value1['948'] * 1.158;
            }

            if (isset($value1['949'])) {
                $total += $value1['949'] * 2.01574 / 1000;
            }

            if (isset($value1['950'])) {
                $total += $value1['950'] * 1.55709;
            }

            if (isset($value1['951'])) {
                $total += $value1['951'] * 1.54354;
            }

            if (isset($value1['952'])) {
                $total += $value1['952'] * 2.54514;
            }

            if (isset($value1['953'])) {
                $total += $value1['953'] * 2.54013;
            }

            if (isset($value1['954'])) {
                $total += $value1['954'] * 2.55784;
            }

            if (isset($value1['955'])) {
                $total += $value1['955'] * 2.6988;
            }

            if (isset($value1['956'])) {
                $total += $value1['956'] * 2.16185;
            }

            if (isset($value1['957'])) {
                $total += $value1['957'] * 2.3397;
            }

            // vALUE 2
            if (isset($value2['1103'])) {
                $total += $value2['1103'] * 21.2801938;
            }

            if (isset($value2['1104'])) {
                $total += $value2['1104'] * 8.883271318;
            }

            if (isset($value2['1105'])) {
                $total += $value2['1105'] * 21.2801938;
            }

            if (isset($value2['1106'])) {
                $total += $value2['1106'] * 21.2801938;
            }

            if (isset($value2['1107'])) {
                $total += $value2['1107'] * 8.883271318;
            }

            if (isset($value2['1108'])) {
                $total += $value2['1108'] * 21.2801938;
            }

            if (isset($value2['1109'])) {
                $total += $value2['1109'] * 21.2801938;
            }

            if (isset($value2['1110'])) {
                $total += $value2['1110'] * 8.883271318;
            }

            if (isset($value2['1111'])) {
                $total += $value2['1111'] * 21.2801938;
            }

            if (isset($value2['1112'])) {
                $total += $value2['1112'] * 8.910581395;
            }

            if (isset($value2['1114'])) {
                $total += $value2['1114'] * 587.325666;
            }

            if (isset($value2['1115'])) {
                $total += $value2['1115'] * 21.2801938;
            }

            if (isset($value2['1116'])) {
                $total += $value2['1116'] * 21.2801938;
            }

            if (isset($value2['1117'])) {
                $total += $value2['1117'] * 8.883271318;
            }

            if (isset($value2['1118'])) {
                $total += $value2['1118'] * 21.2801938;
            }

            if (isset($value2['1197'])) {
                $total += $value2['1197'] * 8.910581395;
            }

            // vALUE 3
            if (isset($value2['1119'])) {
                $total += $value2['1119'] * 0.149;
            }

            if (isset($value2['1120'])) {
                $total += $value2['1120'] * 0.272;
            }

            // Value 4

            if (isset($value4['1160'])) {
                $total += $value4['1160'] * 7.75101835;
            }

            if (isset($value4['1161'])) {
                $total += $value4['1161'] * 80.33776678;
            }

            if (isset($value4['1162'])) {
                $total += $value4['1162'] * 39.21249183;
            }

            if (isset($value4['1163'])) {
                $total += $value4['1163'] * 241.7510184;
            }

            if (isset($value4['1164'])) {
                $total += $value4['1164'] * 131.7510184;
            }

            if (isset($value4['1165'])) {
                $total += $value4['1165'] * 1861.751018;
            }

            if (isset($value4['1166'])) {
                $total += $value4['1166'] * 4018.002952;
            }

            if (isset($value4['1167'])) {
                $total += $value4['1167'] * 1401;
            }

            if (isset($value4['1168'])) {
                $total += $value4['1168'] * 120.05;
            }

            if (isset($value4['1169'])) {
                $total += $value4['1169'] * 3335.5719;
            }

            if (isset($value4['1170'])) {
                $total += $value4['1170'] * 312.6117802;
            }

            if (isset($value4['1171'])) {
                $total += $value4['1171'] * 1402.766667;
            }

            if (isset($value4['1172'])) {
                $total += $value4['1172'] * 22310;
            }

            if (isset($value4['1173'])) {
                $total += $value4['1173'] * 3701.403593;
            }

            if (isset($value4['1174'])) {
                $total += $value4['1174'] * 112.0155814;
            }

            if (isset($value4['1175'])) {
                $total += $value4['1175'] * 114.8322064;
            }

            if (isset($value4['1176'])) {
                $total += $value4['1176'] * 4363.333333;
            }

            if (isset($value4['1177'])) {
                $total += $value4['1177'] * 3267;
            }

            if (isset($value4['1178'])) {
                $total += $value4['1178'] * 24865.47556;
            }

            if (isset($value4['1179'])) {
                $total += $value4['1179'] * 5647.945634;
            }

            if (isset($value4['1180'])) {
                $total += $value4['1180'] * 4633.478261;
            }

            if (isset($value4['1181'])) {
                $total += $value4['1181'] * 6308;
            }

            if (isset($value4['1182'])) {
                $total += $value4['1182'] * 28380;
            }

            if (isset($value4['1183'])) {
                $total += $value4['1183'] * 9122.6364;
            }

            if (isset($value4['1184'])) {
                $total += $value4['1184'] * 5268.5564;
            }

            if (isset($value4['1185'])) {
                $total += $value4['1185'] * 3682.6829;
            }

            if (isset($value4['1186'])) {
                $total += $value4['1186'] * 3100.6364;
            }

            if (isset($value4['1187'])) {
                $total += $value4['1187'] * 3116.291564;
            }

            if (isset($value4['1188'])) {
                $total += $value4['1188'] * 2574.164753;
            }

            if (isset($value4['1189'])) {
                $total += $value4['1189'] * 3276.706933;
            }

            if (isset($value4['1190'])) {
                $total += $value4['1190'] * 828.868156;
            }

            if (isset($value4['1191'])) {
                $total += $value4['1191'] * 884.1607826;
            }

            if (isset($value4['1192'])) {
                $total += $value4['1192'] * 919.39628;
            }

            // Value 5
            if (isset($value5)) {
                $total += array_sum($value5) * 0.149;
            }
        }

        return $total ? $total / 1000 : 0;
    }

    public function parseDataForScope()
    {
        $scope1 = round($this->calculateScope1(), 2);
        $scope2 = round($this->calculateScope2(), 2);
        $scope3 = round($this->calculateScope3(), 2);

        if ($scope1 === 0 && $scope2 === 0 && $scope3 === 0) {
            return null;
        }

        $labels = ['Âmbito 1', 'Âmbito 2', 'Âmbito 3'];
        $data = [
            $scope1 != 0 ? $scope1 : 0,
            $scope2 != 0 ? $scope2 : 0,
            $scope3 != 0 ? $scope3 : 0,
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalWorkers()
    {
        $sum = 0;
        foreach ($this->answers as $answers) {
            $answers1 = $answers['828'] ?? null;
            $sum += isset($answers1->value) ? json_decode($answers1->value, true)[1] : 0;
        }

        if ($sum == null || $sum == 0) {
            return 0;
        }

        return $sum;
    }

    public function praseDataForBusinessUnits()
    {
        $sum = 0;
        foreach ($this->answers as $answers) {
            $answers1 = $answers['829'] ?? null;
            $sum += isset($answers1->value) ? json_decode($answers1->value, true)[1] : 0;
        }

        if ($sum == null || $sum == 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForAverageMonthsActivity()
    {
        $sum = 0;
        $data = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1044'] ?? null;
            $sum += isset($answers1->value) ? json_decode($answers1->value, true)[1] : 0;
            $data++;
        }

        if (($sum == null || $sum == 0) && ($data == 0)) {
            return 0;
        }

        return round(calculateDivision($sum, $data), 0);
    }

    public function parseDataForAverageLevelsSatisfactionPlatforms()
    {
        $labels = [];
        $data = [];

        $data901 = 0;
        $data902 = 0;
        $data903 = 0;
        $data904 = 0;
        $data8 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['831'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [
                    901, 902, 903, 904, 8,
                ];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        if ($value == 901) {
                            $data901++;
                        } elseif ($value == 902) {
                            $data902++;
                        } elseif ($value == 903) {
                            $data903++;
                        } elseif ($value == 904) {
                            $data904++;
                        } elseif ($value == 8) {
                            $data8++;
                        }

                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        foreach ($data as $key => $value) {
            if ($key == 901) {
                $data[$key] = calculateDivision($data[$key], $data901);
            } elseif ($key == 902) {
                $data[$key] = calculateDivision($data[$key], $data902);
            } elseif ($key == 903) {
                $data[$key] = calculateDivision($data[$key], $data903);
            } elseif ($key == 904) {
                $data[$key] = calculateDivision($data[$key], $data904);
            } elseif ($key == 8) {
                $data[$key] = calculateDivision($data[$key], $data8);
            }
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForNumberOfComplaints()
    {
        $sum1 = 0;
        $sum2 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['832'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;

            $answers2 = $answers['833'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum2 += $value2 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0) {
            return null;
        }

        $labels = ['Reclamações', 'Elogios'];
        $data = [$sum1, $sum2];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalSuppliers()
    {
        $sum1 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['836'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;
        }

        if ($sum1 === 0) {
            return 0;
        }

        return $sum1;
    }

    public function parseDataForTotalReportingUnitSuppliers()
    {
        $sum1 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1144'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;
        }

        if ($sum1 === 0) {
            return 0;
        }

        return $sum1;
    }

    public function parseDataForTotalReportingUnitSuppliersChildLabor()
    {
        $sum1 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1145'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;
        }

        if ($sum1 === 0) {
            return 0;
        }

        return $sum1;
    }

    public function parseDataForTurnoverTotalOperatingCosts()
    {
        $sum1 = 0;
        $sum2 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['839'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;

            $answers2 = $answers['840'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum2 += $value2 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0) {
            return null;
        }

        $labels = ['Volume de Negócio Total', 'Custos Operacionais'];
        $data = [$sum1, $sum2];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalValueEmployeeSalaries()
    {
        $sum1 = 0;
        $sum2 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['841'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;

            $answers2 = $answers['842'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum2 += $value2 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0) {
            return null;
        }

        $labels = ['Salários', 'Benefícios de empregados'];
        $data = [$sum1, $sum2];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalCapitalProviders()
    {
        $sum1 = 0;
        $sum2 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['843'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;

            $answers2 = $answers['844'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum2 += $value2 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0) {
            return null;
        }

        $labels = ['Pagamentos a fornecedores de capital', 'Pagamentos ao Estado'];
        $data = [$sum1, $sum2];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalInvestmentValue()
    {
        $sum3 = 0;
        $sum4 = 0;
        $sum5 = 0;
        $sum6 = 0;
        $sum7 = 0;

        foreach ($this->answers as $answers) {
            $answers3 = $answers['847'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;

            $answers4 = $answers['848'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;
            $sum4 += $value4 ?? 0;

            $answers5 = $answers['849'] ?? null;
            $value5 = isset($answers5->value) ? json_decode($answers5->value, true)[1] : null;
            $sum5 += $value5 ?? 0;

            $answers6 = $answers['850'] ?? null;
            $value6 = isset($answers6->value) ? json_decode($answers6->value, true)[1] : null;
            $sum6 += $value6 ?? 0;

            $answers7 = $answers['851'] ?? null;
            $value7 = isset($answers7->value) ? json_decode($answers7->value, true)[1] : null;
            $sum7 += $value7 ?? 0;
        }

        if ($sum3 === 0 && $sum4 === 0 && $sum5 === 0 && $sum6 === 0 && $sum7 === 0) {
            return null;
        }

        $labels = [
            'Inovação',
            'Digitalizaçao e cibersegurança',
            'Investigação & Desenvolvimento',
            'Proteção Ambiental',
            'Valorização Territorial',
            'Total',
        ];
        $data = [$sum3, $sum4, $sum5, $sum6, $sum7, ($sum3 + $sum4 + $sum5 + $sum6 + $sum7)];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    protected function removeNullData($labels, $rowdata)
    {
        $label = [];
        $data = [];
        $loop = 0;

        foreach ($rowdata as $row) {
            if ($row !== 0 && $row != null) {
                array_push($label, $labels[$loop]);
                array_push($data, $row);
            }

            $loop++;
        }

        return [
            'label' => $label,
            'data' => $data,
        ];
    }

    public function parseDataForTotalFinancialSupportReceived()
    {
        $sum1 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['852'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;
        }

        if ($sum1 === 0) {
            return 0;
        }

        return $sum1;
    }

    public function parseDataForTurnoverResultingFromProducts()
    {
        $sum1 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['853'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;
        }

        if ($sum1 === 0) {
            return 0;
        }

        return $sum1;
    }

    public function parseDataForWithheldEconomicValue()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;
        $sum5 = 0;
        $sum6 = 0;
        $sum7 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['839'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;

            $answers2 = $answers['840'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum2 += $value2 ?? 0;

            $answers3 = $answers['841'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

            $sum3 += $value3 ?? 0;

            $answers4 = $answers['842'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

            $sum4 += $value4 ?? 0;

            $answers5 = $answers['843'] ?? null;
            $value5 = isset($answers5->value) ? json_decode($answers5->value, true)[1] : null;

            $sum5 += $value5 ?? 0;

            $answers6 = $answers['844'] ?? null;
            $value6 = isset($answers6->value) ? json_decode($answers6->value, true)[1] : null;

            $sum6 += $value6 ?? 0;

            $answers7 = $answers['845'] ?? null;
            $value7 = isset($answers7->value) ? json_decode($answers7->value, true)[1] : null;

            $sum7 += $value7 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0 && $sum4 === 0 && $sum5 === 0 && $sum6 === 0 && $sum7 === 0) {
            return 0;
        }

        return $sum1 - ($sum2 + $sum3 + $sum4 + $sum5 + $sum6 + $sum7);
    }

    public function parseDataForTurnoverValue()
    {
        $sum1 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['839'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;
        }

        if ($sum1 === 0) {
            return 0;
        }

        return $sum1;
    }

    public function parseDataForTotalAmountCapexDa()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['854'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;

            $answers2 = $answers['855'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum2 += $value2 ?? 0;

            $answers3 = $answers['856'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

            $sum3 += $value3 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0) {
            return 0;
        }

        $labels = [
            'Bens e equipamentos do ano vigente',
            'Bens e Equipamentos do ano anterior',
            'Depreciação e Amortização',
        ];
        $data = [$sum1, $sum2, $sum3];

        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForDirectEconomicValue()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;
        $sum5 = 0;
        $sum6 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['840'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;

            $answers2 = $answers['841'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum2 += $value2 ?? 0;

            $answers3 = $answers['842'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

            $sum3 += $value3 ?? 0;

            $answers4 = $answers['843'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

            $sum4 += $value4 ?? 0;

            $answers5 = $answers['844'] ?? null;
            $value5 = isset($answers5->value) ? json_decode($answers5->value, true)[1] : null;

            $sum5 += $value5 ?? 0;

            $answers6 = $answers['845'] ?? null;
            $value6 = isset($answers6->value) ? json_decode($answers6->value, true)[1] : null;

            $sum6 += $value6 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0 && $sum4 === 0 && $sum5 === 0 && $sum6 === 0) {
            return 0;
        }

        $labels = [
            __('Custos Operacionais'),
            __('Salários'),
            __('Benefícios de empregados'),
            __('Pagamentos a fornecedores de capital'),
            __('Pagamentos ao estado'),
            __('Investimentos na comunidade'),
        ];
        $data = [$value1, $value2, $value3, $value4, $value5, $value6];

        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTaxonomyTurnover()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;
        $sum5 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['853'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum1 += $value1 ?? 0;

            $answers2 = $answers['854'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum2 += $value2 ?? 0;

            $answers3 = $answers['855'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

            $sum3 += $value3 ?? 0;

            $answers4 = $answers['856'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

            $sum4 += $value4 ?? 0;

            $answers5 = $answers['857'] ?? null;
            $value5 = isset($answers5->value) ? json_decode($answers5->value, true)[1] : null;

            $sum5 += $value5 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0 && $sum4 === 0 && $sum5 === 0) {
            return 0;
        }

        $labels = [
            __('Volume de negócios'),
            __('CapEx'),
            __('OpEx'),
        ];
        $data = [$value1, ($value2 - $value3 + $value4), $value5];

        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageOfWorkersByContractualRegime()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['923'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['924'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $answers['925'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;

            $answers4 = $answers['828'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;
            $sum4 += $value4 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0 && $sum4 === 0) {
            return null;
        }

        $labels = [
            'Full-time',
            'Part-time',
            'Outros regimes contratuais',
        ];

        $data = [
            calculatePercentage($sum1, $sum4),
            calculatePercentage($sum2, $sum4),
            calculatePercentage($sum3, $sum4),
        ];

        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageWorkersByContract()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1026'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['1027'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $answers['828'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0) {
            return null;
        }

        $labels = ['Termo certo', 'Sem termo'];
        $data = [
            calculatePercentage($sum1, $sum3),
            calculatePercentage($sum2, $sum3),
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalNumberOfHires()
    {
        $sum = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['926'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForPercentageOfHiresByContractualRegime()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['926'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['927'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $answers['928'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;

            $answers4 = $answers['929'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;
            $sum4 += $value4 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0 && $sum4 === 0) {
            return null;
        }

        $labels = ['Termo certo', 'Sem termo', 'Outros regimes contratuais'];
        $data = [
            calculatePercentage($sum2, $sum1),
            calculatePercentage($sum3, $sum1),
            calculatePercentage($sum4, $sum1),
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageOfHiringOfWorkersByGender()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['926'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['930'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $answers['931'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;

            $answers4 = $answers['1015'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;
            $sum4 += $value4 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0 && $sum4 === 0) {
            return null;
        }

        $labels = ['Masculino', 'Feminino', 'Outros'];
        $data = [
            calculatePercentage($sum2, $sum1),
            calculatePercentage($sum3, $sum1),
            calculatePercentage($sum4, $sum1),
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageOfWorkersResidingLocally()
    {
        $sum1 = 0;
        $sum2 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['937'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['828'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0) {
            return null;
        }

        return calculatePercentage($sum1, $sum2);
    }

    public function parseDataForJobCreation()
    {
        $sum1 = 0;
        $sum2 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['932'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['933'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0) {
            return null;
        }

        return $sum1 - $sum2;
    }

    public function parseDataForAverageTurnover()
    {
        $sum1 = 0;
        $sum2 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['932'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['933'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0) {
            return null;
        }

        return round(($sum1 + $sum2) / 2, 2);
    }

    public function parseDataForAverageTurnoverRate()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['932'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['933'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $answers['828'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0) {
            return null;
        }

        return calculatePercentage((($sum1 + $sum2) / 2), $sum3);
    }

    public function parseDataForAbsenteeismRate()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['935'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['937'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $answers['828'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0) {
            return null;
        }

        return calculatePercentage($sum1, ($sum2 * $sum3));
    }

    public function parseDataForSumOfBasicWagesOfWorkersByGender()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['938'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['939'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $answers['1016'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$sum1, $sum2, $sum3];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForBaseSalaryOfFemaleWorkersByProfessional()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1029'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;
        }

        if ($sum1193 === 0 && $sum1194 === 0 && $sum1195 === 0 && $sum1196 === 0) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$sum1193, $sum1194, $sum1195, $sum1196];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function praseDataForBaseSalaryOfmaleWorkersByProfessional()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1028'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;
        }

        if ($sum1193 === 0 && $sum1194 === 0 && $sum1195 === 0 && $sum1196 === 0) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$sum1193, $sum1194, $sum1195, $sum1196];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForSalaryOfWorkersOfOtherGenderByProfessional()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1032'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;
        }

        if ($sum1193 === 0 && $sum1194 === 0 && $sum1195 === 0 && $sum1196 === 0) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$sum1193, $sum1194, $sum1195, $sum1196];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForRemunerationOfEmployeesByGender()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $this->answers['941'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $this->answers['940'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $this->answers['1017'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$sum1, $sum2, $sum3];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForGrossRemunerationOfFemaleWorkersByProfessional()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1031'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;
        }

        if ($sum1193 === 0 && $sum1194 === 0 && $sum1195 === 0 && $sum1196 === 0) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$sum1193, $sum1194, $sum1195, $sum1196];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForGrossRemunerationOfmaleWorkersByProfessional()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1030'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;
        }

        if ($sum1193 === 0 && $sum1194 === 0 && $sum1195 === 0 && $sum1196 === 0) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$sum1193, $sum1194, $sum1195, $sum1196];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForGrossRemunerationOfWorkersOtherGenderByProfessional()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1033'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;
        }

        if ($sum1193 === 0 && $sum1194 === 0 && $sum1195 === 0 && $sum1196 === 0) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$sum1193, $sum1194, $sum1195, $sum1196];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForEarningMoreThanTheNationalMinimumWageByGender()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $this->answers['943'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $this->answers['942'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $this->answers['1018'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;

            $answers4 = $this->answers['828'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;
            $sum4 += $value4 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0 && $sum4 === 0) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [
            calculatePercentage($sum1, $sum4),
            calculatePercentage($sum2, $sum4),
            calculatePercentage($sum3, $sum4),
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageMaleWorkersProfessional()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        $sum1193_sepcific = 0;
        $sum1194_sepcific = 0;
        $sum1195_sepcific = 0;
        $sum1196_sepcific = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1023'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $answers2 = $answers['1023'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

            $answers3 = $answers['1024'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

            $answers4 = $answers['1019'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;

            $sum1193_sepcific += ($value2[1193] ?? 0) + ($value3[1193] ?? 0) + ($value4[1193] ?? 0);
            $sum1194_sepcific += ($value2[1194] ?? 0) + ($value3[1194] ?? 0) + ($value4[1194] ?? 0);
            $sum1195_sepcific += ($value2[1195] ?? 0) + ($value3[1195] ?? 0) + ($value4[1195] ?? 0);
            $sum1196_sepcific += ($value2[1196] ?? 0) + ($value3[1196] ?? 0) + ($value4[1196] ?? 0);
        }

        if ($sum1193_sepcific === 0 && $sum1194_sepcific === 0 && $sum1195_sepcific === 0 && $sum1196_sepcific === 0) {
            return null;
        }

        $data1 = calculatePercentage($sum1193, $sum1193_sepcific);
        $data2 = calculatePercentage($sum1194, $sum1194_sepcific);
        $data3 = calculatePercentage($sum1195, $sum1195_sepcific);
        $data4 = calculatePercentage($sum1196, $sum1196_sepcific);

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [round($data1), round($data2), round($data3), round($data4)];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageFemaleWorkersProfessional()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        $sum1193_sepcific = 0;
        $sum1194_sepcific = 0;
        $sum1195_sepcific = 0;
        $sum1196_sepcific = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1024'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $answers2 = $answers['1023'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

            $answers3 = $answers['1024'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

            $answers4 = $answers['1019'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;

            $sum1193_sepcific += ($value2[1193] ?? 0) + ($value3[1193] ?? 0) + ($value4[1193] ?? 0);
            $sum1194_sepcific += ($value2[1194] ?? 0) + ($value3[1194] ?? 0) + ($value4[1194] ?? 0);
            $sum1195_sepcific += ($value2[1195] ?? 0) + ($value3[1195] ?? 0) + ($value4[1195] ?? 0);
            $sum1196_sepcific += ($value2[1196] ?? 0) + ($value3[1196] ?? 0) + ($value4[1196] ?? 0);
        }

        if ($sum1193_sepcific === 0 && $sum1194_sepcific === 0 && $sum1195_sepcific === 0 && $sum1196_sepcific === 0) {
            return null;
        }

        $data1 = calculatePercentage($sum1193, $sum1193_sepcific);
        $data2 = calculatePercentage($sum1194, $sum1194_sepcific);
        $data3 = calculatePercentage($sum1195, $sum1195_sepcific);
        $data4 = calculatePercentage($sum1196, $sum1196_sepcific);

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [round($data1), round($data2), round($data3), round($data4)];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageOtherGenderWorkersProfessional()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        $sum1193_sepcific = 0;
        $sum1194_sepcific = 0;
        $sum1195_sepcific = 0;
        $sum1196_sepcific = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1019'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $answers2 = $answers['1023'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

            $answers3 = $answers['1024'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

            $answers4 = $answers['1019'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;

            $sum1193_sepcific += ($value2[1193] ?? 0) + ($value3[1193] ?? 0) + ($value4[1193] ?? 0);
            $sum1194_sepcific += ($value2[1194] ?? 0) + ($value3[1194] ?? 0) + ($value4[1194] ?? 0);
            $sum1195_sepcific += ($value2[1195] ?? 0) + ($value3[1195] ?? 0) + ($value4[1195] ?? 0);
            $sum1196_sepcific += ($value2[1196] ?? 0) + ($value3[1196] ?? 0) + ($value4[1196] ?? 0);
        }

        if ($sum1193_sepcific === 0 && $sum1194_sepcific === 0 && $sum1195_sepcific === 0 && $sum1196_sepcific === 0) {
            return null;
        }

        $data1 = calculatePercentage($sum1193, $sum1193_sepcific);
        $data2 = calculatePercentage($sum1194, $sum1194_sepcific);
        $data3 = calculatePercentage($sum1195, $sum1195_sepcific);
        $data4 = calculatePercentage($sum1196, $sum1196_sepcific);

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [round($data1), round($data2), round($data3), round($data4)];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageWorkersForeignNationality()
    {
        $sum1 = 0;
        $sum2 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['946'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['828'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0) {
            return 0;
        }

        return calculatePercentage($sum1, $sum2);
    }

    public function parseDataForNumberTrainingHours()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['946'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['828'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $answers['828'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0) {
            return 0;
        }

        return ($sum1 ?? 0) + ($sum2 ?? 0) + ($sum3 ?? 0);
    }

    public function parseDataForPercentageWorkersAgegroup()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['947'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['948'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $answers['949'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;

            $answers4 = $answers['828'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;
            $sum4 += $value4 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0 && $sum4 === 0) {
            return null;
        }

        $labels = ['<30 anos', '30 a 50 anos', '>50 anos'];
        $data = [
            calculatePercentage($sum1, $sum4),
            calculatePercentage($sum2, $sum4),
            calculatePercentage($sum3, $sum4),
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTrainingHoursByGender()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['950'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['951'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $answers['1020'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$sum1, $sum2, $sum3];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForAvgHoursTrainingPerWorker()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;
        $sum5 = 0;
        $sum6 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['950'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum1 += $value1 ?? 0;

            $answers2 = $answers['951'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            $sum2 += $value2 ?? 0;

            $answers3 = $answers['1020'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            $sum3 += $value3 ?? 0;

            $answers4 = $answers['1024'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true) : null;

            if ($value4 != null) {
                $sum4 += array_sum($value4);
            }

            $answers5 = $answers['1023'] ?? null;
            $value5 = isset($answers5->value) ? json_decode($answers5->value, true) : null;

            if ($value5 != null) {
                $sum5 += array_sum($value5);
            }

            $answers6 = $answers['1019'] ?? null;
            $value6 = isset($answers6->value) ? json_decode($answers6->value, true) : null;

            if ($value6 != null) {
                $sum6 += array_sum($value6);
            }
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0 && $sum4 === 0 && $sum5 === 0 && $sum6 === 0) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [
            number_format(calculateDivision($sum1, $sum4), 2),
            number_format(calculateDivision($sum2, $sum5), 2),
            number_format(calculateDivision($sum3, $sum6), 2),
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForWorkersTrainingActions()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1034'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;
        }

        if ($sum1193 === 0 && $sum1194 === 0 && $sum1195 === 0 && $sum1196 === 0) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$sum1193, $sum1194, $sum1195, $sum1196];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForFemaleWorkersPerformanceEvaluation()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1035'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;
        }

        if ($sum1193 === 0 && $sum1194 === 0 && $sum1195 === 0 && $sum1196 === 0) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$sum1193, $sum1194, $sum1195, $sum1196];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForMaleWorkersPerformanceEvaluation()
    {
        $sum1193 = 0;
        $sum1194 = 0;
        $sum1195 = 0;
        $sum1196 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1036'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum1193 += $value1[1193] ?? 0;
            $sum1194 += $value1[1194] ?? 0;
            $sum1195 += $value1[1195] ?? 0;
            $sum1196 += $value1[1196] ?? 0;
        }

        if ($sum1193 === 0 && $sum1194 === 0 && $sum1195 === 0 && $sum1196 === 0) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$sum1193, $sum1194, $sum1195, $sum1196];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForWorkersAnotherGenderPerformanceEvaluation()
    {
        $sum = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1021'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForHoursTrainingOccupationalHealthSafety()
    {
        $sum = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['958'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForReceivedTrainingOccupationalHealthSafety()
    {
        $sum = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['959'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForAccidentsAtWork()
    {
        $sum = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['961'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForWorkdaysLostDueToAccidents()
    {
        $sum = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['962'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForNumberOfDisabledDays()
    {
        $sum = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['963'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForModalitiesSchedulesReportingUnitsCharts()
    {
        $sum908 = 0;
        $sum909 = 0;
        $sum910 = 0;
        $sum911 = 0;
        $sum8 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['964'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum908 += $value1[908] ?? 0;
            $sum909 += $value1[909] ?? 0;
            $sum910 += $value1[910] ?? 0;
            $sum911 += $value1[911] ?? 0;
            $sum8 += isset($value1[8]) && $value1[8] != null ? 1 : 0;
        }

        if ($sum908 === 0 && $sum909 === 0 && $sum910 === 0 && $sum911 === 0 && $sum8 === 0) {
            return null;
        }

        $labels = ['Horas extras', 'Banco de horas', 'Horários repartidos', 'Turnos rotativos', 'Outro'];
        $data = [$sum908, $sum909, $sum910, $sum911, $sum8];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForConciliationMeasuresUnitCharts()
    {
        $sum909 = 0;
        $sum912 = 0;
        $sum913 = 0;
        $sum914 = 0;
        $sum915 = 0;
        $sum916 = 0;
        $sum8 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['965'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum909 += $value1[909] ?? 0;
            $sum912 += $value1[912] ?? 0;
            $sum913 += $value1[913] ?? 0;
            $sum914 += $value1[914] ?? 0;
            $sum915 += $value1[915] ?? 0;
            $sum916 += $value1[916] ?? 0;
            $sum8 += isset($value1[8]) && $value1[8] != null ? 1 : 0;
        }

        if ($sum909 === 0 && $sum912 === 0 && $sum913 === 0 && $sum914 === 0 && $sum915 === 0 && $sum916 === 0 && $sum8 === 0) {
            return null;
        }

        $labels = [
            'Banco de horas',
            'Flexibilidade de horário',
            'Dias de férias adicionais',
            'Horário compactado',
            'Trabalho a partir de casa/Escritório móvel',
            'Teletrabalho',
            'Outro',
        ];
        $data = [
            $sum909,
            $sum912,
            $sum913,
            $sum914,
            $sum915,
            $sum916,
            $sum8,
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForWorkersInitialParentalLeave()
    {
        $sum151 = 0;
        $sum152 = 0;
        $sum8 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1037'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum151 += $value1[151] ?? 0;
            $sum152 += $value1[152] ?? 0;
            $sum8 += $value1[158] ?? 0;
        }

        if ($sum151 === 0 && $sum152 === 0 && $sum8 === 0) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$sum151, $sum152, $sum8];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForWorkersReturnToworkAfterParentalLeave()
    {
        $sum151 = 0;
        $sum152 = 0;
        $sum8 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1038'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum151 += $value1[151] ?? 0;
            $sum152 += $value1[152] ?? 0;
            $sum8 += $value1[158] ?? 0;
        }

        if ($sum151 === 0 && $sum152 === 0 && $sum8 === 0) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$sum151, $sum152, $sum8];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForWorkersReturnToworkAfterParentalLeaveTwelveMonth()
    {
        $sum151 = 0;
        $sum152 = 0;
        $sum8 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1039'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum151 += $value1[151] ?? 0;
            $sum152 += $value1[152] ?? 0;
            $sum8 += $value1[158] ?? 0;
        }

        if ($sum151 === 0 && $sum152 === 0 && $sum8 === 0) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$sum151, $sum152, $sum8];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForWorkerExpectToreturnAfterLeave()
    {
        $answers1 = $this->answers['969'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForEstablishedByLawCharts()
    {
        $sum1206 = 0;
        $sum1207 = 0;
        $sum1208 = 0;
        $sum1209 = 0;
        $sum8 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1046'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum1206 += $value1[1206] ?? 0;
            $sum1207 += $value1[1207] ?? 0;
            $sum1208 += $value1[1208] ?? 0;
            $sum1209 += $value1[1209] ?? 0;
            $sum8 += isset($value1[8]) && $value1[8] != null ? 1 : 0;
        }

        if ($sum1206 === 0 && $sum1207 === 0 && $sum1208 === 0 && $sum1209 === 0 && $sum8 === 0) {
            return null;
        }

        $labels = [
            'Flexibilização do tempo e de formas de trabalho',
            'Ajuste de função',
            'Apoios sociais',
            'Dias de licença adicionais',
            'Outro',
        ];
        $data = [
            $sum1206,
            $sum1207,
            $sum1208,
            $sum1209,
            $sum8,
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function praseDataForPoliciesProgress($questionnaire = null)
    {
        if ($questionnaire == null) {
            $sum1068 = 0;
            $yes1068 = 0;
            $sum1076 = 0;
            $yes1076 = 0;
            $sum1077 = 0;
            $yes1077 = 0;

            foreach ($this->answers as $answers) {
                $answers1 = $answers['1068'] ?? null;
                $value1 = isset($answers1->value) ? $answers1->value : null;

                if ($value1 !== null) {
                    if ($value1 == 'yes') {
                        $yes1068++;
                    }

                    $sum1068++;
                }

                $answers2 = $answers['1076'] ?? null;
                $value2 = isset($answers2->value) ? $answers2->value : null;

                if ($value2 !== null) {
                    if ($value2 == 'yes') {
                        $yes1076++;
                    }

                    $sum1076++;
                }

                $answers3 = $answers['1077'] ?? null;
                $value3 = isset($answers3->value) ? $answers3->value : null;

                if ($value3 !== null) {
                    if ($value3 == 'yes') {
                        $yes1077++;
                    }

                    $sum1077++;
                }
            }

            return [
                calculatePercentage($yes1068, $sum1068),
                calculatePercentage($yes1076, $sum1076),
                calculatePercentage($yes1077, $sum1077),
            ];
        } else {
            $answers1 = $this->reportQuestionnaire['1068'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            $answers2 = $this->reportQuestionnaire['1076'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;

            $answers3 = $this->reportQuestionnaire['1077'] ?? null;
            $value3 = isset($answers3->value) ? $answers3->value : null;

            return [
                'política_de_direitos_humanos' => $value1 ?? 0,
                'política_de_fornecedores' => $value2 ?? 0,
                'política_de_remuneração' => $value3 ?? 0,
            ];
        }
    }

    public function parseDataForReturnToWorkRate()
    {
        $sum969 = 0;
        $sum1038 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['969'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum969 += $value1 ?? 0;

            $answers2 = $answers['1038'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;
            $sum1038 += isset($value2) ? array_sum($value2) : 0;
        }

        if ($sum969 === 0 && $sum1038 === 0) {
            return 0;
        }

        return calculatePercentage($sum969, $sum1038);
    }

    public function parseDataForLocalDevelopmentPrograms()
    {
        $sum = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['975'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $answers['1047'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum += ($value1 ?? 0) + ($value2 ?? 0);
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForMonetaryLocalPurchases()
    {
        $sum = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['976'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $answers['972'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum += ($value1 ?? 0) + ($value2 ?? 0);
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForMonetaryAmountSpentPurchases()
    {
        $sum = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['977'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $answers['973'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum += ($value1 ?? 0) + ($value2 ?? 0);
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForMonetaryAmountSpentLocalProducts()
    {
        $sum = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['978'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $answers['974'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum += ($value1 ?? 0) + ($value2 ?? 0);
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForNumberOfHoursOfEthicsTraining($questionnaire = null)
    {
        if ($questionnaire == null) {
            $sum = 0;

            foreach ($this->answers as $answer) {
                $answers1 = $answer['985'] ?? null;
                $sum += isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            }

            if ($sum === 0) {
                return 0;
            }

            return $sum;
        } else {
            $answers1 = $this->reportQuestionnaire['985'] ?? null;
            $value = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            return $value ?? 0;
        }
    }

    public function parseDataForBoardOfDirectorsByGender($questionnaire = null)
    {
        if ($questionnaire == null) {
            $sum987 = 0;
            $sum988 = 0;
            $sum989 = 0;
            $sum1022 = 0;

            foreach ($this->answers as $answer) {
                $answers1 = $answer['987'] ?? null;
                $answers2 = $answer['988'] ?? null;
                $answers3 = $answer['989'] ?? null;
                $answers4 = $answer['1022'] ?? null;

                $sum987 += isset($answers1->value) ? (int) json_decode($answers1->value, true)[1] : null;
                $sum988 += isset($answers2->value) ? (int) json_decode($answers2->value, true)[1] : null;
                $sum989 += isset($answers3->value) ? (int) json_decode($answers3->value, true)[1] : null;
                $sum1022 += isset($answers4->value) ? (int) json_decode($answers4->value, true)[1] : null;
            }

            if ($sum987 === 0 && $sum988 === 0 && $sum989 === 0 && $sum1022 === 0) {
                return null;
            }

            $labels = ['Feminino', 'Masculino', 'Outro'];
            $data = [
                calculatePercentage($sum987, $sum989),
                calculatePercentage($sum988, $sum989),
                calculatePercentage($sum1022, $sum989),
            ];
            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        } else {
            $answers1 = $this->reportQuestionnaire['987'] ?? null;
            $answers2 = $this->reportQuestionnaire['988'] ?? null;
            $answers3 = $this->reportQuestionnaire['989'] ?? null;
            $answers4 = $this->reportQuestionnaire['1022'] ?? null;

            $value1 = isset($answers1->value) ? (int) json_decode($answers1->value, true)[1] : null;
            $value2 = isset($answers2->value) ? (int) json_decode($answers2->value, true)[1] : null;
            $value3 = isset($answers3->value) ? (int) json_decode($answers3->value, true)[1] : null;
            $value4 = isset($answers4->value) ? (int) json_decode($answers4->value, true)[1] : null;

            if ($value1 == null && $value2 == null && $value3 == null && $value4 == null) {
                return null;
            }

            $labels = ['Feminino', 'Masculino', 'Outros'];
            $data = [
                calculatePercentage($value1, $value3),
                calculatePercentage($value2, $value3),
                calculatePercentage($value4, $value3),
            ];
            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        }
    }

    public function parseDataForIndependentMembersParticipateBoardOfDirector($questionnaire = null)
    {
        if ($questionnaire == null) {
            $sum990 = 0;
            $sum991 = 0;

            foreach ($this->answers as $answer) {
                $answers1 = $answer['990'] ?? null;
                $sum990 += isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

                $answers2 = $answer['991'] ?? null;
                $sum991 += isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;
            }

            if ($sum990 === 0 && $sum991 === 0) {
                return null;
            }

            $labels = ['Feminino', 'Masculino'];
            $data = [$sum990, $sum991];
            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        } else {
            $answers1 = $this->reportQuestionnaire['990'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $this->reportQuestionnaire['991'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            if ($value1 == null && $value2 == null) {
                return null;
            }

            $labels = ['Feminino', 'Masculino'];
            $data = [$value1, $value2];
            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        }
    }

    public function parseDataForBoardOfDirectorsByAgeGroup($questionnaire = null)
    {
        if ($questionnaire == null) {
            $sum992 = 0;
            $sum993 = 0;
            $sum994 = 0;

            foreach ($this->answers as $answer) {
                $answers1 = $answer['992'] ?? null;
                $sum992 += isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

                $answers2 = $answer['993'] ?? null;
                $sum993 += isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

                $answers3 = $answer['994'] ?? null;
                $sum994 += isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;
            }

            if ($sum992 === 0 && $sum993 === 0 && $sum994 === 0) {
                return null;
            }

            $labels = ['<30 anos', '30 a 50 anos', '>50 anos'];
            $data = [$sum992, $sum993, $sum994];
            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        } else {
            $answers1 = $this->reportQuestionnaire['992'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $this->reportQuestionnaire['993'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $answers3 = $this->reportQuestionnaire['994'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

            $answers4 = $this->reportQuestionnaire['989'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

            if ($value1 == null && $value2 == null && $value3 == null && $value4 == null) {
                return null;
            }

            $data1 = calculatePercentage($value1, $value4);
            $data2 = calculatePercentage($value2, $value4);
            $data3 = calculatePercentage($value3, $value4);

            $labels = ['<30 anos', '30 a 50 anos', '>50 anos'];
            $data = [
                ($data1 == 100 ? 100 : $data1),
                ($data2 == 100 ? 100 : $data2),
                ($data3 == 100 ? 100 : $data3),
            ];
            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        }
    }

    public function parseDataForRisksArisingSupplyChainReportingUnitsCharts($questionnaire = null)
    {
        if ($questionnaire == null) {
            $sum1 = 0;
            $sum2 = 0;
            $sum3 = 0;
            $sum4 = 0;
            $sum5 = 0;
            $sum6 = 0;
            $sum7 = 0;
            $sum8 = 0;

            foreach ($this->answers as $answer) {
                $answers1 = $answer['995'] ?? null;
                $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

                $sum1 += $value1[917] ?? 0;
                $sum2 += $value1[918] ?? 0;
                $sum3 += $value1[919] ?? 0;
                $sum4 += $value1[920] ?? 0;
                $sum5 += $value1[921] ?? 0;
                $sum6 += $value1[922] ?? 0;
                $sum7 += $value1[928] ?? 0;
                $sum8 += isset($value1[8]) && $value1[8] != null ? 1 : 0;
            }

            $labels = [
                'Trabalho infantil e abuso de direitos humanos',
                'Condições inseguras de trabalho',
                'Desrespeito pela legislação laboral',
                'Incumprimento da legislação ambiental',
                'Uso de substâncias perigosas',
                'Situações de suborno e corrupção',
                'Incumprimento das leis da concorrência',
                'Outro',
            ];
            $data = [$sum1, $sum2, $sum3, $sum4, $sum5, $sum6, $sum7, $sum8];
            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        } else {
            $answers1 = $this->reportQuestionnaire['995'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $labels = [
                'Trabalho infantil e abuso de direitos humanos',
                'Condições inseguras de trabalho',
                'Desrespeito pela legislação laboral',
                'Incumprimento da legislação ambiental',
                'Uso de substâncias perigosas',
                'Situações de suborno e corrupção',
                'Incumprimento das leis da concorrência',
                'Outro',
            ];

            $sum1 = isset($value1[917]) && $value1[917] == 'yes' ? 1 : 0;
            $sum2 = isset($value1[918]) && $value1[918] == 'yes' ? 1 : 0;
            $sum3 = isset($value1[919]) && $value1[919] == 'yes' ? 1 : 0;
            $sum4 = isset($value1[920]) && $value1[920] == 'yes' ? 1 : 0;
            $sum5 = isset($value1[921]) && $value1[921] == 'yes' ? 1 : 0;
            $sum6 = isset($value1[922]) && $value1[922] == 'yes' ? 1 : 0;
            $sum7 = isset($value1[928]) && $value1[928] == 'yes' ? 1 : 0;
            $sum8 = isset($value1[8]) && $value1[8] != null ? 1 : 0;

            $data = [$sum1, $sum2, $sum3, $sum4, $sum5, $sum6, $sum7, $sum8];

            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        }
    }

    public function parseDataForAverageProbabilityRiskCategories($questionnaire = null)
    {
        if ($questionnaire == null) {
            $sum1124 = 0;
            $select1124 = 0;
            $sum1125 = 0;
            $select1125 = 0;
            $sum1126 = 0;
            $select1126 = 0;
            $sum1127 = 0;
            $select1127 = 0;
            $sum1128 = 0;
            $select1128 = 0;
            $sum1129 = 0;
            $select1129 = 0;
            $sum1130 = 0;
            $select1130 = 0;
            $sum1131 = 0;
            $select1131 = 0;
            $sum1132 = 0;
            $select1132 = 0;
            $sum1133 = 0;
            $select1133 = 0;
            $sum1134 = 0;
            $select1134 = 0;
            $sum1135 = 0;
            $select1135 = 0;

            foreach ($this->answers as $answer) {
                $answers1 = $answer['997'] ?? null;
                $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

                $sum1124 += $value1[1124] ?? 0;
                $sum1125 += $value1[1125] ?? 0;
                $sum1126 += $value1[1126] ?? 0;
                $sum1127 += $value1[1127] ?? 0;
                $sum1128 += $value1[1128] ?? 0;
                $sum1129 += $value1[1129] ?? 0;
                $sum1130 += $value1[1130] ?? 0;
                $sum1131 += $value1[1131] ?? 0;
                $sum1132 += $value1[1132] ?? 0;
                $sum1133 += $value1[1133] ?? 0;
                $sum1134 += $value1[1134] ?? 0;
                $sum1135 += $value1[1135] ?? 0;

                if (isset($value1[1124])) {
                    $select1124++;
                }

                if (isset($value1[1125])) {
                    $select1125++;
                }

                if (isset($value1[1126])) {
                    $select1126++;
                }

                if (isset($value1[1127])) {
                    $select1127++;
                }

                if (isset($value1[1128])) {
                    $select1128++;
                }

                if (isset($value1[1129])) {
                    $select1129++;
                }

                if (isset($value1[1130])) {
                    $select1130++;
                }

                if (isset($value1[1131])) {
                    $select1131++;
                }

                if (isset($value1[1132])) {
                    $select1132++;
                }

                if (isset($value1[1133])) {
                    $select1133++;
                }

                if (isset($value1[1134])) {
                    $select1134++;
                }

                if (isset($value1[1135])) {
                    $select1135++;
                }
            }

            $result1124 = calculatePercentage($sum1124, $select1124);
            $result1125 = calculatePercentage($sum1125, $select1125);
            $result1126 = calculatePercentage($sum1126, $select1126);
            $result1127 = calculatePercentage($sum1127, $select1127);
            $result1128 = calculatePercentage($sum1128, $select1128);
            $result1129 = calculatePercentage($sum1129, $select1129);
            $result1130 = calculatePercentage($sum1130, $select1130);
            $result1131 = calculatePercentage($sum1131, $select1131);
            $result1132 = calculatePercentage($sum1132, $select1132);
            $result1133 = calculatePercentage($sum1133, $select1133);
            $result1134 = calculatePercentage($sum1134, $select1134);
            $result1135 = calculatePercentage($sum1135, $select1135);

            $labels = [
                'Ético',
                'Sustentabilidade económica',
                'Continuidade de negócio',
                'Financiamento',
                'Legais',
                'Físicos',
                'Envolvente política',
                'Impacto ambiental',
                'Riscos Humanos',
                'Impacto na saúde',
                'Risco de Mercado',
                'Segurança da cadeia de abastecimento',
            ];
            $data = [
                $result1124,
                $result1125,
                $result1126,
                $result1127,
                $result1128,
                $result1129,
                $result1130,
                $result1131,
                $result1132,
                $result1133,
                $result1134,
                $result1135,
            ];
            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        } else {
            $answers1 = $this->reportQuestionnaire['997'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $labels = [
                'Ético',
                'Sustentabilidade económica',
                'Continuidade de negócio',
                'Financiamento',
                'Legais',
                'Físicos',
                'Envolvente política',
                'Impacto ambiental',
                'Riscos Humanos',
                'Impacto na saúde',
                'Risco de Mercado',
                'Segurança da cadeia de abastecimento',
            ];

            $data = [
                $value1[1124] ?? 0,
                $value1[1125] ?? 0,
                $value1[1126] ?? 0,
                $value1[1127] ?? 0,
                $value1[1128] ?? 0,
                $value1[1129] ?? 0,
                $value1[1130] ?? 0,
                $value1[1131] ?? 0,
                $value1[1132] ?? 0,
                $value1[1133] ?? 0,
                $value1[1134] ?? 0,
                $value1[1135] ?? 0,
            ];
            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        }
    }

    public function parseDataForAverageSeverityRiskCategories($questionnaire = null)
    {
        if ($questionnaire == null) {
            $sum1124 = 0;
            $select1124 = 0;
            $sum1125 = 0;
            $select1125 = 0;
            $sum1126 = 0;
            $select1126 = 0;
            $sum1127 = 0;
            $select1127 = 0;
            $sum1128 = 0;
            $select1128 = 0;
            $sum1129 = 0;
            $select1129 = 0;
            $sum1130 = 0;
            $select1130 = 0;
            $sum1131 = 0;
            $select1131 = 0;
            $sum1132 = 0;
            $select1132 = 0;
            $sum1133 = 0;
            $select1133 = 0;
            $sum1134 = 0;
            $select1134 = 0;
            $sum1135 = 0;
            $select1135 = 0;

            foreach ($this->answers as $answer) {
                $answers1 = $answer['1012'] ?? null;
                $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

                $sum1124 += $value1[1124] ?? 0;
                $sum1125 += $value1[1125] ?? 0;
                $sum1126 += $value1[1126] ?? 0;
                $sum1127 += $value1[1127] ?? 0;
                $sum1128 += $value1[1128] ?? 0;
                $sum1129 += $value1[1129] ?? 0;
                $sum1130 += $value1[1130] ?? 0;
                $sum1131 += $value1[1131] ?? 0;
                $sum1132 += $value1[1132] ?? 0;
                $sum1133 += $value1[1133] ?? 0;
                $sum1134 += $value1[1134] ?? 0;
                $sum1135 += $value1[1135] ?? 0;

                if (isset($value1[1124])) {
                    $select1124++;
                }

                if (isset($value1[1125])) {
                    $select1125++;
                }

                if (isset($value1[1126])) {
                    $select1126++;
                }

                if (isset($value1[1127])) {
                    $select1127++;
                }

                if (isset($value1[1128])) {
                    $select1128++;
                }

                if (isset($value1[1129])) {
                    $select1129++;
                }

                if (isset($value1[1130])) {
                    $select1130++;
                }

                if (isset($value1[1131])) {
                    $select1131++;
                }

                if (isset($value1[1132])) {
                    $select1132++;
                }

                if (isset($value1[1133])) {
                    $select1133++;
                }

                if (isset($value1[1134])) {
                    $select1134++;
                }

                if (isset($value1[1135])) {
                    $select1135++;
                }
            }

            $result1124 = calculatePercentage($sum1124, $select1124);
            $result1125 = calculatePercentage($sum1125, $select1125);
            $result1126 = calculatePercentage($sum1126, $select1126);
            $result1127 = calculatePercentage($sum1127, $select1127);
            $result1128 = calculatePercentage($sum1128, $select1128);
            $result1129 = calculatePercentage($sum1129, $select1129);
            $result1130 = calculatePercentage($sum1130, $select1130);
            $result1131 = calculatePercentage($sum1131, $select1131);
            $result1132 = calculatePercentage($sum1132, $select1132);
            $result1133 = calculatePercentage($sum1133, $select1133);
            $result1134 = calculatePercentage($sum1134, $select1134);
            $result1135 = calculatePercentage($sum1135, $select1135);

            $labels = [
                'Ético',
                'Sustentabilidade económica',
                'Continuidade de negócio',
                'Financiamento',
                'Legais',
                'Físicos',
                'Envolvente política',
                'Impacto ambiental',
                'Riscos Humanos',
                'Impacto na saúde',
                'Risco de Mercado',
                'Segurança da cadeia de abastecimento',
            ];
            $data = [
                $result1124,
                $result1125,
                $result1126,
                $result1127,
                $result1128,
                $result1129,
                $result1130,
                $result1131,
                $result1132,
                $result1133,
                $result1134,
                $result1135,
            ];
            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        } else {
            $answers1 = $this->reportQuestionnaire['1012'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $labels = [
                'Ético',
                'Sustentabilidade económica',
                'Continuidade de negócio',
                'Financiamento',
                'Legais',
                'Físicos',
                'Envolvente política',
                'Impacto ambiental',
                'Riscos Humanos',
                'Impacto na saúde',
                'Risco de Mercado',
                'Segurança da cadeia de abastecimento',
            ];
            $data = [
                $value1[1124] ?? 0,
                $value1[1125] ?? 0,
                $value1[1126] ?? 0,
                $value1[1127] ?? 0,
                $value1[1128] ?? 0,
                $value1[1129] ?? 0,
                $value1[1130] ?? 0,
                $value1[1131] ?? 0,
                $value1[1132] ?? 0,
                $value1[1133] ?? 0,
                $value1[1134] ?? 0,
                $value1[1135] ?? 0,
            ];
            $parseData = $this->removeNullData($labels, $data);

            if (($parseData['label'] == []) && ($parseData['data'] == [])) {
                return null;
            }

            return [
                'label' => $parseData['label'],
                'data' => $parseData['data'],
            ];
        }
    }

    public function parseDataForPoliciesProgress($questionnaire = null)
    {
        if ($questionnaire == null) {
            $sum1069 = 0;
            $yes1069 = 0;
            $sum1070 = 0;
            $yes1070 = 0;
            $sum1071 = 0;
            $yes1071 = 0;
            $sum1074 = 0;
            $yes1074 = 0;

            foreach ($this->answers as $answer) {
                $answers1 = $answer['1069'] ?? null;
                $value1 = isset($answers1->value) ? $answers1->value : null;

                if ($value1 !== null) {
                    if ($value1 == 'yes') {
                        $yes1069++;
                    }

                    $sum1069++;
                }

                $answers2 = $answer['1070'] ?? null;
                $value2 = isset($answers2->value) ? $answers2->value : null;

                if ($value2 !== null) {
                    if ($value2 == 'yes') {
                        $yes1070++;
                    }

                    $sum1070++;
                }

                $answers3 = $answer['1071'] ?? null;
                $value3 = isset($answers3->value) ? $answers3->value : null;

                if ($value3 !== null) {
                    if ($value3 == 'yes') {
                        $yes1071++;
                    }

                    $sum1071++;
                }

                $answers4 = $answer['1074'] ?? null;
                $value4 = isset($answers4->value) ? $answers4->value : null;

                if ($value4 !== null) {
                    if ($value4 == 'yes') {
                        $yes1074++;
                    }

                    $sum1074++;
                }
            }

            return [
                calculatePercentage($yes1069, $sum1069),
                calculatePercentage($yes1070, $sum1070),
                calculatePercentage($yes1071, $sum1071),
                calculatePercentage($yes1074, $sum1074),
            ];
        } else {
            $answers1 = $this->reportQuestionnaire['1069'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            $answers2 = $this->reportQuestionnaire['1070'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;

            $answers3 = $this->reportQuestionnaire['1071'] ?? null;
            $value3 = isset($answers3->value) ? $answers3->value : null;

            $answers4 = $this->reportQuestionnaire['1072'] ?? null;
            $value4 = isset($answers4->value) ? $answers4->value : null;

            $answers5 = $this->reportQuestionnaire['1072'] ?? null;
            $value5 = isset($answers5->value) ? $answers5->value : null;

            return [
                'anti_corruption' => $value1 ?? 0,
                'conflicts_interest' => $value2 ?? 0,
                'code_of_ethics_and_conduct_from_suppliers' => $value3 ?? 0,
                'reporting_channel' => $value4 ?? 0,
                'data_privacy_policy' => $value5 ?? 0,
            ];
        }
    }

    public function parseDataForParticipatesLocalDevelopmentProgramsProgress()
    {
        $sum = 0;
        $yes = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1048'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            $answers2 = $answer['1152'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;

            if (isset($value1) || isset($value2)) {
                if ($value1 == 'yes' || $value2 == 'yes') {
                    $yes++;
                }

                $sum++;
            }
        }

        $calculation = calculatePercentage($yes, $sum);

        return [
            'label' => ['Sim', 'Não'],
            'data' => [
                $calculation,
                100 - ($calculation),
            ],
        ];
    }

    public function parseDataForTotalAmountOfWaterConsumed()
    {
        $sum905 = 0;
        $sum906 = 0;
        $sum907 = 0;
        $sum1127 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['862'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $answers2 = $answer['858'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

            $sum905 += ($value1[905] ?? 0) + ($value2[905] ?? 0);
            $sum906 += ($value1[906] ?? 0) + ($value2[905] ?? 0);
            $sum907 += ($value1[907] ?? 0) + ($value2[905] ?? 0);
            $sum1127 += ($value1[1127] ?? 0) + ($value2[905] ?? 0);
        }

        if ($sum905 === 0 && $sum906 === 0 && $sum907 === 0 && $sum1127 === 0) {
            return null;
        }

        $labels = [
            'Água da companhia',
            'Água de recursos hídricos (mar/rio)',
            'Água do furo/poço',
            'Água reciclada/reutilizada',
        ];
        $data = [
            $sum905,
            $sum906,
            $sum907,
            $sum1127,
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForReduceConsumptionCharts()
    {
        $sum1265 = 0;
        $sum1266 = 0;
        $sum1267 = 0;
        $sum1268 = 0;
        $sum8 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1134'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $answers2 = $answer['1135'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

            $sum1265 += (isset($value1[1265]) && $value1[1265] != null) || (isset($value2[1265]) && $value2[1265] != null) ? 1 : 0;
            $sum1266 += (isset($value1[1266]) && $value1[1266] != null) || (isset($value2[1266]) && $value2[1266] != null) ? 1 : 0;
            $sum1267 += (isset($value1[1267]) && $value1[1267] != null) || (isset($value2[1267]) && $value2[1267] != null) ? 1 : 0;
            $sum1268 += (isset($value1[1268]) && $value1[1268] != null) || (isset($value2[1268]) && $value2[1268] != null) ? 1 : 0;
            $sum8 += (isset($value1[8]) && $value1[8] != null) || (isset($value2[8]) && $value2[8] != null) ? 1 : 0;
        }

        if ($sum1265 === 0 && $sum1266 === 0 && $sum1267 === 0 && $sum1268 === 0 && $sum8 === 0) {
            return null;
        }

        $labels = [
            'Temporizadores',
            'Redutores de caudal',
            'Alteração de pressão',
            'Alteração dos autoclismos',
            'Outro',
        ];
        $data = [
            $sum1265,
            $sum1266,
            $sum1267,
            $sum1268,
            $sum8,
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForReportingUnitsWWTP()
    {
        $sum1066 = 0;
        $yes1066 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1066'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if (isset($value1)) {
                if ($value1 == 'yes') {
                    $yes1066++;
                }

                $sum1066++;
            }
        }

        if ($sum1066 === 0 && $yes1066 === 0) {
            return null;
        }

        $calculation = calculatePercentage($yes1066, $sum1066);

        return [
            'label' => ['Sim', 'Não'],
            'data' => [
                $calculation,
                100 - ($calculation),
            ],
        ];
    }

    public function parseDataForEmissionValuePerParameter()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1154'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                foreach ($value1 as $key => $value) {
                    if ($value) {
                        $label = Simple::find($key)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($key, $data)) {
                            $data[$key] += $value;
                        } else {
                            $data[$key] = $value;
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForElectricityConsumedPerSource()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1151'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                foreach ($value1 as $key => $value) {
                    if ($value) {
                        $label = Simple::find($key)->label;

                        if ($label == 'Adquirida externamente - Fonte renovável: Eólica') {
                            $label = 'Renovável: Eólica';
                        }
                        if ($label == 'Adquirida externamente - Fonte renovável: Hídrica') {
                            $label = 'Renovável: Hídrica';
                        }
                        if ($label == 'Adquirida externamente - Fonte renovável: Cogeração renovável') {
                            $label = 'Renovável: Cogeração';
                        }
                        if ($label == 'Adquirida externamente - Fonte renovável: Geotermia') {
                            $label = 'Renovável: Geotermia';
                        }
                        if ($label == 'Adquirida externamente - Fonte renovável: Outras renováveis') {
                            $label = 'Outras renováveis';
                        }
                        if ($label == 'Adquirida externamente - Fonte não renovável: Resíduos sólidos urbanos') {
                            $label = 'Não renovável: Resíduos sólidos urbanos';
                        }
                        if ($label == 'Adquirida externamente - Fonte não renovável: Cogeração fóssil') {
                            $label = 'Não renovável: Cogeração fóssil';
                        }
                        if ($label == 'Adquirida externamente - Fonte não renovável: Gás natural') {
                            $label = 'Não renovável: Gás natural';
                        }
                        if ($label == 'Adquirida externamente - Fonte não renovável: Carvão') {
                            $label = 'Não renovável: Carvão';
                        }
                        if ($label == 'Adquirida externamente - Fonte não renovável: Diesel') {
                            $label = 'Não renovável: Diesel';
                        }
                        if ($label == 'Adquirida externamente - Fonte não renovável: Fuelóleo') {
                            $label = 'Não renovável: Fuelóleo';
                        }
                        if ($label == 'Adquirida externamente - Fonte não renovável: Nuclear') {
                            $label = 'Não renovável: Nuclear';
                        }
                        if ($label == 'Produção para autoconsumo - Fonte renovável') {
                            $label = 'Produção para autoconsumo - Renovável';
                        }
                        if ($label == 'externally purchased - renewable source: Wind') {
                            $label = 'Renewable: Wind';
                        }
                        if ($label == 'externally purchased - Renewable source: Hydro') {
                            $label = 'Renewable: Hydro';
                        }
                        if ($label == 'externally purchased - Renewable source: Renewable Cogeneration') {
                            $label = 'Renewable: Cogeneration';
                        }
                        if ($label == 'externally purchased - Renewable source: Geothermal') {
                            $label = 'Renewable: Geothermal';
                        }
                        if ($label == 'Externally purchased - Renewable source: Other renewables') {
                            $label = 'Other renewables';
                        }
                        if ($label == 'externally purchased - Non-renewable source: Municipal solid waste') {
                            $label = 'Non-renewable: Municipal solid waste';
                        }
                        if ($label == 'Externally procured - Non-renewable source: Fossil cogeneration') {
                            $label = 'Non-renewable: Fossil cogeneration';
                        }
                        if ($label == 'Externally procured - Non-renewable source: Natural gas') {
                            $label = 'Non-renewable: Natural gas';
                        }
                        if ($label == 'Externally procured - Non-renewable source: Coal') {
                            $label = 'Non-renewable: Coal';
                        }
                        if ($label == 'externally purchased - Non-renewable source: Diesel') {
                            $label = 'Non-renewable: Diesel';
                        }
                        if ($label == 'Externally purchased - Non-renewable source: Fuel oil') {
                            $label = 'Non-renewable: Fuel oil';
                        }
                        if ($label == 'externally purchased - Non-renewable source: Nuclear') {
                            $label = 'Non-renewable: Nuclear';
                        }
                        if ($label == 'Production for own use - Renewable source') {
                            $label = 'Production for own use - Renewable';
                        }

                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($key, $data)) {
                            $data[$key] += $value;
                        } else {
                            $data[$key] = $value;
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForTotalGrossProductionValue()
    {
        $sum = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['866'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForTotalIndirectCosts()
    {
        $sum = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['867'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForGrossAddedValueCompany()
    {
        $sum = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['868'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForTotalProductionVolume()
    {
        $sum = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['869'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForPercentageElectricityConsumption()
    {
        $val1 = 0;
        $val2 = 0;
        $val3 = 0;
        $val4 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1151'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 !== null) {
                $val1 += ($value1[1273] ?? 0) + ($value1[1274] ?? 0) + ($value1[1275] ?? 0) + ($value1[1276] ?? 0) + ($value1[1277] ?? 0) + ($value1[1285] ?? 0);
                $val2 += intval(
                    ($value1[1273] ?? 0) + ($value1[1274] ?? 0) + ($value1[1275] ?? 0) + ($value1[1276] ?? 0) + ($value1[1277] ?? 0) + ($value1[1285] ?? 0) +
                    ($value1[1278] ?? 0) + ($value1[1279] ?? 0) + ($value1[1280] ?? 0) + ($value1[1281] ?? 0) + ($value1[1282] ?? 0) + ($value1[1283] ?? 0) + ($value1[1284] ?? 0)
                );

                $val3 += ($value1[1278] ?? 0) + ($value1[1279] ?? 0) + ($value1[1280] ?? 0) + ($value1[1281] ?? 0) + ($value1[1282] ?? 0) + ($value1[1283] ?? 0) + ($value1[1284] ?? 0);
                $val4 += intval(
                    ($value1[1273] ?? 0) + ($value1[1274] ?? 0) + ($value1[1275] ?? 0) + ($value1[1276] ?? 0) + ($value1[1277] ?? 0) + ($value1[1285] ?? 0) +
                    ($value1[1278] ?? 0) + ($value1[1279] ?? 0) + ($value1[1280] ?? 0) + ($value1[1281] ?? 0) + ($value1[1282] ?? 0) + ($value1[1283] ?? 0) + ($value1[1284] ?? 0)
                );
            }
        }

        if ($val1 === 0 && $val2 === 0 && $val3 === 0 && $val4 === 0) {
            return null;
        }

        $labels = ['Fonte renovável', 'Fonte não renovável'];
        $data = [calculatePercentage($val1, $val2), calculatePercentage($val3, $val4)];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForGhgEmissionsProgress()
    {
        $sum = 0;
        $yes = 0;
        foreach ($this->answers as $answer) {
            $answers1 = $answer['871'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;
            if ($value1) {
                if ($value1 == 'yes') {
                    $yes++;
                }
                $sum++;
            }
        }

        $calculation = calculatePercentage($yes, $sum);

        return [
            'label' => ['Sim', 'Não'],
            'data' => [
                $calculation,
                100 - ($calculation),
            ],
        ];
    }

    public function parseDataForTravelInVehiclesOwnedProgress()
    {
        $sum873 = 0;
        $yes873 = 0;
        $sum874 = 0;
        $yes874 = 0;
        $sum876 = 0;
        $yes876 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['873'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;
            if ($value1) {
                if ($value1 == 'yes') {
                    $yes873++;
                }
                $sum873++;
            }

            $answers2 = $answer['874'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;
            if ($value2) {
                if ($value2 == 'yes') {
                    $yes874++;
                }
                $sum874++;
            }
            $answers3 = $answer['876'] ?? null;
            $value3 = isset($answers3->value) ? $answers3->value : null;
            if ($value3) {
                if ($value3 == 'yes') {
                    $yes876++;
                }
                $sum876++;
            }
        }

        return [
            calculatePercentage($yes873, $sum873),
            calculatePercentage($yes874, $sum874),
            calculatePercentage($yes876, $sum876),
        ];
    }

    public function parseDataForAmountOfNonRoadFuelConsumed()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['872'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [946, 947, 948, 949, 950, 951, 952, 953, 954, 955, 956, 957, 958, 959, 960];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForTravelInVehiclesFuelConsumed()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['875'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [946, 947, 948, 949, 950, 951, 952, 953, 954, 955, 956, 957];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForVehicleAndDistanceTravelled()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1156'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [
                    961, 962, 963, 964, 965, 966, 967, 968, 969, 970, 971, 972, 973, 977, 978, 979, 980,
                    981, 982, 983, 984, 985, 986, 987, 988, 989, 990, 991, 992, 993, 994, 995, 996, 997, 998, 999,
                    1000, 1002, 1003, 1004, 1005,
                ];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForTravelVehiclesReportingUnitsDonotOwnProgress()
    {
        $sum878 = 0;
        $yes878 = 0;
        $sum879 = 0;
        $yes879 = 0;
        $sum881 = 0;
        $yes881 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['878'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;
            if ($value1) {
                if ($value1 == 'yes') {
                    $yes878++;
                }
                $sum878++;
            }

            $answers2 = $answer['879'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;
            if ($value2) {
                if ($value2 == 'yes') {
                    $yes879++;
                }
                $sum879++;
            }

            $answers3 = $answer['881'] ?? null;
            $value3 = isset($answers3->value) ? $answers3->value : null;
            if ($value3) {
                if ($value3 == 'yes') {
                    $yes881++;
                }
                $sum881++;
            }
        }

        return [
            calculatePercentage($yes878, $sum878),
            calculatePercentage($yes879, $sum879),
            calculatePercentage($yes881, $sum881),
        ];
    }

    public function parseDataForControlOrOperateFuelConsumed()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['880'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                foreach ($value1 as $key => $value) {
                    if ($value) {
                        $label = Simple::find($key)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($key, $data)) {
                            $data[$key] += $value;
                        } else {
                            $data[$key] = $value;
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForTypeOfTransportVehicleDistance()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1157'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [
                    961, 962, 963, 964, 965, 966, 967, 968, 969, 970, 971, 972, 973, 977, 978, 979, 980,
                    981, 982, 983, 984, 985, 986, 987, 988, 989, 990, 991, 992, 993, 994, 995, 996, 997, 998, 999,
                    1000, 1002, 1003, 1004, 1005,
                ];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForEquipmentContainingGreebhouseGasProgress()
    {
        $sum883 = 0;
        $yes883 = 0;
        $sum884 = 0;
        $yes884 = 0;
        $sum885 = 0;
        $yes885 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['883'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if ($value1) {
                if ($value1 == 'yes') {
                    $yes883++;
                }
                $sum883++;
            }

            $answers2 = $answer['884'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;

            if ($value2) {
                if ($value2 == 'yes') {
                    $yes884++;
                }
                $sum884++;
            }

            $answers3 = $answer['885'] ?? null;
            $value3 = isset($answers3->value) ? $answers3->value : null;

            if ($value3) {
                if ($value3 == 'yes') {
                    $yes885++;
                }
                $sum885++;
            }
        }

        return [
            calculatePercentage($yes883, $sum883),
            calculatePercentage($yes884, $sum884),
            calculatePercentage($yes885, $sum885),
        ];
    }

    public function parseDataForTotalAmountOfLeak()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['887'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [
                    1009, 1010, 1011, 1012, 1013, 1014, 1015, 1016, 1017, 1018, 1019, 1020,
                    1021, 1022, 1023, 1024, 1025, 1026, 1027, 1028, 1029, 1030, 1031, 1032,
                    1033, 1034, 1035, 1036, 1037, 1038, 1039, 1040, 1041, 1042, 1043, 1044,
                    1045, 1046, 1047, 1048, 1049, 1050, 1051, 1052, 1053, 1054, 1055, 1056,
                    1057, 1058, 1059, 1060, 1061, 1062, 1063, 1064, 1065, 1066, 1067, 1068,
                    1069, 1070, 1071, 1072, 1073, 1074, 1075, 1076, 1077, 1078, 1079, 1080,
                    1081, 1082, 1083, 1084, 1085, 1086, 1087, 1088, 1089, 1090, 1091, 1092,
                    1093, 1094, 1095, 1096, 1097, 1098,
                ];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForTotalAmountOfElectricity()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['888'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [1099, 1100, 1101, 1102];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function praseDataForWasteProductionFacilitiesProgress()
    {
        $sum889 = 0;
        $yes889 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['889'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if ($value1) {
                if ($value1 == 'yes') {
                    $yes889++;
                }
                $sum889++;
            }
        }

        $calculation = calculatePercentage($yes889, $sum889);

        return [
            'label' => ['Sim', 'Não'],
            'data' => [
                $calculation,
                100 - ($calculation),
            ],
        ];
    }

    public function parseDataForWasteProducedInTon()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1025'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [1197, 1103, 1104, 1105, 1106, 1107, 1108, 1109, 1110, 1111, 1112, 1114, 1115, 1116, 1117, 1118];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForWastePlacedInRecycling()
    {
        $val1 = 0;
        $val2 = 0;
        $val3 = 0;
        $val4 = 0;
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1025'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $val1 += ($value1[1197] ?? 0) + ($value1[1103] ?? 0);
                $val2 += ($value1[1106] ?? 0) + ($value1[1116] ?? 0);
                $val3 += $value1[1109] ?? 0;
                $val4 += $value1[1112] ?? 0;

                $sum1 += ($value1[1197] ?? 0) + ($value1[1103] ?? 0) + ($value1[1104] ?? 0) + ($value1[1105] ?? 0);
                $sum2 += ($value1[1106] ?? 0) + ($value1[1107] ?? 0) + ($value1[1108] ?? 0) + ($value1[1116] ?? 0) + ($value1[1117] ?? 0) + ($value1[1118] ?? 0);
                $sum3 += ($value1[1109] ?? 0) + ($value1[1110] ?? 0) + ($value1[1111] ?? 0);
                $sum4 += ($value1[1112] ?? 0) + ($value1[1114] ?? 0) + ($value1[1115] ?? 0);
            }
        }

        $labels = ['Papel', 'Plástico/Metal', 'Vidro', 'Orgânico'];
        $data = [
            calculatePercentage($val1, $sum1),
            calculatePercentage($val2, $sum2),
            calculatePercentage($val3, $sum3),
            calculatePercentage($val4, $sum4),
        ];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForWateronItsPremisesProgress()
    {
        $sum891 = 0;
        $yes891 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['891'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if ($value1) {
                if ($value1 == 'yes') {
                    $yes891++;
                }
                $sum891++;
            }
        }

        $calculation = calculatePercentage($yes891, $sum891);

        return [
            'label' => ['Sim', 'Não'],
            'data' => [
                $calculation,
                100 - ($calculation),
            ],
        ];
    }

    public function parseDataForTotalAmountOfWaterM3()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['892'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [1119, 1120];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForPurchasedGoodsDuringReportingPeriodProgress()
    {
        $sum893 = 0;
        $yes893 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['893'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if ($value1) {
                if ($value1 == 'yes') {
                    $yes893++;
                }
                $sum893++;
            }
        }

        $calculation = calculatePercentage($yes893, $sum893);

        return [
            'label' => ['Sim', 'Não'],
            'data' => [
                $calculation,
                100 - ($calculation),
            ],
        ];
    }

    public function parseDataForTypeTotalQuantityGoodsPurchasedTon()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1014'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [1160, 1161, 1162, 1163, 1164, 1165, 1166, 1167, 1168, 1169, 1170, 1171, 1172, 1173, 1174,
                    1175, 1176, 1177, 1178, 1179, 1180, 1181, 1182, 1183, 1184, 1185, 1186, 1187, 1188, 1189, 1190, 1191, 1192];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForTelecommutingWorkersProgress()
    {
        $sum895 = 0;
        $yes895 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['895'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if ($value1) {
                if ($value1 == 'yes') {
                    $yes895++;
                }
                $sum895++;
            }
        }

        $calculation = calculatePercentage($yes895, $sum895);

        return [
            'label' => ['Sim', 'Não'],
            'data' => [
                $calculation,
                100 - ($calculation),
            ],
        ];
    }

    public function parseDataForHoursWorkedInTelecommuting()
    {
        $sum = 0;
        foreach ($this->answers as $answer) {
            $answers1 = $answer['896'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForcarbonSequestrationCapacityGhgProgress()
    {
        $sum1146 = 0;
        $yes1146 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1146'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if ($value1) {
                if ($value1 == 'yes') {
                    $yes1146++;
                }
                $sum1146++;
            }
        }

        $calculation = calculatePercentage($yes1146, $sum1146);

        return [
            'label' => ['Sim', 'Não'],
            'data' => [
                $calculation,
                100 - ($calculation),
            ],
        ];
    }

    public function parseDataForGhgEmissionsAndCarbonSequestration()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1147'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [1269, 1270, 1271, 1272];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        if ($value == 1270) {
                            $label = 'Âmbito 1';
                        }
                        if ($value == 1271) {
                            $label = 'Âmbito 2';
                        }
                        if ($value == 1272) {
                            $label = 'Âmbito 3';
                        }
                        if ($value == 1269) {
                            $label = 'Compensação/sequestro de carbono';
                        }

                        //$label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForEmissionAirPollutantsProgress()
    {
        $sum1051 = 0;
        $yes1051 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1051'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if ($value1) {
                if ($value1 == 'yes') {
                    $yes1051++;
                }
                $sum1051++;
            }
        }

        $calculation = calculatePercentage($yes1051, $sum1051);

        return [
            'label' => ['Sim', 'Não'],
            'data' => [
                $calculation,
                100 - ($calculation),
            ],
        ];
    }

    public function parseDataForAirPollutant()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1052'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [1210, 1211, 1212, 1213, 1214, 1215, 1216, 1217];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForDepleteTheOzoneLayerProgress()
    {
        $sum1053 = 0;
        $yes1053 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1053'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if ($value1) {
                if ($value1 == 'Yes') {
                    $yes1053++;
                }
                $sum1053++;
            }
        }

        $calculation = calculatePercentage($yes1053, $sum1053);

        return [
            'label' => ['Sim', 'Não'],
            'data' => [
                $calculation,
                100 - ($calculation),
            ],
        ];
    }

    public function parseDataForDepletesTheOzoneLayerInTons()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1054'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                $options = [1218, 1219, 1220, 1221, 1222, 1223, 1224];

                foreach ($options as $value) {
                    if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                        $label = Simple::find($value)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($value, $data)) {
                            $data[$value] += $value1[$value];
                        } else {
                            $data[$value] = $value1[$value];
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataFor25KmRadiusEnvironmentalProtectionAreaProgress()
    {
        $sum1077 = 0;
        $yes1077 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1077'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if ($value1) {
                if ($value1 == 'yes') {
                    $yes1077++;
                }
                $sum1077++;
            }
        }

        $calculation = calculatePercentage($yes1077, $sum1077);

        return [
            'label' => ['Sim', 'Não'],
            'data' => [
                $calculation,
                100 - ($calculation),
            ],
        ];
    }

    public function parseDataForEnvironmentalImpactStudiesProgress()
    {
        $sum1078 = 0;
        $yes1078 = 0;
        $sum1079 = 0;
        $yes1079 = 0;
        $sum1080 = 0;
        $yes1080 = 0;
        $sum1081 = 0;
        $yes1081 = 0;
        $sum1083 = 0;
        $yes1083 = 0;
        $sum1085 = 0;
        $yes1085 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1078'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if ($value1) {
                if ($value1 == 'yes') {
                    $yes1078++;
                }
                $sum1078++;
            }

            $answers2 = $answer['1079'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;

            if ($value2) {
                if ($value2 == 'yes') {
                    $yes1079++;
                }
                $sum1079++;
            }

            $answers3 = $answer['1080'] ?? null;
            $value3 = isset($answers3->value) ? $answers3->value : null;

            if ($value3) {
                if ($value3 == 'yes') {
                    $yes1080++;
                }
                $sum1080++;
            }

            $answers4 = $answer['1081'] ?? null;
            $value4 = isset($answers4->value) ? $answers4->value : null;

            if ($value4) {
                if ($value4 == 'yes') {
                    $yes1081++;
                }
                $sum1081++;
            }

            $answers5 = $answer['1083'] ?? null;
            $value5 = isset($answers5->value) ? $answers5->value : null;

            if ($value5) {
                if ($value5 == 'yes') {
                    $yes1083++;
                }
                $sum1083++;
            }

            $answers6 = $answer['1085'] ?? null;
            $value6 = isset($answers6->value) ? $answers6->value : null;

            if ($value6) {
                if ($value6 == 'yes') {
                    $yes1085++;
                }
                $sum1085++;
            }
        }

        return [
            '1078' => calculatePercentage($yes1078, $sum1078),
            '1079' => calculatePercentage($yes1079, $sum1079),
            '1080' => calculatePercentage($yes1080, $sum1080),
            '1081' => calculatePercentage($yes1081, $sum1081),
            '1083' => calculatePercentage($yes1083, $sum1083),
            '1085' => calculatePercentage($yes1085, $sum1085),
        ];
    }

    public function parseDataForSpeciesAffected()
    {
        $sum = 0;
        foreach ($this->answers as $answer) {
            $answers1 = $answer['1086'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForEnvironmentalImpactStudies1Progress()
    {
        $sum1087 = 0;
        $yes1087 = 0;
        $sum1089 = 0;
        $yes1089 = 0;
        $sum1090 = 0;
        $yes1090 = 0;
        $sum1092 = 0;
        $yes1092 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1087'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            if ($value1) {
                if ($value1 == 'yes') {
                    $yes1087++;
                }
                $sum1087++;
            }

            $answers2 = $answer['1089'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;

            if ($value2) {
                if ($value2 == 'yes') {
                    $yes1089++;
                }
                $sum1089++;
            }

            $answers3 = $answer['1090'] ?? null;
            $value3 = isset($answers3->value) ? $answers3->value : null;

            if ($value3) {
                if ($value3 == 'yes') {
                    $yes1090++;
                }
                $sum1090++;
            }

            $answers4 = $answer['1092'] ?? null;
            $value4 = isset($answers4->value) ? $answers4->value : null;

            if ($value4) {
                if ($value4 == 'yes') {
                    $yes1092++;
                }
                $sum1092++;
            }
        }

        return [
            '1087' => calculatePercentage($yes1087, $sum1087),
            '1089' => calculatePercentage($yes1089, $sum1089),
            '1090' => calculatePercentage($yes1090, $sum1090),
            '1092' => calculatePercentage($yes1092, $sum1092),
        ];
    }

    public function parseDataForHabitatsOutsideTheStudiesProgress()
    {
        $sum1093 = 0;
        $yes1093 = 0;
        $sum1083 = 0;
        $yes1083 = 0;
        $sum1085 = 0;
        $yes1085 = 0;
        $sum1094 = 0;
        $yes1094 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1093'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;
            if ($value1) {
                if ($value1 == 'yes') {
                    $yes1093++;
                }
                $sum1093++;
            }

            $answers2 = $answer['1083'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;
            if ($value2) {
                if ($value2 == 'yes') {
                    $yes1083++;
                }
                $sum1083++;
            }

            $answers3 = $answer['1085'] ?? null;
            $value3 = isset($answers3->value) ? $answers3->value : null;
            if ($value3) {
                if ($value3 == 'yes') {
                    $yes1085++;
                }
                $sum1085++;
            }

            $answers4 = $answer['1094'] ?? null;
            $value4 = isset($answers4->value) ? $answers4->value : null;
            if ($value4) {
                if ($value4 == 'yes') {
                    $yes1094++;
                }
                $sum1094++;
            }
        }

        return [
            '1093' => calculatePercentage($yes1093, $sum1093),
            '1083' => calculatePercentage($yes1083, $sum1083),
            '1085' => calculatePercentage($yes1085, $sum1085),
            '1094' => calculatePercentage($yes1094, $sum1094),
        ];
    }

    public function parseDataForEnergyCosts()
    {
        $sum282 = 0;
        $sum283 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1155'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
            $value1Arr = is_array($value1) ? $value1 : [$value1];
            $sum282 += array_sum($value1Arr);
            $sum283 += array_sum($value1Arr);
        }

        if ($sum282 === 0 && $sum283 === 0) {
            return null;
        }

        $labels = ['Combustível', 'Eletricidade'];
        $data = [$sum282, $sum283];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForHazardousWaste()
    {
        $labels = [];
        $data = [];

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1065'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if ($value1 != null) {
                foreach ($value1 as $key => $value) {
                    if ($value) {
                        $label = Simple::find($key)->label;
                        if (! in_array($label, $labels)) {
                            $labels[] = $label;
                        }

                        if (array_key_exists($key, $data)) {
                            $data[$key] += $value;
                        } else {
                            $data[$key] = $value;
                        }
                    }
                }
            }
        }

        if (empty($labels) && empty($data)) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($data),
        ];
    }

    public function parseDataForWasteManagementProgress()
    {
        $sum1061 = 0;
        $yes1061 = 0;
        $sum1062 = 0;
        $yes1062 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1061'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;
            if ($value1) {
                if ($value1 == 'yes') {
                    $yes1061++;
                }
                $sum1061++;
            }

            $answers2 = $answer['1062'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;
            if ($value2) {
                if ($value2 == 'yes') {
                    $yes1062++;
                }
                $sum1062++;
            }
        }

        return [
            calculatePercentage($yes1061, $sum1061),
            calculatePercentage($yes1062, $sum1062),
        ];
    }

    public function parseDataForRadioactiveWastesProgress()
    {
        $sum1064 = 0;
        $yes1064 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1064'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;
            if ($value1) {
                if ($value1 == 'yes') {
                    $yes1064++;
                }
                $sum1064++;
            }
        }

        return calculatePercentage($yes1064, $sum1064);
    }

    public function parseDataForEnvironmentalPoliciesProgress($questionnaire = null)
    {
        if ($questionnaire == null) {
            $sum1050 = 0;
            $yes1050 = 0;
            $sum1055 = 0;
            $yes1055 = 0;
            $sum1056 = 0;
            $yes1056 = 0;
            $sum1058 = 0;
            $yes1058 = 0;

            foreach ($this->answers as $answer) {
                $answers1 = $answer['1050'] ?? null;
                $value1 = isset($answers1->value) ? $answers1->value : null;
                if ($value1) {
                    if ($value1 == 'yes') {
                        $yes1050++;
                    }
                    $sum1050++;
                }

                $answers2 = $answer['1055'] ?? null;
                $value2 = isset($answers2->value) ? $answers2->value : null;
                if ($value2) {
                    if ($value2 == 'yes') {
                        $yes1055++;
                    }
                    $sum1055++;
                }

                $answers3 = $answer['1056'] ?? null;
                $value3 = isset($answers3->value) ? $answers3->value : null;
                if ($value3) {
                    if ($value3 == 'yes') {
                        $yes1056++;
                    }
                    $sum1056++;
                }

                $answers4 = $answer['1058'] ?? null;
                $value4 = isset($answers4->value) ? $answers4->value : null;
                if ($value4) {
                    if ($value4 == 'yes') {
                        $yes1058++;
                    }
                    $sum1058++;
                }
            }

            return [
                calculatePercentage($yes1050, $sum1050),
                calculatePercentage($yes1055, $sum1055),
                calculatePercentage($yes1056, $sum1056),
                calculatePercentage($yes1058, $sum1058),
            ];
        } else {
            $answers1 = $this->reportQuestionnaire['1050'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            $answers2 = $this->reportQuestionnaire['1055'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;

            $answers3 = $this->reportQuestionnaire['1056'] ?? null;
            $value3 = isset($answers3->value) ? $answers3->value : null;

            $answers4 = $this->reportQuestionnaire['1058'] ?? null;
            $value4 = isset($answers4->value) ? $answers4->value : null;

            return [
                'environmental_policy' => $value1 ?? 0,
                'emissions_reduction_policy' => $value2 ?? 0,
                'reduce_emissions' => $value3 ?? 0,
                'biodiversity_protection_policy' => $value4 ?? 0,
            ];
        }
    }

    public function parseDataForExpenditureOnInnovation()
    {
        $sum = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['901'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $answer['1150'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum += ($value1 ?? 0) + ($value2 ?? 0);
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForTotalWasteGenerated()
    {
        $sum = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1063'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            $sum += $value1 ?? 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForReusedMaterials()
    {
        $sum = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['906'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $answer['911'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum += ($value1 ?? 0) + ($value2 ?? 0);
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForWastedFood()
    {
        $sum = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['907'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $answer['912'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum += ($value1 ?? 0) + ($value2 ?? 0);
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForMealsPrepared()
    {
        $sum = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['908'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $answer['913'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum += ($value1 ?? 0) + ($value2 ?? 0);
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForCookingOilsRecycled()
    {
        $sum = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['909'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $answer['914'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $sum += ($value1 ?? 0) + ($value2 ?? 0);
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForWaterConsumptionCustomer()
    {
        $sum = 0;
        $sum1 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['858'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $answers2 = $answer['862'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

            $answers3 = $answer['869'] ?? null;
            $value3 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum += ($value1 != null ? array_sum($value1) : 0) + ($value2 != null ? array_sum($value2) : 0);
            $sum1 += $value3 != null ? array_sum($value3) : 0;
        }

        if ($sum === 0 && $sum1 === 0) {
            return 0;
        }

        return calculateDivision($sum, $sum1);
    }

    public function parseDataForEnergyConsumptionValue()
    {
        $sum = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1159'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            $sum += $value1 != null ? array_sum($value1) : 0;
        }

        if ($sum === 0) {
            return 0;
        }

        return $sum;
    }

    public function parseDataForPercentageLocalPurchases()
    {
        $sum = 0;
        $sum1 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['976'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

            $answers2 = $answer['977'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

            $answers3 = $answer['972'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

            $answers4 = $answer['973'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

            $sum += ($value1 ?? 0) + ($value3 ?? 0);
            $sum1 += ($value2 ?? 0) + ($value4 ?? 0);

            //All the answers are returning null, but in reality they aren't - dd($answers1,$answers2,$answers3,$answers4);
        }

        if ($sum === 0 && $sum1 === 0) {
            return 0;
        }

        return calculatePercentage($sum, $sum1);
    }

    public function parseDataForEnergyIntensity()
    {
        $total = 0;
        $total2 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1151'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
            $total += $value1 != null ? array_sum($value1) : 0;

            $answers2 = $answer['872'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;
            $total += $value2 != null ? $this->calculationForEnerygy($value2) : 0;

            $answers3 = $answer['875'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;
            $total += $value3 != null ? $this->calculationForEnerygy($value3) : 0;

            $answers4 = $answer['868'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;
            $total2 += $value4 ?? 0;
        }

        if ($total === 0 && $total2 === 0) {
            return 0;
        }

        return number_format(calculateDivision($total, $total2), 2);
    }

    protected function calculationForEnerygy($value)
    {
        $total = 0;

        if (isset($value[946])) {
            $total += $value[946] * 7.23;
        }

        if (isset($value[947])) {
            $total += $value[947] * 2.20;
        }

        if (isset($value[948])) {
            $total += $value[948] * 5.68;
        }

        if (isset($value[949])) {
            $total += $value[949] * 0.01;
        }

        if (isset($value[950])) {
            $total += $value[950] * 6.76;
        }

        if (isset($value[951])) {
            $total += $value[951] * 6.63;
        }

        if (isset($value[952])) {
            $total += $value[952] * 9.06;
        }

        if (isset($value[953])) {
            $total += $value[953] * 9.76;
        }

        if (isset($value[954])) {
            $total += $value[954] * 9.98;
        }

        if (isset($value[955])) {
            $total += $value[955] * 10.02;
        }

        if (isset($value[956])) {
            $total += $value[956] * 9.02;
        }

        if (isset($value[957])) {
            $total += $value[957] * 9.20;
        }

        return $total;
    }

    public function parseDataForEnergyConsumption()
    {
        $total = 0;
        $total2 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1151'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
            $total += $value1 != null ? array_sum($value1) : 0;

            $answers2 = $answer['872'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;
            $total += $value2 != null ? $this->calculationForEnerygy($value2) : 0;

            $answers3 = $answer['875'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;
            $total += $value3 != null ? $this->calculationForEnerygy($value3) : 0;

            $answers4 = $answer['869'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;
            $total2 += $value4 ?? 0;
        }

        if ($total === 0 && $total2 === 0) {
            return 0;
        }

        return round(calculateDivision($total, $total2));
    }

    public function parseDataForEnergyEfficiency()
    {
        $total = 0;
        $total2 = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1151'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
            $total += $value1 != null ? array_sum($value1) : 0;

            $answers2 = $answer['872'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;
            $total += $value2 != null ? $this->calculationForEnerygy($value2) : 0;

            $answers3 = $answer['875'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;
            $total += $value3 != null ? $this->calculationForEnerygy($value3) : 0;

            $answers4 = $answer['1159'] ?? null;
            $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;
            $total2 += $value4 ?? 0;
        }

        if ($total === 0 && $total2 === 0) {
            return 0;
        }

        return round(calculateDivision($total, $total2));
    }

    public function parseDataForCarbonIntensity()
    {
        $total = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1151'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
            $total += $value1 != null ? array_sum($value1) : 0;

            $answers2 = $answer['872'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;
            $total += $value2 != null ? $this->calculationForEnerygy($value2) : 0;

            $answers3 = $answer['875'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;
            $total += $value3 != null ? $this->calculationForEnerygy($value3) : 0;
        }

        $scope1 = $this->calculateScope1();

        if ($total === 0 && $scope1 === 0) {
            return 0;
        }

        return number_format(calculateDivision($scope1, $total), 2);
    }

    public function parseDataForSustainableDevelopmentGoals()
    {
        $data = [];
        foreach ($this->answers as $answer) {
            $answers1 = $answer['1153'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
            if ($value1) {
                $data = array_replace_recursive($data, $value1);
            }
        }

        return $data;
    }

    public function parseDataForMarketsReportingUnitsOperate()
    {
        $country = [];

        foreach ($this->answers as $answers) {
            $answers1 = $answers['830'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

            if (isset($value1)) {
                array_push($country, array_values($value1));
            }
        }

        if (empty($country)) {
            return null;
        }

        return getCountriesWhereIn(array_reduce($country, 'array_merge', []));
    }

    public function parseDataForLegalRequirementsApplicable($questionnaire = null)
    {
        if ($questionnaire == null) {
            $sum979 = 0;
            $yes979 = 0;
            $sum983 = 0;
            $yes983 = 0;
            $sum984 = 0;
            $yes984 = 0;

            foreach ($this->answers as $answer) {
                $answers1 = $answer['979'] ?? null;
                $answers2 = $answer['983'] ?? null;
                $answers3 = $answer['984'] ?? null;

                $value1 = isset($answers1->value) ? $answers1->value : null;
                $value2 = isset($answers2->value) ? $answers2->value : null;
                $value3 = isset($answers3->value) ? $answers3->value : null;

                if ($value1 !== null) {
                    if ($value1 == 'yes') {
                        $yes979++;
                    }

                    $sum979++;
                }

                if ($value2 !== null) {
                    if ($value2 == 'yes') {
                        $yes983++;
                    }

                    $sum983++;
                }

                if ($value3 !== null) {
                    if ($value3 == 'yes') {
                        $yes984++;
                    }

                    $sum984++;
                }
            }

            return [
                calculatePercentage($yes979, $sum979),
                calculatePercentage($yes983, $sum983),
                calculatePercentage($yes984, $sum984),
            ];
        } else {
            $answers1 = $this->reportQuestionnaire['979'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            return [
                'status' => $value1 ?? 0,
            ];
        }
    }

    public function parseDataForEthic($questionnaire = null)
    {
        if ($questionnaire != null) {
            $answers1 = $this->reportQuestionnaire['983'] ?? null;
            $value1 = isset($answers1->value) ? $answers1->value : null;

            $answers2 = $this->reportQuestionnaire['984'] ?? null;
            $value2 = isset($answers2->value) ? $answers2->value : null;

            return [
                'reporting_unit_code_ethics' => $value1 ?? 0,
                'world_tourism_code_of_ethics' => $value2 ?? 0,
            ];
        }
    }

    public function avg_months_operation()
    {
        $sum = 0;
        $count = 0;

        foreach ($this->answers as $answer) {
            $answers1 = $answer['1044'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;
            if ($value1) {
                $sum += $value1;
                $count++;
            }
        }

        return number_format(calculateDivision($sum, $count));
    }

    public function parseDataForNumberWorkersGender()
    {
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        foreach ($this->answers as $answers) {
            $answers1 = $answers['1024'] ?? null;
            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
            $sum1 += $value1 != null ? array_sum($value1) : 0;

            $answers2 = $answers['1023'] ?? null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;
            $sum2 += $value2 != null ? array_sum($value2) : 0;

            $answers3 = $answers['1019'] ?? null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;
            $sum3 += $value3 != null ? array_sum($value3) : 0;
        }

        if ($sum1 === 0 && $sum2 === 0 && $sum3 === 0) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$sum1, $sum2, $sum3];
        $parseData = $this->removeNullData($labels, $data);

        if (($parseData['label'] == []) && ($parseData['data'] == [])) {
            return null;
        }

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }
}
