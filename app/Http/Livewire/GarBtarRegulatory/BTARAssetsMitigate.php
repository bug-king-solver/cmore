<?php

namespace App\Http\Livewire\GarBtarRegulatory;

use App\Models\Tenant\GarBtar\BankAssets;
use Illuminate\View\View;

class BTARAssetsMitigate extends BaseMitigate
{

    public $rows;
    public $search;
    public $frameworkList;
    public $framework;
    public $optionsKpi;
    public $kpi;
    public $letters;

    public $rowsPercentageTable;
    public $lettersPercentageTable;
    public $dataPercentageTable;
    public $formatToCurrencyPercentageTable;
    public $searchPercentageTable;
    public $frameworkPercentageTable;
    public $kpiPercentageTable;

    public $dataSummary;

    public function fillData()
    {
        $this->rows = [];

        $bankAssets = new BankAssets();
        $defaultArray = [['value' => ''], ['value' => ''], ['value' => ''], ['value' => ''], ['value' => ''], ['value' => '']];
        $arrayBackgrounds = [null, "#757575", "#757575", "#757575", "#757575", "#757575"];

        // RAE total dos ativos
        $dataGAR = [
            $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1, 2, 3], [1, 2, 3, 4])),
            $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1, 2, 3], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::YES)),
            $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([4, 5, 6, 7, 8, 9])),
        ];
        $totalGAR = [
            $this->getTotalValues($dataGAR[0]),
            $this->getTotalValues($dataGAR[1]),
            $this->getTotalValues($dataGAR[2]),
        ];

        $totalGARPercentageTableageLine = [
            $this->getTotalValues($dataGAR[0], true, false, $this->kpiPercentageTable, $this->frameworkPercentageTable),
            $this->getTotalValues($dataGAR[1], true, false, $this->kpiPercentageTable, $this->frameworkPercentageTable),
            $this->getTotalValues($dataGAR[2], true, false, $this->kpiPercentageTable, $this->frameworkPercentageTable),
        ];
        $totalGARLine = $this->getSumatoryTotalValues($totalGAR);
        $totalGARLinePercentage = $this->getSumatoryTotalValues($totalGARPercentageTableageLine);
        $items = [
            $this->createItem(__('Total GAR Assets'), $totalGARLine),
        ];

        // Ativos excluídos do numerador para cálculo do RAE (abrangidos no denominador), mas incluídos no numerador e no denominador do BTAR
        $items[] = $this->createItem(__('Assets excluded from the numerator for GAR calculation (covered in the denominator) but included in the numerator and denominator of the BTAR'), [], 1, [
            'specsTitle' => ['class' => 'uppercase font-bold'],
            'singleRow' => true,
            'index' => false,
            'background' => '#E1E6EF'
        ]);

        $dataNonFinancialCorporationsEU = [
            $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::YES)),
            $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([2], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::YES)),
            $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([3], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::YES)),
        ];
        $totalNonFinancialCorporationsEU = [
            $this->getTotalValues($dataNonFinancialCorporationsEU[0]),
            $this->getTotalValues($dataNonFinancialCorporationsEU[1]),
            $this->getTotalValues($dataNonFinancialCorporationsEU[2]),
        ];
        $totalNonFinancialCorporationsEUPercentage = [
            $this->getTotalValues($dataNonFinancialCorporationsEU[0], true, false, $this->kpiPercentageTable, $this->frameworkPercentageTable),
            $this->getTotalValues($dataNonFinancialCorporationsEU[1], true, false, $this->kpiPercentageTable, $this->frameworkPercentageTable),
            $this->getTotalValues($dataNonFinancialCorporationsEU[2], true, false, $this->kpiPercentageTable, $this->frameworkPercentageTable),
        ];

        $dataNonFinancialCorporationsNonEU = [
            $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::NO)),
            $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([2], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::NO)),
            $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([3], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::NO)),
        ];
        $totalNonFinancialCorporationsNonEU = [
            $this->getTotalValues($dataNonFinancialCorporationsNonEU[0]),
            $this->getTotalValues($dataNonFinancialCorporationsNonEU[1]),
            $this->getTotalValues($dataNonFinancialCorporationsNonEU[2]),
        ];
        $totalNonFinancialCorporationsNonEUPercentage = [
            $this->getTotalValues($dataNonFinancialCorporationsNonEU[0], true, false, $this->kpiPercentageTable, $this->frameworkPercentageTable),
            $this->getTotalValues($dataNonFinancialCorporationsNonEU[1], true, false, $this->kpiPercentageTable, $this->frameworkPercentageTable),
            $this->getTotalValues($dataNonFinancialCorporationsNonEU[2], true, false, $this->kpiPercentageTable, $this->frameworkPercentageTable),
        ];
        $totalNonFinancialCorporationsEULine = $this->getSumatoryTotalValues($totalNonFinancialCorporationsEU);
        $totalNonFinancialCorporationsNonEULine = $this->getSumatoryTotalValues($totalNonFinancialCorporationsNonEU);
        $totalNonFinancialCorporationsEULinePercentage = $this->getSumatoryTotalValues($totalNonFinancialCorporationsEUPercentage);
        $totalNonFinancialCorporationsNonEULinePercentage = $this->getSumatoryTotalValues($totalNonFinancialCorporationsNonEUPercentage);

        // Empresas não financeiras da UE (não sujeitas às obrigações de divulgação da Diretiva NFI)
        $items[] = $this->createItem(
            __('EU Non-financial corporations'),
            $totalNonFinancialCorporationsEULine,
            1,
            [
                'specsTitle' => ['color' => '#39B54A', 'class' => 'font-bold'],
                'subtitle' => [
                    'text' => __('(not subject to NFRD disclosure obligations)'),
                    'color' => '#444444',
                    'class' => 'font-normal'
                ]
            ]
        );

        // Empréstimos e adiantamentos
        $items[] = $this->createItem(__('Loans and advances'), $totalNonFinancialCorporationsEU[0]);
        // dos quais, empréstimos garantidos por imóveis comerciais
        $items[] = $this->createItem(__('Loans collateralised by'), $defaultArray, 3, [
            'subtitle' => [
                'text' => __('commercial immovable property'),
                'color' => '#44724D',
                'class' => 'font-bold'
            ]
        ]);
        // dos quais, empréstimos para a renovação de edifícios
        $items[] = $this->createItem(__('Building'), $defaultArray, 3, [
            'specsTitle' => ['class' => 'font-bold text-[#44724D]'],
            'subtitle' => [
                'text' => __('renovation loans'),
                'color' => '#444444',
                'class' => 'font-normal'
            ]
        ]);

        // Títulos de dívida
        $items[] = $this->createItem(__('Debt securities'), $totalNonFinancialCorporationsEU[1]);
        // Instrumentos de capital próprio
        $items[] = $this->createItem(__('Equity instruments'), $totalNonFinancialCorporationsEU[2]);

        // Empresas não financeiras exteriores à UE (não sujeitas às obrigações de divulgação da Diretiva NFI)
        $items[] = $this->createItem(
            __('Non-EU Non-financial corporations'),
            $totalNonFinancialCorporationsNonEULine,
            1,
            [
                'specsTitle' => ['color' => '#39B54A', 'class' => 'font-bold'],
                'subtitle' => [
                    'text' => __('(not subject to NFRD disclosure obligations)'),
                    'color' => '#444444',
                    'class' => 'font-normal'
                ]
            ]
        );
        // Empréstimos e adiantamentos
        $items[] = $this->createItem(__('Loans and advances'), $totalNonFinancialCorporationsNonEU[0]);
        // Títulos de dívida
        $items[] = $this->createItem(__('Debt securities'), $totalNonFinancialCorporationsNonEU[1]);
        // Instrumentos de capital próprio
        $items[] = $this->createItem(__('Equity instruments'), $totalNonFinancialCorporationsNonEU[2]);

        // TOTAL DOS ATIVOS DO RATCB
        $totalBTAR = $this->getSumatoryTotalValues([$totalGARLine, $totalNonFinancialCorporationsEULine, $totalNonFinancialCorporationsNonEULine]);
        $totalBTARPercentage = $this->getSumatoryTotalValues([$totalGARLinePercentage, $totalNonFinancialCorporationsEULinePercentage, $totalNonFinancialCorporationsNonEULinePercentage]);
        $items[] = $this->createItem(__('Total BTAR assets'), $totalBTAR, 1, [
            'specsTitle' => ['class' => 'uppercase font-bold', 'color' => '#39B54A']
        ]);

        // Ativos excluídos do numerador do BTAR (abrangidos no denominador)
        $items[] = $this->createItem(__('Assets excluded from the numerator of BTAR (covered in the denominator)'), [], 1, [
            'specsTitle' => ['class' => 'uppercase font-bold'],
            'singleRow' => true,
            'index' => false,
            'background' => '#E1E6EF'
        ]);

        // Derivados
        $totalDerivatives = $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([10]), true), true, true, null, null, $arrayBackgrounds);
        $items[] = $this->createItem(__('Derivatives'), $totalDerivatives);

        // Empréstimos interbancários à vista
        $totalInterbankLoans = $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([11]), true), true, true, null, null, $arrayBackgrounds);
        $items[] = $this->createItem(__('On demand interbank loans'), $totalInterbankLoans);

        // Ativos em numerário e equivalentes a numerário
        $totalCashRelatedAssets = $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([12]), true), true, true, null, null, $arrayBackgrounds);
        $items[] = $this->createItem(__('Cash and cash-related assets'), $totalCashRelatedAssets);

        // Outros ativos (p. ex.: goodwill, mercadorias, etc.)
        $totalOtherAssets = $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([13]), true), true, true, null, null, $arrayBackgrounds);
        $items[] = $this->createItem(__('Other assets (e.g. Goodwill, commodities etc.)'), $totalOtherAssets);

        // TOTAL DOS ATIVOS NO DENOMINADOR (RAE)
        $totalAssetsDenominator = $this->getSumatoryTotalValues([
            $totalGARLine,
            $totalNonFinancialCorporationsEULine,
            $totalNonFinancialCorporationsNonEULine,
            $totalDerivatives,
            $totalInterbankLoans,
            $totalCashRelatedAssets,
            $totalOtherAssets,
        ], false, $arrayBackgrounds);
        $items[] = $this->createItem(__('Total assets in the denominator'), $totalAssetsDenominator, 1, [
            'specsTitle' => ['class' => 'uppercase font-bold', 'color' => '#39B54A']
        ]);


        // Outros ativos excluídos tanto do numerador como do denominador para efeitos do cálculo do BTAR
        $items[] = $this->createItem(__('Other assets excluded from both the numerator and denominator for BTAR calculation'), [], 1, [
            'specsTitle' => ['class' => 'font-bold uppercase'],
            'singleRow' => true,
            'index' => false,
            'background' => '#E1E6EF',
        ]);

        // TOTAL DOS ATIVOS EXCLUÍDOS DO NUMERADOR E DO DENOMINADOR
        $totalAssetsExcluded = $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([14, 15, 16]), true), true, true, null, null, $arrayBackgrounds);
        $items[] = $this->createItem(__('Total assets excluded from numerator and denominator'), $totalAssetsExcluded, 1, [
            'specsTitle' => ['class' => 'uppercase font-bold', 'color' => '#39B54A']
        ]);


        // TOTAL DOS ATIVOS
        $totalAssets = $this->getSumatoryTotalValues([
            $totalAssetsDenominator,
            $totalAssetsExcluded,
        ]);

        $items[] = $this->createItem(__('Total assets'), $totalAssets, 1, [
            'specsTitle' => ['class' => 'uppercase font-bold'],
            'background' => '#E1E6EF',
        ]);
        $index = 1;
        foreach ($items as $i => $item) {
            $newItem = [
                'id' => $i,
                'title' => $item['title'],
                'singleRow' => $item['singleRow'] ?? false,
                'values' => $item['values'],
                'background' => $item['background'] ?? null,
            ];
            if (isset($item['index']) && $item['index']) {
                $newItem['rowNumber'] = str_pad($index, 2, "0", STR_PAD_LEFT);
                $index++;
            }
            $this->rows[] = $newItem;
        }


        // Percentage Table
        $relevantForRatios = $bankAssets->getAssetsRelevantForRatios();
        $totalBankAssets = $bankAssets->getBankTotalAssets();
        if ($this->frameworkPercentageTable !== 'total') {
            array_pop($defaultArray);
        }
        $this->rowsPercentageTable = [];

        // RATCB
        $totalBTARPercentageLine = [];
        foreach($totalBTARPercentage as $key => $item) {
            if ($this->frameworkPercentageTable !== 'total' && $key === 0) {
                continue;
            }
            $totalBTARPercentageLine[$key] = ['value' => 0];
        }

        // RAE
        $totalGARPercentageLine = [];
        foreach($totalGARLinePercentage as $key => $item) {
            $denominator = $relevantForRatios;
            if ($this->frameworkPercentageTable === 'total') {
                if ($key === 0) {
                    $denominator = $totalBankAssets;
                    $key = sizeof($defaultArray) - 1;
                } else {
                    $key -= 1;
                }
            } else if ($key === 0) {
                continue;
            }
            $totalGARPercentageLine[$key] = ['value' => calculatePercentage($item['value'], $denominator[$this->kpiPercentageTable], 2)];
            $totalBTARPercentageLine[$key]['value'] += $totalGARPercentageLine[$key]['value'];
        }

        // Empresas não financeiras da UE não sujeitas às obrigações de divulgação da Diretiva NFI
        $totalEUNonFinancialPercentageLine = [];
        foreach($totalNonFinancialCorporationsEULinePercentage as $key => $item) {
            $denominator = $totalAssetsDenominator[0]['value'];
            $numerator = $item['value'];
            if ($this->frameworkPercentageTable === 'total') {
                if ($key === 0) {
                    $numerator = $this->sumPercentageTable($dataNonFinancialCorporationsEU);
                    $denominator = $totalBankAssets[$this->kpiPercentageTable];
                    $key = sizeof($defaultArray) - 1;
                } else {
                    $key -= 1;
                }
            } else if ($key === 0) {
                continue;
            }
            $totalEUNonFinancialPercentageLine[$key] = ['value' => calculatePercentage($numerator, $denominator, 2)];
            $totalBTARPercentageLine[$key]['value'] += $totalEUNonFinancialPercentageLine[$key]['value'];
        }

        // Contrapartes de países terceiros não sujeitas às obrigações de divulgação da NFRD
        $totalNonEUCounterPartiesPercentageLine = [];
        foreach($totalNonFinancialCorporationsNonEULinePercentage as $key => $item) {
            $denominator = $totalAssetsDenominator[0]['value'];
            $numerator = $item['value'];
            if ($this->frameworkPercentageTable === 'total') {
                if ($key === 0) {
                    $numerator = $this->sumPercentageTable($dataNonFinancialCorporationsNonEU);
                    $denominator = $totalBankAssets[$this->kpiPercentageTable];
                    $key = sizeof($defaultArray) - 1;
                } else {
                    $key -= 1;
                }
            } else if ($key === 0) {
                continue;
            }
            $totalNonEUCounterPartiesPercentageLine[$key] = ['value' => calculatePercentage($numerator, $denominator, 2)];
            $totalBTARPercentageLine[$key]['value'] += $totalNonEUCounterPartiesPercentageLine[$key]['value'];
        }

        // RATCB
        $items = [
            $this->createItem(__('BTAR'), $totalBTARPercentageLine, 1, [
                'specsTitle' => ['class' => 'uppercase font-bold', 'color' => '#39B54A'],
            ]),
        ];

        // RAE
        $items[] = $this->createItem(__('GAR'), $totalGARPercentageLine, 2, [
            'specsTitle' => ['class' => 'uppercase font-bold', 'color' => '#39B54A'],
        ]);

        // Empresas não financeiras da UE não sujeitas às obrigações de divulgação da Diretiva NFI
        $items[] = $this->createItem(
            __('EU Non-financial corporations'),
            $totalEUNonFinancialPercentageLine,
            3,
            [
                'specsTitle' => ['color' => '#39B54A', 'class' => 'font-bold'],
                'subtitle' => [
                    'text' => __('not subject to NFRD disclosure obligations'),
                    'color' => '#444444',
                    'class' => 'font-normal'
                ]
            ]
        );

        // dos quais, empréstimos garantidos por imóveis comerciais
        $items[] = $this->createItem(
            __('Loans collateralised by'),
            $defaultArray,
            4,
            [
                'subtitle' => [
                    'text' => __('commercial immovable property'),
                    'color' => '#44724D',
                    'class' => 'font-bold',
                ]
            ]
        );

        // dos quais, empréstimos para a renovação de edifícios
        $items[] = $this->createItem(
            __('Building renovation'),
            $defaultArray,
            4,
            [
                'specsTitle' => ['color' => '#44724D', 'class' => 'font-bold'],
                'subtitle' => [
                    'text' => __('loans'),
                    'color' => '#444444',
                    'class' => 'font-normal',
                ]
            ]
        );

        // Contrapartes de países terceiros não sujeitas às obrigações de divulgação da NFRD
        $items[] = $this->createItem(
            __('Non-EU country counterparties'),
            $totalNonEUCounterPartiesPercentageLine,
            3,
            [
                'specsTitle' => ['color' => '#39B54A', 'class' => 'font-bold'],
                'subtitle' => [
                    'text' => __('not subject to NFRD disclosure obligations'),
                    'color' => '#444444',
                    'class' => 'font-normal'
                ]
            ]
        );

        $index = 1;
        foreach ($items as $i => $item) {
            $newItem = [
                'id' => $i,
                'title' => $item['title'],
                'singleRow' => $item['singleRow'] ?? false,
                'values' => $item['values'],
                'background' => $item['background'] ?? null,
            ];
            if (isset($item['index']) && $item['index']) {
                $newItem['rowNumber'] = str_pad($index, 2, "0", STR_PAD_LEFT);
                $index++;
            }
            $this->rowsPercentageTable[] = $newItem;
        }

        // Summary table
        $kpi = [
            BankAssets::VN_PREFIX,
            BankAssets::CAPEX_PREFIX
        ];
        $dataTable = [
            BankAssets::VN_PREFIX => [],
            BankAssets::CAPEX_PREFIX => [],
        ];
        foreach ($kpi as $prefix) {
            $dataBTARSummary = $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([1, 2, 3, 4, 5, 6, 7, 8, 9]), false, $prefix);
            $ccmFlow = $dataBTARSummary[BankAssets::CCM][BankAssets::FLOW][BankAssets::ALIGNED];
            $ccaFlow = $dataBTARSummary[BankAssets::CCA][BankAssets::FLOW][BankAssets::ALIGNED];
            $ccmStock = $dataBTARSummary[BankAssets::CCM][BankAssets::STOCK][BankAssets::ALIGNED];
            $ccaStock = $dataBTARSummary[BankAssets::CCA][BankAssets::STOCK][BankAssets::ALIGNED];
            $dataOthers = $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([10, 11, 12, 13]), false, $prefix);

            $denominatorStock = $dataBTARSummary[BankAssets::STOCK] + $dataOthers[BankAssets::STOCK];
            $denominatorFlow = $dataBTARSummary[BankAssets::FLOW] + $dataOthers[BankAssets::FLOW];

            $dataExcluded = $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([14, 15, 16]), false, $prefix);
            $denominatorTotalStock = $denominatorStock + $dataExcluded[BankAssets::STOCK];
            $denominatorTotalFlow = $denominatorFlow + $dataExcluded[BankAssets::FLOW];

            $dataTable[$prefix] = [
                BankAssets::STOCK => [
                    'ccm' => calculatePercentage($ccmStock, $denominatorStock, 2),
                    'cca' => calculatePercentage($ccaStock, $denominatorStock, 2),
                    'last' => calculatePercentage($denominatorStock, $denominatorTotalStock, 2),
                ],
                BankAssets::FLOW => [
                    'ccm' => calculatePercentage($ccmFlow, $denominatorFlow, 2),
                    'cca' => calculatePercentage($ccaFlow, $denominatorFlow, 2),
                    'last' => calculatePercentage($denominatorFlow, $denominatorTotalFlow, 2),
                ],
            ];
        }

        $this->dataSummary = [
            [
                'title' => __('Business volume'),
                'data' => [
                    [
                        'id' => 1,
                        'rowTitle' => __('BTAR Stock'),
                        'values' => [
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::STOCK][BankAssets::CCM],
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::STOCK][BankAssets::CCA],
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::STOCK][BankAssets::CCM] + $dataTable[BankAssets::VN_PREFIX][BankAssets::STOCK][BankAssets::CCA],
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::STOCK]['last'],
                        ],
                    ],
                    [
                        'id' => 2,
                        'rowTitle' => __('BTAR Flow'),
                        'values' => [
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::FLOW][BankAssets::CCM],
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::FLOW][BankAssets::CCA],
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::FLOW][BankAssets::CCM] + $dataTable[BankAssets::VN_PREFIX][BankAssets::FLOW][BankAssets::CCA],
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::FLOW]['last'],
                        ],
                    ]
                ],
            ],
            [
                'title' => __('CAPEX'),
                'data' => [
                    [
                        'id' => 1,
                        'rowTitle' => __('GAR Stock'),
                        'values' => [
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::STOCK][BankAssets::CCM],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::STOCK][BankAssets::CCA],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::STOCK][BankAssets::CCM] + $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::STOCK][BankAssets::CCA],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::STOCK]['last'],
                        ],
                    ],
                    [
                        'id' => 2,
                        'rowTitle' => __('GAR Flow'),
                        'values' => [
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::FLOW][BankAssets::CCM],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::FLOW][BankAssets::CCA],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::FLOW][BankAssets::CCM] + $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::FLOW][BankAssets::CCA],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::FLOW]['last'],
                        ],
                    ]
                ],
            ],
        ];
    }

    public function mount()
    {
        $this->search = '';
        $this->rows = [];

        $this->letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        $this->lettersPercentageTable = ['A', 'B', 'C', 'D', 'E'];
        $this->dataPercentageTable = [];
        $this->formatToCurrencyPercentageTable = false;

        $this->frameworkList = [
            'ccm' => __('Climate Change Mitigation (CCM)'),
            'cca' => __('Climate Change Adaptation (CCA)'),
            'total' => __('Total (CCM + CCA)'),
        ];
        $this->framework = 'ccm';
        $this->frameworkPercentageTable = 'ccm';

        $this->optionsKpi = [
            'stock' => __('Disclosure reference date T: KPIs on stock'),
            'flow' => __('Disclosure reference date T: KPIs on flow'),
        ];
        $this->kpi = 'stock';
        $this->kpiPercentageTable = 'stock';

        $this->fillData();
    }

    public function render(): View
    {
        $this->searchData();
        $this->searchPercentageTable();
        return view('livewire.tenant.garbtarregulatory.btarassetsmitigate');
    }

    public function updatedKpi()
    {
        $this->fillData();
    }

    public function updatedFramework()
    {
        if ($this->framework === 'ccm') {
            $this->letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        } else if ($this->framework === 'cca') {
            $this->letters = ['A', 'G', 'H', 'I', 'J', 'K'];
        } else if ($this->framework === 'total') {
            $this->letters = ['A', 'L', 'M', 'N', 'O', 'P'];
        }
        $this->fillData();
    }

    public function updatedKpiPercentageTable()
    {
        $this->fillData();
    }

    public function updatedFrameworkPercentageTable()
    {
        if ($this->frameworkPercentageTable === 'ccm') {
            $this->lettersPercentageTable = ['A', 'B', 'C', 'D', 'E'];
        } else if ($this->frameworkPercentageTable === 'cca') {
            $this->lettersPercentageTable = ['F', 'G', 'H', 'I', 'J'];
        } else if ($this->frameworkPercentageTable === 'total') {
            $this->lettersPercentageTable = ['K', 'L', 'M', 'N', 'O', 'P'];
        }
        $this->fillData();
    }

    public function searchPercentageTable()
    {
        $this->dataPercentageTable = [];
        $re = '/^([A-Z]{1,2})([0-9]{1,2})$/m';
        preg_match_all($re, $this->searchPercentageTable, $cell);
        $isCellSearch = !empty($cell[0]);
        $cellFound = false;
        $letters = array_flip(["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P"]);
        foreach ($this->rowsPercentageTable as $item) {
            if ($isCellSearch) {
                if (!$cellFound && isset($item['rowNumber'])) {
                    if (intval($item['rowNumber']) === intval($cell[2][0])) {
                        $item['values'][$letters[$cell[1][0]]] = [
                            'text' => $item['values'][$letters[$cell[1][0]]],
                            'highlighted' => true,
                        ];
                    }
                }
                $this->dataPercentageTable[] = $item;
            }
            if (str_contains(mb_strtolower($item['title']['text']), mb_strtolower($this->search))) {
                $this->dataPercentageTable[] = $item;
            }
        }
    }

    private function sumPercentageTable($data)
    {
        $value = 0;
        foreach($data as $item) {
            $value += $item[$this->kpiPercentageTable];
        }
        return $value;
    }
}
