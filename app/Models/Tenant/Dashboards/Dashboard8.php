<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Answer;
use App\Models\Tenant\Category;
use App\Models\Tenant\InternalTag;
use App\Models\Tenant\QuestionOptions\Simple;
use App\Models\Tenant\QuestionnaireType;
use App\Models\Tenant\Sdg;
use App\Models\Traits\DashboardIndicatorData;

class Dashboard8
{
    use DashboardIndicatorData;

    protected $indicatorValues;
    protected $dashboardData;
    protected $charts;


    public function view($questionnaireId)
    {
        $this->setQuestionnaire($questionnaireId, true);
        $this->parseDashboardInfo();
        $data = $this->getQuestionnaireName($this->questionnaire['id']);

        return tenantView(
            request()->print == 'true' ? 'tenant.dashboards.reports.8' : 'tenant.dashboards.8',
            [
                'questionnaire' => $this->questionnaire,
                'charts' => $this->charts,
                'questionnaireinfo' => $data,
                'business_sector' => $data->company->business_sector()->first(),
                'country' => getCountriesWhereIn([$data->company->country]),
                'dashboardData' => $this->dashboardData,
                'indicatorValues' => $this->indicatorValues,
            ]
        );
    }

    protected function parseDashboardInfo()
    {
        $this->dashboardData = [
            'main' => [
                'documentation' => [
                    'list' => $this->parseMainDocumentationList()
                ],
            ],
            'report' => [
                'company' => $this->questionnaire->company()->first(),
                'business_sector' => $this->questionnaire->company->business_sector()->first(),
                'country' => getCountriesWhereIn([$this->questionnaire->company->country]),
                'colaborators' => [
                    'indicators' => 513
                ],
                'ebit' => [
                    'indicators' => 2641
                ],
                'liabilities'  => [
                    'indicators' => 2641
                ],
                'total_assets'  => [
                    'indicators' => 2643
                ],
                'expenses_human_resources'  => [
                    'indicators' => 2645
                ],
                'expenditure_raw_materials'  => [
                    'indicators' => 2647
                ],
                'capital_expenditure'  => [
                    'indicators' => 2648
                ],
                'total_value_of_organisation_debt'  => [
                    'indicators' => 2650
                ],
                'net_debt'  => [
                    'indicators' => 2651
                ],
                'amount_of_interest_expenses'  => [
                    'indicators' => 2652
                ],
                'net_profit_or_loss'  => [
                    'indicators' => 2653
                ],
                'listed_company'  => [
                    'indicators' => 4885
                ],
                'closing_price_per_share'  => [
                    'indicators' => 4886
                ],
                'value_cost_of_capital'  => [
                    'indicators' => 2656
                ],
                'number_shares_of_organization'  => [
                    'indicators' => 2657
                ],
                'value_of_equity'  => [
                    'indicators' => 2654
                ],
                'biogenic_co2_emissions' => [
                    'indicators' => 3616
                ],
                'percentage_worker_minority' => [
                    'indicators' => 6589
                ],
                'percentage_worker_minority' => [
                    'indicators' => 6589
                ],
                'improvements_result_of_the_assessment' => [
                    'indicators' => 140
                ],
                'organisation_ceased_business_relations' => [
                    'indicators' => 141
                ],
                'social_improvements_result_of_the_assessment' => [
                    'indicators' => 140
                ],
                'social_organisation_ceased_business_relations' => [
                    'indicators' => 141
                ],
                'number_of_consumer_end_user_list' => [
                    'list' => $this->parseNumberOfConsumerEndUserList()
                ],

            ],
            'climate_change' => [
                'high_climate_impact_activities' => [
                    'checkbox_lables' => $this->parseHighClimateActivities()
                ],
                'operates_in_the_fossil_fuel_sector' => [
                    'indicators' => 2141,
                ],
                'energy_consumption_monitoring' => [
                    'indicators' => 1750,
                ],
                'energy_intensity' => [
                    'unit' => 'MWh / €',
                    'is_formula' => true,
                    'formula' => '2120+787=(5/168)',
                ],
                'policy_for_energy_consumption_reduction' => [
                    'indicators' => 1865,
                ],
                'emissions_scope_1_monitoring' => [
                    'indicators' => 1757
                ],
                'main_sources_of_scope_1_emissions' => [
                    'unit' => $this->getUnitByIndicatorIds([3611, 3612, 3613, 3614]) ?? 't CO2 eq',
                    'list' => $this->parseMainSourceOfScope1Emission()
                ],
                'produces_biogenic_co_2_emissions' => [
                    'indicators' => 3615
                ],
                'monitors_biogenic_co_2_emissions' => [
                    'indicators' => 1837
                ],
                'emissions_scope_2_monitoring' => [
                    'indicators' => 1758
                ],
                'main_sources_of_scope_2_emissions' => [
                    'list' => $this->parseMainSourceOfScope2Emission(),
                    'unit' => $this->getUnitByIndicatorIds([3629, 3630, 3631]) ?? 't CO2 eq',
                ],

                'emissions_scope_3_monitoring' => [
                    'indicators' => 1759
                ],
                'knows_the_main_sources_of_scope_3_emissions' => [
                    'indicators' => 3641
                ],
                'main_sources_of_scope_3_emissions' => [
                    'list' => $this->parseMainSourceOfScope3Emission(),
                    'unit' => $this->getUnitByIndicatorIds([3643, 1843, 3659, 6491, 3644]) ?? 't CO2 eq',
                ],
                'ghg_emissions_removed_stored_natural_removal_forest' => [
                    'unit' => $this->getUnitByIndicatorIds([3660]) ?? 't CO2 eq / €',
                    'indicators' => 3660
                ],
                'ghg_emissions_removed_stored_storage_through_technology' => [
                    'unit' => $this->getUnitByIndicatorIds([3661]) ?? 't CO2 eq / €',
                    'indicators' => 3661
                ],
                'carbon_intensity' => [
                    'unit' => $this->getUnitByIndicatorIds([5674]) ?? 't CO2 eq / €',
                    'indicators' => 5674
                ],
            ],
            'polution' => [
                'list_1' => [
                    'unit' => $this->getUnitByIndicatorIds([3765, 3769]) ?? 't CO2 eq',
                    'list' => $this->parsePolutionList1()
                ],
                'amounts_of_substances_generated' => [
                    'unit' => $this->getUnitByIndicatorIds([3767, 3768]) ?? 't CO2 eq',
                    'list' => $this->parseAmountOfSubstancesList()
                ]
            ],
            'water_and_marine' => [
                'use_of_water_resources' => [
                    'unit' => $this->getUnitByIndicatorIds([2139, 2146, 3968]) ?? 't CO2 eq',
                    'list' => $this->parseUseOfWaterList()
                ],
                'medium_waste_water_is_discharged' => [
                    'checkbox_lables' => $this->parseMediumWaterDischarged()
                ],
                'source_of_water_consumed' => [
                    'checkbox_lables' => $this->parseSourceOfWaterConsumed()
                ],
                'water_intensity' => [
                    'unit' => $this->getUnitByIndicatorIds([5676]) ?? 'm3 / €',
                    'indicators' => 5676
                ]
            ],
            'biodiversity_and_ecosystems' => [
                'impacts_on_biodiversity_and_ecosystems' => [
                    'unit' => $this->getUnitByIndicatorIds([5638, 4577, 4606, 2144, 2148, 4103, 5638]) ?? 't CO2 eq',
                    'list' => $this->parseImpactOnBioAndEcoList()
                ],
                'operation_protected_areas' => [
                    'unit' => $this->getUnitByIndicatorIds([5639, 5640, 772]) ?? 'operations',
                    'list' => $this->parseOperationAreasList()
                ]
            ],
            'use_of_resources' => [
                'raw_materials_consumption' => [
                    'list' => $this->parseRawMaterialConsumptionList()
                ],
                'products_materials_used' => [
                    'unit' => $this->getUnitByIndicatorIds([4399, 4401, 4404, 4405, 4406, 4407, 4408]) ?? 't',
                    'list' => $this->parseProductsMaterialsUsedList()
                ],
                'total_waste_generated' => [
                    'unit' => $this->getUnitByIndicatorIds([25]) ?? 't',
                    'indicators' => 25
                ],
                'destination_for_radioactive' => [
                    'checkbox_lables' => $this->parseDestinationForRadio()
                ],
            ],
            'workers_in_org' => [
                'contracted_workers_turnover' => [
                    'unit' => $this->getUnitByIndicatorIds([369]) ?? '%',
                    'indicators' => 369
                ],
                'gender_pay_gap' => [
                    'unit' => $this->getUnitByIndicatorIds([5663]) ?? '%',
                    'indicators' => 5663
                ],
                'safety_and_health_at_work' => [
                    'list' => $this->parseSafetyHealthAtWorkList()
                ],
                'accidents_at_work' => [
                    'unit' => $this->getUnitByIndicatorIds([3428]) ?? 'accidents',
                    'indicators' => 3428
                ],
                'days_lost' => [
                    'unit' => $this->getUnitByIndicatorIds([2124]) ?? 'days',
                    'indicators' => 2124
                ],
                'hours_on_training' => [
                    'unit' => $this->getUnitByIndicatorIds([120]) ?? 'hours',
                    'indicators' => 120
                ],
                'workers_attended_training' => [
                    'unit' => $this->getUnitByIndicatorIds([5173]) ?? 'workers',
                    'indicators' => 5173
                ],
                'training_for_the_workers' => [
                    'list' => $this->parseTrainingForWorkersList()
                ]
            ],
            'workers_value_chain' => [
                'value_chain_workers' => [
                    'list' => $this->parseValueChainWorkersList()
                ],
                'topics_covered_in_the_policies' => [
                    'checkbox_lables' => $this->parseTopicsCoveredInPolicies()
                ]
            ],
            'affected_communities' => [
                'affected_communities' => [
                    'list' => $this->parseAffectedCommunityList()
                ],
                'topics_covered_in_the_policies' => [
                    'checkbox_lables' => $this->parsePoliciesAffectedCommunity()
                ],
                'type_of_investments_made_in_the_community' => [
                    'checkbox_lables' => $this->parseTypeOfInvestment()
                ],
                'operations_of_the_local_community' => [
                    'unit' => $this->getUnitByIndicatorIds([6569]) ?? '%',
                    'indicators' => 6569
                ],
                'financial_investment_in_the_community' => [
                    'unit' => $this->getUnitByIndicatorIds([5495]) ?? '€',
                    'indicators' => 5495
                ],
                'volunteer_hours_contracted_workers' => [
                    'unit' => $this->getUnitByIndicatorIds([5496]) ?? 'hours',
                    'indicators' => 5496
                ]
            ],
            'consumers_and_end_users' => [
                'consumers_and_end_users' => [
                    'list' => $this->parseConsumerEndUserList()
                ],
                'level_of_satisfaction' => [
                    'unit' => $this->getUnitByIndicatorIds([5351, 5352, 5353, 5354, 5355, 5356]) ?? '%',
                    'list' => $this->parseLevelOfSatisfactionList()
                ],
                'number_of_consumers_end_users' => [
                    'list' => $this->parseNumberOfConsumerList()
                ],
                'variation_of_new_consumers' => [
                    'unit' => $this->getUnitByIndicatorIds([6593]) ?? '%',
                    'indicators' => 6593
                ]
            ],
            'conduct_policies_corporate_culture' => [
                'highest_governance_body_of_the_organisation' => [
                    'checkbox_lables' => $this->parseHighestGovernanceBody()
                ],
                'annual_revenue' => [
                    'unit' => $this->getUnitByIndicatorIds([2640]) ?? '€',
                    'indicators' => 2640
                ],
                'annual_net_revenue' => [
                    'unit' => $this->getUnitByIndicatorIds([168]) ?? '€',
                    'indicators' => 168
                ],
                'annual_reporting' => [
                    'list' => $this->parseAnnualReportingList()
                ],
            ],
            'relations_with_suppliers' => [
                'characteristics_of_suppliers' => [
                    'list' => $this->parseCharOfSupplierList()
                ],
                'percentag_suppliers_environmental' => [
                    'unit' => $this->getUnitByIndicatorIds([349]) ?? '%',
                    'indicators' => 349
                ],
                'percentag_suppliers_social' => [
                    'unit' => $this->getUnitByIndicatorIds([486]) ?? '%',
                    'indicators' => 486
                ],
                'business_partners_were_terminated' => [
                    'unit' => $this->getUnitByIndicatorIds([192, 5165]) ?? 'cases',
                    'list' => $this->parseBusinessPartnerTerminatedList()
                ],
                'proceedings_against_organisation' => [
                    'unit' => $this->getUnitByIndicatorIds([193, 5168]) ?? 'cases',
                    'list' => $this->parseProceedingOrgList()
                ],
                'payments_made_to_suppliers' => [
                    'unit' => $this->getUnitByIndicatorIds([4987]) ?? '€',
                    'indicators' => 4987
                ],
                'going_to_local_suppliers' => [
                    'unit' => $this->getUnitByIndicatorIds([4989]) ?? '€',
                    'indicators' => 4989
                ],
                'payment_ratio_to_local_suppliers' => [
                    'unit' => $this->getUnitByIndicatorIds([5666]) ?? '%',
                    'indicators' => 5666
                ],
                'lawsuits_for_late_payment_to_suppliers' => [
                    'indicators' => 5008
                ],
            ],
            'corruption_prevention_detection' => [
                'corruption_prevention_detection' => [
                    'list' => $this->parseCorruptionDetectionList()
                ],
                'number_of_convictions' => [
                    'unit' => $this->getUnitByIndicatorIds([5162]) ?? 'convictions',
                    'indicators' => 5162
                ],
                'monetary_value_of_the_fines_imposed' => [
                    'unit' => $this->getUnitByIndicatorIds([5163]) ?? '€',
                    'indicators' => 5163
                ],
                'business_partners_were_terminated' => [
                    'unit' => $this->getUnitByIndicatorIds([192, 5165]) ?? 'cases',
                    'list' => $this->parseBusinessPartnerTerminatedList()
                ],
                'proceedings_against_organisation' => [
                    'unit' => $this->getUnitByIndicatorIds([193, 5168]) ?? 'legal proceedings',
                    'list' => $this->parseProceedingOrgList()
                ]
            ],
            'risk_management' => [
                'incidents_of_discrimination_sanctions' => [
                    'indicators' => 2129
                ],
                'incidents_of_discrimination' => [
                    'unit' => $this->getUnitByIndicatorIds([2130]) ?? 'incidents',
                    'indicators' => 2130
                ],
                'corruption_assessment_and_risks' => [
                    'list' => $this->parseCorruptionAssesmentRiskList()
                ],
                'types_of_risks_identified' => [
                    'list' => $this->parseTypeOfRiskIdentifiedList()
                ],
                'operation_assesed_for_risk' => [
                    'unit' => $this->getUnitByIndicatorIds([235]) ?? 'operations',
                    'indicators' => 235
                ],
                'human_rights' => [
                    'list' => $this->parseHumanRightsList()
                ],
                'operations_human_rights' => [
                    'unit' => $this->getUnitByIndicatorIds([476]) ?? '%',
                    'indicators' => 476
                ],
                'investments_human_rights' => [
                    'unit' => $this->getUnitByIndicatorIds([5336]) ?? 'investiments',
                    'indicators' => 5336
                ],
                'studies_relevant_social_impacts' => [
                    'indicators' => 5261
                ],
                'vulnerable_groups_impacted' => [
                    'indicators' => 5268
                ],
                'studies_related_to_environment' => [
                    'checkbox_lables' => $this->parseStudyRelatedToEnv()
                ],
                'organisation_economic_impacts' => [
                    'checkbox_lables' => $this->parseorgEconomicImpacts()
                ],
                'creation_of_due_diligence_processes' => [
                    'indicators' => 5285
                ],
                'affected_stakeholders' => [
                    'indicators' => 5293
                ],
                'types_affected_stakeholders' => [
                    'checkbox_lables' => $this->parseTypeOfAffectedStackHolders()
                ],
                'unethical_corporate_behavior' => [
                    'list' => $this->parseCorporateBehaviorList()
                ],
            ]

        ];

        $this->charts = [
            'main' => [
                'global_maturity' => [],
                'global_maturity_category' => [],
                'global_esg_maturity_level' => $this->parseMaturityLevelChart(),
                'global_esg_maturity_level_by_category' => $this->parseMaturityLevelCategoryChart(),
                'action_plan' => $this->parseDataForChartActionPlanChart(),
                'action_plan_table' => $this->parseDataForChartActionPlanTable(),
            ],
            'climate_change' => [
                'energy_consumption' => [
                    'base_line_year' => $this->parseBaselineYearChart(),
                    'reporting_year' => $this->parseReportingPeriodYearChart(),
                ],
                'energy_from_non_renewable_sources_consumed' => $this->parseEnergyFromNonRenewableChart(),
                'energy_from_renewable_sources_consumed' => $this->parseEnergyFromRenewableChart(),
                'ghg_scope_1_emissions' => $this->parseGHGScopeEmissionChart(5625, [3649, 20]),
                'ghg_scope_2_emissions' => $this->parseGHGScopeEmissionChart(5626, [3650, 21]),
                'ghg_scope_3_emissions' => $this->parseGHGScopeEmissionChart(5627, [3651, 22]),
            ],
            'polution' => [
                'emission_of_air_pollutants' => $this->parseEmissionOfAirPolutionChart(),
                'emission_of_pollutants_water_and_soil' => $this->parseEmissionOfWaterSoilPolutionChart(),
                'emission_of_pollutants_other' => $this->parseEmissionOfOtherPolutionChart(),
            ],
            'water_and_marine' => [
                'water_consumed' => $this->parseWaterConsumedChart(),
            ],
            'biodiversity_and_ecosystems' => [
                'operations_located' => $this->parseOperationLocatedChart(),
            ],
            'use_of_resources' => [
                'waste' => $this->parseWasteChart(),
                'waste_distribution' => $this->parseWasteDistributionChart(),
                'final_destination_applied' => $this->parseWasteDestinationChart(),
            ],
            'workers_in_org' => [
                'contracted_workers' => $this->parseContractedWorkersChart(),
                'outsourced_workers' => $this->parseOutsourcedWorkersChart(),
                'category_of_contract' => $this->parseCategoryContractChart(),
                'type_of_contract' => $this->parseTypeOfContractChart(),
                'contracted_age_distribution' => $this->parseContractedAgeDistChart(),
                'outsourced_age_distribution' => $this->parseOutsourcedAgeDistChart(),
                'workers_minorities' => $this->parseWorkersMinorityChart(),
                'middle_management_gender_contracted' => $this->parseMiddleMgtGenderContractChart(),
                'middle_management_age_contracted' => $this->parseMiddleMgtAgeContractChart(),
                'top_management_gender_contracted' => $this->parseTopMgtGenderContractChart(),
                'top_management_age_contracted' => $this->parseTopMgtAgeContractChart(),
                'middle_management_gender_outsourced' => $this->parseMiddleMgtGenderOutsourcedChart(),
                'middle_management_age_outsourced' => $this->parseMiddleMgtAgeOutsourcedChart(),
                'top_management_gender_outsourced' => $this->parseTopMgtGenderOutsourcedChart(),
                'top_management_age_outsourced' => $this->parseTopMgtAgeOutsourcedChart(),
                'hourly_earning_variation' => $this->parseHourlyEarningChart(),
                'salary_variation' => $this->parseSalaryChart(),
                'distribution_of_hiring_and_layoffs' => $this->parseHiringLayoffChart(),
                'workers_minorities_percentage' => $this->parseMinoritiesPerChart()
            ],
            'conduct_policies_corporate_culture' => [
                'highest_governance_body' => $this->parseHighestGovernanceBodyChart()
            ],
            'relations_with_suppliers' => [
                'type_of_suppliers' => $this->parseTypesOfSupplierChart(),
                'suppliers_by_continent' => $this->parseSupplierByContChart(),
                'suppliers_by_industry_sector' => $this->parseSypplierBySectorChart(),
                'timings_payment_to_suppliers' => $this->parseTimingPaymentToSupplierChart(),
            ],
            'corruption_prevention_detection' => [
                'cases_of_corruption' => $this->parseCasesOfCorruptionChart(),
            ],
            'report' => [
                'water_consumed' => $this->parseWaterConsumedReportChart(),
                'global_maturity' => $this->parseGlobaMaturityChartForeReport()
            ]
        ];

        $this->parseDashboardData();
    }

    protected function parseDashboardData()
    {
        foreach ($this->dashboardData as &$tabName) {
            foreach ($tabName as &$tabContent) {
                if (isset($tabContent['indicators']) && is_array($tabContent['indicators'])) {
                    $tabContent['values'] = arrReplaceValueFromArray($tabContent['indicators'], $this->indicatorValues);
                    $tabContent['indicatorArr'] = $this->getIndicatorArr($tabContent['indicators']);
                } else if (isset($tabContent['indicators']) && !is_array($tabContent['indicators'])) {
                    $tabContent['values'] = $this->indicatorValues[$tabContent['indicators']] ?? null;
                    $tabContent['indicatorArr'] = $this->getIndicatorArr($tabContent['indicators']);
                } else if (isset($tabContent['is_formula']) && $tabContent['is_formula'] != "") {
                    $tabContent['values'] = evalmath(replaceIndicatorWithValueInCalc($this->indicatorValues, $tabContent['formula']));
                }
            }
        }
    }

    protected function parseMainDocumentationList()
    {
        $indicators = [
            1555 => [
                'label' => __('Website')
            ],
            1557 => [
                'label' => __('Institutional Presentation')
            ],
            1597 => [
                'label' => __('Code of Ethics and Conduct')
            ],
            1663 => [
                'label' => __('Remuneration Policy')
            ],
            5129 => [
                'label' => __('Anti-Corruption and Anti-Bribery Policy')
            ],
            1676 => [
                'label' => __('Conflict of Interest Prevention Policy')
            ],
            2128 => [
                'label' => __('Due Diligence Process in relation to human rights ')
            ],
            1723 => [
                'label' => __('Supplier Policy')
            ],
            2033 => [
                'label' => __('Supplier Code of Ethics and Conduct')
            ],
            1768 => [
                'label' => __('Water Management Policy')
            ],
            5517 => [
                'label' => __('Climate change mitigation and adaptation policies')
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseMainSourceOfScope1Emission()
    {
        $indicators = [
            3611 => [
                'label' => __('Use of fossil fuels'),
            ],
            3612 => [
                'label' => __('Leakage from refrigeration equipment'),
            ],
            3613 => [
                'label' => __('Industrial processes'),
            ],
            3614 => [
                'label' => __('Other'),
            ]
        ];

        if (
            isset($this->indicatorValues['3615']) && $this->indicatorValues['3615'] == 'yes' &&
            isset($this->indicatorValues['1837']) && $this->indicatorValues['1837'] == 'yes'
        ) {
            $indicators[3616] = [
                'label' => __('Biogenic CO2 emissions derived from biomass burning or biodegradation')
            ];
        }

        return $this->parseListType($indicators);
    }

    protected function parseMainSourceOfScope2Emission()
    {
        $indicators = [
            3629 => [
                'label' => __('Electricity'),
            ],
            3630 => [
                'label' => __('Steam'),
            ],
            3631 => [
                'label' => __('Industrial heating and/or cooling'),
            ]
        ];

        return $this->parseListType($indicators);
    }


    protected function parseMainSourceOfScope3Emission()
    {
        $indicators = [
            3643 => [
                'label' => __('Produces biogenic CO2 emissions derived from biomass burning or biodegradation throughout its value chain'),
                'is_boolean' => true
            ],
            1843 => [
                'label' => __('Monitors biogenic CO2 emissions derived from biomass burning or biodegradation throughout its value chain'),
                'is_boolean' => true
            ],
            3659 => [
                'label' => __('Has (internal) greenhouse gas (GHG) removal or storage capacity'),
                'is_boolean' => true
            ],
            6491 => [
                'label' => __('Monitors the quantity of (internal) greenhouse gas (GHG) emissions removed/stored'),
                'is_boolean' => true
            ],
            3644 => [
                'label' => __('Biogenic CO2 emissions derived from biomass burning or biodegradation throughout the value chain'),
                'is_boolean' => false
            ]
        ];

        return $this->parseListType($indicators);
    }

    protected function parsePolutionList1()
    {
        $indicators = [
            3765 => [
                'label' => __('Activities of production, use, distribution, marketing or import/export of substances of concern and/or very high concern'),
                'is_boolean' => true
            ],
            3769 => [
                'label' => __('In the course of its activities, does the organisation cause emissions of pollutant'),
                'is_boolean' => true
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseAmountOfSubstancesList()
    {
        $indicators = [
            3767 => [
                'label' => __('Substances of concern'),
                'is_boolean' => false
            ],
            3768 => [
                'label' => __('Substances of very high concern'),
                'is_boolean' => false
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseUseOfWaterList()
    {
        $indicators = [
            2139 => [
                'label' => __('Located in an area(s) of high water stress'),
                'is_boolean' => true
            ],
            2146 => [
                'label' => __('Activities that include exploring seas and/or oceans'),
                'is_boolean' => true
            ],
            3968 => [
                'label' => __('Discharges wastewater'),
                'is_boolean' => true
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseHighClimateActivities()
    {
        $indicators = [
            3553 => [
                'label' => 'Agriculture, forestry and fishing'
            ],
            3554 => [
                'label' => 'Extractive industries'
            ],
            3555 => [
                'label' => 'Manufacturing industries'
            ],
            3556 => [
                'label' => 'Electricity, gas, steam and air conditioning production and distribution'
            ],
            3557 => [
                'label' => 'Collection, purification and distribution of water, sanitation, waste management and depollution'
            ],
            3558 => [
                'label' => 'Construction'
            ],
            3559 => [
                'label' => 'Wholesale and retail trade, repair of motor vehicles and motorbikes'
            ],
            3560 => [
                'label' => 'Transport and storage'
            ],
            3561 => [
                'label' => 'Accommodation and food service activities'
            ],
            3562 => [
                'label' => 'Information and communication'
            ],
            3563 => [
                'label' => 'Financial and insurance activities'
            ],
            3564 => [
                'label' => 'Real estate activities'
            ],
            3565 => [
                'label' => 'Consulting, scientific and technical activities'
            ],
            3566 => [
                'label' => 'Administrative and support service activities'
            ],
            3567 => [
                'label' => 'Public administration and defence, compulsory social security'
            ],
            3568 => [
                'label' => 'Education'
            ],
            3569 => [
                'label' => 'Human health and social work activities'
            ],
            3570 => [
                'label' => 'Artistic, entertainment and recreational activities'
            ],
            3571 => [
                'label' => 'Other service activities'
            ],
            3572 => [
                'label' => 'Activities of households as employers, undifferentiated goods- and services-producing activities of households for own use'
            ],
            3573 => [
                'label' => 'Activities of extraterritorial organisations and bodies'
            ],
        ];
        return $this->parseCheckboxList($indicators, 'array');
    }

    protected function parseMediumWaterDischarged()
    {
        $indicators = [
            3969 => [
                'label' => __('Aquatic (river, stream, sea)'),
            ],
            3970 => [
                'label' => __('Soil'),
            ],
            3971 => [
                'label' => __('Other'),
            ]
        ];
        return $this->parseCheckboxList($indicators);
    }

    protected function parseSourceOfWaterConsumed()
    {
        $indicators = [
            3963 => [
                'label' => __('Own Collection'),
            ],
            3964 => [
                'label' => __('Urban Network'),
            ],
            3965 => [
                'label' => __('Other sources'),
            ]
        ];
        return $this->parseCheckboxList($indicators);
    }

    protected function parseImpactOnBioAndEcoList()
    {
        $indicators = [
            5638 => [
                'label' => __('Organisation has operations in or near protected areas and/or areas rich in biodiversity'),
                'is_boolean' => true
            ],
            4577 => [
                'label' => __('Protected areas and/or areas of high biodiversity value'),
                'is_boolean' => true
            ],
            4606 => [
                'label' => __('Areas where species are on the IUCN (International Union for Conservation of Nature) Red List and on national conservation lists with habitats'),
                'is_boolean' => true
            ],
            2144 => [
                'label' => __('Organisation has activities that contribute to degradation, desertification, deforestation, artificiality and/or soil sealing'),
                'is_boolean' => true
            ],
            2148 => [
                'label' => __('Organisation develops activities in forest areas'),
                'is_boolean' => true
            ],
            4103 => [
                'label' => __('Organisation\'s activities have a negative impact on a biodiversity '),
                'is_boolean' => true
            ],
            5638 => [
                'label' => __('Organisation has operations in or near protected areas and/or areas rich in biodiversity'),
                'is_boolean' => true
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseOperationAreasList()
    {
        $indicators = [
            5639 => [
                'label' => __('Total number of operations of the organisation in the reporting period'),
                'is_boolean' => false
            ],
            5640 => [
                'label' => __('Number of operations in or adjacent to protected areas and/or areas rich in biodiversity, in the reporting period'),
                'is_boolean' => false
            ],
            772 => [
                'label' => __('Number of the organisation\'s operations located in sensitive, protected or high biodiversity value areas, outside environmentally protected areas, in the reporting period'),
                'is_boolean' => false
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseRawMaterialConsumptionList()
    {
        $indicators = [
            4358 => [
                'label' => __('Use of product and raw materials for the development of activities'),
            ],
            2118 => [
                'label' => __('Production of Hazardous Waste'),
            ],
            4410 => [
                'label' => __('Production of non-hazardous/non-radioactive waste'),
            ],
            4465 => [
                'label' => __('Production of radioactive waste'),
            ],
            6548 => [
                'label' => __('Produces surplus or by-products'),
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseProductsMaterialsUsedList()
    {
        $indicators = [
            4399 => [
                'label' => __('Electronics and technology')
            ],
            4401 => [
                'label' => __('Batteries and vehicles')
            ],
            4404 => [
                'label' => __('Packaging')
            ],
            4405 => [
                'label' => __('Plastic')
            ],
            4406 => [
                'label' => __('Textiles')
            ],
            4407 => [
                'label' => __('Construction and buildings')
            ],
            4408 => [
                'label' => __('Food, water and nutrients')
            ],
        ];
        return $this->parseListType($indicators);
    }

    protected function parseDestinationForRadio()
    {
        $indicators = [
            4470 => [
                'label' => __('Certified entity to manage radioactive waste')
            ],
            5637 => [
                'label' => __('Other')
            ]
        ];
        return $this->parseCheckboxList($indicators);
    }

    protected function parseSafetyHealthAtWorkList()
    {
        $indicators = [
            4537 => [
                'label' => __('Occupational Health and Safety System')
            ],
            4545 => [
                'label' => __('Workers, activities or locations not covered in OHS system')
            ],
            1714 => [
                'label' => __('Accidents at work during the reporting period')
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseTrainingForWorkersList()
    {
        $indicators = [
            4477 => [
                'label' => __('Training and capacity development'),
            ],
            2107 => [
                'label' => __('Trainning on the topics covered in the code of ethics and conduct during the reporting period'),
            ],
            5169 => [
                'label' => __('Trainning on preventing and combating corruption and bribery'),
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseValueChainWorkersList()
    {
        $indicators = [
            5147 => [
                'label' => __('Assessment of negative impacts on workers of the value chain caused or contributed to by the organisation')
            ],
            5140 => [
                'label' => __('Assessment of positive impacts on workers of the value chain caused or contributed to by the organisation')
            ],
            5257 => [
                'label' => __('Communication channels available for workers in the value chain to communicate their concerns and/or complaints in order to solve them')
            ],
            5185 => [
                'label' => __('Policies to manage the impacts, risks and opportunities related to the workers in the value chain')
            ],
        ];
        return $this->parseListType($indicators);
    }

    protected function parseTopicsCoveredInPolicies()
    {
        $indicators = [
            5224 => [
                'label' => __('Respect for human rights, including labour rights')
            ],
            5225 => [
                'label' => __('Interaction with affected stakeholders')
            ],
            5226 => [
                'label' => __('Measures to ensure mitigation of human rights impacts')
            ],
            5227 => [
                'label' => __('Elimination of discrimination (including harassment)')
            ],
            5228 => [
                'label' => __('Promotion of equal opportunities')
            ],
            5229 => [
                'label' => __('Promotion of diversity and inclusion')
            ],
            5537 => [
                'label' => __('Human Trafficking')
            ],
            5538 => [
                'label' => __('Forced labour')
            ],
            5539 => [
                'label' => __('Child labour')
            ],
        ];
        return $this->parseCheckboxList($indicators, 'array');
    }

    protected function parseAffectedCommunityList()
    {
        $indicators = [
            5405 => [
                'label' => __('Existence of affected communities ')
            ],
            5408 => [
                'label' => __('Negative impacts on the local communities identified')
            ],
            5412 => [
                'label' => __('Mechanism to receive complaints from local communities')
            ],
            5547 => [
                'label' => __('Indigenous communities affected by the organisation')
            ],
            5437 => [
                'label' => __('Policies to manage impacts, risks and opportunities related to affected communities')
            ],
            5456 => [
                'label' => __('Commitments related to inclusion and/or affirmative action for people from groups at high vulnerability risk in the affected communities')
            ],
            5456 => [
                'label' => __('Significant investment in infrastructure, service support, social projects, volunteering or donations in the community')
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parsePoliciesAffectedCommunity()
    {
        $parentIndicator = $this->indicatorValues['5437'] ?? null;
        if ($parentIndicator != 'yes') {
            return [];
        }

        $indicators = [
            5441 => [
                'label' => __('Respect for human rights, including labour rights')
            ],
            5442 => [
                'label' => __('Interaction with affected stakeholders')
            ],
            5443 => [
                'label' => __('Measures to ensure mitigation of human rights impacts')
            ],
            5444 => [
                'label' => __('Elimination of discrimination (including harassment)')
            ],
            5445 => [
                'label' => __('Promotion of equal opportunities')
            ],
            5446 => [
                'label' => __('Promotion of diversity and inclusion')
            ]
        ];
        return $this->parseCheckboxList($indicators, 'array');
    }

    protected function parseTypeOfInvestment()
    {
        $parentIndicator = $this->indicatorValues['5474'] ?? null;
        if ($parentIndicator != 'yes') {
            return [];
        }
        $indicators = [
            5475 => [
                'label' => __('Transport networks')
            ],
            5476 => [
                'label' => __('Public services')
            ],
            5477 => [
                'label' => __('Community social spaces')
            ],
            5478 => [
                'label' => __('Health Centers and Social Welfare')
            ],
            5479 => [
                'label' => __('Sports centers')
            ],
            5480 => [
                'label' => __('Workers volunteer hours')
            ],
            5481 => [
                'label' => __('Financial investment in social projects of Associations or NGOs')
            ],
            5482 => [
                'label' => __('Donations in kind to Associations or NGOs')
            ],
            5483 => [
                'label' => __('Offer of a service provided by the organisation')
            ],
            5484 => [
                'label' => __('Development of a programme with an impact on the community in partnership with Associations or NGOs')
            ],
            5485 => [
                'label' => __('Other')
            ]
        ];
        return $this->parseCheckboxList($indicators, 'array');
    }

    protected function parseConsumerEndUserList()
    {
        $indicators = [
            5340 => [
                'label' => __('Assessment of the level of satisfaction of consumers and end-users')
            ],
            5361 => [
                'label' => __('Monitoring the number of consumers and/or end-users')
            ],
            5386 => [
                'label' => __('Commitments related to inclusion and/or affirmative action for people from groups at particular risk of vulnerability regarding its end-users')
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseLevelOfSatisfactionList()
    {
        $indicators = [
            5351 => [
                'label' => __('Net Promoter Score (NPS)')
            ],
            5352 => [
                'label' => __('Customer Effort Score (CES)')
            ],
            5353 => [
                'label' => __('Customer Satisfaction Score (CSAT)')
            ],
            5354 => [
                'label' => __('Questionnaires created by the organisation')
            ],
            5355 => [
                'label' => __('Online assessment through social networks')
            ],
            5356 => [
                'label' => __('Other')
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseNumberOfConsumerList()
    {
        $indicators = [
            5362 => [
                'label' => __('At the end of the last 12 months')
            ],
            5364 => [
                'label' => __('At the beginning of the last 12 months')
            ],
            5363 => [
                'label' => __('Acquired in the past 12 months')
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseHighestGovernanceBody()
    {
        $parentIndicator = $this->indicatorValues['5437'] ?? null;

        $indicators = [
            4662 => [
                'label' => __('Board of Directors with no Executive Members')
            ],
            4663 => [
                'label' => __('Board of Directors with Executive Members')
            ],
            4664 => [
                'label' => __('Mixed')
            ],
            4665 => [
                'label' => __('Management Board')
            ]
        ];
        return $this->parseCheckboxList($indicators);
    }

    protected function parseAnnualReportingList()
    {
        $indicators = [
            3412 => [
                'label' => __('Financial report')
            ],
            3413 => [
                'label' => __('Impact report')
            ],
            3414 => [
                'label' => __('Sustainability report')
            ],
            3415 => [
                'label' => __('Activities Report')
            ],
            3416 => [
                'label' => __('Sales Report')
            ],
            3417 => [
                'label' => __('Customer Satisfaction Report')
            ],
            3418 => [
                'label' => __('Satisfaction / Welfare Report of Workers')
            ],
            3419 => [
                'label' => __('Other ')
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseCharOfSupplierList()
    {
        $indicators = [
            5012 => [
                'label' => __('Number of suppliers monitoring')
            ],
            6572 => [
                'label' => __('Suppliers identified as causing negative environmental impacts')
            ],
            6573 => [
                'label' => __('Suppliers identified as causing negative social impacts')
            ],
            5102 => [
                'label' => __('Request of information from the suppliers about their own suppliers')
            ],
            5103 => [
                'label' => __('Evidence requested from suppliers regarding good practices and impacts of the company')
            ],
            5106 => [
                'label' => __('Requirements of  annual reporting of non-financial indicators to suppliers')
            ],
            5104 => [
                'label' => __('Organisation raises awareness and empower suppliers regarding sustainability')
            ],
            5105 => [
                'label' => __('Organisation encourage its suppliers to adopt more sustainable practices and policies')
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseBusinessPartnerTerminatedList()
    {
        $indicators = [
            192 => [
                'label' => __('Corruption')
            ],
            5165 => [
                'label' => __('Bribery	')
            ],
        ];
        return $this->parseListType($indicators);
    }

    protected function parseProceedingOrgList()
    {
        $indicators = [
            193 => [
                'label' => __('Corruption')
            ],
            5168 => [
                'label' => __('Bribery	')
            ],
        ];
        return $this->parseListType($indicators);
    }

    protected function parseCorruptionDetectionList()
    {
        $indicators = [
            5136 => [
                'label' => __('Mechanisms to prevent, detect and handle allegations or situations of corruption and bribery')
            ],
            142 => [
                'label' => __('Mechanism for reporting situations of conflict of interest, corruption or bribery')
            ],
            6577 => [
                'label' => __('Mechanism guarantees the anonymity and protection of the whistleblower')
            ],
            5150 => [
                'label' => __('Situations of corruption and/or bribery')
            ],
            5161 => [
                'label' => __('Confirmed cases result in convictions')
            ],
            6578 => [
                'label' => __('Initiatives/changes as a result of allegations made')
            ],
            5166 => [
                'label' => __('Judicial proceedings related to corruption and/or bribery been initiated against the organisation or its workers')
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseCorruptionAssesmentRiskList()
    {
        $indicators = [
            5193 => [
                'label' => __('Risk assessment with a focus on corruption and/or bribery')
            ],
            5196 => [
                'label' => __('Risks identified in the corruption and/or bribery assessment')
            ],
            5208 => [
                'label' => __('Corruption and bribery factors are included in the overall risk assessments conducted by the organisation')
            ]
        ];
        return $this->parseListType($indicators);
    }


    protected function parseTypeOfRiskIdentifiedList()
    {
        $indicators = [
            5197 => [
                'label' => __('Commercial bribery')
            ],
            5198 => [
                'label' => __('Extortion and solicitation')
            ],
            5199 => [
                'label' => __('Gifts and hospitality')
            ],
            5200 => [
                'label' => __('Fees and commissions')
            ],
            5201 => [
                'label' => __('Collusion')
            ],
            5202 => [
                'label' => __('Trading of information')
            ],
            5203 => [
                'label' => __('Trading in influence')
            ],
            5204 => [
                'label' => __('Embezzlement')
            ],
            5205 => [
                'label' => __('Favouritism, nepotism, cronyism, clientelism')
            ],
            5206 => [
                'label' => __('Other')
            ]
        ];
        return $this->parseListType($indicators);
    }

    protected function parseHumanRightsList()
    {
        $indicators = [
            5328 => [
                'label' => __('Organisation performs risk assessment of human rights violations in its operations')
            ],
            5209 => [
                'label' => __('Organisation received any kind of political contributions')
            ],
            5099 => [
                'label' => __('Operations, suppliers or investments of the organisation considered to have significant risk of incidents of child labour or young workers exposed to hazardous work')
            ],
            5100 => [
                'label' => __('Incidents or cases regarding forced labour in operations or suppliers')
            ],
            5101 => [
                'label' => __('Actions were taken to resolve this or these incident(s)')
            ],
        ];
        return $this->parseListType($indicators);
    }

    protected function parseStudyRelatedToEnv()
    {
        $indicators = [
            5270 => [
                'label' => __('Climate change')
            ],
            5271 => [
                'label' => __('Local community')
            ]
        ];
        return $this->parseCheckboxList($indicators, 'array');
    }

    protected function parseorgEconomicImpacts()
    {
        $indicators = [
            5273 => [
                'label' => __('Changes in the productivity of organisations, sectors or the economy as a whole (as a consequence of the adoption of information technology)')
            ],
            5274 => [
                'label' => __('Economic development in areas with a high poverty index')
            ],
            5275 => [
                'label' => __('Improvement of social and / or environmental conditions')
            ],
            5276 => [
                'label' => __('Deterioration of social and / or environmental conditions')
            ],
            5277 => [
                'label' => __('Availability of products and services for low-income people')
            ],
            5278 => [
                'label' => __('Strengths of skills and knowledge of a professional community or geographical region')
            ],
            5279 => [
                'label' => __('Indirect jobs in supplier chains or distribution')
            ],
            5280 => [
                'label' => __('Other')
            ]
        ];
        return $this->parseCheckboxList($indicators, 'array');
    }

    protected function parseTypeOfAffectedStackHolders()
    {
        $indicators = [
            5298 => [
                'label' => __('Workers')
            ],
            5300 => [
                'label' => __('Shareholders')
            ],
            5301 => [
                'label' => __('Temporary workers')
            ],
            5303 => [
                'label' => __('Subcontracted / supplied workers')
            ],
            5306 => [
                'label' => __('External Supplies and Services (ESS\'s)')
            ],
            5307 => [
                'label' => __('Vulnerable groups')
            ],
            5308 => [
                'label' => __('Local communities')
            ],
            5309 => [
                'label' => __('NGOs')
            ],
            5310 => [
                'label' => __('Other civil society organisations')
            ],
            5311 => [
                'label' => __('Other')
            ]
        ];
        return $this->parseCheckboxList($indicators, 'array');
    }

    protected function parseCorporateBehaviorList()
    {
        $indicators = [
            5220 => [
                'label' => __('Organisation makes financial contributions to industry associations, lobby groups, political parties or similar')
            ],
            5222 => [
                'label' => __('Organisation is engage in any lobbying activities')
            ],
            5359 => [
                'label' => __('Organisation has pending or concluded legal actions regarding unfair competition and violations of antitrust laws ')
            ]
        ];
        return $this->parseListType($indicators);
    }


    /*
    CHARTS
    */
    protected function parseGlobaMaturityChart()
    {
        $taggablesLables = [
            'gestao-dos-criterios-esg',
            'identificacao-e-avaliacao',
            'integracao-na-estrategia',
            'resiliencia'
        ];
        $taggables = InternalTag::whereIn('slug', $taggablesLables)->orderBy('id', 'asc')->get();

        $data = [];
        foreach ($taggables as $internalTags) {
            $data[] = $this->questionnaire->calculatePontuation(
                fn ($q) => $q->internalTags->contains($internalTags)
            );
        }

        $data = createQuestionnaireSpiderChart($data, $taggables->pluck('name')->toArray(), $taggables);
        return $data;
    }

    protected function parseGlobaMaturityChartForeReport()
    {
        $filtersTaggablesQuestionSum = 0;
        $filtersTaggableQuestionsIds = [];
        $taggablesLables = ['environment', 'social', 'governance'];
        $subTaggablesLables = ['resiliencia', 'integracao-na-estrategia', 'identificacao-e-avaliacao', 'gestao-dos-criterios-esg'];

        $taggables = InternalTag::whereIn('slug', $taggablesLables)
            ->with('questions')
            ->orderBy('id', 'asc')->get();
        $subTaggables = InternalTag::whereIn('slug', $subTaggablesLables)
            ->with('questions')
            ->orderBy('id', 'desc')->get();

        $colors = [
            [
                'class' => 'esg2',
                'color' => color(2),
            ],
            [
                'class' => 'esg1',
                'color' => color(1),
            ],
            [
                'class' => 'esg3',
                'color' => color(3),
            ]
        ];
        $datasets = [];

        $answerQuestionnaire = Answer::where('questionnaire_id', $this->questionnaire->id)->get();
        foreach ($taggables as $taggablesKey => $internalTags) {
            $mainColor = isset($colors[$taggablesKey]) ? $colors[$taggablesKey]['color'] : color(1);
            $tempArr = [
                'label' => $internalTags->name,
                'mainColor' => $mainColor,
                'backgroundColor' => hex2rgba($mainColor, 0.175),
                'borderColor' => $mainColor,
                'pointBorderColor' => $mainColor,
                'pointHoverBackgroundColor' => $mainColor,
                'pointHoverBorderColor' => '#FFF',
                'backgroundLightColor' => hex2rgba($mainColor, 0.15),
                'pointBackgroundColor' => 'white',
                'borderWidth' => 1,
                'pointRadius' => 6,
                'colorClass' => isset($colors[$taggablesKey]) ? $colors[$taggablesKey]['class'] : 'esg1',
                'data' => []
            ];
            $mainTaggableQuestions = $internalTags->questions;
            $mainTaggableQuestionsArr = $mainTaggableQuestions->where('questionnaire_type_id', $this->questionnaire->type->id);
            $mainQuestionSum = $filtersTaggablesQuestionSum + $mainTaggableQuestionsArr->sum('weight');
            $mainTaggableQuestionsIds = $mainTaggableQuestionsArr->pluck('id')->toArray();

            foreach ($subTaggables as $subTaggable) {
                $subTaggableQuestions = $subTaggable->questions;
                $subTaggableQuestionsArr = $subTaggableQuestions->where('questionnaire_type_id', $this->questionnaire->type->id);
                $subQuestionSum = $subTaggableQuestionsArr->sum('weight');

                $questionSum = $mainQuestionSum + $subQuestionSum;
                $answerOfQuestions = array_merge(
                    $filtersTaggableQuestionsIds,
                    $mainTaggableQuestionsIds,
                    $subTaggableQuestionsArr->pluck('id')->toArray()
                );

                $answerSum = $answerQuestionnaire->whereIn('question_id', $answerOfQuestions)->sum('weight');
                $score = calculateDivision($answerSum * 100, $questionSum, 0);
                $tempArr['data'][] = $score;
            }
            $datasets[] = $tempArr;
        }

        $data['labels'] = $subTaggables->pluck('name')->toArray();
        $data['data'] = $datasets;

        return [
            'chartData' => $data,
        ];
    }

    protected function parseGlobaMaturityByCategoryChart()
    {
        $chartData = [];
        $taggables = InternalTag::whereIn('slug', ['environment', 'social', 'governance'])->orderBy('id', 'asc')->pluck('name', 'slug');
        $subTaggables = InternalTag::whereIn('slug', ['gestao-dos-criterios-esg', 'identificacao-e-avaliacao', 'integracao-na-estrategia', 'resiliencia'])->orderBy('id', 'desc')->pluck('name', 'slug');
        $chartData['optionsList1'] = $taggables->toArray();
        $chartData['optionsList2'] = $subTaggables->toArray();
        $chartData['colors'] = [
            [
                'class' => 'esg1',
                'color' => color(1),
            ],
            [
                'class' => 'esg2',
                'color' => color(2),
            ],
            [
                'class' => 'esg3',
                'color' => color(3),
            ],
            [
                'class' => 'esg5',
                'color' => color(5),
            ],
            [
                'class' => 'esg6',
                'color' => color(6),
            ]
        ];
        $chartData['catgoryArr'] = [
            'options2' => [
                'gestao-dos-criterios-esg' => [
                    'c-metricas-de-desempenho',
                    'c-objetivos-e-metas',
                    'c-recursos-para-implementar-accoes',
                    'c-planos-e-politicas',
                    'local-action'
                ],
                'identificacao-e-avaliacao' => [
                    'a-impactos-no-es',
                    'b-riscos-e-oportunidades',
                    'b-impactos-na-empresa'
                ],
                'integracao-na-estrategia' => [
                    'c-alinhamento-de-objectivos-e-metas-com-o-modelo-de-negocio',
                    'c-integracao-na-estrategia-do-negocio'
                ],
                'resiliencia' => [
                    'c-envolvimento-do-mais-alto-orgao-de-governanca',
                    'c-analise-de-cenarios',
                    'c-resposta-adaptacao-da-estrategia-aos-impactos-dos-riscos-e-oportunidades'
                ]
            ],
            'options1' => [
                'environment' => [
                    'alteracoes-climaticas',
                    'poluicao',
                    'agua-e-recursos-marinhos',
                    'biodiversidade-e-ecossistemas',
                    'utilizacao-de-recursos-e-economia-circular'
                ],
                'social' => [
                    'trabalhadores-da-organizacao',
                    'consumidores-e-utilizadores-finais',
                    'comunidades-afetadas',
                    'trabalhadores-da-cadeia-de-valor'
                ],
                'governance' => [
                    'prevencao-e-detecao-de-corrupcao-e-suborno',
                    'relacao-com-fornecedores-e-praticas-de-pagamento',
                    'gestao-de-riscos',
                    'principais-politicas-de-conduta-e-cultura-corporativa'
                ]
            ]
        ];
        return [
            'chartData' => $chartData,
        ];
    }

    protected function parseDataForChartActionPlanChart()
    {
        $businessSectorId = $this->questionnaire->company->business_sector_id;

        if (!$this->initiatives) {
            return null;
        }

        $i = 0;
        $xsMax = 0;
        $ysMax = 0;

        $initiatives = $this->initiatives->map(function ($initiative) use ($businessSectorId, &$i, &$xsMax, &$ysMax) {
            $i++;
            $indexes = array_column($this->questionnaire->categories, 'id');
            $key = array_search($initiative->category_id, $indexes, false);

            $x = round(Category::ponderation(
                $this->questionnaire->questionnaire_type_id,
                $initiative->category->parent_id ?? $initiative->category_id,
                $businessSectorId
            ));

            $xsMax = $xsMax > $x ? $xsMax : $x;

            $y = round(100 - ($this->questionnaire->categories[$key]['maturity'] ?? 0)) + (10 - $i) * 10;
            $ysMax = $ysMax > $y ? $ysMax : $y;

            return [
                'name' => $i,
                'data' => [
                    [$x, $y, 20],
                ],
            ];
        });

        $initiatives->transform(function ($initiative) use ($ysMax, $xsMax) {
            $x = round($initiative['data'][0][0] * 100 / ($xsMax ?: 1), 3) - 6;
            $y = round($initiative['data'][0][1] * 100 / ($ysMax ?: 1), 3) - 6;
            $initiative['data'][0][0] = $x > 6 ? $x : 6;
            $initiative['data'][0][1] = $y > 6 ? $y : 6;

            return $initiative;
        });

        return [
            'series' => json_encode(array_values($initiatives->toArray())),
            'xaxis' => [
                'max' => 100,
                'min' => 0,
            ],
            'yaxis' => [
                'max' => 100,
                'min' => 0,
            ],
        ];
    }

    protected function parseDataForChartActionPlanTable()
    {
        return $this->initiatives;
    }

    protected function parseBaselineYearChart()
    {
        $baseLineYear = $this->indicatorValues[3576] ?? 0;

        if ($baseLineYear == null) {
            return null;
        }

        $labels = [
            __('Renewable'),
            __('Non-renewable')
        ];

        $dataset = [
            $this->indicatorValues['3598'] ?? 0,
            $this->indicatorValues['3597'] ?? 0
        ];

        $unit = $this->getUnitByIndicatorIds([3598, 3597]);

        return [
            'year' => $baseLineYear,
            'labels' => $labels,
            'dataset' => $dataset,
            'total' => array_sum($dataset),
            'unit' => $unit ?? 'MWh'
        ];
    }

    protected function parseReportingPeriodYearChart()
    {
        $reportPeriodYear = $this->questionnaire->from->format('Y') ?? null;
        if ($reportPeriodYear == null) {
            return null;
        }

        $labels = [
            __('Renewable'),
            __('Non-renewable')
        ];

        $dataset = [
            $this->indicatorValues['787'] ?? 0,
            $this->indicatorValues['2120'] ?? 0
        ];

        $unit = $this->getUnitByIndicatorIds([787, 2120]);

        return [
            'year' => $reportPeriodYear,
            'labels' => $labels,
            'dataset' => $dataset,
            'total' => array_sum($dataset),
            'unit' => $unit ?? 'MWh'
        ];
    }

    protected function parseEnergyFromNonRenewableChart()
    {
        $indicators = [3577, 3578, 3579, 3580, 3678, 3581, 3582, 3583, 3584, 3585];
        $labels = [
            __('Coal and coal products'),
            __('Crude oil and oil products'),
            __('Compressed Natural Gas'),
            __('Hydrogen (non-green)'),
            __('Nuclear sources'),
            __('Electricity purchased externally (non-renewable sources)'),
            __('Externally procured steam'),
            __('externally purchased Heating (industrial)'),
            __('externally purchased Cooling (industrial)'),
            __('Other fuels from non-renewable sources'),
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        $unit = $this->getUnitByIndicatorIds($indicators);

        return [
            'labels' => $labels,
            'dataset' => array_values($dataset),
            'total' => array_sum($dataset),
            'unit' => $unit ?? 'MWh'
        ];
    }

    protected function parseEnergyFromRenewableChart()
    {
        $indicators = [3586, 3587, 3588, 3589, 3592, 3590, 3591, 3593, 3594];
        $labels = [
            __('Biomass'),
            __('Biogas'),
            __('Hydrogen (green)'),
            __('Electricity produced (renewable sources)'),
            __('Electricity purchased externally (renewable sources)'),
            __('Externally procured steam'),
            __('externally purchased Heating (industrial)'),
            __('externally purchased Cooling (industrial)'),
            __('Other'),
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        $unit = $this->getUnitByIndicatorIds($indicators);

        return [
            'labels' => $labels,
            'dataset' => $dataset,
            'total' => array_sum($dataset),
            'unit' => $unit ?? 'MWh'
        ];
    }

    protected function parseGHGScopeEmissionChart($baseLineIndicator, $indicators)
    {
        $baseLineYear = $this->indicatorValues[$baseLineIndicator] ?? 0;
        $reportPeriodYear = $this->questionnaire->from->format('Y');

        $labels = [
            $baseLineYear,
            $reportPeriodYear
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        $subPoint = [
            ['color' => 'bg-[#008131]', 'text' => __('Baseline year: ' . $baseLineYear)],
            ['color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: ' . $reportPeriodYear)]
        ];

        $unit = $this->getUnitByIndicatorIds($indicators);

        $subInfo = [
            ['value' => array_sum($dataset), 'unit' => $unit ?? 't CO2 eq']
        ];


        return [
            'baseLineYear' => $baseLineYear,
            'reportPeriodYear' => $reportPeriodYear,
            'labels' => $labels,
            'dataset' => $dataset,
            'subPoint' => $subPoint,
            'subInfo' => $subInfo,
            'unit' => $unit ?? 't CO2 eq',
        ];
    }

    protected function parseEmissionOfAirPolutionChart()
    {
        $chartData = [];
        $baseLineYearIndicators = [3835, 3836, 3837, 3838, 3839, 3840, 3841, 3842, 3843, 3844, 3845];
        $baseLineValueIndicators = [3855, 3856, 3857, 3858, 3859, 3860, 3861, 3862, 3863, 3864, 3865];
        $reportingValueIndicators = [3795, 3796, 3797, 3798, 3799, 3800, 3801, 3802, 3803, 3804, 3805];
        $chartData['reportPeriodYear'] = $this->questionnaire->from->format('Y') ?? null;
        $chartData['baseYears'] = arrReplaceValueFromArray($baseLineYearIndicators, $this->indicatorValues, true);
        $chartData['baseYearValues'] = arrReplaceValueFromArray($baseLineValueIndicators, $this->indicatorValues, true);
        $chartData['reportYearValues'] = arrReplaceValueFromArray($reportingValueIndicators, $this->indicatorValues, true);
        $chartData['optionsList'] = [
            __('CFCs'),
            __('HCFCs'),
            __('HBFCs'),
            __('Halons'),
            __('CH3Br'),
            __('CCl₄'),
            __('C2H3Cl3'),
            __('SO2'),
            __('NOx'),
            __('NMVOC'),
            __('PM2,5')
        ];
        $chartData['unit'] = __('t CO2 eq');
        return [
            'chartData' => $chartData,
        ];
    }

    protected function parseEmissionOfWaterSoilPolutionChart()
    {
        $chartData = [];
        $baseLineYearIndicators = [3846, 3847, 3848, 3849, 3850, 3851, 3852];
        $baseLineValueIndicators = [3866, 3867, 3868, 3869, 3870, 3871, 3872];
        $reportingValueIndicators = [3806, 3807, 3808, 3809, 3810, 3811, 3812];
        $chartData['reportPeriodYear'] = $this->questionnaire->from->format('Y') ?? null;
        $chartData['baseYears'] = arrReplaceValueFromArray($baseLineYearIndicators, $this->indicatorValues, true);
        $chartData['baseYearValues'] = arrReplaceValueFromArray($baseLineValueIndicators, $this->indicatorValues, true);
        $chartData['reportYearValues'] = arrReplaceValueFromArray($reportingValueIndicators, $this->indicatorValues, true);
        $chartData['optionsList'] = [
            __('Ammonia'),
            __('Heavy metals'),
            __('Nitrates'),
            __('Phosphates'),
            __('Phosphorus compounds'),
            __('Pesticides'),
            __('Microplastics')
        ];
        $chartData['unit'] = __('t CO2 eq');
        return [
            'chartData' => $chartData,
        ];
    }

    protected function parseEmissionOfOtherPolutionChart()
    {
        $chartData = [];
        $baseLineYearIndicators = [3853];
        $baseLineValueIndicators = [3873];
        $reportingValueIndicators = [3813];
        $chartData['reportPeriodYear'] = $this->questionnaire->from->format('Y') ?? null;
        $chartData['baseYears'] = arrReplaceValueFromArray($baseLineYearIndicators, $this->indicatorValues, true);
        $chartData['baseYearValues'] = arrReplaceValueFromArray($baseLineValueIndicators, $this->indicatorValues, true);
        $chartData['reportYearValues'] = arrReplaceValueFromArray($reportingValueIndicators, $this->indicatorValues, true);
        $chartData['optionsList'] = [
            __('Other')
        ];
        $chartData['unit'] = __('t CO2 eq');
        return [
            'chartData' => $chartData,
        ];
    }

    protected function parseWaterConsumedChart()
    {
        $baseLineYear = $this->indicatorValues[2117] ?? 0;
        $reportPeriodYear = $this->questionnaire->from->format('Y') ?? null;

        $baseLineIndicator = [4345, 4346, 4905];
        $baseLineData = arrReplaceValueFromArray($baseLineIndicator, $this->indicatorValues, true);

        $reportPeriodIndicator = [4900, 4901, 5574];
        $reportPeriodData = arrReplaceValueFromArray($reportPeriodIndicator, $this->indicatorValues, true);

        $labels = [
            __('Own Collection'),
            __('Urban Network'),
            __('Other sources')
        ];

        $dataset = [
            [
                'label' => __('Baseline year: ' . $baseLineYear),
                'backgroundColor' => '#008131',
                'datalabels' => $this->dataLabels('#008131'),
                'data' => $baseLineData
            ],
            [
                'label' => __('Reporting period: ' . $reportPeriodYear),
                'backgroundColor' => '#99CA3C',
                'datalabels' => $this->dataLabels('#99CA3C'),
                'data' => $reportPeriodData
            ]
        ];

        $subPoint = [
            ['color' => 'bg-[#008131]', 'text' => __('Baseline year: ' . $baseLineYear)],
            ['color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: ' . $reportPeriodYear)]
        ];

        $unit = $this->getUnitByIndicatorIds([4345, 4346, 4905, 4900, 4901, 5574]);

        $subInfo = [
            ['value' => array_sum($baseLineData), 'unit' => $unit ?? 'm3', 'color' => 'text-[#008131]'],
            ['value' => array_sum($reportPeriodData), 'unit' => $unit ?? 'm3', 'color' => 'text-[#99CA3C]']
        ];

        return [
            'baseLineYear' => $baseLineYear,
            'reportPeriodYear' => $reportPeriodYear,
            'labels' => $labels,
            'dataset' => $dataset,
            'subPoint' => $subPoint,
            'subInfo' => $subInfo,
            'unit' => $unit ?? 'm3',
        ];
    }

    protected function parseOperationLocatedChart()
    {
        $indicatorValue = $this->indicatorValues[2659] ?? 0;

        $labels = [__('operation'), __('no operation')];

        $dataset = [
            $indicatorValue,
            100 - $indicatorValue,
        ];

        return [
            'labels' => $labels,
            'dataset' => $dataset,
        ];
    }

    protected function parseWasteChart()
    {
        $chartData = [];
        $baseLineYearIndicators = [4443, 4416, 4467];
        $baseLineValueIndicators = [4452, 4420, 4469];
        $reportingValueIndicators = [4445, 4418, 4468];
        $chartData['reportPeriodYear'] = $this->questionnaire->from->format('Y') ?? null;
        $chartData['baseYears'] = arrReplaceValueFromArray($baseLineYearIndicators, $this->indicatorValues, true);
        $chartData['baseYearValues'] = arrReplaceValueFromArray($baseLineValueIndicators, $this->indicatorValues, true);
        $chartData['reportYearValues'] = arrReplaceValueFromArray($reportingValueIndicators, $this->indicatorValues, true);
        $chartData['optionsList'] = [__('Hazardous'), __('Non-hazardous'), __('Radioactive')];
        $chartData['unit'] = __('t');
        return [
            'chartData' => $chartData,
        ];
    }

    protected function parseWasteDistributionChart()
    {
        $indicators = [5667, 5668, 6587];
        $labels = [__('Hazardous'), __('Non-hazardous'), __('Radioactive')];
        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);
        return [
            'labels' => $labels,
            'dataset' => $dataset,
        ];
    }

    protected function parseWasteDestinationChart()
    {

        $labels = [
            __('Hazardous'),
            __('Non-hazardous')
        ];

        $dataset = [
            [
                'label' => __('Other disposal operation'),
                'backgroundColor' => '#F44336',
                'data' => arrReplaceValueFromArray([4464, 4440], $this->indicatorValues, true)
            ],
            [
                'label' => __('Landfill'),
                'backgroundColor' => '#FA9805',
                'data' => arrReplaceValueFromArray([4463, 4439], $this->indicatorValues, true)
            ],
            [
                'label' => __('Incineration'),
                'backgroundColor' => '#FBB040',
                'data' => arrReplaceValueFromArray([4462, 4438], $this->indicatorValues, true)
            ],
            [
                'label' => __('Other type of recovery operation'),
                'backgroundColor' => '#D7C66A',
                'data' => arrReplaceValueFromArray([4461, 4436], $this->indicatorValues, true)
            ],
            [
                'label' => __('Recycling'),
                'backgroundColor' => '#99CA3C',
                'data' => arrReplaceValueFromArray([4460, 4435], $this->indicatorValues, true)
            ],
            [
                'label' => __('Preparation for reuse'),
                'backgroundColor' => '#C4EB7A',
                'data' => arrReplaceValueFromArray([4459, 4434], $this->indicatorValues, true)
            ]
        ];

        $unit = $this->getUnitByIndicatorIds([4464, 4440, 4463, 4439, 4462, 4438, 4461, 4436, 4460, 4435, 4459, 4434]);

        return [
            'labels' => $labels,
            'dataset' => $dataset,
            'unit' => $unit ?? 't',
        ];
    }

    protected function parseContractedWorkersChart()
    {
        $indicators = [66, 65, 67];
        $labels = [__('Male'), __('Female'), __('Other')];
        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        $unit = $this->getUnitByIndicatorIds($indicators);

        return [
            'labels' => $labels,
            'dataset' => $dataset,
            'unit' => $unit ?? 'workers',
        ];
    }

    protected function parseOutsourcedWorkersChart()
    {
        $indicators = [4221, 4220, 3541];
        $labels = [__('Male'), __('Female'), __('Other')];
        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        $unit = $this->getUnitByIndicatorIds($indicators);

        return [
            'labels' => $labels,
            'dataset' => $dataset,
            'unit' => $unit ?? 'workers',
        ];
    }

    protected function parseCategoryContractChart()
    {
        $labels = [
            __('Permanent'),
            __('Temporary'),
            __('Non-guaranteed hours')
        ];

        $dataset = [
            [
                'label' => __('Male'),
                'backgroundColor' => '#21A6E8',
                'datalabels' => $this->dataLabels('#21A6E8'),
                'data' => arrReplaceValueFromArray([4112, 4119, 4120], $this->indicatorValues, true)
            ],
            [
                'label' => __('Female'),
                'backgroundColor' => '#C5A8FF',
                'datalabels' => $this->dataLabels('#C5A8FF'),
                'data' => arrReplaceValueFromArray([4107, 4108, 4109], $this->indicatorValues, true)
            ],
            [
                'label' => __('Other'),
                'backgroundColor' => '#02C6A1',
                'datalabels' => $this->dataLabels('#02C6A1'),
                'data' => arrReplaceValueFromArray([4126, 4130, 4134], $this->indicatorValues, true)
            ]
        ];

        return [
            'labels' => $labels,
            'dataset' => $dataset,
        ];
    }

    protected function parseTypeOfContractChart()
    {
        $labels = [
            __('Part-time'),
            __('Full-time')
        ];

        $dataset = [
            [
                'label' => __('Male'),
                'backgroundColor' => '#21A6E8',
                'datalabels' => $this->dataLabels('#21A6E8'),
                'data' => arrReplaceValueFromArray([4149, 4148], $this->indicatorValues, true)
            ],
            [
                'label' => __('Female'),
                'backgroundColor' => '#C5A8FF',
                'datalabels' => $this->dataLabels('#C5A8FF'),
                'data' => arrReplaceValueFromArray([4146, 4143], $this->indicatorValues, true)
            ],
            [
                'label' => __('Other'),
                'backgroundColor' => '#02C6A1',
                'datalabels' => $this->dataLabels('#02C6A1'),
                'data' => arrReplaceValueFromArray([4151, 4150], $this->indicatorValues, true)
            ]
        ];

        return [
            'labels' => $labels,
            'dataset' => $dataset,
        ];
    }

    protected function parseContractedAgeDistChart()
    {
        $indicators = [70, 71, 72];
        $labels = [
            __('< 30'),
            __('30 - 50'),
            __('> 50')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }

    protected function parseOutsourcedAgeDistChart()
    {
        $indicators = [4224, 4225, 4226];
        $labels = [
            __('< 30'),
            __('30 - 50'),
            __('> 50')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }

    protected function parseWorkersMinorityChart()
    {
        $indicators = [4186, 4188, 4210, 4192, 4194, 4197, 4190];
        $labels = [
            __('Indigenous'),
            __('Black'),
            __('Gypsy ethnicity'),
            __('LGBTQIA+'),
            __('Persons with special needs'),
            __('Religious minorities'),
            __('Others')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        return [
            'labels' => $labels,
            'dataset' => array_values($dataset)
        ];
    }

    protected function parseMiddleMgtGenderContractChart()
    {
        $indicators = [434, 433, 435];
        $labels = [
            __('Male'),
            __('Female'),
            __('Other')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }

    protected function parseMiddleMgtAgeContractChart()
    {
        $indicators = [430, 431, 432];
        $labels = [
            __('< 30'),
            __('30 - 50'),
            __('> 50')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }

    protected function parseTopMgtGenderContractChart()
    {
        $indicators = [81, 80, 82];
        $labels = [
            __('Male'),
            __('Female'),
            __('Other')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }

    protected function parseTopMgtAgeContractChart()
    {
        $indicators = [4157, 4158, 4159];
        $labels = [
            __('< 30'),
            __('30 - 50'),
            __('> 50')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }


    protected function parseMiddleMgtGenderOutsourcedChart()
    {
        $indicators = [5618, 5617, 5619];
        $labels = [
            __('Male'),
            __('Female'),
            __('Other')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }

    protected function parseMiddleMgtAgeOutsourcedChart()
    {
        $indicators = [4176, 4179, 4181];
        $labels = [
            __('< 30'),
            __('30 - 50'),
            __('> 50')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }

    protected function parseTopMgtGenderOutsourcedChart()
    {
        $indicators = [4259, 4258, 4260];
        $labels = [
            __('Male'),
            __('Female'),
            __('Other')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }

    protected function parseTopMgtAgeOutsourcedChart()
    {
        $indicators = [4262, 4263, 4264];
        $labels = [
            __('< 30'),
            __('30 - 50'),
            __('> 50')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }

    protected function parseHourlyEarningChart()
    {
        $indicators = [4371, 4370, 4372];
        $labels = [
            __('Male'),
            __('Female'),
            __('Other')
        ];

        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        $unit = $this->getUnitByIndicatorIds($indicators);

        return [
            'labels' => $labels,
            'dataset' => $dataset,
            'unit' => $unit ?? '€',
        ];
    }

    protected function parseSalaryChart()
    {
        $labels = [__('Operatopns'), __('Middle Management'), __('Top Management')];

        $dataset = [
            [
                'label' => __('Male'),
                'backgroundColor' => '#21A6E8',
                'datalabels' => $this->dataLabels('#21A6E8'),
                'data' => arrReplaceValueFromArray([4402, 4419, 4430], $this->indicatorValues, true)
            ],
            [
                'label' => __('Female'),
                'backgroundColor' => '#C5A8FF',
                'datalabels' => $this->dataLabels('#C5A8FF'),
                'data' => arrReplaceValueFromArray([4419, 4417, 4425], $this->indicatorValues, true)
            ],
            [
                'label' => __('Other'),
                'backgroundColor' => '#02C6A1',
                'datalabels' => $this->dataLabels('#02C6A1'),
                'data' => arrReplaceValueFromArray([6555, 4421, 4433], $this->indicatorValues, true)
            ]
        ];

        $unit = $this->getUnitByIndicatorIds([4402, 4419, 4430, 4419, 4417, 4425, 6555, 4421, 4433]);

        return [
            'labels' => $labels,
            'dataset' => $dataset,
            'unit' => $unit ?? '€',
        ];
    }

    protected function parseHiringLayoffChart()
    {
        $labels = [__('Hiring'), __('Layoffs')];

        $dataset = [
            [
                'label' => __('Less than 30'),
                'backgroundColor' => '#E86321',
                'datalabels' => $this->dataLabels('#E86321'),
                'data' => arrReplaceValueFromArray([359, 366], $this->indicatorValues, true)
            ],
            [
                'label' => __('Between 30 and 50'),
                'backgroundColor' => '#FBB040',
                'datalabels' => $this->dataLabels('#FBB040'),
                'data' => arrReplaceValueFromArray([360, 367], $this->indicatorValues, true)
            ],
            [
                'label' => __('More than 50'),
                'backgroundColor' => '#FDC97B',
                'datalabels' => $this->dataLabels('#FDC97B'),
                'data' => arrReplaceValueFromArray([361, 368], $this->indicatorValues, true)
            ]
        ];

        return [
            'labels' => $labels,
            'dataset' => $dataset,
        ];
    }

    protected function parseMinoritiesPerChart()
    {
        $indicatorValue = $this->indicatorValues[6589] ?? 0;

        $labels = [__('Minorities'), __('Not In Minorities')];

        $dataset = [
            $indicatorValue,
            100 - $indicatorValue,
        ];

        return [
            'labels' => $labels,
            'dataset' => $dataset,
        ];
    }

    protected function parseHighestGovernanceBodyChart()
    {
        $indicators = [446, 445, 447];
        $labels = [__('Male'), __('Female'), __('Other')];
        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);

        $unit = $this->getUnitByIndicatorIds($indicators);

        return [
            'labels' => $labels,
            'dataset' => $dataset,
            'unit' => $unit ?? 'workers',
        ];
    }

    protected function parseTypesOfSupplierChart()
    {
        $indicators = [130, 131, 132, 133];
        $labels = [
            __('Organisations that provide a product'),
            __('Organisations that provide a service'),
            __('People who provide a product'),
            __('People who provide a service')
        ];
        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);
        return [
            'labels' => $labels,
            'dataset' => $dataset,
        ];
    }

    protected function parseSupplierByContChart()
    {
        $indicators = [5046, 5047, 5048, 5049, 5050, 5608, 5051];
        $labels = [
            __('Europe'),
            __('Asia'),
            __('Africa'),
            __('North America'),
            __('South America'),
            __('Central America'),
            __('Oceania')
        ];
        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);
        return [
            'labels' => $labels,
            'dataset' => $dataset,
        ];
    }

    protected function parseSypplierBySectorChart()
    {
        $indicators = [148, 149, 150, 151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 5045];
        $labels = [
            __('Consumer Goods'),
            __('Extractives & Mineral Processing'),
            __('Financials'),
            __('Food & Beverage'),
            __('Health Care'),
            __('Infrastructure'),
            __('Renewable Resources & Alternative Energy'),
            __('Resource Transformation'),
            __('Services'),
            __('Real Estate'),
            __('Information Technology'),
            __('Construction & Engineering'),
            __('Logistics/Transportation'),
            __('Other'),
        ];
        $dataset = arrReplaceValueFromArray($indicators, $this->indicatorValues, true);
        return [
            'labels' => $labels,
            'dataset' => $dataset,
        ];
    }

    protected function parseTimingPaymentToSupplierChart()
    {

        $labels = [__('Up to 30 days'), __('30 - 60 days'), __('60 - 90 days'), __('More than 90 days')];

        $dataset = [
            [
                'label' => __('Regular suppliers'),
                'backgroundColor' => '#058894',
                'datalabels' => $this->dataLabels('#058894'),
                'data' => arrReplaceValueFromArray([4992, 4993, 4994, 4996], $this->indicatorValues, true)
            ],
            [
                'label' => __('Small and medium suppliers'),
                'backgroundColor' => '#83D2DA',
                'datalabels' => $this->dataLabels('#83D2DA'),
                'data' => arrReplaceValueFromArray([4998, 4999, 5004, 5006], $this->indicatorValues, true)
            ]
        ];

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }

    protected function parseCasesOfCorruptionChart()
    {

        $labels = [__('Reported'), __('Confirmed'), __('Dismissed or punished')];

        $dataset = [
            [
                'label' => __('Corruption'),
                'backgroundColor' => '#058894',
                'datalabels' => $this->dataLabels('#058894'),
                'data' => arrReplaceValueFromArray([189, 190, 191], $this->indicatorValues, true)
            ],
            [
                'label' => __('Bribery'),
                'backgroundColor' => '#83D2DA',
                'datalabels' => $this->dataLabels('#83D2DA'),
                'data' => arrReplaceValueFromArray([5154, 5157, 5159], $this->indicatorValues, true)
            ]
        ];

        return [
            'labels' => $labels,
            'dataset' => $dataset
        ];
    }

    protected function parseWaterConsumedReportChart()
    {
        $chartData = [];
        $baseLineValueIndicators = [4345, 4346, 4905];
        $reportingValueIndicators = [4900, 4901, 5574];
        $chartData['baseYears'] = $this->indicatorValues[2117] ?? 0;;
        $chartData['baseYearValues'] = arrReplaceValueFromArray($baseLineValueIndicators, $this->indicatorValues, true);
        $chartData['reportYearValues'] = arrReplaceValueFromArray($reportingValueIndicators, $this->indicatorValues, true);
        $chartData['optionsList'] = [
            __('Own Collection'),
            __('Urban Network'),
            __('Other sources')
        ];
        $chartData['unit'] = __('m3');
        return [
            'chartData' => $chartData,
        ];
    }

    protected function parseNumberOfConsumerEndUserList()
    {
        $indicators = [
            5362 => [
                'label' => __('At the end of the last 12 months'),
            ],
            5364 => [
                'label' => __('At the beginning of the last 12 months'),
            ],
            5363 => [
                'label' => __('Acquired in the past 12 months'),
            ]
        ];
        return $this->parseListType($indicators);
    }
}
