<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Answer;
use App\Models\Tenant\InternalTag;
use App\Models\Traits\Dashboard;

class Dashboard11
{
    use Dashboard;

    /**
     * Radner view with data
     */
    public function view($questionnaireId)
    {
        $this->getDataByQuestionnaire($questionnaireId);

        $view = 'tenant.dashboards.11';

        if (request()->print == true) {
            $view = 'tenant.dashboards.reports.11';
        } elseif (request()->print_vertical == true) {
            $view = 'tenant.dashboards.reports.11_vertical';
        }

        return tenantView(
            $view,
            [
                'questionnaire' => $this->questionnaire,
                'questionnaire' => $this->questionnaire,
                'action_plan' => $this->parseDataForChartActionPlan(),
                'action_plan_table' => $this->parseDataForChartActionPlanTable(),
                'alignment_with_sustainability_principles' => $this->parseGlobaMaturityByCategoryChart(),
                'questionnaire' => $this->questionnaire,
                // Enviroment
                // Climate
                'carbon_intensity' => $this->getValueByIndicatorId(5674),
                'energy_intensity' => $this->getValueByIndicatorId(5675),
                'energy_consumption' => $this->parseDataForChartsWithStaticLabels(
                    [__('Renewable'), __('Non-renewable')],
                    [787, 2120]
                ),
                'ghg' => $this->parseDataForChartsWithStaticLabels(
                    [__('Scope 1'), __('Scope 2'), __('Scope 3')],
                    [20, 21, 22]
                ),
                'store_ghg' => [[
                    'label' => __('Internal capacity to remove or store GHG'),
                    'status' => $this->getValueByIndicatorId(3659, 'value')
                ]],
                // Water
                'water_intensity' => $this->getValueByIndicatorId(5676),
                'water_consumed' => $this->getValueByIndicatorId(3061),
                'water_stress' => [[
                    'label' => __('Located in an area(s) of acute water stress'),
                    'status' => $this->getValueByIndicatorId(2139, 'value')
                ]],
                // ecosystems
                'biodiversity' => [[
                    'label' => __('Present on protected areas and/or areas of high biodiversity value'),
                    'status' => $this->getValueByIndicatorId(1779, 'value')
                ]],
                // waste
                'waste_produced' => $this->parseDataForChartsWithStaticLabels(
                    [__('Hazardous'), __('Non-hazardous')],
                    [4445, 5609]
                ),
                // Socal
                // Own
                'gender_distribution' => $this->parseDataForGenderDistribution(),
                'management_positions' => [[
                    'label' => __('Members of the local community in top management positions'),
                    'status' => $this->getValueByIndicatorId(4208, 'value')
                ]],
                'local_community' => [[
                    'label' => __('Contracted workers who are members of the local community'),
                    'status' => $this->getValueByIndicatorId(4201, 'value')
                ]],
                // Satisfaction Condition
                'perfomance' => $this->parseDataForPerfomace(),
                'pay_gap' => $this->getValueByIndicatorId(5663),
                'turnover' => $this->getValueByIndicatorId(369),
                // Safty
                'osh' => $this->parseDataForOSH(),
                // traning
                'capasity_development' => $this->getValueByIndicatorId(120),
                'ethic_conduct' => $this->getValueByIndicatorId(5614),
                'social_issues' => $this->getValueByIndicatorId(4841),
                // Social action
                'vulnerability' => [[
                    'label' => __('Commitments related to inclusion and/or affirmative action for people from groups at particular risk of vulnerability in its own workforce'),
                    'status' => $this->getValueByIndicatorId(5239, 'value')
                ]],
                'donation' => [[
                    'label' => __('Investment in infrastructure, service support, social projects, volunteering or donations in the community'),
                    'status' => $this->getValueByIndicatorId(5474, 'value')
                ]],
                'corporate' => [[
                    'label' => __('Corporate Social Responsibility policy'),
                    'status' => $this->getValueByIndicatorId(5486, 'value')
                ]],
                'mechanism' => [[
                    'label' => __('Mechanism to receive complaints from local communities'),
                    'status' => $this->getValueByIndicatorId(5412, 'value')
                ]],
                'social_activity' => $this->getValueByIndicatorId(2644),
                // Governance
                // Structure
                'mission' => [
                    [
                        'label' => __('MISSION AND VALUES'),
                        'status' => $this->getValueByIndicatorId(4659, 'value')
                    ]
                ],
                'purpose' => $this->getValueByIndicatorId(5633),
                'goal_issue' => [[
                    'label' => __('Inclusion of goals related to social and/or environmental issues'),
                    'status' => $this->getValueByIndicatorId(5631, 'value')
                ]],
                'member_status' => $this->getValueByIndicatorId(5610),
                'member' => [
                    [
                        'label' => __('Board of directors with executive members'),
                        'status' => $this->getValueByIndicatorId(4663, 'value')
                    ],
                    [
                        'label' => __('Board of directors with no executive members'),
                        'status' => $this->getValueByIndicatorId(4662, 'value')
                    ],
                    [
                        'label' => __('Mixed'),
                        'status' => $this->getValueByIndicatorId(4664, 'value')
                    ],
                    [
                        'label' => __('Management Board'),
                        'status' => $this->getValueByIndicatorId(4665, 'value')
                    ],
                ],
                'high_governance_body' => $this->parseDataForChartsWithStaticLabels(
                    [__('Male'), __('Female'), __('Other')],
                    [424, 423, 425]
                ),
                // Practices
                'governance_body' => $this->parseDataForGovernanceBody(),
                'governance_ethic' => $this->parseDataForGovernanceEthic(),
                'policy' => $this->parseDataForPolicy(),
                'risk' => $this->parseDataForRisk(),
                // Relationship with suppliers
                'industry_sector' => $this->parseDataForIndustrySector(),
                'enviroment_impact' => $this->getValueByIndicatorId(349),
                'social_impact' => $this->getValueByIndicatorId(486),
                'supplier_paid' => $this->getValueByIndicatorId(5666),
                'number_suppliers_industry_sector' => $this->getValueByIndicatorId(5652),
                'supplier' => [
                    [
                        'label' => __('Policy to avoid late payment to suppliers'),
                        'status' => $this->getValueByIndicatorId(5531, 'value')
                    ],
                    [
                        'label' => __('Supplier`s policy'),
                        'status' => $this->getValueByIndicatorId(1723, 'value')
                    ],
                    [
                        'label' => __('Local suppliers'),
                        'status' => $this->getValueByIndicatorId(5613, 'value')
                    ],
                ],
                // Financial information
                'annual_revenue' => $this->getValueByIndicatorId(2640),
                'annual_net_revenue' => $this->getValueByIndicatorId(168),
                'pridict_report' => $this->parseDataForPridictReport(),
                'pricipal' => $this->parseDataForPricipal(),
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
        if (request()->print || request()->print_vertical) {
            return [
                // Page 3
                'company' => $this->questionnaire->company()->first(),
                'business_sector' => $this->questionnaire->company->business_sector()->first(),
                'country' => getCountriesWhereIn([$this->questionnaire->company->country]),
                'annual_revenue' => $this->getValueByIndicatorId(2640),
                'annual_net_revenue' => $this->getValueByIndicatorId(168),
                'net_profit_loss' => $this->getValueByIndicatorId(2653),
                'ebit' => $this->getValueByIndicatorId(2641),
                'liabilities' => $this->getValueByIndicatorId(2642),
                'total_assets' => $this->getValueByIndicatorId(2643),
                'total_debt' => $this->getValueByIndicatorId(2650),
                'hr_activity' => $this->getValueByIndicatorId(2645),
                'activity_raw_matrial' => $this->getValueByIndicatorId(2647),
                'intrest_expanse' => $this->getValueByIndicatorId(2652),
                'colaborators' => $this->getValueByIndicatorId(513),
                'listed_company' => [
                    [
                        'label' => __('Listed company'),
                        'status' => $this->getValueByIndicatorId(4885, 'value')
                    ]
                ],
                // Page 8
                'gas' => [
                    [
                        'label' => __('Information on greenhouse gas emissions verified by an independent external entity'),
                        'status' => $this->getValueByIndicatorId(3653, 'value')
                    ],
                    [
                        'label' => __('Greenhouse gas (GHG) removal or storage capacity'),
                        'status' => $this->getValueByIndicatorId(3659, 'value')
                    ]
                ],
                // Page 9
                'water_stress' => [
                    [
                        'label' => __('Organisation located in an area(s) of acute water stress'),
                        'status' => $this->getValueByIndicatorId(5519, 'value')
                    ]
                ],
                // Page 10
                'impacts_biodiversity_ecosystems' => [
                    [
                        'label' => __('Organizations present on protected areas and/or areas of high biodiversity value'),
                        'status' => $this->getValueByIndicatorId(1779, 'value')
                    ],
                    [
                        'label' => __('Policy, strategy or plan to address impacts on biodiversity and ecosystems'),
                        'status' => $this->getValueByIndicatorId(4147, 'value')
                    ],
                    [
                        'label' => __('Mitigation objectives and targets related to biodiversity and ecosystems'),
                        'status' => $this->getValueByIndicatorId(4169, 'value')
                    ],
                    [
                        'label' => __('Planned actions to address impacts on biodiversity and ecosystems'),
                        'status' => $this->getValueByIndicatorId(4173, 'value')
                    ],
                ],
                // Page 16
                'traning_capcity' => [
                    [
                        'label' => __('Training and capacity development'),
                        'status' => $this->getValueByIndicatorId(4477, 'value')
                    ],
                    [
                        'label' => __('Regarding the organisations ethics and conduct'),
                        'status' => $this->getValueByIndicatorId(5634, 'value')
                    ],
                    [
                        'label' => __('Regarding social issues such as human rights, forced labour or modern slavery'),
                        'status' => $this->getValueByIndicatorId(4838, 'value')
                    ],
                    [ // TODO :: REVALIDATE INDICATOR ID NEEDED
                        'label' => __('Communication channels available to value chain workers'),
                        'status' => $this->getValueByIndicatorId(4327, 'value')
                    ],
                ],
                // Page 17
                'social_action' => [
                    [
                        'label' => __('Commitments related to inclusion and/or affirmative action for people from groups at particular risk of vulnerability in its own workforce'),
                        'status' => $this->getValueByIndicatorId(5239, 'value')
                    ],
                    [
                        'label' => __('Mechanism to receive complaints from local communities'),
                        'status' => $this->getValueByIndicatorId(5412, 'value')
                    ],
                    [
                        'label' => __('Investment in infrastructure, service support, social projects, volunteering or donations in the community'),
                        'status' => $this->getValueByIndicatorId(5474, 'value')
                    ],
                    [
                        'label' => __('Corporate Social Responsibility policy'),
                        'status' => $this->getValueByIndicatorId(5486, 'value')
                    ],
                ],
                // Page 19
                'corporate' => [
                    [
                        'label' => __('Corporate presentation'),
                        'status' => $this->getValueByIndicatorId(1557, 'value')
                    ],
                    [
                        'label' => __('Organisation chart'),
                        'status' => $this->getValueByIndicatorId(1560, 'value')
                    ],
                ],
                // Page 22
                'expenses_suppliers' => $this->getValueByIndicatorId(2646),
                'payment_suppliers' => $this->getValueByIndicatorId(5666),
                // Page 23
                'capital_expenditure' => $this->getValueByIndicatorId(2648),
                'share' => $this->getValueByIndicatorId(2657),
                'net_profit_loss' => $this->getValueByIndicatorId(2653),
                'equity' => $this->getValueByIndicatorId(2654),
                'net_debt' => $this->getValueByIndicatorId(2651),
                // Page 24
                'closing_price_share' => $this->getValueByIndicatorId(4886),
                'avg_cost_capital' => $this->getValueByIndicatorId(2656),
                'aditional_info' => $this->getValueByIndicatorId(5657, 'value'),
            ];
        }

        return [];
    }

    /**
     * Parse Data for Gender distriubution
     */
    public function parseDataForGenderDistribution()
    {
        $contactMale = $this->getValueByIndicatorId(66, 'value');
        $outsourceMale = $this->getValueByIndicatorId(208, 'value');

        $contactFemale = $this->getValueByIndicatorId(65, 'value');
        $outsourceFemale = $this->getValueByIndicatorId(207, 'value');

        $contactOther = $this->getValueByIndicatorId(67, 'value');
        $outsourceOther = $this->getValueByIndicatorId(3541, 'value');

        $labels = [__('Contracted'), __('Outsourced')];
        $data = [
            ['backgroundColor' => "#F90", 'data' => [$contactMale, $outsourceMale]],
            ['backgroundColor' => "#FBB040", 'data' => [$contactFemale, $outsourceFemale]],
            ['backgroundColor' => "#FFDDAB", 'data' => [$contactOther, $outsourceOther]]
        ];

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    protected function parseGlobaMaturityByCategoryChart()
    {
        $taggablesLables = [
            'principled-business',
            'strengthening-society',
            'leadership-commitment',
            'reporting-progress',
            'local-action',
        ];
        $taggables = InternalTag::whereIn('slug', $taggablesLables)->orderBy('id', 'asc')->get();

        $data = [];
        foreach ($taggables as $internalTags) {
            $data[] = $this->questionnaire->calculatePontuation(
                function ($q) use ($internalTags) {
                    return $q->internalTags->contains($internalTags);
                }
            );
        }

        return createQuestionnaireSpiderChart($data, $taggables->pluck('name')->toArray(), $taggables);
    }

    /**
     * Parse Data for OSH
     */
    public function parseDataForOSH()
    {
        $data = [[
            'label' => __('Occupational safety and health (OSH) system'),
            'status' => $this->getValueByIndicatorId(4537, 'value')
        ]];

        if (request()->print || request()->print_vertical) {
            array_push($data, [
                'label' => __('Any groups of workers, activities or workplaces not covered by the OSH system'),
                'status' => $this->getValueByIndicatorId(4545, 'value')
            ]);
        }

        return $data;
    }

    /**
     * Parse data for perfomace
     */
    public function parseDataForPerfomace()
    {
        $data = [
            [
                'label' => __('Performance evaluation process for contracted workers'),
                'status' => $this->getValueByIndicatorId(4447, 'value')
            ],
            [
                'label' => __('Policy to respect the rights to freedom of association and collective bargaining of contracted workers'),
                'status' => $this->getValueByIndicatorId(4367, 'value')
            ]
        ];

        if (request()->print || request()->print_vertical) {
            array_push($data, [
                'label' => __('Workers covered by collective bargaining agreements'),
                'status' => $this->getValueByIndicatorId(5636, 'value')
            ]);

            array_push($data, [
                'label' => __('Policy to respect the rights to freedom of association and collective bargaining of contract workers'),
                'status' => $this->getValueByIndicatorId(4367, 'value')
            ]);
        }

        return $data;
    }

    /**
     * Parse data for governace body
     */
    public function parseDataForGovernanceBody()
    {
        $data = [
            [
                'label' => __('The chair of the highest governance body also an executive director'),
                'status' => $this->getValueByIndicatorId(1577, 'value')
            ],
            [
                'label' => __('The role performed by the members of the highest governance body is defined'),
                'status' => $this->getValueByIndicatorId(4681, 'value')
            ],
            [
                'label' => __('Process for nominating and selecting members of the highest governance body and its committees'),
                'status' => $this->getValueByIndicatorId(4685, 'value')
            ],
            [
                'label' => __('The highest governance body have members with competencies related to economic, social and environmental topics'),
                'status' => $this->getValueByIndicatorId(4699, 'value')
            ],
            [
                'label' => __('The highest governance body is evaluated on its performance'),
                'status' => $this->getValueByIndicatorId(4707, 'value')
            ]
        ];

        if (request()->print || request()->print_vertical) {
            array_push($data, [
                'label' => __('Involvment of the highest governance body'),
                'status' => $this->getValueByIndicatorId(5344, 'value')
            ]);
        }

        return $data;
    }

    /**
     * Parse data for governace ethic
     */
    public function parseDataForGovernanceEthic()
    {
        if (request()->print || request()->print_vertical) {
            $data = [
                [
                    'label' => __('(Performance evaluation) include sustainability-related topics'),
                    'status' => $this->getValueByIndicatorId(4708, 'value')
                ],
                [
                    'label' => __('Code of conduct or ethics code'),
                    'status' => $this->getValueByIndicatorId(1597, 'value')
                ],
                [
                    'label' => __('Statement/policy reflecting its position on human rights violations, forced labour, child labour and discrimination'),
                    'status' => $this->getValueByIndicatorId(4836, 'value')
                ],
                [
                    'label' => __('Reporting mechanism in the event of violation of the code of ethics and conduct, illicit behaviour or behaviour related to the integrity of the organisation regarding taxes'),
                    'status' => $this->getValueByIndicatorId(4777, 'value')
                ],
                [
                    'label' => __('Anti-corruption and anti-bribery policy'),
                    'status' => $this->getValueByIndicatorId(5129, 'value')
                ],
            ];
        } else {
            $data = [
                [
                    'label' => __('Code of conduct or ethics code'),
                    'status' => $this->getValueByIndicatorId(1597, 'value')
                ],
                [
                    'label' => __('Statement/policy reflecting its position on human rights violations, forced labour, child labour and discrimination'),
                    'status' => $this->getValueByIndicatorId(4836, 'value')
                ],
                [
                    'label' => __('Reporting mechanism in the event of violation of the code of ethics and conduct, illicit behaviour or behaviour related to the integrity of the organisation regarding taxes'),
                    'status' => $this->getValueByIndicatorId(4777, 'value')
                ],
                [
                    'label' => __('Anti-corruption and anti-bribery policy'),
                    'status' => $this->getValueByIndicatorId(5129, 'value')
                ],
                [
                    'label' => __('Mechanisms to prevent, detect and handle allegations or situations of corruption and bribery'),
                    'status' => $this->getValueByIndicatorId(5136, 'value')
                ]
            ];
        }

        return $data;
    }

    /**
     * Parse data for industry sector
     */
    public function parseDataForIndustrySector()
    {
        return $this->parseDataForChartsWithStaticLabels(
            [
                __('Consumer goods'), __('Extractives & mineral processing'), __('Financials'), __('Food & Beverage'),
                __('Health care'), __('Infrastructure'), __('Renewable resources & Alternative energy'),
                __('Resources transformation'), __('Services'), __('Real estate'), __('Information technology'),
                __('Construction & engineering'), __('Logistics/Transportation'), __('Other')
            ],
            [148, 149, 150, 151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 5054]
        );
    }

    /**
     * Parse data for pridict report
     */
    public function parseDataForPridictReport()
    {
        return [
            [
                'label' => __('Financial report'),
                'status' => $this->getValueByIndicatorId(3412, 'value')
            ],
            [
                'label' => __('Impact report'),
                'status' => $this->getValueByIndicatorId(3413, 'value')
            ],
            [
                'label' => __('Sustainability report'),
                'status' => $this->getValueByIndicatorId(3414, 'value')
            ],
            [
                'label' => __('Activities report'),
                'status' => $this->getValueByIndicatorId(3415, 'value')
            ],
            [
                'label' => __('Sales report'),
                'status' => $this->getValueByIndicatorId(3416, 'value')
            ],
            [
                'label' => __('Customer Satisfaction report'),
                'status' => $this->getValueByIndicatorId(3417, 'value')
            ],
            [
                'label' => __('Satisfaction / Welfare Report of Workers'),
                'status' => $this->getValueByIndicatorId(3418, 'value')
            ],
            [
                'label' => __('Other'),
                'status' => $this->getValueByIndicatorId(3419, 'value')
            ]
        ];
    }

    /**
     * Parse data for pricipal
     */
    public function parseDataForPricipal()
    {
        return [
            [
                'label' => __('UN Global Compact'),
                'status' => $this->getValueByIndicatorId(3420, 'value')
            ],
            [
                'label' => __('Principles of Responsability Investment'),
                'status' => $this->getValueByIndicatorId(3422, 'value')
            ],
            [
                'label' => __('Science Based Targets'),
                'status' => $this->getValueByIndicatorId(3421, 'value')
            ],
            [
                'label' => __('B-Corp'),
                'status' => $this->getValueByIndicatorId(3423, 'value')
            ],
            [
                'label' => __('Principles of responsible bank'),
                'status' => $this->getValueByIndicatorId(3425, 'value')
            ],
            [
                'label' => __('World Business Council for Sustainable Development (WBCSD)'),
                'status' => $this->getValueByIndicatorId(5616, 'value')
            ]
        ];
    }

    /**
     * Prase data for policy
     */
    public function parseDataForPolicy()
    {
        $data = [
            [
                'label' => __('Remuneration policy'),
                'status' => $this->getValueByIndicatorId(1663, 'value')
            ],
            [
                'label' => __('Compensation variable based on the performance of sustainability goals'),
                'status' => $this->getValueByIndicatorId(5612, 'value')
            ],
        ];

        if (request()->print || request()->print_vertical) {
            array_push($data, [
                'label' => __('Policy disclosed to members of the highest governance body'),
                'status' => $this->getValueByIndicatorId(5130, 'value')
            ]);

            array_push($data, [
                'label' => __('Policy made known to workers employed by the organisation'),
                'status' => $this->getValueByIndicatorId(5132, 'value')
            ]);

            array_push($data, [
                'label' => __('Policy disclosed to the organisations business partners and suppliers'),
                'status' => $this->getValueByIndicatorId(5134, 'value')
            ]);

            array_push($data, [
                'label' => __('Mechanisms to prevent, detect and handle allegations or situations of corruption and bribery'),
                'status' => $this->getValueByIndicatorId(5136, 'value')
            ]);
        }

        return $data;
    }

    /**
     * Prase data for risk management
     */
    public function parseDataForRisk()
    {
        $data = [
            [
                'label' => __('Risk assessment with a focus on corruption and/or bribery'),
                'status' => $this->getValueByIndicatorId(5193, 'value')
            ],
            [
                'label' => __('Practice of carrying out risk and opportunity analysis in operations, development and introduction of new products'),
                'status' => $this->getValueByIndicatorId(5324, 'value')
            ],
            [
                'label' => __('Risk management processes regarding environmental and/or social issues'),
                'status' => $this->getValueByIndicatorId(5350, 'value')
            ],
        ];

        if (request()->print || request()->print_vertical) {
            array_push($data, [
                'label' => __('When identifying potential risks, the organization involves different working groups, occupational safety and health (OSH) committees and other entities to discuss impacts'),
                'status' => $this->getValueByIndicatorId(5281, 'value')
            ]);

            array_push($data, [
                'label' => __('Anti-corruption and anti-bribery policy'),
                'status' => $this->getValueByIndicatorId(5129, 'value')
            ]);
        }

        return $data;
    }
}
