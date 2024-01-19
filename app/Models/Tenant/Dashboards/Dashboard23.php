<?php

namespace App\Models\Tenant\Dashboards;

use App\Models\Tenant\Answer;
use App\Models\Tenant\InternalTag;
use App\Models\Traits\Dashboard;

class Dashboard23
{
    use Dashboard;

    /**
     * Radner view with data
     */
    public function view($questionnaireId = null)
    {
    }

    /**
     * Return data for single qiestionnaire
     */
    public function chartForOneQuestionnaire($questionnaireId)
    {
        $this->getDataByQuestionnaire($questionnaireId);

        $charts = [
            // Section : Main
                'sdg' => $this->parseDataForSDG(),

            //Section : Enviroment
                'waste_water_discharges' => [
                    [
                        'label' => __('Reporting units with WWTP'),
                        'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                    ],
                    [
                        'label' => __('Disposal of wastewater in the aquatic environment (river, stream, sea)'),
                        'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                    ],
                    [
                        'label' => __('Disposal of wastewater in the soil'),
                        'status' => $this->getValueByIndicatorId(5628, 'checkbox')
                    ],
                    [
                        'label' => __('Disposal of wastewater in other medium'),
                        'status' => $this->getValueByIndicatorId(4515, 'checkbox')
                    ],
                    [
                        'label' => __('Wastewater treatment plants (WWTPs)'),
                        'status' => $this->getValueByIndicatorId(4515, 'checkbox')
                    ],
                ],
                // Greenhouse gas emissions
                    'emission_gas' => [
                        [
                            'label' => __('GHG emissions monitoring'),
                            'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                        ],
                        [
                            'label' => __('Carbon sequestration capacity'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                        [
                            'label' => __('Monitoring air pollutant emissions'),
                            'status' => $this->getValueByIndicatorId(5628, 'checkbox')
                        ],
                    ],
                // Pressure on biodiversity
                    'enviroment_impact' => [
                        [
                            'label' => __('Environmental impact study'),
                            'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                        ],
                        [
                            'label' => __('Location in an environmental protection area or an area of high biodiversity value'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                    ],
                // Waste management
                    'waste_management' => [
                        [
                            'label' => __('Monitor the non-hazardous waste generated'),
                            'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                        ],
                        [
                            'label' => __('Monitor the non-hazardous waste generated'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                    ],
                // Circular economy
                    'moniter' => [
                        [
                            'label' => __('Monitoring food waste'),
                            'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                        ],
                        [
                            'label' => __('Circular economy measures'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                    ],
                // Environmental policies
                    'policies' => [
                        [
                            'label' => __('Environmental policy'),
                            'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                        ],
                        [
                            'label' => __('Emissions reduction policy'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                        [
                            'label' => __('Biodiversity protection policy'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                        [
                            'label' => __('Waste treatment and/or reduction strategy'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                    ],
                // Social
                    'social_policies' => [
                        [
                            'label' => __('Human rights policy'),
                            'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                        ],
                        [
                            'label' => __('Supplier policy'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                        [
                            'label' => __('Remuneration policy'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ]
                    ],
                    'local_development' => [
                        [
                            'label' => __('Local development programmes'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ]
                    ],
                // Governance
                    'legal_compliance' => [
                        [
                            'label' => __('Reporting units have formal internal mechanisms to ensure compliance with the legal requirements applicable to their activity'),
                            'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                        ]
                    ],
                    'ethics' => [
                        [
                            'label' => __('Reporting units which have a Code of Ethics'),
                            'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                        ],
                        [
                            'label' => __('Reporting units which have subscribed to the Global Code of Ethics for Tourism'),
                            'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                        ]
                    ],
                    'governance_policies' => [
                        [
                            'label' => __('Anti-corruption and fraud policy'),
                            'status' => $this->getValueByIndicatorId(5611, 'checkbox')
                        ],
                        [
                            'label' => __('Prevention and management of conflicts of interest Policy'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                        [
                            'label' => __('Data privacy policy'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                        [
                            'label' => __('Code of Ethics and Conduct for suppliers'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ],
                        [
                            'label' => __('Whistleblowing channel for employees'),
                            'status' => $this->getValueByIndicatorId(1920, 'checkbox')
                        ]
                    ]
        ];

        return [
            'charts' => $charts ?? null
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
}
