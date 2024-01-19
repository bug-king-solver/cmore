<?php

namespace App\Http\Livewire\GarBtarRegulatory;

use App\Models\Tenant\GarBtar\BankAssets;
use Illuminate\View\View;

class GARAssetsMitigate extends BaseMitigate
{

    public $rows;
    public $search;
    public $frameworkList;
    public $framework;
    public $optionsKpi;
    public $kpi;
    public $letters;

    public function fillData()
    {
        $this->rows = [];
        $bankAssets = new BankAssets();
        $arrayBackgrounds = [null, "#757575", "#757575", "#757575", "#757575", "#757575"];

        // Empresas financeiras
        $totalFinancialCorporations = [
            // Instituições de crédito
            [
                $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1], [1]))),
                $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([2], [1]))),
                $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([3], [1])), true, false, null, null, [null, null, null, "#757575", null, null]),
            ],
            // Outras empresas financeiras
            [
                // das quais, empresas de investimento
                [
                    $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1], [2]))),
                    $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([2], [2]))),
                    $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([3], [2])), true, false, null, null, [null, null, null, "#757575", null, null]),
                ],
                // dos quais, sociedades gestoras
                [
                    $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1], [3]))),
                    $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([2], [3]))),
                    $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([3], [3])), true, false, null, null, [null, null, null, "#757575", null, null]),
                ],
                // das quais, empresas de seguros
                [
                    $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1], [4]))),
                    $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([2], [4]))),
                    $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([3], [4])), true, false, null, null, [null, null, null, "#757575", null, null]),
                ]
            ],
            // Empresas não financeiras (sujeitas às obrigações de divulgação da Diretiva NFI)
            [
                $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::YES))),
                $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([2], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::YES))),
                $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([3], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::YES)), true, false, null, null, [null, null, null, "#757575", null, null]),
            ]
        ];

        // Famílias
        $totalFamilies = [
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([4]))),
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([5]))),
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([6]))),
        ];

        // Financiamento do setor público local (Autarquias)
        $totalPublicSector = [
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([7]))),
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([8]))),
        ];

        // Bens dados em garantia obtidos por aquisição da posse: bens imóveis residenciais e comerciais
        $totalType9 = $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([9])));

        $totalCreditInstitutions = $this->getSumatoryTotalValues($totalFinancialCorporations[0]);
        $totalInvestmentFirms = $this->getSumatoryTotalValues($totalFinancialCorporations[1][0]);
        $totalManagementCompanies = $this->getSumatoryTotalValues($totalFinancialCorporations[1][1]);
        $totalInsuranceUndertakings = $this->getSumatoryTotalValues($totalFinancialCorporations[1][2]);
        $totalOtherFinancialCorporations = $this->getSumatoryTotalValues([$totalInvestmentFirms, $totalManagementCompanies, $totalInsuranceUndertakings]);
        $totalFinancial = $this->getSumatoryTotalValues([$totalCreditInstitutions, $totalOtherFinancialCorporations]);
        $totalNonFinancialCorporations = $this->getSumatoryTotalValues($totalFinancialCorporations[2]);
        $totalElegibleGARcalculations = $this->getSumatoryTotalValues([$totalFinancial, $totalNonFinancialCorporations]);
        $totalFamiliesLine = $this->getSumatoryTotalValues($totalFamilies);
        $totalPublicSectorLine = $this->getSumatoryTotalValues($totalPublicSector);

        $totalGARLine = $this->getSumatoryTotalValues([$totalFinancial, $totalNonFinancialCorporations, $totalFamiliesLine, $totalPublicSectorLine, $totalType9]);

        $items = [
            $this->createItem(__('GAR - Covered assets in both numerator and denominator'), [], 1, [
                'specsTitle' => ['class' => 'uppercase font-bold'],
                'singleRow' => true,
                'index' => false,
                'background' => '#E1E6EF'
            ])
        ];
        // Empréstimos e adiantamentos, títulos de dívida e instrumentos de capital não  detidos para negociação, elegíveis para o cálculo do RAE
        $items[] = $this->createItem(__('Loans and advances, debt securities, and equity instruments not high-frequency trading (HfT) eligible for GAR calculation'), $totalElegibleGARcalculations);
        // Empresas financeiras
        $items[] = $this->createItem(__('Financial corporations'), $totalFinancial, 1, [
            'specsTitle' => [
                'class' => 'font-bold',
                'color' => '#39B54A'
            ]
        ]);

        // Instituições de crédito
        $items[] = $this->createItem(__('Credit institutions'), $totalCreditInstitutions, 2, [
            'specsTitle' => ['class' => 'uppercase']
        ]);
        $items[] = $this->createItem(__('Loans and advances'), $totalFinancialCorporations[0][0], 3);
        $items[] = $this->createItem(__('Debt securities, including UoP'), $totalFinancialCorporations[0][1], 3);
        $items[] = $this->createItem(__('Equity instruments'), $totalFinancialCorporations[0][2], 3);

        // Outras empresas financeiras
        $items[] = $this->createItem(__('Other financial corporations'), $totalOtherFinancialCorporations, 2, [
            'specsTitle' => ['class' => 'uppercase']
        ]);

        // das quais, empresas de investimento
        $items[] = $this->createItem(__('of which'), $totalInvestmentFirms, 3, [
            'subtitle' => [
                'text' => __('investment firms'),
                'color' => '#44724D',
                'class' => 'font-bold',
            ]
        ]);
        $items[] = $this->createItem(__('Loans and advances'), $totalFinancialCorporations[1][0][0], 4);
        $items[] = $this->createItem(__('Debt securities, including UoP'), $totalFinancialCorporations[1][0][1], 4);
        $items[] = $this->createItem(__('Equity instruments'), $totalFinancialCorporations[1][0][2], 4);


        // dos quais, sociedades gestoras
        $items[] = $this->createItem(__('of which'), $totalManagementCompanies, 3, [
            'subtitle' => [
                'text' => __('management companies'),
                'color' => '#44724D',
                'class' => 'font-bold',
            ]
        ]);
        $items[] = $this->createItem(__('Loans and advances'), $totalFinancialCorporations[1][1][0], 4);
        $items[] = $this->createItem(__('Debt securities, including UoP'), $totalFinancialCorporations[1][1][1], 4);
        $items[] = $this->createItem(__('Equity instruments'), $totalFinancialCorporations[1][1][2], 4);


        // das quais, empresas de seguros
        $items[] = $this->createItem(__('of which'), $totalInsuranceUndertakings, 3, [
            'subtitle' => [
                'text' => __('insurance undertakings'),
                'color' => '#44724D',
                'class' => 'font-bold',
            ]
        ]);
        $items[] = $this->createItem(__('Loans and advances'), $totalFinancialCorporations[1][2][0], 4);
        $items[] = $this->createItem(__('Debt securities, including UoP'), $totalFinancialCorporations[1][2][1], 4);
        $items[] = $this->createItem(__('Equity instruments'), $totalFinancialCorporations[1][2][2], 4);


        // Empresas não financeiras (sujeitas às obrigações de divulgação da Diretiva NFI)
        $items[] = $this->createItem(
            __('Non-financial corporations'),
            $totalNonFinancialCorporations,
            1,
            [
                'specsTitle' => ['color' => '#39B54A', 'class' => 'font-bold'],
                'subtitle' => [
                    'text' => __('(subject to NFRD disclosure obligations)'),
                    'color' => '#444444',
                    'class' => 'font-normal'
                ]
            ]
        );
        $items[] = $this->createItem(__('Loans and advances'), $totalFinancialCorporations[2][0]);
        $items[] = $this->createItem(__('Debt securities, including UoP'), $totalFinancialCorporations[2][1]);
        $items[] = $this->createItem(__('Equity instruments'), $totalFinancialCorporations[2][2]);

        // Famílias
        $items[] = $this->createItem(__('Households'), $totalFamiliesLine, 3, [
            'specsTitle' => ['class' => 'uppercase']
        ]);
        // dos quais, empréstimos garantidos por imóveis de habitação
        $items[] = $this->createItem(__('of which loans collateralised by residential immovable property'), $totalFamilies[0], 3);
        // dos quais, empréstimos para a renovação de edifícios
        $items[] = $this->createItem(__('of which building renovation loans'), $totalFamilies[1], 3);
        // dos quais, empréstimos automóveis
        $items[] = $this->createItem(__('of which'), $totalFamilies[2], 3, [
            'subtitle' => [
                'text' => __('motor vehicle loans'),
                'color' => '#44724D',
                'class' => 'font-bold'
            ]
        ]);

        // Financiamento do setor público local (Autarquias)
        $items[] = $this->createItem(__('Local governments financing'), $totalPublicSectorLine, 3, [
            'specsTitle' => ['class' => 'uppercase']
        ]);
        $items[] = $this->createItem(__('Housing financing'), $totalPublicSector[0], 3);
        $items[] = $this->createItem(__('Other local governments financing'), $totalPublicSector[1], 3);

        // Bens dados em garantia obtidos por aquisição da posse: bens imóveis residenciais e comerciais
        $items[] = $this->createItem(__('Collateral obtained by taking possession: residential and commercial immovable properties'), $totalType9);

        // TOTAL DOS ATIVOS DO RAE
        $items[] = $this->createItem(__('Total GAR Assets'), $totalGARLine, 1, [
            'specsTitle' => ['class' => 'uppercase font-bold', 'color' => '#39B54A']
        ]);


        // Ativos excluídos do numerador para efeitos do cálculo do RAE (abrangidos no denominador)
        $totalNonFinancialCorporationsEU = [
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::YES), true)),
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([2], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::YES), true)),
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([3], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::YES), true)),
        ];

        $totalNonFinancialCorporationsNonEU = [
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::NO), true)),
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([2], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::NO), true)),
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([3], [5])->where('data->' . BankAssets::SUBJECT_NFDR, BankAssets::NO)->where('data->' . BankAssets::EUROPEAN_COMPANY, BankAssets::NO), true)),
        ];

        $totalNonFinancialCorporationsEULine = $this->getSumatoryTotalValues($totalNonFinancialCorporationsEU);
        $totalNonFinancialCorporationsNonEULine = $this->getSumatoryTotalValues($totalNonFinancialCorporationsNonEU);

        $items[] = $this->createItem(__('Assets excluded from the numerator for GAR calculation (covered in the denominator)'), [], 1, [
            'specsTitle' => ['class' => 'font-bold uppercase'],
            'singleRow' => true,
            'index' => false,
            'background' => '#E1E6EF',
        ]);

        // Empresas não financeiras da UE (não sujeitas às obrigações de divulgação da Diretiva NFI)
        $totalNonFinancialCorporationsEU = [
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1], [5])->where('data->sujeição_nfdr', 'N')->where('data->empresa_europeia', 'S'), true), true, true, null, null, $arrayBackgrounds),
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([2], [5])->where('data->sujeição_nfdr', 'N')->where('data->empresa_europeia', 'S'), true), true, true, null, null, $arrayBackgrounds),
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([3], [5])->where('data->sujeição_nfdr', 'N')->where('data->empresa_europeia', 'S'), true), true, true, null, null, $arrayBackgrounds),
        ];
        $totalNonFinancialCorporationsEULine = $this->getSumatoryTotalValues($totalNonFinancialCorporationsEU, true, $arrayBackgrounds);
        $items[] = $this->createItem(
            __('EU Non-financial corporations'),
            $totalNonFinancialCorporationsEULine,
            1,
            [
                'specsTitle' => ['color' => '#39B54A', 'class' => 'font-bold'],
                'subtitle' => [
                    'text' => __('(subject to NFRD disclosure obligations)'),
                    'color' => '#444444',
                    'class' => 'font-normal'
                ]
            ]
        );

        // Empresas não financeiras exteriores à UE (não sujeitas às obrigações de divulgação da Diretiva NFI)
        $totalNonFinancialCorporationsNonEU = [
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1], [5])->where('data->sujeição_nfdr', 'N')->where('data->empresa_europeia', 'N'), true), true, true, null, null, $arrayBackgrounds),
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([2], [5])->where('data->sujeição_nfdr', 'N')->where('data->empresa_europeia', 'N'), true), true, true, null, null, $arrayBackgrounds),
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([3], [5])->where('data->sujeição_nfdr', 'N')->where('data->empresa_europeia', 'N'), true), true, true, null, null, $arrayBackgrounds),
        ];
        $totalNonFinancialCorporationsNonEULine = $this->getSumatoryTotalValues($totalNonFinancialCorporationsNonEU, true, $arrayBackgrounds);
        $items[] = $this->createItem(__('Loans and advances'), $totalNonFinancialCorporationsEU[0]);
        $items[] = $this->createItem(__('Debt securities, including UoP'), $totalNonFinancialCorporationsEU[1]);
        $items[] = $this->createItem(__('Equity instruments'), $totalNonFinancialCorporationsEU[2]);
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
        $items[] = $this->createItem(__('Loans and advances'), $totalNonFinancialCorporationsNonEU[0]);
        $items[] = $this->createItem(__('Debt securities, including UoP'), $totalNonFinancialCorporationsNonEU[1]);
        $items[] = $this->createItem(__('Equity instruments'), $totalNonFinancialCorporationsNonEU[2]);


        // Outros Ativos: Derivados
        $totalDerivatives = $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([10]), true), true, true, null, null, $arrayBackgrounds);
        $items[] = $this->createItem(__('Derivatives'), $totalDerivatives);

        // Outros Ativos: Empréstimos interbancários à vista
        $totalInterbankLoans = $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([11]), true), true, true, null, null, $arrayBackgrounds);
        $items[] = $this->createItem(__('On demand interbank loans'), $totalInterbankLoans);

        // Outros Ativos: Ativos em numerário e equivalentes a numerário
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
        ], true, $arrayBackgrounds);
        $items[] = $this->createItem(__('Total assets in the denominator (GAR)'), $totalAssetsDenominator, 1, [
            'specsTitle' => ['class' => 'uppercase font-bold', 'color' => '#39B54A']
        ]);

        // Outros ativos excluídos tanto do numerador como do denominador para efeitos do cálculo do RAE
        $items[] = $this->createItem(__('Other assets excluded from both the numerator and denominator for GAR calculation'), [], 1, [
            'specsTitle' => ['class' => 'font-bold uppercase'],
            'singleRow' => true,
            'index' => false,
            'background' => '#E1E6EF',
        ]);

        // Entidades soberanas
        $totalSovereigns = $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([14]), true), true, true, null, null, $arrayBackgrounds);
        $items[] = $this->createItem(__('Sovereigns'), $totalSovereigns);

        // Posições em risco sobre bancos centrais
        $totalBanksExposure = $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([15]), true), true, true, null, null, $arrayBackgrounds);
        $items[] = $this->createItem(__('Central banks exposure'), $totalBanksExposure);

        // Carteira de negociação
        $totalTradingBook = $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([16]), true), true, true, null, null, $arrayBackgrounds);
        $items[] = $this->createItem(__('Trading book'), $totalTradingBook);

        // TOTAL DOS ATIVOS EXCLUÍDOS DO NUMERADOR E DO DENOMINADOR
        $totalAssetsExcluded = $this->getSumatoryTotalValues([
            $totalSovereigns,
            $totalBanksExposure,
            $totalTradingBook,
        ], true, $arrayBackgrounds);
        $items[] = $this->createItem(__('Total assets excluded from numerator and denominator'), $totalAssetsExcluded, 1, [
            'specsTitle' => ['class' => 'uppercase font-bold', 'color' => '#39B54A']
        ]);


        $totalAssets = $this->getSumatoryTotalValues([
            $totalGARLine,
            $totalNonFinancialCorporationsEULine,
            $totalNonFinancialCorporationsNonEULine,
            $totalDerivatives,
            $totalInterbankLoans,
            $totalCashRelatedAssets,
            $totalOtherAssets,
            $totalAssetsExcluded,
        ]);
        // TOTAL DOS ATIVOS
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
    }

    public function mount()
    {
        $this->search = '';
        $this->rows = [];

        $this->letters = ['A', 'B', 'C', 'D', 'E', 'F'];

        $this->frameworkList = [
            'ccm' => __('Climate Change Mitigation (CCM)'),
            'cca' => __('Climate Change Adaptation (CCA)'),
            'total' => __('Total (CCM + CCA)'),
        ];
        $this->framework = 'ccm';

        $this->optionsKpi = [
            'stock' => __('Disclosure reference date T: KPIs on stock'),
            'flow' => __('Disclosure reference date T: KPIs on flow'),
        ];
        $this->kpi = 'stock';

        $this->fillData();
    }

    public function render(): View
    {
        $this->searchData();
        return view('livewire.tenant.garbtarregulatory.garassetsmitigate');
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
}
