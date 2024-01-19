<?php

namespace App\Models\Tenant\Dashboards;

use App\Http\Livewire\Traits\Dashboards\DashboardCalcs;
use App\Models\Tenant\Category;
use App\Models\Tenant\Data;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Questionnaire;
use App\Models\Traits\Dashboard;

class Dashboard18
{
    use Dashboard;
    use DashboardCalcs;

    protected $indicatorsData;
    protected $indicators;

    /**
     * The constructor
     */
    public function __construct()
    {
        // get route params
        $this->route_params = request()->query();
        $this->charts = [
            'main' => [
                'name' => __('Main'),
                'categories' => [
                    'document_and_policies' => [
                        'name' => __('Documentation and Policies'),
                        'indicators' => [5129, 1676, 2128, 1768, 5517],
                        'math' => null,
                        'total' => null,
                    ],
                ],
            ],
            'environment' => [
                'name' => __('Environment'),
                'categories' => [
                    'energy_intensity' => [
                        'name' => __('Energy intensity'),
                        'indicators' => [5676],
                        'math' => '5676',
                        'total' => 0,
                    ],
                    'energy_consumption' => [
                        'name' => __('Energy Consumption'),
                        'categories' => [
                            'renewable_sources' => [
                                'name' => __('Renewable sources'),
                                'indicators' => [787, 2120],
                                'math' => '787 / (787 + 2120) * 100',
                                'total' => 0,
                            ],
                            'non_renewable_sources' => [
                                'name' => __('Non-renewable sources'),
                                'indicators' => [787, 2120],
                                'math' => '2120 / (787 + 2120) * 100',
                                'total' => 0,
                            ],
                        ]
                    ],
                    'ghg_emissions_chart' => [
                        'name' => __('GHG EMISSIONS BY CATEGORY'),
                        'categories' => [
                            'scope_1' => [
                                'name' => __('Scope 1'),
                                'indicators' => [20],
                                'math' => '20',
                                'total' => 0,
                            ],
                            'scope_2' => [
                                'name' => __('Scope 2'),
                                'indicators' => [21],
                                'math' => '21',
                                'total' => 0,
                            ],
                            'scope_3' => [
                                'name' => __('Scope 3'),
                                'indicators' => [22],
                                'math' => '22',
                                'total' => 0,
                            ],
                        ]
                    ],
                    'main_sources_of_scope' => [
                        'name' => __('MAIN SOURCES OF SCOPE'),
                        'categories' => [
                            'scope_1' => [
                                'name' => __('Present in biodiversity sensitive areas'),
                                'indicators' => [3616],
                                'math' => '3616',
                                'total' => 0,
                            ],
                            'scope_3' => [
                                'name' => __('Biogenic CO2 emissions derived from biomass burning or biodegradation throughout the value chain'),
                                'indicators' => [3644],
                                'math' => '3644',
                                'total' => 0,
                            ],
                            'ghg_natural_removal' => [
                                'name' => __('GHG emissions removed/stored: Natural removal (forest)'),
                                'indicators' => [3660],
                                'math' => '3660',
                                'total' => 0,
                            ],
                            'ghg_storage_through' => [
                                'name' => __('GHG emissions removed/stored: Storage through technology'),
                                'indicators' => [3661],
                                'math' => '3661',
                                'total' => 0,
                            ],
                        ]
                    ],
                    'water_and_marine_resources' => [
                        'name' => __('Water and marine resources'),
                        'categories' => [
                            'water_consumed' => [
                                'name' => __('Water consumed (in reporting period)'),
                                'indicators' => [3061],
                                'math' => '3061',
                                'total' => 0,
                            ],
                            'water_discharged' => [
                                'name' => __('Water discharged (in reporting period)'),
                                'indicators' => [3976],
                                'math' => '3976',
                                'total' => 0,
                            ],
                            'water_treated' => [
                                'name' => __('Water treated (in reporting period)'),
                                'indicators' => [6744],
                                'math' => '6744',
                                'total' => 0,
                            ],
                            'hazardous_waste' => [
                                'name' => __('Hazardous Waste (in the reporting year)'),
                                'indicators' => [4445],
                                'math' => '4445',
                                'total' => 0,
                            ],
                        ]
                    ],
                    'biodiversity_impact' => [
                        'name' => __('Biodiversity Impact'),
                        'categories' => [
                            'list' => [
                                'name' => __('Operations'),
                                'indicators' => [5638, 2148],
                                'math' => null,
                                'total' => null,
                            ],
                            'values' => [
                                'name' => __('Operation Values'),
                                'indicators' => [5639, 5640, 772],
                                'math' => null,
                                'total' => 0,
                            ],
                        ]
                    ],
                    'organisation_activities_impact' => [
                        'name' => __('Organisation Activities Impact'),
                        'indicators' => [6669, 6675, 6681, 6687],
                        'math' => null,
                        'total' => null,
                    ],
                    'specific_sectors' => [
                        'name' => __('Specific sectors'),
                        'categories' => [
                            'maritime_transport_sector' => [
                                'name' => __('Activites in the Maritime Transport sector'),
                                'indicators' => [6747],
                                'math' => null,
                                'total' => null,
                                'categories' => [
                                    'average_tons_CO2_per_passenger_km' => [
                                        'name' => __('Average value in tons of CO2 per passenger-km'),
                                        'indicators' => [6745],
                                        'math' => '6745',
                                        'total' => null,
                                    ],
                                    'average_gCO2_per_mj' => [
                                        'name' => __('Average in gCO2/MJ'),
                                        'indicators' => [6748],
                                        'math' => '6748',
                                        'total' => null,
                                    ],
                                    'average_percentage_high_carbon_technologies' => [
                                        'name' => __('Average percentage of high-carbon technologies'),
                                        'indicators' => [6746],
                                        'math' => '6746',
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'oil_and_gas_sector' => [
                                'name' => __('Oil and Gas Sector'),
                                'indicators' => [6754],
                                'math' => null,
                                'total' => null,
                                'categories' => [
                                    'average_tons_CO2_per_gj' => [
                                        'name' => __('Average value in tons of CO2 per GJ'),
                                        'indicators' => [6756],
                                        'math' => '6756',
                                        'total' => null,
                                    ],
                                    'average_percentage_high_carbon_technologies' => [
                                        'name' => __('Average percentage of high-carbon technologies'),
                                        'indicators' => [6757],
                                        'math' => '6757',
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'iron_and_steel_production' => [
                                'name' => __('Production of iron and steel, coke and metal ore'),
                                'indicators' => [6758],
                                'math' => null,
                                'total' => null,
                                'categories' => [
                                    'average_tons_CO2_per_ton' => [
                                        'name' => __('Average value in tons of CO2'),
                                        'indicators' => [6760],
                                        'math' => '6760',
                                        'total' => null,
                                    ],
                                    'average_percentage_high_carbon_technologies' => [
                                        'name' => __('Average percentage of high-carbon technologies'),
                                        'indicators' => [6761],
                                        'math' => '6761',
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'energy_sector' => [
                                'name' => __('Energy Sector'),
                                'indicators' => [6750],
                                'math' => null,
                                'total' => null,
                                'categories' => [
                                    'average_tons_CO2_per_mwh' => [
                                        'name' => __('Average value in tons of CO2 per MWh'),
                                        'indicators' => [6752],
                                        'math' => '6752',
                                        'total' => null,
                                    ],
                                    'average_percentage_high_carbon_technologies' => [
                                        'name' => __('Average percentage of high-carbon technologies'),
                                        'indicators' => [6753],
                                        'math' => '6753',
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'extractive_industries' => [
                                'name' => __('Related to extractive industries'),
                                'indicators' => [6762],
                                'math' => null,
                                'total' => null,
                                'categories' => [
                                    'average_tons_CO2_per_gj' => [
                                        'name' => __('Average value in tons of CO2 per GJ'),
                                        'indicators' => [6764],
                                        'math' => '6764',
                                        'total' => null,
                                    ],
                                    'average_percentage_high_carbon_technologies' => [
                                        'name' => __('Average percentage of high-carbon technologies'),
                                        'indicators' => [6765],
                                        'math' => '6765',
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'cement_clinker_lime_production' => [
                                'name' => __('Include cement, clinker, and lime production'),
                                'indicators' => [6766],
                                'math' => null,
                                'total' => null,
                                'categories' => [
                                    'average_tons_CO2_per_ton' => [
                                        'name' => __('Average value in tons of CO2 per ton produced'),
                                        'indicators' => [6768],
                                        'math' => '6768',
                                        'total' => null,
                                    ],
                                    'average_percentage_high_carbon_technologies' => [
                                        'name' => __('Average percentage of high-carbon technologies'),
                                        'indicators' => [6769],
                                        'math' => '6769',
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'aviation_sector' => [
                                'name' => __('Aviation sector'),
                                'indicators' => [6770],
                                'math' => null,
                                'total' => null,
                                'categories' => [
                                    'average_percentage_of_sustainable_jet_fuels' => [
                                        'name' => __('Average percentage of sustainable jet fuels'),
                                        'indicators' => [6772],
                                        'math' => '6772',
                                        'total' => null,
                                    ],
                                    'average_tons_CO2_per_passenger_km' => [
                                        'name' => __('Average value in tons of CO2 per passenger-km'),
                                        'indicators' => [6773],
                                        'math' => '6773',
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'automobile_sector' => [
                                'name' => __('Automobile sector'),
                                'indicators' => [6774],
                                'math' => null,
                                'total' => null,
                                'categories' => [
                                    'average_tons_CO2_per_passenger_km' => [
                                        'name' => __('Average value in tons of CO2 per passenger-km'),
                                        'indicators' => [6776],
                                        'math' => '6776',
                                        'total' => null,
                                    ],
                                    'average_percentage_high_carbon_technologies' => [
                                        'name' => __('Average percentage of high-carbon technologies'),
                                        'indicators' => [6777],
                                        'math' => '6777',
                                        'total' => null,
                                    ],
                                ],
                            ],
                        ],
                    ],

                ],
            ],
            'social' => [
                'name' => __('Social'),
                'categories' => [
                    'workers_of_the_organisation' => [
                        'name' => __('Workers of the organisation'),
                        'categories' => [
                            'percentage_for_contract_workers' => [
                                'name' => __('Percentage for contract workers:'),
                                'categories' => [
                                    'percentage_for_female_contract_workers' => [
                                        'name' => __('Percentage for female contract workers'),
                                        'indicators' => [65, 66, 67],
                                        'math' => '65 / (65 + 66 + 67) * 100',
                                        'total' => null,
                                    ],
                                    'percentage_for_male_contract_workers' => [
                                        'name' => __('Percentage for male contract workers'),
                                        'indicators' => [65, 66, 67],
                                        'math' => '66 / (65 + 66 + 67) * 100',
                                        'total' => null,
                                    ],
                                    'percentage_for_other_gender_contract_workers' => [
                                        'name' => __('Percentage for other gender contract workers'),
                                        'indicators' => [65, 66, 67],
                                        'math' => '67 / (65 + 66 + 67) * 100',
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'percentage_for_outsourced_workers' => [
                                'name' => __('Percentage for outsourced workers:'),
                                'categories' => [
                                    'percentage_for_female_outsourced_workers' => [
                                        'name' => __('Percentage for female outsourced workers:'),
                                        'indicators' => [207, 208, 3541],
                                        'math' => '207 / (207 + 208 + 3541) * 100',
                                        'total' => null,
                                    ],
                                    'percentage_for_male_outsourced_workers' => [
                                        'name' => __('Percentage for male outsourced workers:'),
                                        'indicators' => [208, 207, 208, 3541],
                                        'math' => '208 / (207 + 208 + 3541) * 100',
                                        'total' => null,
                                    ],
                                    'percentage_for_other_gender_outsourced_workers' => [
                                        'name' => __('Percentage for other gender outsourced workers:'),
                                        'indicators' => [3541, 207, 208, 3541],
                                        'math' => '3541 / (207 + 208 + 3541) * 100',
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'migrant_workers' => [
                                'name' => __('Migrant workers:'),
                                'indicators' => [6600],
                                'math' => null,
                                'total' => null,
                            ],
                        ],
                    ],
                    'workers' => [
                        'name' => __('Workers satisfaction and conditions'),
                        'categories' => [
                            'turnover' => [
                                'name' => __('Turnover'),
                                'indicators' => [3411, 64],
                                'math' => '3411/64*100',
                                'total' => null,
                            ],
                            'gender_pay_gap' => [
                                'name' => __('Gender Pay gap'),
                                'indicators' => [4371, 4370],
                                'math' => '(4371-4370)/4371*100',
                                'total' => null,
                            ],
                            'hourly_earnings_variation' => [
                                'name' => __('Hourly Earnings Variation (Total)'),
                                'indicators' => [4370, 4371, 4372],
                                'math' => null,
                                'total' => null,
                            ],
                            'workers_satisfaction_and_conditions' => [
                                'categories' => [

                                    'assessment_of_negative_impacts' => [
                                        'name' => __('Assessment of negative impacts, risks and/or opportunities on workers caused or contributed to by the organisation'),
                                        'indicators' => [6594],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'communication_channels' => [
                                        'name' => __('Communication channels available for workers to communicate their concerns and/or complaints in order to solve them'),
                                        'indicators' => [2101],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'policies_to_manage_impacts' => [
                                        'name' => __('Policies to manage the impacts, risks and opportunities related to the workers'),
                                        'indicators' => [4972],
                                        'math' => null,
                                        'total' => null,
                                    ],

                                ],
                            ],
                            'topics_covered_in_policies' => [
                                'name' => __('Topics covered in the policies to manage impacts, risks and opportunities related to workers'),
                                'indicators' => [4981, 4982, 4983, 4985, 4986, 4988, 4990],
                                'math' => null,
                                'total' => null,
                            ],
                            'workers_policies_topics' => [
                                'name' => __('Topics covered in the policies to manage impacts, risks and opportunities related to workers'),
                                'categories' => [
                                    'respect_for_human_rights' => [
                                        'name' => __('Respect for human rights, including labour rights'),
                                        'indicators' => [4981],
                                        'math' => '4981',
                                        'total' => null,
                                    ],
                                    'interaction_with_affected_stakeholders' => [
                                        'name' => __('Interaction with affected stakeholders'),
                                        'indicators' => [4982],
                                        'math' => '4982',
                                        'total' => null,
                                    ],
                                    'measures_to_ensure_mitigation_of_human_rights_impacts' => [
                                        'name' => __('Measures to ensure mitigation of human rights impacts'),
                                        'indicators' => [4983],
                                        'math' => '4983',
                                        'total' => null,
                                    ],
                                    'elimination_of_discrimination' => [
                                        'name' => __('Elimination of discrimination (including harassment)'),
                                        'indicators' => [4985],
                                        'math' => '4985',
                                        'total' => null,
                                    ],
                                    'promotion_of_equal_opportunities' => [
                                        'name' => __('Promotion of equal opportunities'),
                                        'indicators' => [4986],
                                        'math' => '4986',
                                        'total' => null,
                                    ],
                                    'promotion_of_diversity_and_inclusion' => [
                                        'name' => __('Promotion of diversity and inclusion'),
                                        'indicators' => [4988],
                                        'math' => '4988',
                                        'total' => null,
                                    ],
                                    'other' => [
                                        'name' => __('Other'),
                                        'indicators' => [4990],
                                        'math' => '4990',
                                        'total' => null,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'safety_and_health_at_work' => [
                        'name' => __('Safety and health at Work'),
                        'categories' => [
                            'occupational_health_and_safety_system' => [
                                'name' => __('Occupational Health and Safety System'),
                                'indicators' => [4537],
                                'math' => null,
                                'total' => null,
                            ],
                            'existence_of_workers_activities_or_locations_not_covered' => [
                                'name' => __('Existence of workers, activities or locations not covered in OHS system'),
                                'indicators' => [4545],
                                'math' => null,
                                'total' => null,
                            ],
                            'ohs_system_cover_migrant_workers' => [
                                'name' => __('Occupational Health and Safety system cover migrant workers'),
                                'indicators' => [6602],
                                'math' => null,
                                'total' => null,
                            ],
                        ],
                    ],
                    'training_for_the_workers' => [
                        'name' => __('Training for the Workers'),
                        'categories' => [
                            'training_on_code_of_conduct_or_ethics' => [
                                'name' => __('Training on the topics covered in the code of conduct or ethics and/or policies that include its values, mission, ethical conduct, sustainability and position on human rights violations, forced labor, child labor and discrimination'),
                                'indicators' => [6632],
                                'math' => null,
                                'total' => null,
                            ],
                            'training_on_preventing_and_combating_corruption' => [
                                'name' => __('Training on preventing and combating corruption and bribery'),
                                'indicators' => [5169],
                                'math' => null,
                                'total' => null,
                            ],
                            'training_mandatory_for_all_contract_workers' => [
                                'name' => __('Training is mandatory for all contracted workers in the organisation'),
                                'indicators' => [6633],
                                'math' => null,
                                'total' => null,
                            ],
                        ],
                    ],
                    'communities' => [
                        'name' => __('Communities'),
                        'categories' => [
                            'affected_communities' => [
                                'categories' => [
                                    'existence_of_affected_communities' => [
                                        'name' => __('Existence of affected communities'),
                                        'indicators' => [5404],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'negative_impacts_on_communities' => [
                                        'name' => __('Negative impacts on the local communities identified'),
                                        'indicators' => [5408],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'complaints_mechanism' => [
                                        'name' => __('Mechanism to receive complaints from local communities'),
                                        'indicators' => [5412],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'manage_impacts_policies' => [
                                        'name' => __('Policies to manage impacts, risks and opportunities related to affected communities'),
                                        'indicators' => [5437],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'infrastructure_significant_investment' => [
                                        'name' => __('Significant investment in infrastructure, service support, social projects, volunteering or donations in the community'),
                                        'indicators' => [5474],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'community_investments' => [
                                'name' => __('Type of investments made in the community'),
                                'categories' => [
                                    'transport_networks' => [
                                        'name' => __('Transport networks'),
                                        'indicators' => [5475],
                                        'math' => '5475',
                                        'total' => null,
                                    ],
                                    'public_services' => [
                                        'name' => __('Public services'),
                                        'indicators' => [5476],
                                        'math' => '5476',
                                        'total' => null,
                                    ],
                                    'community_social_spaces' => [
                                        'name' => __('Community social spaces'),
                                        'indicators' => [5477],
                                        'math' => '5477',
                                        'total' => null,
                                    ],
                                    'health_centers_and_social_welfare' => [
                                        'name' => __('Health Centers and Social Welfare'),
                                        'indicators' => [5478],
                                        'math' => '5478',
                                        'total' => null,
                                    ],
                                    'sports_centers' => [
                                        'name' => __('Sports centers'),
                                        'indicators' => [5479],
                                        'math' => '5479',
                                        'total' => null,
                                    ],
                                    'employee_volunteer_hours' => [
                                        'name' => __('Workers volunteer hours'),
                                        'indicators' => [5480],
                                        'math' => '5480',
                                        'total' => null,
                                    ],
                                    'financial_investment_in_social_projects' => [
                                        'name' => __('Financial investment in social projects of Associations or NGOs'),
                                        'indicators' => [5481],
                                        'math' => '5481',
                                        'total' => null,
                                    ],
                                    'donations_in_kind' => [
                                        'name' => __('Donations in kind to Associations or NGOs'),
                                        'indicators' => [5482],
                                        'math' => '5482',
                                        'total' => null,
                                    ],
                                    'offer_of_a_service' => [
                                        'name' => __('Offer of a service provided by the organisation'),
                                        'indicators' => [5483],
                                        'math' => '5483',
                                        'total' => null,
                                    ],
                                    'development_of_a_programme' => [
                                        'name' => __('Development of a programme with an impact on the community in partnership with Associations or NGOs'),
                                        'indicators' => [5484],
                                        'math' => '5484',
                                        'total' => null,
                                    ],
                                    'other' => [
                                        'name' => __('Other'),
                                        'indicators' => [5485],
                                        'math' => '5485',
                                        'total' => null,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'consumers_and_end_users' => [
                        'name' => __('Consumers and End-Users'),
                        'categories' => [
                            'assessment_of_satisfaction' => [
                                'name' => __('Assessment of the level of satisfaction of consumers and end-users'),
                                'indicators' => [5340],
                                'math' => null,
                                'total' => null,
                            ],
                            'assessment_of_negative_impacts' => [
                                'name' => __('Assessment of the negative impacts the organisation may contribute to or cause for consumers and/or end-users'),
                                'indicators' => [5262],
                                'math' => null,
                                'total' => null,
                            ],
                        ],
                    ],
                ],
            ],
            'governance' => [
                'name' => __('Governance'),
                'categories' => [
                    'structure' => [
                        'name' => 'Structure',
                        'categories' => [
                            'percentage_for_gender_distribution' => [
                                'name' => __('Percentage for gender distribution'),
                                'categories' => [
                                    'percentage_for_female_distribution' => [
                                        'name' => __('Percentage for female distribution'),
                                        'indicators' => [423, 424, 425],
                                        'math' => '423 / (423 + 424 + 425) * 100',
                                        'total' => null,
                                    ],
                                    'percentage_for_male_distribution' => [
                                        'name' => __('Percentage for male distribution'),
                                        'indicators' => [423, 424, 425],
                                        'math' => '424 / (423 + 424 + 425) * 100',
                                        'total' => null,
                                    ],
                                    'percentage_for_other_gender_distribution' => [
                                        'name' => __('Percentage for other gender distribution'),
                                        'indicators' => [423, 424, 425],
                                        'math' => '425 / (423 + 424 + 425) * 100',
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'governance_body_structure' => [
                                'name' => __('Highest governance body of the organisation constituted and structured'),
                                'indicators' => [5610, 4662, 4663, 4664, 4665],
                                'math' => null,
                                'total' => null,
                            ],
                        ],
                    ],
                    'significant_incidents_risks' => [
                        'name' => __('Significant Incidents/Risks and Risk Management Practices'),
                        'categories' => [
                            'discrimination_incidents' => [
                                'name' => __('Discrimination incidents'),
                                'categories' => [
                                    'incidents_of_discrimination' => [
                                        'name' => __('Incidents of discrimination, in particular resulting in the application of sanctions'),
                                        'indicators' => [2129],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'number_of_incidents_of_discrimination' => [
                                        'name' => __('Number of incidents of discrimination that have occurred'),
                                        'indicators' => [2130],
                                        'math' => '2130',
                                        'total' => null,
                                    ],
                                ],
                            ],
                            'corruption_and_bribery_assessment_and_risks' => [
                                'name' => __('Corruption and bribery assessment and risks'),
                                'categories' => [
                                    'risk_assessment_focus_on_corruption_bribery' => [
                                        'name' => __('Risk assessment with a focus on corruption and/or bribery'),
                                        'indicators' => [5193],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'risks_identified_in_assessment' => [
                                        'name' => __('Risks identified in the corruption and/or bribery assessment'),
                                        'indicators' => [5196],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'corruption_bribery_factors_in_overall_risk_assessments' => [
                                        'name' => __('Corruption and bribery factors are included in the overall risk assessments conducted by the organisation'),
                                        'indicators' => [5208],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'financial_contributions_to_associations' => [
                                        'name' => __('Organisation makes financial contributions to industry associations, lobby groups, political parties or similar'),
                                        'indicators' => [5220],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'corruption_and_bribery' => [
                        'name' => __('Corruption and Bribery Prevention'),
                        'categories' => [
                            'corruption_and_bribery_prevention' => [
                                'categories' => [
                                    'mechanisms_to_prevent_detect_handle' => [
                                        'name' => __('Mechanisms to prevent, detect and handle allegations or situations of corruption and bribery'),
                                        'indicators' => [5136],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'mechanism_for_reporting_situations' => [
                                        'name' => __('Mechanism for reporting situations of conflict of interest, corruption or bribery'),
                                        'indicators' => [5142],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'situations_of_corruption_bribery' => [
                                        'name' => __('Situations of corruption and/or bribery, during the reporting period'),
                                        'indicators' => [5150],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'confirmed_cases_result_in_convictions' => [
                                        'name' => __('Confirmed cases result in convictions'),
                                        'indicators' => [5161],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                    'judicial_proceedings_initiated' => [
                                        'name' => __('Judicial proceedings related to corruption and/or bribery been initiated against the organisation or its workers, in the reporting period'),
                                        'indicators' => [5166],
                                        'math' => null,
                                        'total' => null,
                                    ],
                                ],
                            ],

                            'corruption_reported' => [
                                'name' => __('Corruption Reported'),
                                'indicators' => [189],
                                'math' => '189',
                                'total' => null,
                            ],
                            'bribery_reported' => [
                                'name' => __('Bribery Reported'),
                                'indicators' => [5154],
                                'math' => '5154',
                                'total' => null,
                            ],

                            'corruption_confirmed' => [
                                'name' => __('Corruption Confirmed'),
                                'indicators' => [190],
                                'math' => '190',
                                'total' => null,
                            ],
                            'bribery_confirmed' => [
                                'name' => __('Bribery Confirmed'),
                                'indicators' => [5157],
                                'math' => '5157',
                                'total' => null,
                            ],
                            'corruption_terminated_contracts' => [
                                'name' => __('Corruption'),
                                'indicators' => [192],
                                'math' => '192',
                                'total' => null,
                            ],
                            'bribery_terminated_contracts' => [
                                'name' => __('Bribery'),
                                'indicators' => [5165],
                                'math' => '5165',
                                'total' => null,
                            ],
                            'corruption_legal_proceedings' => [
                                'name' => __('Corruption'),
                                'indicators' => [193],
                                'math' => '193',
                                'total' => null,
                            ],
                            'bribery_legal_proceedings' => [
                                'name' => __('Bribery'),
                                'indicators' => [5168],
                                'math' => '5168',
                                'total' => null,
                            ],
                        ],
                    ],
                    'corruption_bribery_risks' => [
                        'name' => __('Types of risks identified in the corruption and/or bribery assessment'),
                        'categories' => [
                            'commercial_bribery' => [
                                'name' => __('Commercial bribery'),
                                'indicators' => [5197],
                                'math' => '5197',
                                'total' => null,
                            ],
                            'extortion_and_solicitation' => [
                                'name' => __('Extortion and solicitation'),
                                'indicators' => [5198],
                                'math' => '5198',
                                'total' => null,
                            ],
                            'gifts_and_hospitality' => [
                                'name' => __('Gifts and hospitality'),
                                'indicators' => [5199],
                                'math' => '5199',
                                'total' => null,
                            ],
                            'fees_and_commissions' => [
                                'name' => __('Fees and commissions'),
                                'indicators' => [5200],
                                'math' => '5200',
                                'total' => null,
                            ],
                            'collusion' => [
                                'name' => __('Collusion'),
                                'indicators' => [5201],
                                'math' => '5201',
                                'total' => null,
                            ],
                            'trading_of_information' => [
                                'name' => __('Trading of information'),
                                'indicators' => [5202],
                                'math' => '5202',
                                'total' => null,
                            ],
                            'trading_in_influence' => [
                                'name' => __('Trading in influence'),
                                'indicators' => [5203],
                                'math' => '5203',
                                'total' => null,
                            ],
                            'embezzlement' => [
                                'name' => __('Embezzlement'),
                                'indicators' => [5204],
                                'math' => '5204',
                                'total' => null,
                            ],
                            'favouritism_nepotism_cronyism_clientelism' => [
                                'name' => __('Favouritism, nepotism, cronyism, clientelism'),
                                'indicators' => [5205],
                                'math' => '5205',
                                'total' => null,
                            ],
                            'other' => [
                                'name' => __('Other'),
                                'indicators' => [5206],
                                'math' => '5206',
                                'total' => null,
                            ],
                        ],
                    ],
                    'financial_information' => [
                        'name' => __('Financial Information'),
                        'categories' => [
                            'annual_revenue' => [
                                'name' => __('Annual Revenue'),
                                'indicators' => [2640],
                                'math' => '2640',
                                'total' => null,
                            ],
                            'annual_net_revenue' => [
                                'name' => __('Annual Net Revenue'),
                                'indicators' => [168],
                                'math' => '168',
                                'total' => null,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $params = request()->query();
        if ((isset($params['report']) && $params['report'] == 'true') || (isset($params['report_vertical']) && $params['report_vertical'] == 'true')) {

            $this->charts['report'] = [
                'name' => __('Reports'),
                'categories' => [
                    // Enviroment
                        'colaborators' => [
                            'name' => __('Colaborators'),
                            'indicators' => [513],
                            'math' => null,
                            'total' => 0,
                        ],
                        'financial_information' => [
                            'name' => __('Financial Information'),
                            'indicators' => [2640, 168, 2641, 2642, 2643, 2644, 2645, 2646, 2647],
                            'math' => null,
                            'total' => 0,
                        ],
                        'listed_company' => [
                            'name' => __('Listed company'),
                            'indicators' => [4885],
                            'math' => null,
                            'total' => 0,
                        ],
                        'financial_information_extra' => [
                            'name' => __('Financial Information'),
                            'indicators' => [2648, 2650, 2651, 2652, 2653],
                            'math' => null,
                            'total' => 0,
                        ],
                        'financial_information_extra2' => [
                            'name' => __('Financial Information'),
                            'indicators' => [4886, 2656, 2657, 2654],
                            'math' => null,
                            'total' => 0,
                        ],
                        'climate_change' => [
                            'name' => __('Climate Change'),
                            'indicators' => [5517, 6590],
                            'math' => null,
                            'total' => 0,
                        ],
                        'climate_change_mitigation' => [
                            'name' => __('Climate Change'),
                            'indicators' => [5691],
                            'math' => null,
                            'total' => 0,
                        ],
                        'transition_plan' => [
                            'name' => __('Transition plan'),
                            'indicators' => [3676, 3670],
                            'math' => null,
                            'total' => 0,
                        ],
                        'action_plan_climate_change_mitigation' => [
                            'name' => __('Action Plan Climate Change'),
                            'indicators' => [3671],
                            'math' => null,
                            'total' => 0,
                        ],
                        'resource_for_action' => [
                            'name' => __('Resources used to implement those actions'),
                            'indicators' => [3672, 3673, 3674, 3675],
                            'math' => null,
                            'total' => 0,
                        ],
                        'analysis_risk_opportunity' => [
                            'name' => __('Analysis and risk/opportunity identification'),
                            'indicators' => [3694, 5562],
                            'math' => null,
                            'total' => 0,
                        ],
                        'type_of_risk' => [
                            'name' => __('Types of risks identified in this analysis'),
                            'indicators' => [3705, 3706, 3707, 3708],
                            'math' => null,
                            'total' => 0,
                        ],
                        'risk_opportunity_management' => [
                            'name' => __('Risk/opportunity management'),
                            'indicators' => [3710, 3712, 6592, 3703, 3723, 3715],
                            'math' => null,
                            'total' => 0,
                        ],
                        'risk_strategically' => [
                            'name' => __('Climate-related risks with the potential to affect the organisation/business financially or strategically'),
                            'indicators' => [3716],
                            'math' => null,
                            'total' => 0,
                        ],
                        'co2_emission' => [
                            'name' => __('CO2 Emission'),
                            'indicators' => [3616],
                            'math' => '3616',
                            'total' => 0,
                        ],
                        'co2_emission1' => [
                            'name' => __('CO2 Emission'),
                            'indicators' => [3644],
                            'math' => '3644',
                            'total' => 0,
                        ],
                        'ghg_emission' => [
                            'name' => __('GHG Emission'),
                            'indicators' => [3660, 3661],
                            'math' => null,
                            'total' => 0,
                        ],
                        'water_marine' => [
                            'name' => __('Water and marine resources'),
                            'categories' => [
                                'water_consumed' => [
                                    'name' => __('Water consumed'),
                                    'indicators' => [3061],
                                    'math' => '3061',
                                    'total' => 0,
                                ],
                                'water_discharged' => [
                                    'name' => __('Water discharged'),
                                    'indicators' => [3976],
                                    'math' => '3976',
                                    'total' => 0,
                                ],
                                'water_treated' => [
                                    'name' => __('Water treated'),
                                    'indicators' => [6744],
                                    'math' => '6744',
                                    'total' => 0,
                                ],
                            ]
                        ],
                        'work_develop_water_resource' => [
                            'name' => __('Main results of the work developed with the stakeholders resulting from the definition of the water resources management strategy'),
                            'indicators' => [3998],
                            'math' => null,
                            'total' => 0,
                        ],
                        'organization_policy' => [
                            'name' => __('Organisation has a policy, strategy or plan to address impacts on water resources'),
                            'indicators' => [3994],
                            'math' => null,
                            'total' => 0,
                        ],
                        'policy_topics_cover' => [
                            'name' => __('Topics covered in the policy, strategy or plan to address water resource impacts'),
                            'indicators' => [3995, 3999, 4000, 4001],
                            'math' => null,
                            'total' => 0,
                        ],
                        'goal_defination' => [
                            'name' => __('goal'),
                            'indicators' => [4002, 4003],
                            'math' => null,
                            'total' => 0,
                        ],
                        'objective_targets' => [
                            'name' => __('The objectives and targets include the prevention and control of'),
                            'indicators' => [4004, 4005, 4006, 4007],
                            'math' => null,
                            'total' => 0,
                        ],
                        'risk_identification' => [
                            'name' => __('Analysis and risk/opportunity identification'),
                            'indicators' => [4018],
                            'math' => null,
                            'total' => 0,
                        ],
                        'process_used_identification' => [
                            'name' => __('Description of the process used for identification, assessment and management of risks and opportunities'),
                            'indicators' => [4019],
                            'math' => null,
                            'total' => 0,
                        ],
                        'risk_opportunity_management' => [
                            'name' => __('Risk/opportunity management'),
                            'indicators' => [5564, 4033],
                            'math' => null,
                            'total' => 0,
                        ],
                        'metrics_measure_opportunities' => [
                            'name' => __('Metrics to measure and manage water-related risks and opportunities disclosed'),
                            'indicators' => [4034],
                            'math' => null,
                            'total' => 0,
                        ],
                        'has_capital' => [
                            'name' => __('Has capital (monetary value) made available in response to water-related risks and opportunities'),
                            'indicators' => [4035],
                            'math' => null,
                            'total' => 0,
                        ],
                        'capital_made_available' => [
                            'name' => __('Capital made available (monetary value), i.e. capital expenditure, financing or investment implemented, in response'),
                            'indicators' => [4036],
                            'math' => '4036',
                            'total' => 0,
                        ],
                        'risk_opportunity_effects' => [
                            'name' => __('Risk/opportunity effects'),
                            'indicators' => [4038],
                            'math' => null,
                            'total' => 0,
                        ],
                        'risk_organisation_financially' => [
                            'name' => __('Risks related to water resources with potential to affect the organisation financially or strategically'),
                            'indicators' => [4039],
                            'math' => null,
                            'total' => 0,
                        ],
                        'opportunities_water_potential_benefit' => [
                            'name' => __('Identified water-related opportunities with the potential to benefit the organisation/business financially or strategically'),
                            'indicators' => [4067],
                            'math' => null,
                            'total' => 0,
                        ],
                        'opportunities_potential_benefit ' => [
                            'name' => __('Opportunities with the potential to benefit the organisation/business financially or strategically'),
                            'indicators' => [4068],
                            'math' => null,
                            'total' => 0,
                        ],
                        'risk_opportunity_policies' => [
                            'name' => __('Risk/opportunity policies'),
                            'indicators' => [4084, 4092, 4093, 4096],
                            'math' => null,
                            'total' => 0,
                        ],
                        'body' => [
                            'name' => __('Highest hierarchical level responsible for the implementation of the policy'),
                            'indicators' => [4097, 4099, 4100, 4101],
                            'math' => null,
                            'total' => 0,
                        ],
                        'body_approving_plan' => [
                            'name' => __('Highest hierarchical level responsible for approving the plan'),
                            'indicators' => [4115, 4116, 4117, 4118],
                            'math' => null,
                            'total' => 0,
                        ],
                        'body_approving_plan_deforest' => [
                            'name' => __('Highest hierarchical level responsible for the implementation of the policy'),
                            'indicators' => [6740, 6741, 6742, 6743],
                            'math' => null,
                            'total' => 0,
                        ],
                        'transition_plan' => [
                            'name' => __('Transition plan'),
                            'indicators' => [4106],
                            'math' => null,
                            'total' => 0,
                        ],
                        'transition_plan_year' => [
                            'name' => __('Year of implementation of transition plan for climate change'),
                            'indicators' => [4110],
                            'math' => '4110',
                            'total' => 0,
                        ],
                        'business_development_strategy' => [
                            'name' => __('Business development strategy is related to the implementation of the transition plan'),
                            'indicators' => [4113, 4114],
                            'math' => null,
                            'total' => 0,
                        ],
                        'activity_performance' => [
                            'name' => __('Activities performance and oportunities'),
                            'indicators' => [4124],
                            'math' => null,
                            'total' => 0,
                        ],
                        'opportunities_with_the_potential_benefit' => [
                            'name' => __('Opportunities with the potential to benefit the organization/business financially or strategically'),
                            'indicators' => [4125, 4127, 4128, 4129, 4131, 4132, 4133],
                            'math' => null,
                            'total' => 0,
                        ],
                        'biodiversity_policies' => [
                            'name' => __('Biodiversity policies'),
                            'indicators' => [4147],
                            'math' => null,
                            'total' => 0,
                        ],
                        'biodiversity_policies_ecosystems' => [
                            'name' => __('Topics covered in the policy, strategy or plan for addressing impacts on biodiversity and ecosystems'),
                            'indicators' => [4163, 4164, 4166, 4167, 5568],
                            'math' => null,
                            'total' => 0,
                        ],
                        'mitigation_objectives_action_plans' => [
                            'name' => __('Mitigation objectives and action plans'),
                            'indicators' => [6526, 6527, 6528, 6529, 6530, 6531, 6532, 6533 ],
                            'math' => null,
                            'total' => 0,
                        ],
                        'funds_finance_action' => [
                            'name' => __('Funds and sources to finance the actions defined by the organisation'),
                            'indicators' => [6534],
                            'math' => null,
                            'total' => 0,
                        ],
                        'operation_protected_area' => [
                            'name' => __('Has operations in or near protected areas and/or areas rich in biodiversity'),
                            'indicators' => [5638],
                            'math' => null,
                            'total' => 0,
                        ],
                        'total_number_operation_organisation' => [
                            'name' => __('Total number of operations of the organisation'),
                            'indicators' => [5639],
                            'math' => '5639',
                            'total' => 0,
                        ],
                        'operations_adjacent' => [
                            'name' => __('Number of operations in or adjacent to protected areas and/or areas rich in biodiversity'),
                            'indicators' => [5640],
                            'math' => '5640',
                            'total' => 0,
                        ],
                        'operations_located_sensitive' => [
                            'name' => __('Number of the organisation`s operations located in sensitive, protected or high biodiversity value areas, outside environmentally protected areas'),
                            'indicators' => [772],
                            'math' => '772',
                            'total' => 0,
                        ],
                        'practices_policies' => [
                            'name' => __('Practices and policies'),
                            'indicators' => [2145, 6525],
                            'math' => null,
                            'total' => 0,
                        ],
                        'addressing_impacts_land_use' => [
                            'name' => __('Topics covered in the policy, strategy or plan for addressing impacts on land use'),
                            'indicators' => [6693, 6694, 6695, 6696, 6697],
                            'math' => null,
                            'total' => 0,
                        ],
                        'mitigation_objectives_action_plans' => [
                            'name' => __('Mitigation objectives and action plans'),
                            'indicators' => [6698, 6699, 6700, 6701, 6702, 6703, 6704, 6705],
                            'math' => null,
                            'total' => 0,
                        ],
                        'funds_sources_finance' => [
                            'name' => __('Funds and sources to finance the actions defined by the organisation'),
                            'indicators' => [6706],
                            'math' => null,
                            'total' => 0,
                        ],
                        'deforestation_policies' => [
                            'name' => __('Practices and policies'),
                            'indicators' => [2148, 2149],
                            'math' => null,
                            'total' => 0,
                        ],
                        'deforestation_topic_covered' => [
                            'name' => __('Topics covered in the policy, strategy or plan for addressing the impacts of deforestation'),
                            'indicators' => [6707, 6708, 6709, 6710, 6711],
                            'math' => null,
                            'total' => 0,
                        ],
                        'deforestation_mitigation_objectives_action_plans' => [
                            'name' => __('Mitigation objectives and action plans'),
                            'indicators' => [6712, 6713, 6714, 6715, 6716, 6717, 6718, 6719],
                            'math' => null,
                            'total' => 0,
                        ],
                        'deforestation_finance' => [
                            'name' => __('Funds and sources to finance the actions defined by the organisation'),
                            'indicators' => [6720],
                            'math' => null,
                            'total' => 0,
                        ],
                        'analysis_risk_opportunity' => [
                            'name' => __('Analysis and risk/opportunity identification'),
                            'indicators' => [6721],
                            'math' => null,
                            'total' => 0,
                        ],
                        'risk_opportunity_deforestation' => [
                            'name' => __('Process for identifying, assessing, and managing risks and opportunities related to deforestation'),
                            'indicators' => [6722],
                            'math' => null,
                            'total' => 0,
                        ],
                        'risk_opportunity_management' => [
                            'name' => __('Risk/opportunity management'),
                            'indicators' => [6723, 6724, 6725],
                            'math' => null,
                            'total' => 0,
                        ],
                        'risk_opportunity_identified' => [
                            'name' => __('Risks and opportunities identified'),
                            'indicators' => [6726, 6727, 6728, 6729],
                            'math' => null,
                            'total' => 0,
                        ],
                        'risk_opportunity_identified' => [
                            'name' => __('Risks and opportunities identified'),
                            'indicators' => [6726, 6727, 6728, 6729],
                            'math' => null,
                            'total' => 0,
                        ],
                        'metrics_measure_manage_risks' => [
                            'name' => __('Metrics to measure and manage risks and opportunities related to deforestation are defined'),
                            'indicators' => [6730],
                            'math' => null,
                            'total' => 0,
                        ],
                        'metrics_measure_manage_risks_answer' => [
                            'name' => __('Metrics to measure and manage risks and opportunities related to deforestation disclosed by the organisation'),
                            'indicators' => [6731],
                            'math' => null,
                            'total' => 0,
                        ],
                        'has_capital_available' => [
                            'name' => __('Has capital (monetary value) made available, i.e. capital expenditure, financing or investment implemented, in response to deforestation-related risks and opportunities'),
                            'indicators' => [6732],
                            'math' => null,
                            'total' => 0,
                        ],
                        'has_capital_available_value' => [
                            'name' => __('Capital made available (monetary value), i.e. capital expenditure, financing or investment implemented, in response to deforestation-related risks and opportunities.'),
                            'indicators' => [6733],
                            'math' => '6733',
                            'total' => 0,
                        ],
                        'eforestation_related_opportunity' => [
                            'name' => __('Identified any deforestation-related opportunity with the potential to affect the organisation financially or strategically'),
                            'indicators' => [6734],
                            'math' => null,
                            'total' => 0,
                        ],
                        'eforestation_related_opportunity_value' => [
                            'name' => __('Deforestation related opportunities with the potential to affect the organisation/business financially or strategically'),
                            'indicators' => [6735],
                            'math' => null,
                            'total' => 0,
                        ],
                        'oraganization_policy_deforestation' => [
                            'name' => __('Organisation has a policy for managing risks and opportunities related to deforestation'),
                            'indicators' => [6736, 6737, 6738, 6739],
                            'math' => null,
                            'total' => 0,
                        ],
                        'oraganization_activity_impact' => [
                            'name' => __('organisation Activities Impact'),
                            'indicators' => [6669, 6675, 6681, 6687],
                            'math' => null,
                            'total' => 0,
                        ],
                        'oraganization_activity_related' => [
                            'name' => __('Activities related to the organisation'),
                            'indicators' => [6747],
                            'math' => null,
                            'total' => 0,
                        ],
                        'activity_description' => [
                            'name' => __('Activity description'),
                            'indicators' => [6749],
                            'math' => null,
                            'total' => 0,
                        ],
                        'average_value_tons_CO2' => [
                            'name' => __('Average value in tons of CO2 per passenger-km'),
                            'indicators' => [6745],
                            'math' => '6745',
                            'total' => 0,
                        ],
                        'average_gCO2' => [
                            'name' => __('Average in gCO2/MJ'),
                            'indicators' => [6748],
                            'math' => '6748',
                            'total' => 0,
                        ],
                        'average_percentage' => [
                            'name' => __('Average percentage of high-carbon technologies'),
                            'indicators' => [6746],
                            'math' => '6746',
                            'total' => 0,
                        ],
                        'oraganisation_automobile_sector' => [
                            'name' => __('Organisation with activities in the Automobile sector'),
                            'indicators' => [6774],
                            'math' => null,
                            'total' => 0,
                        ],
                        'oraganisation_automobile_sector_description' => [
                            'name' => __('Organisation with activities in the Automobile sector'),
                            'indicators' => [6775],
                            'math' => null,
                            'total' => 0,
                        ],
                        'avg_value_tons_co2_passenger_km' => [
                            'name' => __('Average value in tons of CO2 per passenger-km'),
                            'indicators' => [6776],
                            'math' => '6776',
                            'total' => 0,
                        ],
                        'high_carbon' => [
                            'name' => __('Average percentage of high-carbon technologies'),
                            'indicators' => [6777],
                            'math' => '6777',
                            'total' => 0,
                        ],
                        'other_activity' => [
                            'name' => __('Other activities'),
                            'indicators' => [6750, 6754, 6758, 6762, 6766, 6770, 6774],
                            'math' => null,
                            'total' => 0,
                        ],
                        'energy_sector' => [
                            'name' => __('Organisation with activities in the Energy sector'),
                            'indicators' => [6750],
                            'math' => null,
                            'total' => 0,
                        ],
                        'energy_sector_value' => [
                            'name' => __('Organisation with activities in the Energy sector'),
                            'indicators' => [6751],
                            'math' => null,
                            'total' => 0,
                        ],
                        'energy_sector_co2' => [
                            'name' => __('Average value in tons of CO2 per MWh'),
                            'indicators' => [6752],
                            'math' => '6752',
                            'total' => 0,
                        ],
                        'energy_sector_high_carbon' => [
                            'name' => __('Average percentage of high-carbon technologies (oil, gas, coal)'),
                            'indicators' => [6753],
                            'math' => '6753',
                            'total' => 0,
                        ],
                        'oil_sector' => [
                            'name' => __('Organisation with activities in the Oil and Gas sector'),
                            'indicators' => [6754],
                            'math' => null,
                            'total' => 0,
                        ],
                        'oil_sector_value' => [
                            'name' => __('Activity description'),
                            'indicators' => [6755],
                            'math' => null,
                            'total' => 0,
                        ],
                        'oil_sector_co2' => [
                            'name' => __('Average value in tons of CO2 per GJ'),
                            'indicators' => [6756],
                            'math' => '6756',
                            'total' => 0,
                        ],
                        'oil_sector_high_carbon' => [
                            'name' => __('Average percentage of high-carbon technologies'),
                            'indicators' => [6757],
                            'math' => '6757',
                            'total' => 0,
                        ],
                        'iron_steel_sector' => [
                            'name' => __('Organisation`s activities include the production of iron and steel, coke and metal ore '),
                            'indicators' => [6758],
                            'math' => null,
                            'total' => 0,
                        ],
                        'iron_steel_sector_value' => [
                            'name' => __('Activity description'),
                            'indicators' => [6759],
                            'math' => null,
                            'total' => 0,
                        ],
                        'iron_steel_sector_co2' => [
                            'name' => __('Average value in tons of CO2 per ton produced'),
                            'indicators' => [6760],
                            'math' => '6760',
                            'total' => 0,
                        ],
                        'iron_steel_sector_high_carbon' => [
                            'name' => __('Average percentage of high-carbon technologies'),
                            'indicators' => [6761],
                            'math' => '6761',
                            'total' => 0,
                        ],
                        'industry_sector' => [
                            'name' => __('Organisation`s activities  and/or services are related to extractive industries (NACE codes: 08 e 09)'),
                            'indicators' => [6762],
                            'math' => null,
                            'total' => 0,
                        ],
                        'industry_sector_value' => [
                            'name' => __('Activity description'),
                            'indicators' => [6763],
                            'math' => null,
                            'total' => 0,
                        ],
                        'industry_sector_co2' => [
                            'name' => __('Average value in tons of CO2 per ton produced'),
                            'indicators' => [6764],
                            'math' => '6764',
                            'total' => 0,
                        ],
                        'industry_sector_high_carbon' => [
                            'name' => __('Average percentage of high-carbon technologies'),
                            'indicators' => [6765],
                            'math' => '6765',
                            'total' => 0,
                        ],
                        'production_sector' => [
                            'name' => __('Organisation``s activities include cement, clinker, and lime production'),
                            'indicators' => [6766],
                            'math' => null,
                            'total' => 0,
                        ],
                        'production_sector_value' => [
                            'name' => __('Activity description'),
                            'indicators' => [6767],
                            'math' => null,
                            'total' => 0,
                        ],
                        'production_sector_co2' => [
                            'name' => __('Average value in tons of CO2 per ton produced'),
                            'indicators' => [6768],
                            'math' => '6768',
                            'total' => 0,
                        ],
                        'production_sector_high_carbon' => [
                            'name' => __('Average percentage of high-carbon technologies'),
                            'indicators' => [6769],
                            'math' => '6769',
                            'total' => 0,
                        ],
                        'aviation_sector' => [
                            'name' => __('Organisation with activities in the Aviation sector '),
                            'indicators' => [6770],
                            'math' => null,
                            'total' => 0,
                        ],
                        'aviation_sector_value' => [
                            'name' => __('Activity description'),
                            'indicators' => [6771],
                            'math' => null,
                            'total' => 0,
                        ],
                        'aviation_sector_co2' => [
                            'name' => __('Average value in tons of CO2 per ton produced'),
                            'indicators' => [6772],
                            'math' => '6772',
                            'total' => 0,
                        ],
                        'aviation_sector_high_carbon' => [
                            'name' => __('Average percentage of high-carbon technologies'),
                            'indicators' => [6773],
                            'math' => '6773',
                            'total' => 0,
                        ],
                    // Social
                        'social' => [
                            'name' => __('Social'),
                            'categories' => [
                                'total_contracted_subcontracted_workers' => [
                                    'name' => __('Total of contracted and subcontracted workers'),
                                    'indicators' => [513],
                                    'math' => '513',
                                    'total' => 0,
                                ],
                                'percentage_contract_workers_left_ina_year' => [
                                    'name' => __('Gender distribution of contract workers that left the organisation in the last 12 months'),
                                    'categories' => [
                                        'female' => [
                                            'name' => __('Female'),
                                            'indicators' => [363, 364, 365],
                                            'math' => '363 / (363 + 364 + 365) * 100',
                                            'total' => null,
                                        ],
                                        'male' => [
                                            'name' => __('Male'),
                                            'indicators' => [363, 364, 365],
                                            'math' => '364 / (363 + 364 + 365) * 100',
                                            'total' => null,
                                        ],
                                        'other' => [
                                            'name' => __('Other'),
                                            'indicators' => [363, 364, 365],
                                            'math' => '365 / (363 + 364 + 365) * 100',
                                            'total' => null,
                                        ],
                                    ],
                                ],
                                'local_community' => [
                                    'name' => __('Minorities and local community'),
                                    'indicators' => [4184, 4201],
                                    'math' => '513',
                                    'total' => 0,
                                ],
                                'low_wage' => [
                                    'name' => __('Lowest wage received by contracted workers by gender'),
                                    'categories' => [
                                        'female' => [
                                            'name' => __('Female'),
                                            'indicators' => [4379],
                                            'math' => '4379',
                                            'total' => 0,
                                        ],
                                        'male' => [
                                            'name' => __('Male'),
                                            'indicators' => [4380],
                                            'math' => '4380',
                                            'total' => 0,
                                        ],
                                        'other' => [
                                            'name' => __('Other'),
                                            'indicators' => [4381],
                                            'math' => '4381',
                                            'total' => 0,
                                        ],
                                    ],
                                ],
                                'local_minimum_weage' => [
                                    'name' => __('Local minimum wage'),
                                    'indicators' => [1686],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'justification_minimum_weage' => [
                                    'name' => __('Justification for the existence of contracted workers receiving less than the local minimum wage'),
                                    'indicators' => [5530],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'osh' => [
                                    'name' => __('Occupational safety and health (OSH)'),
                                    'indicators' => [4537, 4545],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'group_worker_osh' => [
                                    'name' => __('Groups of workers, activities or workplaces not covered by the OSH system and the motives for their exclusion'),
                                    'indicators' => [4548],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'osh_risk' => [
                                    'name' => __('Organisation has an OSH risk assessment system defined'),
                                    'indicators' => [4552],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'subcontracted_workers' => [
                                    'name' => __('Organisation has subcontracted workers'),
                                    'indicators' => [3465],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'remuneration_organization' => [
                                    'name' => __('Organisation is aware of the remuneration of the outsourced workers'),
                                    'indicators' => [1684],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'outsource_workers' => [
                                    'name' => __('Organisation has outsourced workers'),
                                    'indicators' => [3465],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'policies_practices' => [
                                    'name' => __('Policies and practices of the organisation are applicable to the outsourced workers (e.g. code of ethics and conduct, anti-corruption policy, etc.)'),
                                    'indicators' => [4348],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'collective_agreements' => [
                                    'name' => __('Collective agreements and representatives'),
                                    'indicators' => [5636, 4363, 4365],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'agreements_celebrated' => [
                                    'name' => __('Agreements celebrated with the labour representatives regarding respect for the human rights of workers'),
                                    'indicators' => [4366],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'affirmative_actions' => [
                                    'name' => __('Inclusion and affirmative actions'),
                                    'indicators' => [5009],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'commitments_affirmative' => [
                                    'name' => __('Commitments related to inclusion and/or affirmative action for people from groups at particular risk of vulnerability in its own workforce'),
                                    'indicators' => [5011],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'promotion_diversity' => [
                                    'name' => __('Promotion of diversity'),
                                    'indicators' => [5557],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'action_promote_diversity' => [
                                    'name' => __('Actions to promote diversity, equity and inclusion'),
                                    'indicators' => [5558],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'communication_complaints' => [
                                    'name' => __('Communication channels and complaints'),
                                    'indicators' => [2101, 2128, 2127, 2129],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'number_incidents' => [
                                    'name' => __('Number of incidents of discrimination that have occurred, in the reporting period'),
                                    'indicators' => [2130],
                                    'math' => '2130',
                                    'total' => 0,
                                ],
                                'risks_opportunities_assessment' => [
                                    'name' => __('Risks and/or opportunities assessment'),
                                    'indicators' => [6594, 6595],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'negative_impacts,' => [
                                    'name' => __('Negative impacts, risks and/or opportunities on workers identified'),
                                    'indicators' => [6596],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'prevention_mitigation' => [
                                    'name' => __('Prevention, mitigation and remediation of impacts'),
                                    'indicators' => [6597],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'processes_cooperate' => [
                                    'name' => __('Processes to cooperate with its workers in the prevention, mitigation and remediation of those negative impacts and/or risks that it causes or contributes to and/or, in the case of opportunities, the actions planned to pursue them'),
                                    'indicators' => [6598],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'organisation_risks_impact' => [
                                    'name' => __('Risks and impact management'),
                                    'indicators' => [6599, 4972],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'topic_covered_policy' => [
                                    'name' => __('Topics covered in the policy(ies) for managing impacts, risks and opportunities related to workers'),
                                    'indicators' => [4981, 4982, 4983, 4985, 4986, 4988, 4990],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'stackholder_risk' => [
                                    'name' => __('Risks and impact policies'),
                                    'indicators' => [4995, 4997, 5005],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'procedures_ensure_prevention' => [
                                    'name' => __('Implementation procedures to ensure prevention, mitigation and action in cases of discrimination'),
                                    'indicators' => [5007],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'migrant_workers_condition' => [
                                    'name' => __('Migrant workers condition'),
                                    'indicators' => [6600, 6601, 6602, 6603, 6604, 6605, 6606],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'motive_migrant_workers' => [
                                    'name' => __('Motive for migrant workers being paid less than the local minimum wage'),
                                    'indicators' => [6607],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'communication_channels' => [
                                    'name' => __('Organisation has communication channels available for migrant workers to communicate their concerns and/or complaints in order to resolve them'),
                                    'indicators' => [6608],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'satisfaction_impacts' => [
                                    'name' => __('Satisfaction and impacts'),
                                    'indicators' => [5340, 5262, 5263, 5282],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'remedy_negative_impacts' => [
                                    'name' => __('Processes to cooperate with consumers and/or end-users to prevent, mitigate and remedy negative impacts it causes or contributes to'),
                                    'indicators' => [5283],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'banned_products_services' => [
                                    'name' => __('Banned products or services'),
                                    'indicators' => [5550],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'impacts_assessment' => [
                                    'name' => __('Impacts assessment and identification'),
                                    'indicators' => [5404, 5408],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'negative_impacts_identified' => [
                                    'name' => __('Negative impacts identified on local communities'),
                                    'indicators' => [6609],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'communication_channels_complaints' => [
                                    'name' => __('Communication channels and complaints'),
                                    'indicators' => [5410, 5412, 5413, 5417, 6610],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'representatives' => [
                                    'name' => __('Agreement or commitment with representatives of affected communities with regard to respecting the communities human rights'),
                                    'indicators' => [6611],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'policies_investments' => [
                                    'name' => __('Policies and investments'),
                                    'indicators' => [5437, 5474],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'investments_made_community' => [
                                    'name' => __('Type of investments made in the community'),
                                    'indicators' => [5475, 5476, 5477, 5478, 5479, 5480, 5481, 5482, 5483, 5484, 5485],
                                    'math' => null,
                                    'total' => 0,
                                ],
                            ]
                        ],
                    // Governance
                        'governance' => [
                            'name' => __('GOVERNANCE'),
                            'categories' => [
                                'high_governance_body' => [
                                    'name' => __('Highest governance body'),
                                    'indicators' => [4670, 1577],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'high_governance_body_text' => [
                                    'name' => __('Highest governance body Comment'),
                                    'indicators' => [4680],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'role_mandate' => [
                                    'name' => __('Role and mandate'),
                                    'indicators' => [4681],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'each_mandate' => [
                                    'name' => __('Role and mandate'),
                                    'indicators' => [4682, 4683, 4684],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'members_selection' => [
                                    'name' => __('Process of members selection'),
                                    'indicators' => [4685],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'criteria_members_selection' => [
                                    'name' => __('Criteria used to select and appoint the members of the highest governance body of the organisation'),
                                    'indicators' => [4686, 4687, 4688, 4689, 4690],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'meeting' => [
                                    'name' => __('Frequency of the highest governance body meetings'),
                                    'indicators' => [4697, 4696, 4691, 4692, 4693, 4694, 4695],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'ethics_compliance_esg' => [
                                    'name' => __('ethics_compliance_esg'),
                                    'indicators' => [4698, 4699, 4700, 4701, 4702, 4707, 4708, 4719, 4720, 4721],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'code_conduct_ethics' => [
                                    'name' => __('Code of conduct or ethics'),
                                    'indicators' => [6612],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'topic_code_conduct_ethics' => [
                                    'name' => __('Topics included in the code of conduct or ethics or in the organisations policies'),
                                    'indicators' => [6613, 6614, 6615, 6616, 6617, 6618, 6619, 6620, 6621, 6622, 6623, 6624, 6625, 6626, 6627, 6628, 6629],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'policies_availability' => [
                                    'name' => __('Policies availability and training'),
                                    'indicators' => [6630, 6631, 4773, 6632, 6633],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'reporting_mechanism' => [
                                    'name' => __('Reporting mechanism'),
                                    'indicators' => [4777],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'whistleblowing' => [
                                    'name' => __('Issues that can be reported through the whistleblowing mechanism'),
                                    'indicators' => [6634, 6635, 6636, 6637, 6638, 6639, 6640, 6641, 6642, 6643, 6644, 6645, 6646, 6647, 6648, 6649, 6650],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'complaints_received' => [
                                    'name' => __('Complaints received'),
                                    'indicators' => [6651],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'no_complaints_received' => [
                                    'name' => __('Complaints received'),
                                    'indicators' => [4829],
                                    'math' => '4829',
                                    'total' => 0,
                                ],
                                'whistleblowing_mechanism' => [
                                    'name' => __('Issues that can be reported through the whistleblowing mechanism'),
                                    'indicators' => [6652, 6653, 6654, 6655, 6656, 6657, 6658, 6659, 6660, 6661,
                                        6662 ,6663, 6664, 6665, 6666, 6667, 6668],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'initiatives_changes' => [
                                    'name' => __('Organisation defined a set of initiatives/changes as a result of these complaints'),
                                    'indicators' => [4832],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'policies' => [
                                    'name' => __('Policies'),
                                    'indicators' => [1676,5122, 5125],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'member_highest_governance' => [
                                    'name' => __('Number of members of the highest governance body that have been informed of the policy'),
                                    'indicators' => [5123],
                                    'math' => '5123',
                                    'total' => 0,
                                ],
                                'policies_availability' => [
                                    'name' => __('Policies availability'),
                                    'indicators' => [5124, 5127, 5129, 5130, 5132, 5134],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'mechanism_investigation' => [
                                    'name' => __('Prevention mechanism and investigation'),
                                    'indicators' => [5136, 5137],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'reporting_research_bodies' => [
                                    'name' => __('Process for reporting research findings to each of the following bodies'),
                                    'indicators' => [5138, 5139, 5141],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'organisation_reporting_mechanism' => [
                                    'name' => __('mechanism'),
                                    'indicators' => [5142, 5150],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'convictions' => [
                                    'name' => __('Convictions'),
                                    'indicators' => [5161],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'case_contract' => [
                                    'name' => __('Confirmed cases in which contracts with business partners were terminated or not renewed as a result of violations'),
                                    'indicators' => [192],
                                    'math' => '192',
                                    'total' => 0,
                                ],
                                'case_bribery' => [
                                    'name' => __('Confirmed cases in which contracts with business partners were terminated or not renewed as a result of violations'),
                                    'indicators' => [5165],
                                    'math' => '5165',
                                    'total' => 0,
                                ],
                                'legal_proceedings' => [
                                    'name' => __('Legal proceedings'),
                                    'indicators' => [5533, 5166],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'worker_case_contract' => [
                                    'name' => __('Legal proceedings related to corruption and/or bribery have been initiated against the organisation or its workers'),
                                    'indicators' => [193],
                                    'math' => '193',
                                    'total' => 0,
                                ],
                                'worker_case_bribery' => [
                                    'name' => __('Legal proceedings related to corruption and/or bribery have been initiated against the organisation or its workers'),
                                    'indicators' => [5168],
                                    'math' => '5168',
                                    'total' => 0,
                                ],
                                'training_risk_assessment' => [
                                    'name' => __('Training plans and risk assessment'),
                                    'indicators' => [5169, 5193, 5196],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'risk_type' => [
                                    'name' => __('Types of risks identified in the corruption and/or bribery assessment'),
                                    'indicators' => [5197, 5198, 5199, 5200, 5201, 5202, 5203, 5204, 5205, 5206],
                                    'math' => null,
                                    'total' => 0,
                                ],
                                'corruption_bribery' => [
                                    'name' => __('corruption and bribery'),
                                    'indicators' => [5208, 5220],
                                    'math' => null,
                                    'total' => 0,
                                ],
                            ]
                        ]
                ]
            ];
        }
    }


    protected function parseCategories()
    {
        // We don't need to parse the categories multiple times
        if ($this->mainCategories) {
            return;
        }

        $mainCategoriesIds = [227, 228, 229];
        $mainCategories = [];
        $subCategories = [];

        array_map(
            function ($category) use ($mainCategoriesIds, &$mainCategories, &$subCategories) {
                $name = json_decode($category['name'], true);

                $category = (new Category())->forceFill($category);
                $category->setTranslations('name', $name);

                if (in_array($category->id, $mainCategoriesIds, false)) {
                    $mainCategories[] = $category;
                } else {
                    $subCategories[] = $category;
                }
            },
            $this->questionnaire->categories()->toArray()
        );

        $this->mainCategories = $mainCategories;
        $this->subCategories = $subCategories;
    }


    public function view($questionnaireId)
    {
        $this->setQuestionnaire($questionnaireId);

        $questionnaire = Questionnaire::find($questionnaireId);

        $this->setIndicatorsData($questionnaire);


        if (!$this->indicators) {
            $this->indicators = Indicator::all()->pluck('name', 'id')->toArray();
        }

        $charts = $this->parseChartsStructureCategories($this->charts);

        $actionPlan = $this->parseDataForChartActionPlan();
        $action_plan_table = $this->parseDataForChartActionPlanTable();

        $params = request()->query();
        if (isset($params['debug']) && $params['debug'] == 'true') {
            return response()->json($charts);
        }

        $view = 'tenant.dashboards.18';

        if (request()->report == true) {
            $view = 'tenant.dashboards.reports.18';
        } elseif (request()->report_vertical == true) {
            $view = 'tenant.dashboards.reports.18_vertical';
        }

        return tenantView($view, compact('charts', 'actionPlan', 'action_plan_table', 'questionnaire'));
    }
}
