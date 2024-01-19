<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Traits\Dashboard;

class Dashboard24
{
    use Dashboard;

    protected $data;

    /**
     * Rander view
     */
    public function view($questionnaireId)
    {
        $this->getDataByQuestionnaire($questionnaireId);

        return tenantView(
            request()->print == 'true' ? 'tenant.dashboards.reports.24' : 'tenant.dashboards.24',
            [
                'questionnaire' => $this->questionnaire,
                'action_plan' => $this->parseDataForChartActionPlan(),
                'action_plan_table' => $this->parseDataForChartActionPlanTable(),
                'readiness' => $this->parseDataForReadiness(),
                'documents' => $this->parseDataForDocuments(),
                // Enviroment
                    'atmospheric_pollutants' => $this->parseDataForCharts([5623], [5621, 2153], true),
                    'consumption_expenditure' => $this->parseDataForChartsWithStaticLabels(
                        [__('Refrigeration'), __('Air Conditioning')],
                        [8058, 8059]
                    ),
                    'refrigerant_gas' => $this->parseDataForRefrigerantGas(),
                    'renewable_guarantee' => [[
                        'label' => __('Organization has a 100% renewable Guarantee of Origin (GoO) contract'),
                        'status' => $this->getValueByIndicatorId(8065, 'checkbox')
                    ]],
                    'groups' => [
                        [
                            'label' => __('Group 01'),
                            'status' => $this->getValueByIndicatorId(8066, 'checkbox')
                        ],
                        [
                            'label' => __('Group 02'),
                            'status' => $this->getValueByIndicatorId(8067, 'checkbox')
                        ],
                        [
                            'label' => __('Group 03'),
                            'status' => $this->getValueByIndicatorId(8068, 'checkbox')
                        ],
                        [
                            'label' => __('Group 04'),
                            'status' => $this->getValueByIndicatorId(8069, 'checkbox')
                        ],
                        [
                            'label' => __('The company does not appear in the reference table'),
                            'status' => $this->getValueByIndicatorId(8070, 'checkbox')
                        ],
                    ],
                    'fuel_type_vehicles' => [
                        [
                            'label' => __('Petrol (E5, E10)'),
                            'status' => $this->getValueByIndicatorId(8073, 'checkbox')
                        ],
                        [
                            'label' => __('Diesel (A, B, C, B7, B10, B20), CNG, LNG'),
                            'status' => $this->getValueByIndicatorId(8074, 'checkbox')
                        ],
                        [
                            'label' => __('Petrol (E85), Diesel (B30), LPG (Liquefied Petroleum Gas, Autogas)'),
                            'status' => $this->getValueByIndicatorId(8075, 'checkbox')
                        ],
                        [
                            'label' => __('Petrol (E100), Diesel (B100, XTL), H2 (Hydrogen)'),
                            'status' => $this->getValueByIndicatorId(8076, 'checkbox')
                        ],
                        [
                            'label' => __('No consumption'),
                            'status' => $this->getValueByIndicatorId(8077, 'checkbox')
                        ],
                    ],
                    'fuel_type_fix' => [
                        [
                            'label' => __('Fuel oil, petroleum coke'),
                            'status' => $this->getValueByIndicatorId(8079, 'checkbox')
                        ],
                        [
                            'label' => __('Butane gas, propane, Generic LPG, Diesel oil (B, C)'),
                            'status' => $this->getValueByIndicatorId(8080, 'checkbox')
                        ],
                        [
                            'label' => __('Coal, Diesel (B7, B10, B20, B30)'),
                            'status' => $this->getValueByIndicatorId(8081, 'checkbox')
                        ],
                        [
                            'label' => __('Natural Gas, Diesel (B100)'),
                            'status' => $this->getValueByIndicatorId(8082, 'checkbox')
                        ],
                        [
                            'label' => __('No consumption'),
                            'status' => $this->getValueByIndicatorId(8083, 'checkbox')
                        ],
                    ],
                    'energy_consumption_expenditure_fix' => $this->getValueByIndicatorId(8078),
                    'facilities_certified' => $this->getValueByIndicatorId(8084),
                    'electricity_consumption' => $this->getValueByIndicatorId(8154),
                    'gas_consumption' => $this->getValueByIndicatorId(8085),
                    'renewable_energy' => $this->getValueByIndicatorId(8086),
                    'utilisation_rate' => $this->getValueByIndicatorId(8087),
                    'circular_economy' => [
                        [
                            'label' => __('Reduce the use of non-renewable natural resources, by using reused or recycled raw materials'),
                            'status' => $this->getValueByIndicatorId(8134, 'checkbox')
                        ],
                        [
                            'label' => __('Guarantee that the energy consumed comes from renewable sources'),
                            'status' => $this->getValueByIndicatorId(8138, 'checkbox')
                        ],
                        [
                            'label' => __('Prioritise the reduction of water consumption in all processes, and ensure proper treatment for reuse and return to the environment'),
                            'status' => $this->getValueByIndicatorId(8139, 'checkbox')
                        ],
                        [
                            'label' => __('Minimise waste generation as far as possible, encourage reuse, facilitate recycling and promote traceability to its final destination'),
                            'status' => $this->getValueByIndicatorId(8140, 'checkbox')
                        ],
                        [
                            'label' => __('Promote innovation and the overall efficiency of production processes, through the use of high-performance equipment, alternative fuels or environmental management systems'),
                            'status' => $this->getValueByIndicatorId(8141, 'checkbox')
                        ],
                        [
                            'label' => __('Promote innovative forms of sustainable consumption to optimise processes and minimise resources'),
                            'status' => $this->getValueByIndicatorId(8142, 'checkbox')
                        ],
                        [
                            'label' => __('Promote innovative forms of sustainable consumption to optimise processes and minimise resources'),
                            'status' => $this->getValueByIndicatorId(8143, 'checkbox')
                        ],
                        [
                            'label' => __('Disseminate the importance of the transition from a linear economy to a circular economy'),
                            'status' => $this->getValueByIndicatorId(8144, 'checkbox')
                        ]
                    ],
                    'company_promotes' => [
                        [
                            'label' => __('Company does not take these attributes into account in its products'),
                            'status' => $this->getValueByIndicatorId(8146, 'checkbox')
                        ]
                    ],
                    'company_facilitates' => [
                        [
                            'label' => __('Company only has these channels with some of these actors'),
                            'status' => $this->getValueByIndicatorId(8181, 'checkbox')
                        ]
                    ],
                    'recognised_certificate' => [
                        [
                            'label' => __('The company has a recognised environmental certification'),
                            'status' => $this->getValueByIndicatorId(8088, 'checkbox')
                        ]
                    ],
                    'renewable_energy_generation' => $this->getValueByIndicatorId(8071),
                    'energy_consumption_expenditure' => $this->getValueByIndicatorId(8072),
                    'ozone_layer_depleting' => $this->parseDataForCharts([5624], [5622, 2154], true),
                    'GHG_emission' => $this->parseDataForCharts([5625], [3649, 20], true),
                    'GHG_emission2' => $this->parseDataForCharts([5626], [3650, 21], true),
                    'GHG_emission3' => $this->parseDataForCharts([5627], [3651, 22], true),
                    'energy_consumption_baseline_year' => $this->getValueByIndicatorId(3576),
                    'energy_consumption_baseline' => $this->parseDataForChartsWithStaticLabels(
                        [__('Renewable'), __('Non-renewable')],
                        [3598, 3597]
                    ),
                    // TODO: Refactor this functionality. For now we use questionnaire column `from` to display year
                    'energy_consumption_reporting_year' => $this->questionnaire->from->format('Y'),
                    'energy_consumption_reporting' => $this->parseDataForChartsWithStaticLabels(
                        [__('Renewable'), __('Non-renewable')],
                        [787, 2120]
                    ),
                    'carbon_intensity' => $this->getValueByIndicatorId(5674),
                    'energy_intensity' => $this->getValueByIndicatorId(5675),
                    'recycled_waste' => $this->getValueByIndicatorId(3429),
                    'waste_sent_for_disposal' => $this->getValueByIndicatorId(3430),
                    'hazardous_waste' => $this->getValueByIndicatorId(3431),
                    'radioactive_waste' => $this->getValueByIndicatorId(3432),
                    'waste_produced' => $this->parseDataForWasteProduced(),
                    'water_consumption' => $this->parseDataForWaterConsumption(),
                    'water_consumed' => $this->parseDataForCharts([2117], [3062, 3061], true),
                    'water_recycle_reused' => $this->getValueByIndicatorId(2135),
                    'water_intensity' => $this->getValueByIndicatorId(5676),
                    'biodiversity_impact' => $this->parseDataForBiodiversityImpact(),
                    'organization_activity_impact' => $this->parseDataForOrganizationActivityImpact(),
                    'raw_matrial' => [[
                        'label' => __('Carried out new construction and/or major renovation work'),
                        'status' => $this->getValueByIndicatorId(2151, 'checkbox')
                    ]],
                    'total_building_matrial_used' => $this->getValueByIndicatorId(2152),
                    'biological_origin_matrials' => $this->getValueByIndicatorId(3464),

                // Social
                    'contract_workers' => $this->parseDataForChartsWithStaticLabels(
                        [__('Man'), __('Women'), __('Other')],
                        [66, 65, 67]
                    ),
                    'outsource_workers' => $this->parseDataForChartsWithStaticLabels(
                        [__('Man'), __('Women'), __('Other')],
                        [208, 207, 3541]
                    ),
                    'type_of_contract' => $this->parseDataForTypeOfContract(),
                    'contract_workers_distribution' => $this->parseDataForChartsWithStaticLabels(
                        [__('Less than 30'), __('Between 30 and 50'), __('More than 50')],
                        [70, 71, 72]
                    ),
                    'middle_management' => $this->parseDataForChartsWithStaticLabels(
                        [__('Man'), __('Women'), __('Other')],
                        [434, 433, 435]
                    ),
                    'top_management' => $this->parseDataForChartsWithStaticLabels(
                        [__('Man'), __('Women'), __('Other')],
                        [81, 80, 82]
                    ),
                    'worker_satisfaction' => [[
                        'label' => __('Performance appraisal process for contract workers'),
                        'status' => $this->getValueByIndicatorId(4447, 'checkbox')
                    ]],
                    'worker_turnover' => $this->getValueByIndicatorId(369),
                    'gender_paygap' => $this->getValueByIndicatorId(5663),
                    'bonuses' => $this->getValueByIndicatorId(8089),
                    'fulltime_employee_bonuses' => $this->getValueByIndicatorId(8090),
                    'customers_survey' => $this->getValueByIndicatorId(8092),
                    'employees_suffered_accidents' => $this->getValueByIndicatorId(8094),
                    // 'performance_appraisal' => [[
                    //     'label' => __('Performance evaluation process for contract workers'),
                    //     'status' => $this->getValueByIndicatorId(4447, 'checkbox')
                    // ]],
                    'accident_work' => [[
                        'label' => __('Accidents at work during the reporting period'),
                        'status' => $this->getValueByIndicatorId(1714, 'checkbox')
                    ]],
                    'numberof_accident' => $this->getValueByIndicatorId(3428),
                    'day_lost_by_accident' => $this->getValueByIndicatorId(2124),
                    'traning_for_works' => $this->parseDataForTraningForWorks(),
                    'number_of_hours' => $this->getValueByIndicatorId(120),
                    'avg_training_expenditure' => $this->getValueByIndicatorId(8091),
                    'recognised_certification' => [[
                        'label' => __('Company has recognised certification in the social aspect'),
                        'status' => $this->getValueByIndicatorId(8093, 'checkbox')
                    ]],
                // Governance
                    'highest_governance' => $this->getValueByIndicatorId(5610),
                    'no_executive_members' => $this->getValueByIndicatorId(4662),
                    'with_executive_members' => $this->getValueByIndicatorId(4663),
                    'mixed' => $this->getValueByIndicatorId(4664),
                    'management_board' => $this->getValueByIndicatorId(4665),
                    'high_governance_body' => $this->parseDataForChartsWithStaticLabels(
                        [__('Man'), __('Women'), __('Other')],
                        [424, 423, 425]
                    ),
                    'significant_incidents' => $this->parseDataForSignificantIncidents(),
                    'amount_fines_imposed' => $this->getValueByIndicatorId(2126),
                    'incidents_discrimination' => $this->getValueByIndicatorId(2130),
                    'international_corruption' => $this->getValueByIndicatorId(8097),
                    'contributions_political' => $this->getValueByIndicatorId(8098),
                    'amount_fines_penalties' => $this->getValueByIndicatorId(8554),
                    'total_suppliers' => $this->getValueByIndicatorId(129),
                    'external_suppliers' => $this->getValueByIndicatorId(8095),
                    'supplier_turnover' => $this->getValueByIndicatorId(8096),
                    'sales_modified_products' => $this->getValueByIndicatorId(8099),
                    'expenditure_corporate_product' => $this->getValueByIndicatorId(8100),
                    'corporate_product_sold' => $this->getValueByIndicatorId(8101),
                    'products_recalled' => $this->getValueByIndicatorId(8102),
                    'product_withdrawn' => $this->getValueByIndicatorId(8103),
                    'product_services' => [
                        [
                            'label' => __('Increase the eco-efficiency of the client`s production or operations.'),
                            'value' => $this->getValueByIndicatorId(8105, 'checkbox'),
                            'unit' => '%'
                        ],
                        [
                            'label' => __('Development and use of clean technologies'),
                            'value' => $this->getValueByIndicatorId(8106, 'checkbox'),
                            'unit' => '%'
                        ],
                        [
                            'label' => __('Increase fuel consumption efficiency'),
                            'value' => $this->getValueByIndicatorId(8107, 'checkbox'),
                            'unit' => '%'
                        ],
                        [
                            'label' => __('Financing of products/services relevant to ESG aspects'),
                            'value' => $this->getValueByIndicatorId(8108, 'checkbox'),
                            'unit' => '%'
                        ]
                    ],
                    'certificate_governance' => [
                        [
                            'label' => __('Company has recognised certification in the governance aspect'),
                            'status' => $this->getValueByIndicatorId(8111, 'checkbox'),
                        ]
                    ],
                    'annual_revenue' => $this->getValueByIndicatorId(2640),
                    'annual_net_revenue' => $this->getValueByIndicatorId(168),
                    'annual_reporting' => $this->parseDataForAnnualReporting(),
                    'annual_investment' => $this->getValueByIndicatorId(8104),
                    'expenditure_maintenance' => $this->getValueByIndicatorId(8110),
                    'percentage_turnover' => $this->getValueByIndicatorId(8109),
                    'sdg' => $this->parseDataForSDG(),
                    'logos' => $this->parseDataForLogos(),
                // Extra
                    'company_electricity_supply' => [
                        [
                            'label' => __('Group 01'),
                            'status' => $this->getValueByIndicatorId(8114, 'checkbox')
                        ],
                        [
                            'label' => __('Group 02'),
                            'status' => $this->getValueByIndicatorId(8115, 'checkbox')
                        ],
                        [
                            'label' => __('Group 03'),
                            'status' => $this->getValueByIndicatorId(8116, 'checkbox')
                        ],
                        [
                            'label' => __('Group 04'),
                            'status' => $this->getValueByIndicatorId(8117, 'checkbox')
                        ],
                        [
                            'label' => __('Group 05'),
                            'status' => $this->getValueByIndicatorId(8118, 'checkbox')
                        ],
                        [
                            'label' => __('Group 06'),
                            'status' => $this->getValueByIndicatorId(8119, 'checkbox')
                        ],
                        [
                            'label' => __('Company not listed in the reference table'),
                            'status' => $this->getValueByIndicatorId(8120, 'checkbox')
                        ],
                    ],
                    'fuel_tyoe_company' => [
                        [
                            'label' => __('Petrol (E5, E10)'),
                            'status' => $this->getValueByIndicatorId(8125, 'checkbox')
                        ],
                        [
                            'label' => __('Diesel (A, B, C, B7, B10, B20), CNG, LNG'),
                            'status' => $this->getValueByIndicatorId(8126, 'checkbox')
                        ],
                        [
                            'label' => __('Petrol (E85), Diesel (B30), LPG (Liquefied Petroleum Gas, Autogas)'),
                            'status' => $this->getValueByIndicatorId(8127, 'checkbox')
                        ],
                        [
                            'label' => __('Petrol (E100), Diesel (B100, XTL), H2 (Hydrogen)'),
                            'status' => $this->getValueByIndicatorId(8128, 'checkbox')
                        ]
                    ],
                    'electricity_consumption_company' => $this->getValueByIndicatorId(8113),
                    'capacity_photovoltaic' => $this->getValueByIndicatorId(8121),
                    'capacity_renewable_energy' => $this->getValueByIndicatorId(8122),
                    'energy_consumption_per_trip' => $this->getValueByIndicatorId(8123),
                    'energy_consumption_per_trip_cost' => $this->getValueByIndicatorId(8124),
                    'energy_consumption_installation' => $this->getValueByIndicatorId(8129),
                    'energy_consumption_installation_cost' => $this->getValueByIndicatorId(8130),
                    'ac_consumption' => $this->getValueByIndicatorId(8131),
                    'ac_consumption_cost' => $this->getValueByIndicatorId(8132),
                    'gas_used_ac' => [
                        [
                            'label' => __('HFC-23, SF6'),
                            'status' => $this->getValueByIndicatorId(8133, 'checkbox')
                        ],
                        [
                            'label' => __('HFC-125, HFC-143a, HFC-227ea, HFC-236fa, R-404A, R-407A, R-407B, R-410A, R-410B, R-413A, R-417A, R-417B, R-422A, R-422D, R-424A, R-427A, R-428A, R-434A, R-438A, R-452A, R-507A'),
                            'status' => $this->getValueByIndicatorId(8145, 'checkbox')
                        ],
                        [
                            'label' => __('HFC-134, HFC-134a, HFC-236cb, HFC-236ea, HFC-245fa, HFC-43-10mee, R-407C, R-407F, R-426A, R-437A, R-442A, R-449A, R-453A'),
                            'status' => $this->getValueByIndicatorId(8134, 'checkbox')
                        ],
                        [
                            'label' => __('HFC-134, HFC-134a, HFC-236cb, HFC-236ea, HFC-245fa, HFC-43-10mee, R-407C, R-407F, R-426A, R-437A, R-442A, R-449A, R-453A'),
                            'status' => $this->getValueByIndicatorId(8135, 'checkbox')
                        ],
                        [
                            'label' => __('No consumption'),
                            'status' => $this->getValueByIndicatorId(8136, 'checkbox')
                        ]
                    ],
                // Report
                    'report' => $this->parseDataForReport()
            ]
        );
    }

    /**
     * Parse data for report
     */
    public function parseDataForReport()
    {
        if (request()->print) {
             return [
                // Page 3
                    'company' => $this->questionnaire->company()->first(),
                    'business_sector' => $this->questionnaire->company->business_sector()->first(),
                    'country' => getCountriesWhereIn([$this->questionnaire->company->country]),
                    'colaborators' => $this->getValueByIndicatorId(513),
                    'ebit' => $this->getValueByIndicatorId(2641),
                    'liabilities' => $this->getValueByIndicatorId(2642),
                    'total_asset' => $this->getValueByIndicatorId(2643),
                    'expense_activity' => $this->getValueByIndicatorId(2645),
                    'reporting_period_activities' => $this->getValueByIndicatorId(2647),
                    'capital_expenditure' => $this->getValueByIndicatorId(2648),
                    'organisations_debt' => $this->getValueByIndicatorId(2650),
                    'net_debt' => $this->getValueByIndicatorId(2651),
                    'interest_expenses' => $this->getValueByIndicatorId(2652),
                    'profit_loss' => $this->getValueByIndicatorId(2653),
                    'listed_company' => $this->getValueByIndicatorId(4885, 'checkbox'),
                    'closing_price' => $this->getValueByIndicatorId(4886),
                    'avg_capital_cost' => $this->getValueByIndicatorId(2656),
                    'share_organization' => $this->getValueByIndicatorId(2657),
                    'value_organization' => $this->getValueByIndicatorId(2654),
                    'randd_turnover' => $this->getValueByIndicatorId(8104),
                    'business_sustainability' => $this->getValueByIndicatorId(8109),
                    'safety_turnover' => $this->getValueByIndicatorId(8110),
                // Page 8
                    'air_emission' => $this->getValueByIndicatorId(1764, 'checkbox'),
                // Page 8.1
                    'energy_consumption' => [
                        [
                            'label' => __('Energy consumption monitoring'),
                            'status' => $this->getValueByIndicatorId(1750, 'checkbox')
                        ],
                        [
                            'label' => __('Policy for energy consumption reduction'),
                            'status' => $this->getValueByIndicatorId(1865, 'checkbox')
                        ],
                    ],
                    'waste_management' => [
                        [
                            'label' => __('Organisation uses infrastructure (owned, rented or under its management) to conduct its activities'),
                            'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                        ],
                        [
                            'label' => __('Waste production monitoring'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                        [
                            'label' => __('Hazardous and radioactive waste monitoring'),
                            'status' => $this->getValueByIndicatorId(5628, 'checkbox')
                        ],
                        [
                            'label' => __('Policy for addressing the impacts of material use and consumption and to promote circular economy'),
                            'status' => $this->getValueByIndicatorId(4515, 'checkbox')
                        ],
                    ],
                // Page 11
                    'operations_reporting_period' => $this->getValueByIndicatorId(5639),
                    'adjacent_protected_areas' => $this->getValueByIndicatorId(5640),
                    'strategy_address_impacts' => $this->getValueByIndicatorId(772),
                    'strategy_address_impacts_percentage' => $this->getValueByIndicatorId(2559),
                // Page 13
                    'row_matrials' => [
                        [
                            'label' => __('Carried out new construction and/or major renovation work'),
                            'status' => $this->getValueByIndicatorId(2151, 'checkbox')
                        ],
                        [
                            'label' => __('Raw-materials consumption monitoring'),
                            'status' => $this->getValueByIndicatorId(4397, 'checkbox')
                        ]
                    ],
                    'biological_origin_matrials_percentage' => $this->getValueByIndicatorId(5673),
                // Page 15
                    'subcontract_workers' => $this->getValueByIndicatorId(513),
                    'workers' => [
                        [
                            'label' => __('Organisation has subcontracted workers'),
                            'status' => $this->getValueByIndicatorId(3465, 'checkbox')
                        ],
                        [
                            'label' => __('Organisation has subcontracted workers in middle management positions'),
                            'status' => $this->getValueByIndicatorId(5620, 'checkbox')
                        ],
                        [
                            'label' => __('Organisation has subcontracted workers in top management positions'),
                            'status' => $this->getValueByIndicatorId(4255, 'checkbox')
                        ]
                    ],
                    'middle_management' => $this->parseDataForChartsWithStaticLabels(
                        [__('Man'), __('Women'), __('Other')],
                        [5618, 5617, 5619]
                    ),
                    'top_management_outsource' => $this->parseDataForChartsWithStaticLabels(
                        [__('Man'), __('Women'), __('Other')],
                        [4259, 4258, 4260]
                    ),
                // Page 16
                    'worker_condition' => [
                        [
                            'label' => __('Performance evaluation process for contracted workers'),
                            'status' => $this->getValueByIndicatorId(4447, 'checkbox')
                        ],
                        [
                            'label' => __('Performance evaluation process is known by the contracted workers'),
                            'status' => $this->getValueByIndicatorId(4448, 'checkbox')
                        ],
                        [
                            'label' => __('Organisation has information about the salary conditions of subcontracted workers'),
                            'status' => $this->getValueByIndicatorId(1684, 'checkbox')
                        ],
                        [
                            'label' => __('Contracted workers receiving the local minimum wage in any of the regions where the organisation operates'),
                            'status' => $this->getValueByIndicatorId(1686, 'checkbox')
                        ]
                    ],
                    'minimum_wage' => $this->parseDataForChartsWithStaticLabels(
                        [__('Man'), __('Women'), __('Other')],
                        [57, 56, 58]
                    ),
                    'leaving_organisation' => $this->parseDataForChartsWithStaticLabels(
                        [__('Man'), __('Women'), __('Other')],
                        [364, 363, 365]
                    ),
                    'salary_scale' => [
                        [
                            'label' => __('Provision of data relating to salary scale'),
                            'status' => $this->getValueByIndicatorId(2008, 'checkbox')
                        ],
                        [
                            'label' => __('Remuneration Policy'),
                            'status' => $this->getValueByIndicatorId(1663, 'checkbox')
                        ],
                    ],
                // Page 17
                    'gross_earning' => $this->parseDataForChartsWithStaticLabels(
                        [__('Man'), __('Women'), __('Other')],
                        [4371, 4370, 4372]
                    ),
                    'annual_remuneration' => $this->getValueByIndicatorId(4373),
                    'gross_remuneration' => $this->getValueByIndicatorId(4375),
                    'safety_health' => [
                        [
                            'label' => __('Occupational safety and health (OSH) policy'),
                            'status' => $this->getValueByIndicatorId(2100, 'checkbox')
                        ],
                        [
                            'label' => __('Practices being implemented in accordance with this policy'), // TODO :: NOT FOUND
                            'status' => $this->getValueByIndicatorId(2108, 'checkbox')
                        ],
                        [
                            'label' => __('Organisation has subcontracted workers in top management positions'), // TODO :: NOT FOUND
                            'status' => $this->getValueByIndicatorId(2008, 'checkbox')
                        ],
                        [
                            'label' => __('Ensures that subcontracted workers are covered by Occupational Safety and Health (OSH) conditions (either internally or through external contracting)'),
                            'status' => $this->getValueByIndicatorId(2115, 'checkbox')
                        ],
                        [
                            'label' => __('Accidents at work during the reporting period'),
                            'status' => $this->getValueByIndicatorId(1714, 'checkbox')
                        ]
                    ],
                    'practices_safety_health' => [
                        [
                            'label' => __('Health and safety risk assessment for workers'),
                            'status' => $this->getValueByIndicatorId(3526, 'checkbox')
                        ],
                        [
                            'label' => __('Regular audits and inspections by an external entity to ensure safety in the workplace'),
                            'status' => $this->getValueByIndicatorId(3527, 'checkbox')
                        ],
                        [
                            'label' => __('Internal procedures to prevent or anticipate identified risks to workers health and safety'),
                            'status' => $this->getValueByIndicatorId(3528, 'checkbox')
                        ],
                        [
                            'label' => __('Provision of protective equipment for workers'),
                            'status' => $this->getValueByIndicatorId(3529, 'checkbox')
                        ],
                        [
                            'label' => __('Training for all workers on occupational health and safety policies and practices'),
                            'status' => $this->getValueByIndicatorId(3530, 'checkbox')
                        ],
                        [
                            'label' => __('Regular occupational medicine consultation for all workers'),
                            'status' => $this->getValueByIndicatorId(3531, 'checkbox')
                        ]
                    ],
                // Page 20
                    'institutional' => [
                        [
                            'label' => __('Website'),
                            'status' => $this->getValueByIndicatorId(1555, 'checkbox')
                        ],
                        [
                            'label' => __('Institutional Presentation'),
                            'status' => $this->getValueByIndicatorId(1557, 'checkbox')
                        ],
                        [
                            'label' => __('Organization structure'),
                            'status' => $this->getValueByIndicatorId(1560, 'checkbox')
                        ],
                    ],
                    'highest_governer_body' => [
                        [
                            'label' => __('President as CEO'),
                            'status' => $this->getValueByIndicatorId(5610, 'checkbox')
                        ],
                    ],
                    'impacts_communities' => [
                        [
                            'label' => __('Due Diligence Process in relation to human rights'),
                            'status' => $this->getValueByIndicatorId(2128, 'checkbox')
                        ],
                        [
                            'label' => __('Community impact strategy'),
                            'status' => $this->getValueByIndicatorId(2109, 'checkbox')
                        ],
                        [
                            'label' => __('Indicators to monitor the organisations impact on the community'),
                            'status' => $this->getValueByIndicatorId(2111, 'checkbox')
                        ],
                        [
                            'label' => __('Indicators are monitored'),
                            'status' => $this->getValueByIndicatorId(2112, 'checkbox')
                        ],
                    ],
                    'initiatives_undertaken' => [
                        [
                            'label' => __('Cash donations'),
                            'status' => $this->getValueByIndicatorId(3534, 'checkbox')
                        ],
                        [
                            'label' => __('Donations in kind'),
                            'status' => $this->getValueByIndicatorId(3535, 'checkbox')
                        ],
                        [
                            'label' => __('Offer of services'),
                            'status' => $this->getValueByIndicatorId(3536, 'checkbox')
                        ],
                        [
                            'label' => __('Development and implementation of an empowerment program'),
                            'status' => $this->getValueByIndicatorId(3537, 'checkbox')
                        ],
                        [
                            'label' => __('Infrastructure investment'),
                            'status' => $this->getValueByIndicatorId(3538, 'checkbox')
                        ],
                        [
                            'label' => __('Funding for one or more programs with community impact'),
                            'status' => $this->getValueByIndicatorId(3539, 'checkbox')
                        ],
                        [
                            'label' => __('Other'),
                            'status' => $this->getValueByIndicatorId(3540, 'checkbox')
                        ]
                    ],
                // Page 21
                    'complaints' => [
                        [
                            'label' => __('Mechanism to receive complaints from workers anonymously'),
                            'status' => $this->getValueByIndicatorId(2101, 'checkbox')
                        ],
                        [
                            'label' => __('Mechanism for receiving complaints from custumers'),
                            'status' => $this->getValueByIndicatorId(2102, 'checkbox')
                        ],
                        [
                            'label' => __('Mechanism for receiving complaints from suppliers'),
                            'status' => $this->getValueByIndicatorId(2103, 'checkbox')
                        ],
                    ],
                    'anual_reporting' => [
                        [
                            'label' => __('Period Reports'),
                            'status' => $this->getValueByIndicatorId(2116, 'checkbox')
                        ]
                    ],
                    'policies_implemented' => [
                        [
                            'label' => __('Code of Ethics and Conduct'),
                            'status' => $this->getValueByIndicatorId(1597, 'checkbox')
                        ]
                    ],
                    'policies_implemented_option' => [
                        [
                            'label' => __('Mission, Vision and Values of the organisation'),
                            'status' => $this->getValueByIndicatorId(3503, 'checkbox')
                        ],
                        [
                            'label' => __('Business ethics'),
                            'status' => $this->getValueByIndicatorId(3504, 'checkbox')
                        ],
                        [
                            'label' => __('Social responsibility'),
                            'status' => $this->getValueByIndicatorId(3505, 'checkbox')
                        ],
                        [
                            'label' => __('Environmental responsibility'),
                            'status' => $this->getValueByIndicatorId(3506, 'checkbox')
                        ],
                        [
                            'label' => __('Worker rights'),
                            'status' => $this->getValueByIndicatorId(3507, 'checkbox')
                        ],
                        [
                            'label' => __('Commitment and responsibility'),
                            'status' => $this->getValueByIndicatorId(3508, 'checkbox')
                        ],
                        [
                            'label' => __('Diversity and inclusion'),
                            'status' => $this->getValueByIndicatorId(3509, 'checkbox')
                        ],
                        [
                            'label' => __('Standards of professionalism'),
                            'status' => $this->getValueByIndicatorId(3510, 'checkbox')
                        ],
                        [
                            'label' => __('Discrimination and sexual harassment policies'),
                            'status' => $this->getValueByIndicatorId(3511, 'checkbox')
                        ],
                        [
                            'label' => __('Use of company assets'),
                            'status' => $this->getValueByIndicatorId(3512, 'checkbox')
                        ],
                        [
                            'label' => __('Use of social media'),
                            'status' => $this->getValueByIndicatorId(3513, 'checkbox')
                        ],
                        [
                            'label' => __('Communication rules'),
                            'status' => $this->getValueByIndicatorId(3514, 'checkbox')
                        ],
                        [
                            'label' => __('Disciplinary process'),
                            'status' => $this->getValueByIndicatorId(3515, 'checkbox')
                        ],
                        [
                            'label' => __('Confidentiality'),
                            'status' => $this->getValueByIndicatorId(3516, 'checkbox')
                        ],
                        [
                            'label' => __('Privacy'),
                            'status' => $this->getValueByIndicatorId(3517, 'checkbox')
                        ],
                        [
                            'label' => __('Intellectual property policies'),
                            'status' => $this->getValueByIndicatorId(3518, 'checkbox')
                        ],
                        [
                            'label' => __('Customer communication requirements'),
                            'status' => $this->getValueByIndicatorId(3519, 'checkbox')
                        ],
                        [
                            'label' => __('Conflicts of interests'),
                            'status' => $this->getValueByIndicatorId(3520, 'checkbox')
                        ],
                        [
                            'label' => __('Corruption, extortion and bribery'),
                            'status' => $this->getValueByIndicatorId(3521, 'checkbox')
                        ],
                        [
                            'label' => __('Suppliers Policy'),
                            'status' => $this->getValueByIndicatorId(3522, 'checkbox')
                        ],
                        [
                            'label' => __('Competition'),
                            'status' => $this->getValueByIndicatorId(3523, 'checkbox')
                        ],
                        [
                            'label' => __('Conflict Resolution'),
                            'status' => $this->getValueByIndicatorId(3524, 'checkbox')
                        ],
                        [
                            'label' => __('Other'),
                            'status' => $this->getValueByIndicatorId(3525, 'checkbox')
                        ],
                    ],
                // Page 22
                    'prevention_policy' => [
                        [
                            'label' => __('Anti-Corruption and Anti-Bribery Policy'),
                            'status' => $this->getValueByIndicatorId(5129, 'checkbox')
                        ],
                        [
                            'label' => __('Conflict of Interest Prevention Policy'),
                            'status' => $this->getValueByIndicatorId(1676, 'checkbox')
                        ],
                        [
                            'label' => __('Customer Privacy Policy'),
                            'status' => $this->getValueByIndicatorId(1746, 'checkbox')
                        ]
                    ],
                    'relations_suppliers' => [
                        [
                            'label' => __('Supplier Selection Policy'),
                            'status' => $this->getValueByIndicatorId(1723, 'checkbox')
                        ]
                    ],
                    'supplier_policy' => [
                        [
                            'label' => __('Child and youth labour'),
                            'status' => $this->getValueByIndicatorId(5053, 'checkbox')
                        ],
                        [
                            'label' => __('Wages and benefits'),
                            'status' => $this->getValueByIndicatorId(5054, 'checkbox')
                        ],
                        [
                            'label' => __('Working hours'),
                            'status' => $this->getValueByIndicatorId(5055, 'checkbox')
                        ],
                        [
                            'label' => __('Modern slavery (i.e. slavery and forced or compulsory labour and human trafficking)'),
                            'status' => $this->getValueByIndicatorId(5056, 'checkbox')
                        ],
                        [
                            'label' => __('Freedom of association and collective bargaining'),
                            'status' => $this->getValueByIndicatorId(5057, 'checkbox')
                        ],
                        [
                            'label' => __('Harassment and non-discrimination'),
                            'status' => $this->getValueByIndicatorId(5058, 'checkbox')
                        ],
                        [
                            'label' => __('Health and Safety'),
                            'status' => $this->getValueByIndicatorId(5059, 'checkbox')
                        ],
                        [
                            'label' => __('Corruption, extortion and bribery'),
                            'status' => $this->getValueByIndicatorId(5060, 'checkbox')
                        ],
                        [
                            'label' => __('Corruption, extortion and bribery'),
                            'status' => $this->getValueByIndicatorId(5061, 'checkbox')
                        ],
                        [
                            'label' => __('Fair competition and anti-trust'),
                            'status' => $this->getValueByIndicatorId(5062, 'checkbox')
                        ],
                        [
                            'label' => __('Conflicts of interest'),
                            'status' => $this->getValueByIndicatorId(5063, 'checkbox')
                        ],
                        [
                            'label' => __('Whistleblowing and protection against retaliation'),
                            'status' => $this->getValueByIndicatorId(5064, 'checkbox')
                        ],
                        [
                            'label' => __('GHG emissions'),
                            'status' => $this->getValueByIndicatorId(5065, 'checkbox')
                        ],
                        [
                            'label' => __('Energy efficiency and renewable energy'),
                            'status' => $this->getValueByIndicatorId(5066, 'checkbox')
                        ],
                        [
                            'label' => __('Water quality and consumption'),
                            'status' => $this->getValueByIndicatorId(5067, 'checkbox')
                        ],
                        [
                            'label' => __('Air quality'),
                            'status' => $this->getValueByIndicatorId(5068, 'checkbox')
                        ],
                        [
                            'label' => __('Sustainable resource management and waste reduction'),
                            'status' => $this->getValueByIndicatorId(5069, 'checkbox')
                        ],
                        [
                            'label' => __('Responsible chemicals management'),
                            'status' => $this->getValueByIndicatorId(5070, 'checkbox')
                        ],
                        [
                            'label' => __('Sustainability requirements for suppliers'),
                            'status' => $this->getValueByIndicatorId(5071, 'checkbox')
                        ],
                        [
                            'label' => __('Other'),
                            'status' => $this->getValueByIndicatorId(5072, 'checkbox')
                        ]
                    ],
                    'supplier_code_ethics' => [
                        [
                            'label' => __('Supplier Code of Ethics and Conduct'),
                            'status' => $this->getValueByIndicatorId(2033, 'checkbox')
                        ]
                    ]
            ];
        }

        return [];
    }

    /**
     * Parse data for readiness graph
     */
    public function parseDataForReadiness()
    {
        $data = [];
        $currentLevel = 0;
        // level 1
            // level 1 on 1
            $result = $this->getDataByindicator(1663);
            $level1 = $result->value ?? false;

            $result = $this->getDataByindicator(2100);
            $level2 = $result->value ?? false;

            $result = $this->getDataByindicator(1597);
            $level3 = $result->value ?? false;

            $result = $this->getDataByindicator(1746);
            $level4 = $result->value ?? false;

            $result = $this->getDataByindicator(5129);
            $level5 = $result->value ?? false;

            // level 1 on 2
            $level6 = $this->getDataByindicator(5610)->value ?? false;

            // level 1 on 3
            $level7 = $this->getDataByindicator(1723)->value ?? false;

            // level 1 on 4
            $level8 = $this->getDataByindicator(2037)->value ?? false;
            $level9 = $this->getDataByindicator(1676)->value ?? false;

            $result = $level1 && $level2 && $level3 && $level4 && $level5 ?? false;
            $result1 = ($level6 == 'yes' ? true : false);
            $result2 = ($level7 == 'yes' ? true : false);
            $result3 = $level8 && $level9 ?? false;

            if ($result == true && $result1 == true && $result2 == true && $result3 == true) {
                $currentLevel = 1;
            }

        // Level 2
            // Level 2 0n 1
            $level10 = $this->getDataByindicator(4477)->value ?? false;
            $level11 = $this->getDataByindicator(5169)->value ?? false;
            $level12 = $this->getDataByindicator(1677)->value ?? false;

            // Level 2 0n 2
            $level13 = $this->getDataByindicator(2107)->value ?? false;

            // Level 2 0n 3
            $level14 = $this->getDataByindicator(2108)->value ?? false;

            // Level 2 0n 4
            $level15 = $this->getDataByindicator(2128)->value ?? false;

            // Level 2 0n 5
            $level16 = $this->getDataByindicator(4969)->value ?? false;
            $level17 = $this->getDataByindicator(2065)->value ?? false;

            $result4 = $level10 && $level11 && $level12 ?? false;
            $result5 = ($level13 == 'yes' ? true : false);
            $result6 = ($level14 == 'yes' ? true : false);
            $result7 = ($level15 == 'yes' ? true : false);
            $result8 = $level16 && $level17 ?? false;

            if ($result4 == true && $result5 == true && $result6 == true && $result7 == true && $result8 == true && $currentLevel == 1) {
                $currentLevel = 2;
            }

        // level 3
            // Level 3 on 1
            $level18 = $this->getDataByindicator(2101)->value ?? false;
            $level19 = $this->getDataByindicator(2102)->value ?? false;
            $level20 = $this->getDataByindicator(2103)->value ?? false;

            // Level 3 on 2
            $level21 = $this->getDataByindicator(2116)->value ?? false;

            // Level 3 on 3
            $level22 = $this->getDataByindicator(1764)->value ?? false;
            $level23 = $this->getDataByindicator(2131)->value ?? false;
            $level24 = $this->getDataByindicator(1757)->value ?? false;
            $level25 = $this->getDataByindicator(1758)->value ?? false;
            $level26 = $this->getDataByindicator(1759)->value ?? false;
            $level27 = $this->getDataByindicator(1750)->value ?? false;
            $level28 = $this->getDataByindicator(1920)->value ?? false;
            $level29 = $this->getDataByindicator(5628)->value ?? false;
            $level30 = $this->getDataByindicator(3543)->value ?? false;
            $level31 = $this->getDataByindicator(5629)->value ?? false;
            $level32 = $this->getDataByindicator(2137)->value ?? false;
            $level33 = $this->getDataByindicator(4397)->value ?? false;

            // Level 3 on 4
            $level34 = $this->getValueByIndicatorId(3417, 'checkbox');
            // Level 3 on 5
            $level35 = $this->getDataByindicator(445)->value ?? false;

            $result9 = $level18 && $level19 && $level20 ?? false;
            $result10 = ($level21 == 'yes' ? true : false);
            $result11 = $level22 && $level23 && $level24 && $level25 && $level26 && $level27 && $level28 && $level29 && $level30 && $level31 && $level32 && $level33 ?? false;
            $result12 = ($level34 == '1' ? true : false);
            $result13 = ($level35 >= '33.3' ? true : false);

            if ($result9 == true && $result10 == true && $result11 == true && $result12 == true && $result13 == true && $currentLevel == 2) {
                $currentLevel = 3;
            }


        // level 4
            // Level 4 on 1
            $level36 = $this->getDataByindicator(2104)->value ?? false;

            // Level 4 on 2
            $level37 = $this->getDataByindicator(1760)->value ?? false;

            // Level 4 on 3
            $level38 = $this->getDataByindicator(1865)->value ?? false;

            // Level 4 on 4
            $level39 = $this->getDataByindicator(1592)->value ?? false;

            // Level 4 on 5
            $level40 = $this->getDataByindicator(2109)->value ?? false;

            $result14 = ($level36 == 'yes' ? true : false);
            $result15 = ($level37 == 'yes' ? true : false);
            $result16 = ($level38 == 'yes' ? true : false);
            $result17 = ($level39 == 'yes' ? true : false);
            $result18 = ($level40 == 'yes' ? true : false);

            if ($result14 == true && $result15 == true && $result16 == true && $result17 == true && $result18 == true && $currentLevel == 3) {
                $currentLevel = 4;
            }


        if ($currentLevel == 4) {
            $currentLevel = 5;
        }

        $data = [
            'current_level' => $currentLevel,
            'level1' => [
                [ 'complete' => $result, 'tooltip' => __('Master of Policies')],
                [ 'complete' => $result1, 'tooltip' => __('Highest Governance Body constitution')],
                [ 'complete' => $result2, 'tooltip' => __('Select your Suppliers')],
                [ 'complete' => $result3, 'tooltip' => __('Committed to corruption and conflict of interests prevention')]
            ],
            'level2' => [
                [ 'complete' => $result4, 'tooltip' => __('Committed to the best practices')],
                [ 'complete' => $result5, 'tooltip' => __('Committed to the ethic and conduct')],
                [ 'complete' => $result6, 'tooltip' => __('Committed to the policies implementation')],
                [ 'complete' => $result7, 'tooltip' => __('Committed to Due Diligence')],
                [ 'complete' => $result8, 'tooltip' => __('Disclosure of the policies')],
            ],
            'level3' => [
                [ 'complete' => $result9, 'tooltip' => __('Complaint Mechanisms')],
                [ 'complete' => $result10, 'tooltip' => __('Master of Reports')],
                [ 'complete' => $result11, 'tooltip' => __('Environmentally Conscious')],
                [ 'complete' => $result12, 'tooltip' => __('Customer Oriented')],
                [ 'complete' => $result13, 'tooltip' => __('Gender Equality')],
            ],
            'level4' => [
                [ 'complete' => $result14, 'tooltip' => __('Attentive to SDGs')],
                [ 'complete' => $result15, 'tooltip' => __('Committed to your carbon footprint')],
                [ 'complete' => $result16, 'tooltip' => __('Committed to your energy efficiency ')],
                [ 'complete' => $result17, 'tooltip' => __('Adherence to global principles ')],
                [ 'complete' => $result18, 'tooltip' => __('Social Responsibility')],
            ]
        ];
        return $data;
    }

    /**
     * parse data for document and policy
     */
    public function parseDataForDocuments()
    {
        return [
            [
                [
                    'label' => __('Website'),
                    'status' => $this->getValueByIndicatorId(1555, 'checkbox')
                ],
                [
                    'label' => __('Institutional Presentation'),
                    'status' => $this->getValueByIndicatorId(1557, 'checkbox')
                ],
                [
                    'label' => __('Code of Ethics and Conduct'),
                    'status' => $this->getValueByIndicatorId(1597, 'checkbox')
                ],
                [
                    'label' => __('Occupational Health and Safety Policy'),
                    'status' => $this->getValueByIndicatorId(2100, 'checkbox')
                ],
                [
                    'label' => __('Remuneration Policy'),
                    'status' => $this->getValueByIndicatorId(1663, 'checkbox')
                ],
                [
                    'label' => __('Anti-Corruption and Anti-Bribery Policy'),
                    'status' => $this->getValueByIndicatorId(5129, 'checkbox')
                ],
                [
                    'label' => __('Conflict of Interest Prevention Policy'),
                    'status' => $this->getValueByIndicatorId(1676, 'checkbox')
                ],
                [
                    'label' => __('Due Diligence Process in relation to human rights'),
                    'status' => $this->getValueByIndicatorId(2128, 'checkbox')
                ],
                [
                    'label' => __('Complaint Mechanism for Workers'),
                    'status' => $this->getValueByIndicatorId(2101, 'checkbox')
                ],
            ],
            [
                [
                    'label' => __('Complaint Mechanism for Customers'),
                    'status' => $this->getValueByIndicatorId(2102, 'checkbox')
                ],
                [
                    'label' => __('Complaint Mechanism for Suppliers'),
                    'status' => $this->getValueByIndicatorId(2103, 'checkbox')
                ],
                [
                    'label' => __('Customer Privacy Policy'),
                    'status' => $this->getValueByIndicatorId(1746, 'checkbox')
                ],
                [
                    'label' => __('Supplier Policy'),
                    'status' => $this->getValueByIndicatorId(1723, 'checkbox')
                ],
                [
                    'label' => __('Supplier Code of Ethics and Conduct'),
                    'status' => $this->getValueByIndicatorId(2033, 'checkbox')
                ],
                [
                    'label' => __('Policy for emission reduction'),
                    'status' => $this->getValueByIndicatorId(1760, 'checkbox')
                ],
                [
                    'label' => __('Policy to reduce energy consumption'),
                    'status' => $this->getValueByIndicatorId(1865, 'checkbox')
                ],
                [
                    'label' => __('Water Management Policy'),
                    'status' => $this->getValueByIndicatorId(1768, 'checkbox')
                ],
                [
                    'label' => __('Climate change mitigation and adaptation policies'),
                    'status' => $this->getValueByIndicatorId(5517, 'checkbox')
                ],
                [
                    'label' => __('Policy for material use and consumption and to promote circular economy'),
                    'status' => $this->getValueByIndicatorId(4515, 'checkbox')
                ]
            ]
        ];
    }

    /**
     * proccess data for Waste Produced
     */
    public function parseDataForWasteProduced()
    {
        $labels = [
            __('Recycled'),
            __('Sent for disposal'),
            __('Hazardous'),
            __('Radioactive'),
        ];

        return $this->parseDataForChartsWithStaticLabels($labels, [3429, 3430, 3431, 3432]);
    }

    /**
     * Parse Data for Water Consumption
     */
    public function parseDataForWaterConsumption()
    {
        return [
            [
                'label' => __('Located in an area(s) of high water stress'),
                'status' => $this->getValueByIndicatorId(2139, 'checkbox')
            ],
            [
                'label' => __('Recycles and/or reuses water'),
                'status' => $this->getValueByIndicatorId(2134, 'checkbox')
            ],
            [
                'label' => __('Carries out direct discharges into the aquatic environment'),
                'status' => $this->getValueByIndicatorId(2136, 'checkbox')
            ],
            [
                'label' => __('Has a wastewater discharge license'),
                'status' => $this->getValueByIndicatorId(3973, 'checkbox')
            ],
            [
                'label' => __('Monitors water discharge conditions (discharge values - physico-chemical parameters)'),
                'status' => $this->getValueByIndicatorId(2137, 'checkbox')
            ],
            [
                'label' => __('Cases of non-compliance with discharge limits'),
                'status' => $this->getValueByIndicatorId(3978, 'checkbox')
            ]
        ];
    }

    /**
     * Parse data for parseDataForRefrigerantGas
     */
    public function parseDataForRefrigerantGas()
    {
        return [
            [
                'label' => __('HFC-23, SF6'),
                'status' => $this->getValueByIndicatorId(8060, 'checkbox')
            ],
            [
                'label' => __('HFC-125, HFC-143a, HFC-227ea, HFC-236fa, R-404A, R-407A, R-407B, R-410A, R-410B, R-413A, R-417A, R-417B, R-422A, R-422D, R-424A, R-427A, R-428A, R-434A, R-438A, R-452A, R-507A'),
                'status' => $this->getValueByIndicatorId(8061, 'checkbox')
            ],
            [
                'label' => __('HFC-134, HFC-134a, HFC-236cb, HFC-236ea, HFC-245fa, HFC-43-10mee, R-407C, R-407F, R-426A, R-437A, R-442A, R-449A, R-453A'),
                'status' => $this->getValueByIndicatorId(8062, 'checkbox')
            ],
            [
                'label' => __('HFC-32, HFC-41, HFC-143, HFC-152, HFC-152a, HFC-161, HFC-245ca, HFC-365mfc'),
                'status' => $this->getValueByIndicatorId(8063, 'checkbox')
            ],
            [
                'label' => __('No consumption'),
                'status' => $this->getValueByIndicatorId(8064, 'checkbox')
            ],
        ];
    }

    /**
     * Parse Data for Biodiversity Impact
     */
    public function parseDataForBiodiversityImpact()
    {

        $data = [
            [
                'label' => __('Present in biodiversity sensitive areas'),
                'status' => $this->getValueByIndicatorId(1779, 'checkbox')
            ],
            [
                'label' => __('Operations in or near protected areas and/or areas rich in biodiversity'),
                'status' => $this->getValueByIndicatorId(5638, 'checkbox')
            ],
            [
                'label' => __('Activities have a negative impact on a biodiversity'),
                'status' => $this->getValueByIndicatorId(4103, 'checkbox')
            ],
            [
                'label' => __('Policy or strategy to address impacts on biodiversity and ecosystems'),
                'status' => $this->getValueByIndicatorId(4147, 'checkbox')
            ],
        ];

        if (request()->print) {
            $data[] = [
                'label' => __('Policy or strategy to address impacts of infrastructures and/or operational units on biodiversity and ecosystems'),
                'status' => $this->getValueByIndicatorId(5642, 'checkbox')
            ];
        }

        return $data;
    }

    /**
     * Parse Data for Organization Activity Impact
     */
    public function parseDataForOrganizationActivityImpact()
    {
        $data = [
            [
                'label' => __('Involved in activities related to the manufacture or sale of controversial weapons'),
                'status' => $this->getValueByIndicatorId(2142, 'checkbox')
            ],
            [
                'label' => __('Conducts activities that are included in the manufacture of pesticides and other agrochemical products'),
                'status' => $this->getValueByIndicatorId(2143, 'checkbox')
            ],
            [
                'label' => __('Conducts activities that contribute to land degradation, desertification, artificialisation and/or soil sealing'),
                'status' => $this->getValueByIndicatorId(2144, 'checkbox')
            ],
            [
                'label' => __('Conducts activities that include exploiting seas and/or oceans'),
                'status' => $this->getValueByIndicatorId(2145, 'checkbox')
            ],
            [
                'label' => __('Activities in forest areas'),
                'status' => $this->getValueByIndicatorId(2146, 'checkbox')
            ],
        ];

        if (request()->print) {
            $data[] = [
                'label' => __('Policy to combat deforestation'),
                'status' => $this->getValueByIndicatorId(2146, 'checkbox')
            ];

            $data[] = [
                'label' => __('Organisation has climate change mitigation and adaptation policies'),
                'status' => $this->getValueByIndicatorId(5517, 'checkbox')
            ];
        }

        return $data;
    }

    /**
     * Parse data for traning for workrs
     */
    public function parseDataForTraningForWorks()
    {
        return [
            [
                'label' => __('Training and capacity development'),
                'status' => $this->getValueByIndicatorId(4477, 'checkbox')
            ],
            [
                'label' => __('Trainning on the topics covered in the code of ethics and conduct during the reporting period'),
                'status' => $this->getValueByIndicatorId(2107, 'checkbox')
            ],
            [
                'label' => __('Trainning on preventing and combating corruption and bribery'),
                'status' => $this->getValueByIndicatorId(5169, 'checkbox')
            ]
        ];
    }

    /**
     * Parse data for Significant Incidents
     */
    public function parseDataForSignificantIncidents()
    {
        return [
            [
                'label' => __('Convicted of violations of anti-corruption and anti-bribery laws'),
                'status' => $this->getValueByIndicatorId(2125, 'checkbox')
            ],
            [
                'label' => __('Incidents of discrimination, in particular resulting in the application of sanctions'),
                'status' => $this->getValueByIndicatorId(2129, 'checkbox')
            ]
        ];
    }

    /**
     * Parse data for Annual reporting
     */
    public function parseDataForAnnualReporting()
    {
        return [
            [
                'label' => __('Financial report'),
                'status' => $this->getValueByIndicatorId(3412, 'checkbox')
            ],
            [
                'label' => __('Impact report'),
                'status' => $this->getValueByIndicatorId(3413, 'checkbox')
            ],
            [
                'label' => __('Sustainability report'),
                'status' => $this->getValueByIndicatorId(3414, 'checkbox')
            ],
            [
                'label' => __('Activities Report'),
                'status' => $this->getValueByIndicatorId(3415, 'checkbox')
            ],
            [
                'label' => __('Sales Report'),
                'status' => $this->getValueByIndicatorId(3416, 'checkbox')
            ],
            [
                'label' => __('Customer Satisfaction Report'),
                'status' => $this->getValueByIndicatorId(3417, 'checkbox')
            ],
            [
                'label' => __('Satisfaction / Welfare Report of Workers'),
                'status' => $this->getValueByIndicatorId(3418, 'checkbox')
            ],
            [
                'label' => __('Other'),
                'status' => $this->getValueByIndicatorId(3419, 'checkbox')
            ]
        ];
    }

    /**
     * Parse data for SDG
     */
    public function parseDataForSDG()
    {
        $document = [];

        $document['1'] = $this->getDataByindicator(3466)->value ?? false;
        $document['2'] = $this->getDataByindicator(3467)->value ?? false;
        $document['3'] = $this->getDataByindicator(3468)->value ?? false;
        $document['4'] = $this->getDataByindicator(3469)->value ?? false;
        $document['5'] = $this->getDataByindicator(3470)->value ?? false;
        $document['6'] = $this->getDataByindicator(3471)->value ?? false;
        $document['7'] = $this->getDataByindicator(3472)->value ?? false;
        $document['8'] = $this->getDataByindicator(3472)->value ?? false;
        $document['9'] = $this->getDataByindicator(3474)->value ?? false;
        $document['10'] = $this->getDataByindicator(3475)->value ?? false;
        $document['11'] = $this->getDataByindicator(3476)->value ?? false;
        $document['12'] = $this->getDataByindicator(3477)->value ?? false;
        $document['13'] = $this->getDataByindicator(3478)->value ?? false;
        $document['14'] = $this->getDataByindicator(3479)->value ?? false;
        $document['15'] = $this->getDataByindicator(3480)->value ?? false;
        $document['16'] = $this->getDataByindicator(3481)->value ?? false;
        $document['17'] = $this->getDataByindicator(3482)->value ?? false;

        return $document;
    }

    /**
     * Parse data for logos
     */
    public function parseDataForLogos()
    {
        $document = [];

        $document['status'] = $this->getDataByindicator(1592)->value ?? false;
        $document['un'] = $this->getDataByindicator(3420)->value ?? false;
        $document['pri'] = $this->getDataByindicator(3422)->value ?? false;
        $document['sbt'] = $this->getDataByindicator(3421)->value ?? false;
        $document['b_corp'] = $this->getDataByindicator(3423)->value ?? false;
        $document['wbc'] = $this->getDataByindicator(5616)->value ?? false;
        $document['prb'] = $this->getDataByindicator(3425)->value ?? false;

        return $document;
    }

    /**
     * Types of contract
     */
    public function parseDataForTypeOfContract()
    {
        $labels = [
            __('Part-time'),
            __('Full-time'),
        ];

        $dataset = [
            [
                'label' => __('Male'),
                'backgroundColor' => '#21A6E8',
                'data' => [
                    $this->getValueByIndicatorId(4149, 'value'),
                    $this->getValueByIndicatorId(4148, 'value'),
                ]
            ],
            [
                'label' => __('Female'),
                'backgroundColor' => '#C5A8FF',
                'data' => [
                    $this->getValueByIndicatorId(4146, 'value'),
                    $this->getValueByIndicatorId(4143, 'value'),
                ]
            ],
            [
                'label' => __('Other'),
                'backgroundColor' => '#02C6A1',
                'data' => [
                    $this->getValueByIndicatorId(4151, 'value'),
                    $this->getValueByIndicatorId(4150, 'value'),
                ]
            ]
        ];

        return [
            'labels' => $labels,
            'data' => $dataset,
        ];
    }
}
