<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Enums\NaturalHazard;
use App\Models\Enums\Risk;
use App\Models\Enums\Territory\County;
use App\Models\Enums\Territory\District;
use App\Models\Tenant\Answer;
use App\Models\Tenant\Initiative;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\QuestionOptions\Simple;
use App\Models\Tenant\Sdg;
use App\Models\Traits\Dashboard;

class Dashboard5
{
    use Dashboard;

    protected $answers;

    protected $questionIds;

    public function view($questionnaire = null)
    {
    }

    protected function removeNullData($labels, $rowdata)
    {
        $label = [];
        $data = [];
        $loop = 0;

        foreach ($rowdata as $row) {
            if ($row != 0 && $row != null) {
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

    public function chartForOneQuestionnaire($questionnaireId)
    {
        if ($questionnaireId != null) {
            $this->setQuestionnaire($questionnaireId);
            $this->parsePosition();

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
                'modalities_schedules_reporting_units' => $this->parseDataForModalitiesSchedulesReportingUnits(),
                'conciliation_measures_unit' => $this->parseDataForConciliationMeasuresUnit(),
                'workers_initial_parental_leave' => $this->parseDataForWorkersInitialParentalLeave(),
                'workers_return_towork_after_parental_leave' => $this->parseDataForWorkersReturnToworkAfterParentalLeave(),
                'workers_return_towork_after_parental_leave_twelve_month' => $this->parseDataForWorkersReturnToworkAfterParentalLeaveTwelveMonth(),
                'worker_expect_toreturn_after_leave' => $this->parseDataForWorkerExpectToreturnAfterLeave(),
                'established_by_law' => $this->parseDataForEstablishedByLaw(),
                'policies' => $this->praseDataForPolicies(),
                'return_to_work_rate' => $this->parseDataForReturnToWorkRate(),
                'local_development_programs' => $this->parseDataForLocalDevelopmentPrograms(),
                //'monetary_local_purchases' => $this->parseDataForMonetaryLocalPurchases(),
                //'monetary_amount_spent_purchases' => $this->parseDataForMonetaryAmountSpentPurchases(),
                'monetary_amount_spent_local_products' => $this->parseDataForMonetaryAmountSpentLocalProducts(),
                'participates_local_development_programs' => $this->parseDataForParticipatesLocalDevelopmentPrograms(),
                'number_workers_gender' => $this->parseDataForNumberWorkersGender(),

                // Governance
                'ethic' => $this->parseDataForEthic(),
                'number_of_hours_of_ethics_training' => $this->parseDataForNumberOfHoursOfEthicsTraining(),
                'board_of_directors_by_gender' => $this->parseDataForBoardOfDirectorsByGender(),
                'independent_members_participate_board_of_director' => $this->parseDataForIndependentMembersParticipateBoardOfDirector(),
                'board_of_directors_by_age_group' => $this->parseDataForBoardOfDirectorsByAgeGroup(),
                'risks_arising_supply_chain_reporting_units' => $this->parseDataForRisksArisingSupplyChainReportingUnits(),
                'governance_policies' => $this->parseDataForGovernancePolicies(),
                'legal_requirements_applicable' => $this->parseDataForLegalRequirementsApplicable(),
                'risk_matrix' => $this->parseDataForRiskMatrix(),

                // Ambiental
                'total_amount_of_water_consumed' => $this->parseDataForTotalAmountOfWaterConsumed(),
                'reduce_consumption' => $this->parseDataForReduceConsumption(),
                'emission_value_per_parameter' => $this->parseDataForEmissionValuePerParameter(),
                'electricity_consumed_per_source' => $this->parseDataForElectricityConsumedPerSource(),
                'total_gross_production_value' => $this->parseDataForTotalGrossProductionValue(),
                'total_indirect_costs' => $this->parseDataForTotalIndirectCosts(),
                'gross_added_value_company' => $this->parseDataForGrossAddedValueCompany(),
                'total_production_volume' => $this->parseDataForTotalProductionVolume(),
                'percentage_electricity_consumption' => $this->parseDataForPercentageElectricityConsumption(),
                'ghg_emissions' => $this->parseDataForGhgEmissions(),
                'travel_in_vehicles_owned' => $this->parseDataForTravelInVehiclesOwned(),
                'amount_of_non_road_fuel_consumed' => $this->parseDataForAmountOfNonRoadFuelConsumed(),
                'travel_in_vehicles_fuel_consumed' => $this->parseDataForTravelInVehiclesFuelConsumed(),
                'vehicle_and_distance_travelled' => $this->parseDataForVehicleAndDistanceTravelled(),
                'travel_vehicles_reporting_units_donot_own' => $this->parseDataForTravelVehiclesReportingUnitsDonotOwn(),
                'control_or_operate_fuel_consumed' => $this->parseDataForControlOrOperateFuelConsumed(),
                'type_of_transport_vehicle_distance' => $this->parseDataForTypeOfTransportVehicleDistance(),
                'equipment_containing_greebhouse_gas' => $this->parseDataForEquipmentContainingGreebhouseGas(),
                'total_amount_of_leak' => $this->parseDataForTotalAmountOfLeak(),
                'total_amount_of_electricity' => $this->parseDataForTotalAmountOfElectricity(),
                'waste_production_facilities' => $this->praseDataForWasteProductionFacilities(),
                'waste_produced_in_ton' => $this->parseDataForWasteProducedInTon(),
                'waste_placed_in_recycling' => $this->parseDataForWastePlacedInRecycling(),
                'water_on_its_premises' => $this->parseDataForWateronItsPremises(),
                'total_amount_of_water_m3' => $this->parseDataForTotalAmountOfWaterM3(),
                'purchased_goods_during_reporting_period' => $this->parseDataForPurchasedGoodsDuringReportingPeriod(),
                'type_total_quantity_goods_purchased_ton' => $this->parseDataForTypeTotalQuantityGoodsPurchasedTon(),
                'telecommuting_workers' => $this->parseDataForTelecommutingWorkers(),
                'hours_worked_in_telecommuting' => $this->parseDataForHoursWorkedInTelecommuting(),
                'carbon_sequestration_capacity_ghg' => $this->parseDataForcarbonSequestrationCapacityGhg(),
                'ghg_emissions_and_carbon_sequestration' => $this->parseDataForGhgEmissionsAndCarbonSequestration(),
                'emission_air_pollutants' => $this->parseDataForEmissionAirPollutants(),
                'air_pollutant' => $this->parseDataForAirPollutant(),
                'deplete_the_ozone_layer' => $this->parseDataForDepleteTheOzoneLayer(),
                'depletes_the_ozone_layer_in_tons' => $this->parseDataForDepletesTheOzoneLayerInTons(),
                'emissions' => $this->parseDataForEmissions(),
                'scope' => $this->parseDataForScope(),
                '25_km_radius_environmental_protection_area' => $this->parseDataFor25KmRadiusEnvironmentalProtectionArea(),
                'environmental_impact_studies' => $this->parseDataForEnvironmentalImpactStudies(),
                'species_affected' => $this->parseDataForSpeciesAffected(),
                'environmental_impact_studies1' => $this->parseDataForEnvironmentalImpactStudies1(),
                'habitats_outside_the_studies' => $this->parseDataForHabitatsOutsideTheStudies(),
                'physical_risks' => $this->parsePhysicalRisks(),
                'energy_costs' => $this->parseDataForEnergyCosts(),
                'hazardous_waste' => $this->parseDataForHazardousWaste(),
                'waste_management' => $this->parseDataForWasteManagement(),
                'radioactive_waste' => $this->parseDataForRadioactiveWastes(),
                'environmental_policies' => $this->parseDataForEnvironmentalPolicies(),
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
                'materiality_matrix' => $this->parseDataForMaterialityMatrix(),
                'sustainable_development_goals' => $this->parseDataForSustainableDevelopmentGoals(),

                // Print version
                'report' => $this->parseReport($questionnaireId),
            ];
        }

        return [
            'charts' => $charts ?? null,
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
            901, 1063, 906, 907, 908, 909, 1151, 872, 875, 868, 869, 1159, 1153, 830, 858,

            1042, 834, 828, 835, 838, 1139, 1137, 1138, 1005, 1006, 1008, 1131, 1141, 837,
            1128, 1129, 1130, 1150, 911, 912, 913, 914, 972, 973, 974, 1047, 1152, 1135, 971, 975,
            870, 1043,

        ]);

        $this->questionIds = $questionIds;
        $this->answers = $this->answers->whereIn('question_id', $questionIds)->sortBy('question_id');
    }

    public function parseReport($questionnaireId)
    {
        $logo = null;
        $responsibility = null;

        $answers1 = $this->answers['1002'] ?? null;
        $answers2 = $this->answers['1004'] ?? null;
        $answers3 = $this->answers['1007'] ?? null;

        $answers4 = $this->answers['1042'] ?? null;
        $answers5 = $this->answers['834'] ?? null;
        $answers6 = $this->answers['831'] ?? null;
        $answers7 = $this->answers['832'] ?? null;
        $answers8 = $this->answers['833'] ?? null;
        $answers9 = $this->answers['828'] ?? null;
        $answers10 = $this->answers['829'] ?? null;
        $answers11 = $this->answers['1044'] ?? null;
        $answers12 = $this->answers['830'] ?? null;

        $answers13 = $this->answers['836'] ?? null;
        $answers14 = $this->answers['835'] ?? null;
        $answers15 = $this->answers['838'] ?? null;
        $answers16 = $this->answers['1144'] ?? null;
        $answers17 = $this->answers['1145'] ?? null;
        $answers18 = $this->answers['1139'] ?? null;
        $answers19 = $this->answers['1137'] ?? null;
        $answers20 = $this->answers['1138'] ?? null;
        $answers21 = $this->answers['1005'] ?? null;
        $answers22 = $this->answers['1006'] ?? null;
        $answers23 = $this->answers['1008'] ?? null;
        $answers24 = $this->answers['1131'] ?? null;
        $answers25 = $this->answers['1141'] ?? null;
        $answers26 = $this->answers['1128'] ?? null;
        $answers27 = $this->answers['1129'] ?? null;
        $answers28 = $this->answers['1130'] ?? null;

        $answers29 = $this->answers['835'] ?? null;
        $answers30 = $this->answers['837'] ?? null;

        $answers31 = $this->answers['971'] ?? null;
        $answers32 = $this->answers['975'] ?? null;

        $answers33 = $this->answers['870'] ?? null;
        $answers34 = $this->answers['1043'] ?? null;

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
        $data11 = isset($answers11->value) ? json_decode($answers11->value, true)[1] : null;
        $data12 = isset($answers12->value) ? json_decode($answers12->value, true) : null;

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
        $data24 = isset($answers24->value) ? json_decode($answers24->value, true) : null;
        $data25 = isset($answers25->value) ? json_decode($answers25->value, true)[1] : null;

        $data26 = isset($answers26->value) ? json_decode($answers26->value, true)[1] : null;
        $data27 = isset($answers27->value) ? json_decode($answers27->value, true)[1] : null;
        $data28 = isset($answers28->value) ? json_decode($answers28->value, true)[1] : null;

        $data29 = isset($answers29->value) ? json_decode($answers29->value, true)[1] : null;
        $data30 = isset($answers30->value) ? json_decode($answers30->value, true)[1] : null;

        $data31 = isset($answers31->value) ? json_decode($answers31->value, true)[1] : null;
        $data32 = isset($answers32->value) ? json_decode($answers32->value, true)[1] : null;

        $data33 = isset($answers33->value) ? json_decode($answers33->value, true)[1] : null;
        $data34 = isset($answers34->value) ? json_decode($answers34->value, true)[1] : null;

        $company = Questionnaire::questionnaireListByQuestionId([$questionnaireId])->toArray();

        if ($data23) {
            foreach ($data23 as $key => $value) {
                $label = Simple::find($key)->label;
                $responsibility = $label;
            }
        }

        if ($answers24) {
            $attachments = $answers24->attachments()->first();
            if ($attachments) {
                $logo = $attachments->getUrl();
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
            'number_months' => $data11 ?? null,
            'mercados' => isset($data12) && $data12 != null ? getCountriesWhereIn(array_values($data12)) : null,
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
            'ods_text' => $data25 ?? null,
            'ambiental_text' => $data26 ?? null,
            'social_text' => $data27 ?? null,
            'governação_text' => $data28 ?? null,
            'logo' => $logo ?? null,
            'responsibility' => $responsibility ?? null,
            'vat_country' => $company[0]['company']['vat_country'] != null ? getCountriesWhereIn([$company[0]['company']['vat_country']]) : null,
            'tipos_de_fornecedores' => $data29 ?? null,
            'fornecedores_de_primeiro' => $data30 ?? null,
            'desenvolvimento' => $data31 ?? $data32 ?? null,
            'measures_promote_energy_efficiency' => $data33 ?? null,
            'nature_ownership' => $data34 ?? null,
        ];
    }

    public function parseDataForMaterialityMatrix()
    {
        $answers1 = $this->answers->whereIn(
            'question_id',
            [999, 1101, 1104, 1107, 1110, 1113, 1116, 1119, 1122, 1125, 1231, 1237]
        )->first();

        $answers2 = $this->answers->whereIn(
            'question_id',
            [1000, 1102, 1105, 1108, 1111, 1114, 1117, 1120, 1123, 1126, 1232, 1235]
        )->first();

        $answers3 = $this->answers->whereIn(
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

    public function parsePhysicalRisks()
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

        $county = $this->questionnaire->company->cus_county ?? null;
        $district = $county ? County::from($county)->district()->value : '0';

        return $risksByDistrict[$district] ?? $risksByDistrict['0'];
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
        $scope = $this->answers['871'] ?? null;
        $scopeStatus = isset($scope->value) ? $scope->value : null;
        if ($scopeStatus == 'yes') {
            $scope = $this->answers['1147'] ?? null;
            $scopedata = isset($scope->value) ? json_decode($scope->value, true) : null;

            $total = ($scopedata[1270] ?? 0) + ($scopedata[1271] ?? 0) + ($scopedata[1272] ?? 0);
        } else {
            $answers1 = $this->answers['872'] ?? null;
            $answers2 = $this->answers['875'] ?? null;
            $answers3 = $this->answers['887'] ?? null;

            $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
            $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;
            $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

            if ($value1 == null && $value2 == null && $value3 == null) {
                return 0;
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

        return $total ? $total / 1000 : 0;
    }

    protected function calculateScope2()
    {
        $total = 0;
        $answers1 = $this->answers['888'] ?? null;

        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return 0;
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

        return $total ? $total / 1000 : 0;
    }

    protected function calculateScope3()
    {
        $total = 0;
        $answers1 = $this->answers['880'] ?? null;
        $answers2 = $this->answers['890'] ?? null;
        $answers3 = $this->answers['892'] ?? null;
        $answers4 = $this->answers['1014'] ?? null;
        $answers5 = $this->answers['896'] ?? null;

        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true) : null;
        $value5 = isset($answers5->value) ? json_decode($answers5->value, true) : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null && $value5 == null) {
            return 0;
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

        return $total ? $total / 1000 : 0;
    }

    public function parseDataForScope()
    {
        $scope1 = round($this->calculateScope1(), 2);
        $scope2 = round($this->calculateScope2(), 2);
        $scope3 = round($this->calculateScope3(), 2);

        if ($scope1 === 0.0 && $scope2 === 0.0 && $scope3 === 0.0) {
            return null;
        }

        $labels = ['Âmbito 1', 'Âmbito 2', 'Âmbito 3'];
        $data = [
            $scope1 != 0 ? $scope1 : 0,
            $scope2 != 0 ? $scope2 : 0,
            $scope3 != 0 ? $scope3 : 0,
        ];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalWorkers()
    {
        $answers1 = $this->answers['828'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function praseDataForBusinessUnits()
    {
        $answers1 = $this->answers['829'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForAverageMonthsActivity()
    {
        $answers1 = $this->answers['1044'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForAverageLevelsSatisfactionPlatforms()
    {
        $answers1 = $this->answers['831'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [901, 902, 903, 904, 8];
        $labels = [];
        $data = [];

        $data901 = 0;
        $data902 = 0;
        $data903 = 0;
        $data904 = 0;
        $data8 = 0;

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
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
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
            'data' => $data,
        ];
    }

    public function parseDataForNumberOfComplaints()
    {
        $answers1 = $this->answers['832'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['833'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $labels = ['Reclamações', 'Elogios'];
        $data = [$value1, $value2];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalSuppliers()
    {
        $answers1 = $this->answers['836'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForTotalReportingUnitSuppliers()
    {
        $answers1 = $this->answers['1144'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForTotalReportingUnitSuppliersChildLabor()
    {
        $answers1 = $this->answers['1145'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForTurnoverTotalOperatingCosts()
    {
        $answers1 = $this->answers['839'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['840'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $labels = ['Volume de Negócio', 'Custos Operacionais'];
        $data = [$value1, $value2];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalValueEmployeeSalaries()
    {
        $answers1 = $this->answers['841'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['842'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $labels = ['Salários', 'Benefícios'];
        $data = [$value1, $value2];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalCapitalProviders()
    {
        $answers1 = $this->answers['843'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['844'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $labels = ['Fornecedores de capital', 'Estado'];
        $data = [$value1, $value2];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalInvestmentValue()
    {
        // Amount invested at the customer's request - between community and total
        $answers3 = $this->answers['847'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['848'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        $answers5 = $this->answers['849'] ?? null;
        $value5 = isset($answers5->value) ? json_decode($answers5->value, true)[1] : null;

        $answers6 = $this->answers['850'] ?? null;
        $value6 = isset($answers6->value) ? json_decode($answers6->value, true)[1] : null;

        $answers7 = $this->answers['851'] ?? null;
        $value7 = isset($answers7->value) ? json_decode($answers7->value, true)[1] : null;

        if ($value3 == null && $value4 == null && $value5 == null && $value6 == null && $value7 == null) {
            return null;
        }

        $labels = [
            'Inovação',
            'Digitalização e cibersegurança',
            'I & D',
            'Proteção Ambiental',
            'Valorização Territorial',
            'Total',
        ];
        $data = [$value3, $value4, $value5, $value6, $value7, ($value3 + $value4 + $value5 + $value6 + $value7)];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalFinancialSupportReceived()
    {
        $answers1 = $this->answers['852'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForTurnoverResultingFromProducts()
    {
        $answers1 = $this->answers['853'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForWithheldEconomicValue()
    {
        $answers1 = $this->answers['839'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['840'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['841'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['842'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        $answers5 = $this->answers['843'] ?? null;
        $value5 = isset($answers5->value) ? json_decode($answers5->value, true)[1] : null;

        $answers6 = $this->answers['844'] ?? null;
        $value6 = isset($answers6->value) ? json_decode($answers6->value, true)[1] : null;

        $answers7 = $this->answers['845'] ?? null;
        $value7 = isset($answers7->value) ? json_decode($answers7->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null && $value5 == null && $value6 == null && $value7 == null) {
            return 0;
        }

        return $value1 - ($value2 + $value3 + $value4 + $value5 + $value6 + $value7);
    }

    public function parseDataForTurnoverValue()
    {
        $answers1 = $this->answers['839'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForTotalAmountCapexDa()
    {
        $answers1 = $this->answers['854'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['855'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['856'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return 0;
        }

        $labels = [
            'CapEx do ano vigente',
            'CapEx do ano anterior',
            'Depreciação e Amortização',
        ];
        $data = [$value1, $value2, $value3];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForDirectEconomicValue()
    {
        $answers1 = $this->answers['840'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['841'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['842'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['843'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        $answers5 = $this->answers['844'] ?? null;
        $value5 = isset($answers5->value) ? json_decode($answers5->value, true)[1] : null;

        $answers6 = $this->answers['845'] ?? null;
        $value6 = isset($answers6->value) ? json_decode($answers6->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null && $value5 == null && $value6 == null) {
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

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTaxonomyTurnover()
    {
        $answers1 = $this->answers['853'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['854'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['855'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['867'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        $answers5 = $this->answers['857'] ?? null;
        $value5 = isset($answers5->value) ? json_decode($answers5->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null && $value5 == null) {
            return 0;
        }

        $labels = [
            __('Volume de negócios'),
            __('CapEx'),
            __('OpEx'),
        ];

        $data = [$value1, ($value2 - $value3 + $value4), $value5];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageOfWorkersByContractualRegime()
    {
        $answers1 = $this->answers['923'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['924'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['925'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['828'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null) {
            return null;
        }

        $data1 = calculatePercentage($value1, $value4);
        $data2 = calculatePercentage($value2, $value4);
        $data3 = calculatePercentage($value3, $value4);

        $labels = [
            'Full-time',
            'Part-time',
            'Outros regimes contratuais',
        ];
        $data = [
            ($data1 == 100 ? 100 : $data1),
            ($data2 == 100 ? 100 : $data2),
            ($data3 == 100 ? 100 : $data3),
        ];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageWorkersByContract()
    {
        $answers1 = $this->answers['1026'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['1027'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['828'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return null;
        }

        $data1 = calculatePercentage($value1, $value3);
        $data2 = calculatePercentage($value2, $value3);

        $labels = [
            'Termo certo',
            'Sem termo',
        ];
        $data = [
            ($data1 == 100 ? 100 : $data1),
            ($data2 == 100 ? 100 : $data2),
        ];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForTotalNumberOfHires()
    {
        $answers1 = $this->answers['926'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForPercentageOfHiresByContractualRegime()
    {
        $answers1 = $this->answers['926'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['927'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['928'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['929'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null) {
            return null;
        }

        $data1 = calculatePercentage($value2, $value1);
        $data2 = calculatePercentage($value3, $value1);
        $data3 = calculatePercentage($value4, $value1);

        $labels = [
            'Termo certo',
            'Sem termo',
            'Outros regimes contratuais',
        ];
        $data = [
            ($data1 == 100 ? 100 : $data1),
            ($data2 == 100 ? 100 : $data2),
            ($data3 == 100 ? 100 : $data3),
        ];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageOfHiringOfWorkersByGender()
    {
        $answers1 = $this->answers['926'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['930'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['931'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['1015'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null) {
            return null;
        }

        $data1 = calculatePercentage($value2, $value1);
        $data2 = calculatePercentage($value3, $value1);
        $data3 = calculatePercentage($value4, $value1);

        $labels = [
            'Masculino',
            'Feminino',
            'Outros',
        ];
        $data = [
            ($data1 == 100 ? 100 : $data1),
            ($data2 == 100 ? 100 : $data2),
            ($data3 == 100 ? 100 : $data3),
        ];
        $parseData = $this->removeNullData($labels, $data);

        return [
            'label' => $parseData['label'],
            'data' => $parseData['data'],
        ];
    }

    public function parseDataForPercentageOfWorkersResidingLocally()
    {
        $answers1 = $this->answers['937'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['828'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }
        $data = calculatePercentage($value1, $value2);
        if ($data == 100) {
            return (int) $data;
        } else {
            return $data;
        }
    }

    public function parseDataForJobCreation()
    {
        $answers1 = $this->answers['932'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['933'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return $value1 - $value2;
    }

    public function parseDataForAverageTurnover()
    {
        $answers1 = $this->answers['932'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['933'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return ($value1 + $value2 ?: 1) / 2;
    }

    public function parseDataForAverageTurnoverRate()
    {
        $answers1 = $this->answers['932'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['933'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['928'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return 0;
        }

        $data = calculatePercentage($value1 + $value2, $value3);

        if ($data == 100) {
            return (int) $data;
        } else {
            return $data;
        }
    }

    public function parseDataForAbsenteeismRate()
    {
        $answers1 = $this->answers['935'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['937'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['928'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return 0;
        }

        $data = calculatePercentage($value1, $value2 * $value3);
        if ($data == 100) {
            return (int) $data;
        } else {
            return $data;
        }
    }

    public function parseDataForSumOfBasicWagesOfWorkersByGender()
    {
        $answers1 = $this->answers['938'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['939'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['1016'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$value1, $value2, $value3];
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
        $answers1 = $this->answers['1029'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$value1[1193] ?? 0, $value1[1194] ?? 0, $value1[1195] ?? 0, $value1[1196] ?? 0];
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
        $answers1 = $this->answers['1028'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$value1[1193] ?? 0, $value1[1194] ?? 0, $value1[1195] ?? 0, $value1[1196] ?? 0];
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
        $answers1 = $this->answers['1032'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$value1[1193] ?? 0, $value1[1194] ?? 0, $value1[1195] ?? 0, $value1[1196] ?? 0];
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
        $answers1 = $this->answers['941'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['940'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['1017'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$value1, $value2, $value3];
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
        $answers1 = $this->answers['1031'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$value1[1193] ?? 0, $value1[1194] ?? 0, $value1[1195] ?? 0, $value1[1196] ?? 0];
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
        $answers1 = $this->answers['1030'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$value1[1193] ?? 0, $value1[1194] ?? 0, $value1[1195] ?? 0, $value1[1196] ?? 0];
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
        $answers1 = $this->answers['1033'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$value1[1193] ?? 0, $value1[1194] ?? 0, $value1[1195] ?? 0, $value1[1196] ?? 0];
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
        $answers1 = $this->answers['943'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['942'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['1018'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['828'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null) {
            return null;
        }

        if ($value1 == 0 && $value2 == 0 && $value3 == 0 && $value4 == 0) {
            return null;
        }

        $data1 = calculatePercentage($value1, $value4);
        $data2 = calculatePercentage($value2, $value4);
        $data3 = calculatePercentage($value3, $value4);

        $labels = ['Feminino', 'Masculino', 'Outros'];
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

    public function parseDataForPercentageMaleWorkersProfessional()
    {
        $answers1 = $this->answers['1023'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $answers2 = $this->answers['1024'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        $answers3 = $this->answers['1019'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return null;
        }

        $data1 = isset($value1[1193], $value2[1193], $value3[1193])
            ? calculatePercentage($value1[1193], intval($value1[1193] + $value2[1193] + $value3[1193]))
            : 0;

        $data2 = isset($value1[1194], $value2[1194], $value3[1194])
            ? calculatePercentage($value1[1194], intval($value1[1194] + $value2[1194] + $value3[1194]))
            : 0;

        $data3 = isset($value1[1195], $value2[1195], $value3[1195])
            ? calculatePercentage($value1[1195], intval($value1[1195] + $value2[1195] + $value3[1195]))
            : 0;

        $data4 = isset($value1[1196], $value2[1196], $value3[1196])
            ? calculatePercentage($value1[1196], intval($value1[1196] + $value2[1196] + $value3[1196]))
            : 0;

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [
            ($data1 == 100 ? 100 : $data1),
            ($data2 == 100 ? 100 : $data2),
            ($data3 == 100 ? 100 : $data3),
            ($data4 == 100 ? 100 : $data4),
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

    public function parseDataForPercentageFemaleWorkersProfessional()
    {
        $answers1 = $this->answers['1024'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $answers2 = $this->answers['1023'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        $answers3 = $this->answers['1019'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return null;
        }

        $data1 = isset($value1[1193], $value2[1193], $value3[1193])
            ? calculatePercentage($value1[1193], intval($value1[1193] + $value2[1193] + $value3[1193]))
            : 0;

        $data2 = isset($value1[1194], $value2[1194], $value3[1194])
            ? calculatePercentage($value1[1194], intval($value1[1194] + $value2[1194] + $value3[1194]))
            : 0;

        $data3 = isset($value1[1195], $value2[1195], $value3[1195])
            ? calculatePercentage($value1[1195], intval($value1[1195] + $value2[1195] + $value3[1195]))
            : 0;

        $data4 = isset($value1[1196], $value2[1196], $value3[1196])
            ? calculatePercentage($value1[1196], intval($value1[1196] + $value2[1196] + $value3[1196]))
            : 0;

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [
            ($data1 == 100 ? 100 : $data1),
            ($data2 == 100 ? 100 : $data2),
            ($data3 == 100 ? 100 : $data3),
            ($data4 == 100 ? 100 : $data4),
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

    public function parseDataForPercentageOtherGenderWorkersProfessional()
    {
        $answers1 = $this->answers['1019'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $answers2 = $this->answers['1023'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        $answers3 = $this->answers['1024'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return null;
        }

        $data1 = isset($value1[1193], $value2[1193], $value3[1193])
            ? calculatePercentage($value1[1193], intval($value1[1193] + $value2[1193] + $value3[1193]))
            : 0;

        $data2 = isset($value1[1194], $value2[1194], $value3[1194])
            ? calculatePercentage($value1[1194], intval($value1[1194] + $value2[1194] + $value3[1194]))
            : 0;

        $data3 = isset($value1[1195], $value2[1195], $value3[1195])
            ? calculatePercentage($value1[1195], intval($value1[1195] + $value2[1195] + $value3[1195]))
            : 0;

        $data4 = isset($value1[1196], $value2[1196], $value3[1196])
            ? calculatePercentage($value1[1196], intval($value1[1196] + $value2[1196] + $value3[1196]))
            : 0;

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [
            ($data1 == 100 ? 100 : $data1),
            ($data2 == 100 ? 100 : $data2),
            ($data3 == 100 ? 100 : $data3),
            ($data4 == 100 ? 100 : $data4),
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

    public function parseDataForPercentageWorkersForeignNationality()
    {
        $answers1 = $this->answers['946'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['828'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        $data = calculatePercentage($value1, $value2);
        if ($data == 100) {
            return (int) $data;
        } else {
            return $data;
        }
    }

    public function parseDataForNumberTrainingHours()
    {
        $answers1 = $this->answers['950'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['951'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['1020'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return 0;
        }

        return ($value1 ?? 0) + ($value2 ?? 0) + ($value3 ?? 0);
    }

    public function parseDataForPercentageWorkersAgegroup()
    {
        $answers1 = $this->answers['947'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['948'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['949'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['828'] ?? null;
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

    public function parseDataForTrainingHoursByGender()
    {
        $answers1 = $this->answers['950'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['951'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['1020'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$value1, $value2, $value3];
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
        $answers1 = $this->answers['950'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['951'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['1020'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['1024'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true) : null;

        $answers5 = $this->answers['1023'] ?? null;
        $value5 = isset($answers5->value) ? json_decode($answers5->value, true) : null;

        $answers6 = $this->answers['1019'] ?? null;
        $value6 = isset($answers6->value) ? json_decode($answers6->value, true) : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null && $value5 == null && $value6 == null) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [
            number_format(calculateDivision($value1, ($value4 != null ? array_sum($value4) : 0)), 2),
            number_format(calculateDivision($value2, ($value5 != null ? array_sum($value5) : 0)), 2),
            number_format(calculateDivision($value3, ($value6 != null ? array_sum($value6) : 0)), 2),
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
        $answers1 = $this->answers['1034'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$value1[1193] ?? 0, $value1[1194] ?? 0, $value1[1195] ?? 0, $value1[1196] ?? 0];
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
        $answers1 = $this->answers['1035'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$value1[1193] ?? 0, $value1[1194] ?? 0, $value1[1195] ?? 0, $value1[1196] ?? 0];
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
        $answers1 = $this->answers['1036'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Administração', 'Direção', 'Técnicos', 'Operacionais'];
        $data = [$value1[1193] ?? 0, $value1[1194] ?? 0, $value1[1195] ?? 0, $value1[1196] ?? 0];
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
        $answers1 = $this->answers['1021'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForHoursTrainingOccupationalHealthSafety()
    {
        $answers1 = $this->answers['958'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForReceivedTrainingOccupationalHealthSafety()
    {
        $answers1 = $this->answers['959'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForAccidentsAtWork()
    {
        $answers1 = $this->answers['961'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForWorkdaysLostDueToAccidents()
    {
        $answers1 = $this->answers['962'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForNumberOfDisabledDays()
    {
        $answers1 = $this->answers['963'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForModalitiesSchedulesReportingUnits()
    {
        $answers1 = $this->answers['964'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return [
                'horas_extras' => 'no',
                'banco_de_horas' => 'no',
                'horários_repartidos' => 'no',
                'turnos_rotativos' => 'no',
                'outro' => 'no',
            ];
        }

        return [
            'horas_extras' => $value1[908] ?? 0,
            'banco_de_horas' => $value1[909] ?? 0,
            'horários_repartidos' => $value1[910] ?? 0,
            'turnos_rotativos' => $value1[911] ?? 0,
            'outro' => isset($value1[8]) && $value1[8] != null ? 1 : 0,
        ];
    }

    public function parseDataForConciliationMeasuresUnit()
    {
        $answers1 = $this->answers['965'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return [
                'banco_de_horas' => 'no',
                'flexibilidade_de_horário' => 'no',
                'dias_de_férias_adicionais' => 'no',
                'horário_compactado_num_número_reduzido_de_dias_por_semana' => 'no',
                'trabalho_a_partir_de_casa_escritório_móvel' => 'no',
                'teletrabalho' => 'no',
                'outro' => 'no',
            ];
        }

        return [
            'banco_de_horas' => $value1[909] ?? 0,
            'flexibilidade_de_horário' => $value1[912] ?? 0,
            'dias_de_férias_adicionais' => $value1[913] ?? 0,
            'horário_compactado_num_número_reduzido_de_dias_por_semana' => $value1[914] ?? 0,
            'trabalho_a_partir_de_casa_escritório_móvel' => $value1[915] ?? 0,
            'teletrabalho' => $value1[918] ?? 0,
            'outro' => isset($value1[8]) && $value1[8] != null ? 1 : 0,
        ];
    }

    public function parseDataForWorkersInitialParentalLeave()
    {
        $answers1 = $this->answers['1037'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$value1[151] ?? 0, $value1[152] ?? 0, $value1[8] ?? 0];
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
        $answers1 = $this->answers['1038'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$value1[151] ?? 0, $value1[152] ?? 0, $value1[8] ?? 0];
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
        $answers1 = $this->answers['1039'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [$value1[151] ?? 0, $value1[152] ?? 0, $value1[8] ?? 0];
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
            return null;
        }

        return $value1;
    }

    public function parseDataForEstablishedByLaw()
    {
        $answers1 = $this->answers['1046'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return [
                'flexibilização_do_tempo_e_de_formas_de_trabalho' => 'no',
                'ajuste_de_função' => 'no',
                'apoios_sociais' => 'no',
                'dias_de_licença_adicionais' => 'no',
                'outro' => 'no',
            ];
        }

        return [
            'flexibilização_do_tempo_e_de_formas_de_trabalho' => $value1[1206] ?? 0,
            'ajuste_de_função' => $value1[1207] ?? 0,
            'apoios_sociais' => $value1[1208] ?? 0,
            'dias_de_licença_adicionais' => $value1[1209] ?? 0,
            'outro' => isset($value1[8]) && $value1[8] != null ? 1 : 0,
        ];
    }

    public function praseDataForPolicies()
    {
        $answers1 = $this->answers['1068'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['1076'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        $answers3 = $this->answers['1077'] ?? null;
        $value3 = isset($answers3->value) ? $answers3->value : null;

        return [
            'política_de_direitos_humanos' => $value1 ?? 0,
            'política_de_fornecedores' => $value2 ?? 0,
            'política_de_remuneração' => $value3 ?? 0,
        ];
    }

    public function parseDataForReturnToWorkRate()
    {
        $answers1 = $this->answers['969'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['1038'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return calculatePercentage($value1, array_sum($value2));
    }

    public function parseDataForLocalDevelopmentPrograms()
    {
        $answers1 = $this->answers['975'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['1047'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return $value1 + $value2;
    }

    public function parseDataForMonetaryLocalPurchases()
    {
        $answers1 = $this->answers['976'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['972'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return $value1 + $value2;
    }

    public function parseDataForMonetaryAmountSpentPurchases()
    {
        $answers1 = $this->answers['977'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['973'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return $value1 + $value2;
    }

    public function parseDataForMonetaryAmountSpentLocalProducts()
    {
        $answers1 = $this->answers['978'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['974'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return $value1 + $value2;
    }

    public function parseDataForEthic()
    {
        $answers1 = $this->answers['983'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['984'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        return [
            'reporting_unit_code_ethics' => $value1 ?? 0,
            'world_tourism_code_of_ethics' => $value2 ?? 0,
        ];
    }

    public function parseDataForNumberOfHoursOfEthicsTraining()
    {
        $answers1 = $this->answers['985'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForBoardOfDirectorsByGender()
    {
        $answers1 = $this->answers['987'] ?? null;
        $answers2 = $this->answers['988'] ?? null;
        $answers3 = $this->answers['989'] ?? null;
        $answers4 = $this->answers['1022'] ?? null;

        $value1 = isset($answers1->value) ? (int) json_decode($answers1->value, true)[1] : null;
        $value2 = isset($answers2->value) ? (int) json_decode($answers2->value, true)[1] : null;
        $value3 = isset($answers3->value) ? (int) json_decode($answers3->value, true)[1] : null;
        $value4 = isset($answers4->value) ? (int) json_decode($answers4->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [
            ($value1 / $value3 ?: 1) * 100,
            ($value2 / $value3 ?: 1) * 100,
            ($value4 / $value3 ?: 1) * 100,
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

    public function parseDataForIndependentMembersParticipateBoardOfDirector()
    {
        $answers1 = $this->answers['990'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['991'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        return [
            'label' => ['Feminino', 'Masculino'],
            'data' => [$value1, $value2],
        ];
    }

    public function parseDataForBoardOfDirectorsByAgeGroup()
    {
        $answers1 = $this->answers['992'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['993'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['994'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['989'] ?? null;
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

    public function parseDataForRisksArisingSupplyChainReportingUnits()
    {
        $answers1 = $this->answers['995'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return [
                'child_labor' => 'no',
                'unsafe_working_conditions' => 'no',
                'labor_laws' => 'no',
                'environmental_legislation' => 'no',
                'hazardous_substances' => 'no',
                'corruption_situations' => 'no',
                'competition_laws' => 'no',
                'outro' => 'no',
            ];
        }

        return [
            'child_labor' => $value1[917] ?? 0,
            'unsafe_working_conditions' => $value1[918] ?? 0,
            'labor_laws' => $value1[919] ?? 0,
            'environmental_legislation' => $value1[920] ?? 0,
            'hazardous_substances' => $value1[921] ?? 0,
            'corruption_situations' => $value1[922] ?? 0,
            'competition_laws' => $value1[928] ?? 0,
            'outro' => isset($value1[8]) && $value1[8] != null ? 1 : 0,
        ];
    }

    public function parseDataForGovernancePolicies()
    {
        $answers1 = $this->answers['1069'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['1070'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        $answers3 = $this->answers['1071'] ?? null;
        $value3 = isset($answers3->value) ? $answers3->value : null;

        $answers4 = $this->answers['1072'] ?? null;
        $value4 = isset($answers4->value) ? $answers4->value : null;

        $answers5 = $this->answers['1073'] ?? null;
        $value5 = isset($answers5->value) ? $answers5->value : null;

        return [
            'anti_corruption' => $value1 ?? 0,
            'conflicts_interest' => $value2 ?? 0,
            'code_of_ethics_and_conduct_from_suppliers' => $value3 ?? 0,
            'reporting_channel' => $value4 ?? 0,
            'data_privacy_policy' => $value5 ?? 0,
        ];
    }

    public function parseDataForParticipatesLocalDevelopmentPrograms()
    {
        $answers1 = $this->answers['1048'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['1152'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        return [
            'status' => $value1 || $value2 ?? 0,
        ];
    }

    public function parseDataForLegalRequirementsApplicable()
    {
        $answers1 = $this->answers['979'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return [
            'status' => $value1 ?? 0,
        ];
    }

    public function parseDataForTotalAmountOfWaterConsumed()
    {
        $answers1 = $this->answers['862'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;
        $answers2 = $this->answers['858'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        if ($value1 == null && $value2 == null) {
            return null;
        }

        $labels = [
            'Companhia',
            'Recursos hídricos',
            'Furo/poço',
            'Reciclada/reutilizada',
        ];
        $data = [
            ($value1[905] ?? 0) + ($value2[905] ?? 0),
            ($value1[906] ?? 0) + ($value2[906] ?? 0),
            ($value1[907] ?? 0) + ($value2[907] ?? 0),
            ($value1[1127] ?? 0) + ($value2[1127] ?? 0),
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

    public function parseDataForReduceConsumption()
    {
        $answers1 = $this->answers['1134'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $answers2 = $this->answers['1135'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        if ($value1 == null && $value2 == null) {
            return [
                'temporizadores' => 'no',
                'redutores_de_caudal' => 'no',
                'alteração_de_pressão' => 'no',
                'alteração_dos_autoclismos' => 'no',
                'outro' => 'no',
            ];
        }

        return [
            'temporizadores' => (isset($value1[1265]) && $value1[1265] != null) || (isset($value2[1265]) && $value2[1265] != null) ? 'yes' : 0,
            'redutores_de_caudal' => (isset($value1[1266]) && $value1[1266] != null) || (isset($value2[1266]) && $value2[1266] != null) ? 'yes' : 0,
            'alteração_de_pressão' => (isset($value1[1267]) && $value1[1267] != null) || (isset($value2[1267]) && $value2[1267] != null) ? 'yes' : 0,
            'alteração_dos_autoclismos' => (isset($value1[1268]) && $value1[1268] != null) || (isset($value2[1268]) && $value2[1268] != null) ? 'yes' : 0,
            'outro' => (isset($value1[8]) && $value1[8] != null) || (isset($value2[8]) && $value2[8] != null) ? 'yes' : 0,
        ];
    }

    public function parseDataForEmissionValuePerParameter()
    {
        $answers1 = $this->answers['1154'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = [];
        $data = [];
        foreach ($value1 as $key => $value) {
            if ($value) {
                $label = Simple::find($key)->label;
                $labels[] = $label;
                $data[] = $value;
            }
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForElectricityConsumedPerSource()
    {
        $answers1 = $this->answers['1151'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = [];
        $data = [];
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

                $labels[] = $label;
                $data[] = $value;
            }
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForTotalGrossProductionValue()
    {
        $answers1 = $this->answers['866'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForTotalIndirectCosts()
    {
        $answers1 = $this->answers['867'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForGrossAddedValueCompany()
    {
        $answers1 = $this->answers['868'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForTotalProductionVolume()
    {
        $answers1 = $this->answers['869'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForPercentageElectricityConsumption()
    {
        $answers1 = $this->answers['1151'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        return [
            'label' => [
                'Fonte renovável',
                'Fonte não renovável',
            ],
            'data' => [
                (
                    (($value1[1273] ?? 0) + ($value1[1274] ?? 0) + ($value1[1275] ?? 0) + ($value1[1276] ?? 0) + ($value1[1277] ?? 0) + ($value1[1285] ?? 0))
                    /
                    ((intval(
                        ($value1[1273] ?? 0) + ($value1[1274] ?? 0) + ($value1[1275] ?? 0) + ($value1[1276] ?? 0) + ($value1[1277] ?? 0) + ($value1[1285] ?? 0) +
                        ($value1[1278] ?? 0) + ($value1[1279] ?? 0) + ($value1[1280] ?? 0) + ($value1[1281] ?? 0) + ($value1[1282] ?? 0) + ($value1[1283] ?? 0) + ($value1[1284] ?? 0)
                    ) ?: 1))
                    * 100
                ),
                (
                    (($value1[1278] ?? 0) + ($value1[1279] ?? 0) + ($value1[1280] ?? 0) + ($value1[1281] ?? 0) + ($value1[1282] ?? 0) + ($value1[1283] ?? 0) + ($value1[1284] ?? 0))
                    /
                    ((intval(
                        ($value1[1273] ?? 0) + ($value1[1274] ?? 0) + ($value1[1275] ?? 0) + ($value1[1276] ?? 0) + ($value1[1277] ?? 0) + ($value1[1285] ?? 0) +
                        ($value1[1278] ?? 0) + ($value1[1279] ?? 0) + ($value1[1280] ?? 0) + ($value1[1281] ?? 0) + ($value1[1282] ?? 0) + ($value1[1283] ?? 0) + ($value1[1284] ?? 0)
                    ) ?: 1))
                    * 100
                ),
            ],
        ];
    }

    public function parseDataForGhgEmissions()
    {
        $answers1 = $this->answers['871'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return [
            'status' => $value1 ?? 0,
        ];
    }

    public function parseDataForTravelInVehiclesOwned()
    {
        $answers1 = $this->answers['873'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['874'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        $answers3 = $this->answers['876'] ?? null;
        $value3 = isset($answers3->value) ? $answers3->value : null;

        return [
            'displacements_were_made' => $value1 ?? 0,
            'fuel_consumed' => $value2 ?? 0,
            'distances_traveled' => $value3 ?? 0,
        ];
    }

    public function parseDataForAmountOfNonRoadFuelConsumed()
    {
        $answers1 = $this->answers['872'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [946, 947, 948, 949, 950, 951, 952, 953, 954, 955, 956, 957, 958, 959, 960];
        $labels = [];
        $data = [];
        foreach ($options as $value) {
            if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                $label = Simple::find($value)->label;
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForTravelInVehiclesFuelConsumed()
    {
        $answers1 = $this->answers['875'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [946, 947, 948, 949, 950, 951, 952, 953, 954, 955, 956, 957];
        $labels = [];
        $data = [];
        foreach ($options as $value) {
            if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                $label = Simple::find($value)->label;
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForVehicleAndDistanceTravelled()
    {
        $answers1 = $this->answers['1156'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [
            961, 962, 963, 964, 965, 966, 967, 968, 969, 970, 971, 972, 973, 977, 978, 979, 980,
            981, 982, 983, 984, 985, 986, 987, 988, 989, 990, 991, 992, 993, 994, 995, 996, 997, 998, 999,
            1000, 1002, 1003, 1004, 1005,
        ];

        $labels = [];
        $data = [];
        foreach ($options as $value) {
            if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                $label = Simple::find($value)->label;
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForTravelVehiclesReportingUnitsDonotOwn()
    {
        $answers1 = $this->answers['878'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['879'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        $answers3 = $this->answers['881'] ?? null;
        $value3 = isset($answers3->value) ? $answers3->value : null;

        return [
            'displacements_were_made' => $value1 ?? 0,
            'fuel_consumed' => $value2 ?? 0,
            'distances_traveled' => $value3 ?? 0,
        ];
    }

    public function parseDataForControlOrOperateFuelConsumed()
    {
        $answers1 = $this->answers['880'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = [];
        $data = [];
        foreach ($value1 as $key => $value) {
            if ($value) {
                $label = Simple::find($key)->label;
                $labels[] = $label;
                $data[] = $value;
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForTypeOfTransportVehicleDistance()
    {
        $answers1 = $this->answers['1157'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [
            961, 962, 963, 964, 965, 966, 967, 968, 969, 970, 971, 972, 973, 977, 978, 979, 980,
            981, 982, 983, 984, 985, 986, 987, 988, 989, 990, 991, 992, 993, 994, 995, 996, 997, 998, 999,
            1000, 1002, 1003, 1004, 1005,
        ];

        $labels = [];
        $data = [];
        foreach ($options as $value) {
            if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                $label = Simple::find($value)->label;
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForEquipmentContainingGreebhouseGas()
    {
        $answers1 = $this->answers['883'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['884'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        $answers3 = $this->answers['885'] ?? null;
        $value3 = isset($answers3->value) ? $answers3->value : null;

        return [
            'greenhouse_effect' => $value1 ?? 0,
            'equipment_leaks' => $value2 ?? 0,
            'leakage' => $value3 ?? 0,
        ];
    }

    public function parseDataForTotalAmountOfLeak()
    {
        $answers1 = $this->answers['887'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

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

        $labels = [];
        $data = [];
        foreach ($options as $value) {
            if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                $label = Simple::find($value)->label;
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForTotalAmountOfElectricity()
    {
        $answers1 = $this->answers['888'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [1099, 1100, 1101, 1102];

        $labels = [];
        $data = [];
        foreach ($options as $value) {
            if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                $label = Simple::find($value)->label;
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function praseDataForWasteProductionFacilities()
    {
        $answers1 = $this->answers['889'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return [
            'status' => $value1 ?? 0,
        ];
    }

    public function parseDataForWasteProducedInTon()
    {
        $answers1 = $this->answers['1025'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [1197, 1103, 1104, 1105, 1106, 1107, 1108, 1109, 1110, 1111, 1112, 1114, 1115, 1116, 1117, 1118];

        $labels = [];
        $data = [];
        foreach ($options as $value) {
            if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                $label = Simple::find($value)->label;
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForWastePlacedInRecycling()
    {
        $answers1 = $this->answers['1025'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $sum1 = ($value1[1197] ?? 0) + ($value1[1103] ?? 0) + ($value1[1104] ?? 0) + ($value1[1105] ?? 0);
        $sum2 = ($value1[1106] ?? 0) + ($value1[1107] ?? 0) + ($value1[1108] ?? 0) + ($value1[1116] ?? 0) + ($value1[1117] ?? 0) + ($value1[1118] ?? 0);
        $sum3 = ($value1[1109] ?? 0) + ($value1[1110] ?? 0) + ($value1[1111] ?? 0);
        $sum4 = ($value1[1112] ?? 0) + ($value1[1114] ?? 0) + ($value1[1115] ?? 0);

        $labels = ['Papel', 'Plástico/Metal', 'Vidro', 'Orgânico'];
        $data = [
            $sum1 !== 0 ? bcmul(((($value1[1197] ?? 0) + ($value1[1103] ?? 0)) / $sum1 ?: 1), 100, 2) : 0,
            $sum2 !== 0 ? bcmul(((($value1[1106] ?? 0) + ($value1[1116] ?? 0)) / $sum2 ?: 1), 100, 2) : 0,
            $sum3 !== 0 ? bcmul((($value1[1109] ?? 0) / $sum3 ?: 1), 100, 2) : 0,
            $sum4 !== 0 ? bcmul((($value1[1112] ?? 0) / $sum4 ?: 1), 100, 2) : 0,
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

    public function parseDataForWateronItsPremises()
    {
        $answers1 = $this->answers['891'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return [
            'status' => $value1 ?? 0,
        ];
    }

    public function parseDataForTotalAmountOfWaterM3()
    {
        $answers1 = $this->answers['892'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [1119, 1120];

        $labels = [];
        $data = [];
        foreach ($options as $value) {
            if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                $label = Simple::find($value)->label;
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForPurchasedGoodsDuringReportingPeriod()
    {
        $answers1 = $this->answers['893'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return [
            'status' => $value1 ?? 0,
        ];
    }

    public function parseDataForTypeTotalQuantityGoodsPurchasedTon()
    {
        $answers1 = $this->answers['1014'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [1160, 1161, 1162, 1163, 1164, 1165, 1166, 1167, 1168, 1169, 1170, 1171, 1172, 1173, 1174,
            1175, 1176, 1177, 1178, 1179, 1180, 1181, 1182, 1183, 1184, 1185, 1186, 1187, 1188, 1189, 1190, 1191, 1192];

        $labels = [];
        $data = [];
        foreach ($options as $value) {
            if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                $label = Simple::find($value)->label;
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForTelecommutingWorkers()
    {
        $answers1 = $this->answers['895'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return [
            'status' => $value1 ?? 0,
        ];
    }

    public function parseDataForHoursWorkedInTelecommuting()
    {
        $answers1 = $this->answers['896'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForcarbonSequestrationCapacityGhg()
    {
        $answers1 = $this->answers['1146'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return [
            'status' => $value1 ?? 0,
        ];
    }

    public function parseDataForGhgEmissionsAndCarbonSequestration()
    {
        $answers1 = $this->answers['1147'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [1270, 1271, 1272, 1269];
        $labels = [];
        $data = [];

        foreach ($options as $value) {
            if (isset($value1[$value]) && is_numeric($value1[$value])) {
                if ($value == 1270) {
                    $labels[] = 'Âmbito 1';
                }
                if ($value == 1271) {
                    $labels[] = 'Âmbito 2';
                }
                if ($value == 1272) {
                    $labels[] = 'Âmbito 3';
                }
                if ($value == 1269) {
                    $labels[] = 'Compensação/sequestro de carbono';
                }

                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForEmissionAirPollutants()
    {
        $answers1 = $this->answers['1051'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return [
            'status' => $value1 ?? 0,
        ];
    }

    public function parseDataForAirPollutant()
    {
        $answers1 = $this->answers['1052'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [1210, 1211, 1212, 1213, 1214, 1215, 1216, 1217];

        $labels = [];
        $data = [];
        foreach ($options as $value) {
            if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                $label = Simple::find($value)->label;
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataForDepleteTheOzoneLayer()
    {
        $answers1 = $this->answers['1053'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return [
            'status' => $value1 ?? 0,
        ];
    }

    public function parseDataForDepletesTheOzoneLayerInTons()
    {
        $answers1 = $this->answers['1054'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $options = [1218, 1219, 1220, 1221, 1222, 1223, 1224];

        $labels = [];
        $data = [];
        foreach ($options as $value) {
            if (isset($value1[$value]) && $value1[$value] != null && is_numeric($value1[$value])) {
                $label = Simple::find($value)->label;
                $labels[] = $label;
                $data[] = $value1[$value];
            }
        }

        if (($labels == []) && ($data == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => $data,
        ];
    }

    public function parseDataFor25KmRadiusEnvironmentalProtectionArea()
    {
        $answers1 = $this->answers['1077'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return [
            'status' => $value1 ?? 0,
        ];
    }

    public function parseDataForEnvironmentalImpactStudies()
    {
        $answers1 = $this->answers['1078'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['1079'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        $answers3 = $this->answers['1080'] ?? null;
        $value3 = isset($answers3->value) ? $answers3->value : null;

        $answers4 = $this->answers['1081'] ?? null;
        $value4 = isset($answers4->value) ? $answers4->value : null;

        $answers5 = $this->answers['1083'] ?? null;
        $value5 = isset($answers5->value) ? $answers5->value : null;

        $answers6 = $this->answers['1085'] ?? null;
        $value6 = isset($answers6->value) ? $answers6->value : null;

        return [
            'studies' => $value1 ?? 0,
            'network' => $value2 ?? 0,
            'species_habitats' => $value3 ?? 0,
            'mitigation_measures' => $value4 ?? 0,
            'adaptation_measures' => $value5 ?? 0,
            'reversible' => $value6 ?? 0,
        ];
    }

    public function parseDataForSpeciesAffected()
    {
        $answers1 = $this->answers['1086'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForEnvironmentalImpactStudies1()
    {
        $answers1 = $this->answers['1087'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['1089'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        $answers3 = $this->answers['1090'] ?? null;
        $value3 = isset($answers3->value) ? $answers3->value : null;

        $answers4 = $this->answers['1092'] ?? null;
        $value4 = isset($answers4->value) ? $answers4->value : null;

        return [
            'restoration_measures' => $value1 ?? 0,
            'monitoring_measures' => $value2 ?? 0,
            'number_of_species' => $value3 ?? 0,
            'biodiversity' => $value4 ?? 0,
        ];
    }

    public function parseDataForHabitatsOutsideTheStudies()
    {
        $answers1 = $this->answers['1093'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['1083'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        $answers3 = $this->answers['1085'] ?? null;
        $value3 = isset($answers3->value) ? $answers3->value : null;

        $answers4 = $this->answers['1084'] ?? null;
        $value4 = isset($answers4->value) ? $answers4->value : null;

        return [
            'impact_studies' => $value1 ?? 0,
            'adaptation_measures' => $value2 ?? 0,
            'reversible' => $value3 ?? 0,
            'biodiversity' => $value4 ?? 0,
        ];
    }

    public function parseDataForEnergyCosts()
    {
        $answers1 = $this->answers['1155'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        return [
            'label' => ['Combustível', 'Eletricidade'],
            'data' => [$value1[282] ?? 0, $value1[283] ?? 0],
        ];
    }

    public function parseDataForHazardousWaste()
    {
        $answers1 = $this->answers['1065'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return null;
        }

        $labels = [];
        foreach ($value1 as $key => $value) {
            $label = Simple::find($key)->label;
            $labels[] = $label;
        }

        if (($labels == []) && (array_values($value1) == [])) {
            return null;
        }

        return [
            'label' => $labels,
            'data' => array_values($value1),
        ];
    }

    public function parseDataForWasteManagement()
    {
        $answers1 = $this->answers['1061'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['1062'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        return [
            'reduction_strategy' => $value1 ?? 0,
            'waste_production' => $value2 ?? 0,
        ];
    }

    public function parseDataForRadioactiveWastes()
    {
        $answers1 = $this->answers['1064'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        return [
            'status' => $value1 ?? 0,
        ];
    }

    public function parseDataForEnvironmentalPolicies()
    {
        $answers1 = $this->answers['1050'] ?? null;
        $value1 = isset($answers1->value) ? $answers1->value : null;

        $answers2 = $this->answers['1055'] ?? null;
        $value2 = isset($answers2->value) ? $answers2->value : null;

        $answers3 = $this->answers['1056'] ?? null;
        $value3 = isset($answers3->value) ? $answers3->value : null;

        $answers4 = $this->answers['1058'] ?? null;
        $value4 = isset($answers4->value) ? $answers4->value : null;

        return [
            'environmental_policy' => $value1 ?? 0,
            'emissions_reduction_policy' => $value2 ?? 0,
            'reduce_emissions' => $value3 ?? 0,
            'biodiversity_protection_policy' => $value4 ?? 0,
        ];
    }

    public function parseDataForExpenditureOnInnovation()
    {
        $answers1 = $this->answers['901'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['1150'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return $value1 + $value2;
    }

    public function parseDataForTotalWasteGenerated()
    {
        $answers1 = $this->answers['1063'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        if ($value1 == null) {
            return 0;
        }

        return $value1;
    }

    public function parseDataForReusedMaterials()
    {
        $answers1 = $this->answers['906'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['911'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return $value1 + $value2;
    }

    public function parseDataForWastedFood()
    {
        $answers1 = $this->answers['907'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['912'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return $value1 + $value2;
    }

    public function parseDataForMealsPrepared()
    {
        $answers1 = $this->answers['908'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['913'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return $value1 + $value2;
    }

    public function parseDataForCookingOilsRecycled()
    {
        $answers1 = $this->answers['909'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['914'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        if ($value1 == null && $value2 == null) {
            return 0;
        }

        return $value1 + $value2;
    }

    public function parseDataForWaterConsumptionCustomer()
    {
        $consumption = 0;

        $answers1 = $this->answers['858'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $answers2 = $this->answers['862'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        $answers3 = $this->answers['869'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return 0;
        }

        $consumption += ($value1 != null ? array_sum($value1) : 0) + ($value2 != null ? array_sum($value2) : 0);

        return calculateDivision($consumption, $value3);
    }

    public function parseDataForEnergyConsumptionValue()
    {
        $answers1 = $this->answers['1159'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return 0;
        }

        return array_sum($value1);
    }

    public function parseDataForPercentageLocalPurchases()
    {
        $answers1 = $this->answers['976'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true)[1] : null;

        $answers2 = $this->answers['977'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true)[1] : null;

        $answers3 = $this->answers['972'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true)[1] : null;

        $answers4 = $this->answers['973'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null) {
            return 0;
        }

        $val1 = $value1 + $value3;
        $val2 = $value2 + $value4;

        return calculatePercentage($val1, $val2);
    }

    public function parseDataForEnergyIntensity()
    {
        $answers1 = $this->answers['1151'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $answers2 = $this->answers['872'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        $answers3 = $this->answers['875'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

        $answers4 = $this->answers['868'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        if (($value1 == null && $value2 == null && $value3 == null) || ($value4 == null)) {
            return 0;
        }

        $total = 0;
        $total += $value1 != null ? array_sum($value1) : 0;
        $total += $value2 != null ? $this->calculationForEnerygy($value2) : 0;
        $total += $value3 != null ? $this->calculationForEnerygy($value3) : 0;

        return number_format(calculateDivision($total, $value4), 2);
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
        $answers1 = $this->answers['1151'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $answers2 = $this->answers['872'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        $answers3 = $this->answers['875'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

        $answers4 = $this->answers['869'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        if (($value1 == null && $value2 == null && $value3 == null) || ($value4 == null)) {
            return 0;
        }

        $total = 0;
        $total += $value1 != null ? array_sum($value1) : 0;
        $total += $value2 != null ? $this->calculationForEnerygy($value2) : 0;
        $total += $value3 != null ? $this->calculationForEnerygy($value3) : 0;

        return $total ? calculateDivision($total, ($value4 ?: 1)) : 0;
        
    }

    public function parseDataForEnergyEfficiency()
    {
        $answers1 = $this->answers['1151'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $answers2 = $this->answers['872'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        $answers3 = $this->answers['875'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

        $answers4 = $this->answers['1159'] ?? null;
        $value4 = isset($answers4->value) ? json_decode($answers4->value, true)[1] : null;

        if ($value1 == null && $value2 == null && $value3 == null && $value4 == null) {
            return 0;
        }

        $total = 0;
        $total += $value1 != null ? array_sum($value1) : 0;
        $total += $value2 != null ? $this->calculationForEnerygy($value2) : 0;
        $total += $value3 != null ? $this->calculationForEnerygy($value3) : 0;

        return $total ? calculatePercentage($value4, ($total ?: 1)) : 0;
    }

    public function parseDataForCarbonIntensity()
    {
        $answers1 = $this->answers['1151'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $answers2 = $this->answers['872'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        $answers3 = $this->answers['875'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

        $scope1 = $this->calculateScope1();

        if ($value1 == null && $value2 == null && $value3 == null) {
            return 0;
        }

        $total = 0;
        $total += $value1 != null ? array_sum($value1) : 0;
        $total += $value2 != null ? $this->calculationForEnerygy($value2) : 0;
        $total += $value3 != null ? $this->calculationForEnerygy($value3) : 0;

        return $total ? calculateDivision($scope1, $total) : 0;
    }

    public function parseDataForSustainableDevelopmentGoals()
    {
        $answers1 = $this->answers['1153'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return [];
        }

        return $value1;
    }

    public function parseDataForMarketsReportingUnitsOperate()
    {
        $answers1 = $this->answers['830'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        if ($value1 == null) {
            return [];
        }

        return getCountriesWhereIn(array_values($value1));
    }

    public function parseDataForNumberWorkersGender()
    {
        $answers1 = $this->answers['1024'] ?? null;
        $value1 = isset($answers1->value) ? json_decode($answers1->value, true) : null;

        $answers2 = $this->answers['1023'] ?? null;
        $value2 = isset($answers2->value) ? json_decode($answers2->value, true) : null;

        $answers3 = $this->answers['1019'] ?? null;
        $value3 = isset($answers3->value) ? json_decode($answers3->value, true) : null;

        if ($value1 == null && $value2 == null && $value3 == null) {
            return null;
        }

        $labels = ['Feminino', 'Masculino', 'Outros'];
        $data = [
            $value1 != null ? array_sum($value1) : 0,
            $value2 != null ? array_sum($value2) : 0,
            $value3 != null ? array_sum($value3) : 0,
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
