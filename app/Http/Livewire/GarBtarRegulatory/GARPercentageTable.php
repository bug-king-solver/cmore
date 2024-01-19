<?php

namespace App\Http\Livewire\GarBtarRegulatory;

use App\Models\Tenant\GarBtar\BankAssets;
use Illuminate\View\View;

class GARPercentageTable extends BaseMitigate
{

    public $rows;
    public $search;
    public $frameworkList;
    public $framework;
    public $optionsKpi;
    public $kpi;
    public $letters;
    public $formatToCurrency;

    public function fillData()
    {
        $bankAssets = new BankAssets();
        $relevantForRatios = $bankAssets->getAssetsRelevantForRatios();
        $totalBankAssets = $bankAssets->getBankTotalAssets();
        $this->rows = [];
        $defaultArray = [['value' => 0], ['value' => 0], ['value' => 0], ['value' => 0], ['value' => 0]];
        $emptyArray = [['value' => ''], ['value' => ''], ['value' => ''], ['value' => ''], ['value' => '']];
        if ($this->framework === 'total') {
            $defaultArray[] = ['value' => 0];
            $emptyArray[] = ['value' => ''];
        }
        // Empresas financeiras
        $financialComporations = [
            // Instituições de crédito
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1, 2, 3], [1])), $this->framework === 'total'),

            // Outras empresas financeiras
            // das quais, empresas de investimento
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1, 2, 3], [2])), $this->framework === 'total'),
            // dos quais, sociedades gestoras
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1, 2, 3], [3])), $this->framework === 'total'),
            // das quais, empresas de seguros
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1, 2, 3], [4])), $this->framework === 'total'),
            // Empresas não financeiras (sujeitas às obrigações de divulgação da Diretiva NFI)
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1, 2, 3], [5])->where('data->sujeição_nfdr', 'S')), $this->framework === 'total'),

            // Famílias
            // dos quais, empréstimos garantidos por imóveis de habitação
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([4])), $this->framework === 'total'),
            // dos quais, empréstimos para a renovação de edifícios
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([5])), $this->framework === 'total'),
            // dos quais, empréstimos automóveis
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([6])), $this->framework === 'total'),

            // Financiamento do setor público local
            // Financiamento à habitação
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([7])), $this->framework === 'total'),
            // Outros financiamentos do setor público local
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([8])), $this->framework === 'total'),

            // Tipo 9
            $this->getTotalValues($bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([9])), $this->framework === 'total'),
        ];

        $totalGAR = $defaultArray;
        $summatoryGAR = $defaultArray;

        $totalLoansDebtEquity = $defaultArray;
        $summatoryLoansDebtEquity = $defaultArray;
        $totalFinancialCorporations = $defaultArray;
        $summatoryFinancialCorporations = $defaultArray;
        $totalCreditInstitutions = [];
        $totalInvestmentFirms = [];
        $totalManagementCompanies = [];
        $totalInsuranceUndertakings = [];
        $totalOtherFinancialCorporations = $defaultArray;
        $summatoryOtherFinancialCorporations = $defaultArray;
        $totalNonFinancialCorporations = $defaultArray;
        $summatoryNonFinancialCorporations = $defaultArray;

        $totalRealState = [];
        $totalRenovation = [];
        $totalVehicles = [];
        $totalFamilies = $defaultArray;
        $summatoryFamilies = $defaultArray;

        $totalHousing = [];
        $totalOtherPublicSector = [];
        $totalPublicSector = $defaultArray;
        $summatoryPublicSector = $defaultArray;

        $totalType9 = [];

        foreach ($financialComporations as $key => $item) {
            foreach ($item as $subkey => $value) {
                $denominator = $relevantForRatios;
                if ($this->framework === 'total') {
                    if ($subkey === 0) {
                        $denominator = $totalBankAssets;
                        $subkey = sizeof($defaultArray) - 1;
                    } else {
                        $subkey -= 1;
                    }
                }
                switch($key) {
                    case 0:
                        $totalCreditInstitutions[$subkey]['value'] = calculatePercentage($value['value'], $denominator[$this->kpi], 2);
                        break;
                    case 1:
                        $totalInvestmentFirms[$subkey]['value'] = calculatePercentage($value['value'], $denominator[$this->kpi], 2);
                        break;
                    case 2:
                        $totalManagementCompanies[$subkey]['value'] = calculatePercentage($value['value'], $denominator[$this->kpi], 2);
                        break;
                    case 3:
                        $totalInsuranceUndertakings[$subkey]['value'] = calculatePercentage($value['value'], $denominator[$this->kpi], 2);
                        break;
                    case 5:
                        $totalRealState[$subkey]['value'] = calculatePercentage($value['value'], $denominator[$this->kpi], 2);
                        break;
                    case 6:
                        $totalRenovation[$subkey]['value'] = calculatePercentage($value['value'], $denominator[$this->kpi], 2);
                        break;
                    case 7:
                        $totalVehicles[$subkey]['value'] = calculatePercentage($value['value'], $denominator[$this->kpi], 2);
                        break;
                    case 8:
                        $totalHousing[$subkey]['value'] = calculatePercentage($value['value'], $denominator[$this->kpi], 2);
                        break;
                    case 9:
                        $totalOtherPublicSector[$subkey]['value'] = calculatePercentage($value['value'], $denominator[$this->kpi], 2);
                        break;
                    case 10:
                        $totalType9[$subkey]['value'] = calculatePercentage($value['value'], $denominator[$this->kpi], 2);
                        break;
                    default:
                        break;
                }
                if (in_array($key, [0, 1, 2, 3, 4])) {
                    if (in_array($key, [0, 1, 2, 3])) {
                        $summatoryFinancialCorporations[$subkey]['value'] += $value['value'];
                        $totalFinancialCorporations[$subkey]['value'] = calculatePercentage($summatoryFinancialCorporations[$subkey]['value'], $denominator[$this->kpi], 2);
                        if ($key != 0) {
                            $summatoryOtherFinancialCorporations[$subkey]['value'] += $value['value'];
                            $totalOtherFinancialCorporations[$subkey]['value'] = calculatePercentage($summatoryOtherFinancialCorporations[$subkey]['value'], $denominator[$this->kpi], 2);
                        }
                    } else {
                        $summatoryNonFinancialCorporations[$subkey]['value'] += $value['value'];
                        $totalNonFinancialCorporations[$subkey]['value'] = calculatePercentage($summatoryNonFinancialCorporations[$subkey]['value'], $denominator[$this->kpi], 2);
                    }
                    $summatoryLoansDebtEquity[$subkey]['value'] += $value['value'];
                    $totalLoansDebtEquity[$subkey]['value'] = calculatePercentage($summatoryLoansDebtEquity[$subkey]['value'], $denominator[$this->kpi], 2);
                } else if (in_array($key, [5, 6, 7])) {
                    $summatoryFamilies[$subkey]['value'] += $value['value'];
                    $totalFamilies[$subkey]['value'] = calculatePercentage($summatoryFamilies[$subkey]['value'], $denominator[$this->kpi], 2);
                } else if (in_array($key, [8, 9])) {
                    $summatoryPublicSector[$subkey]['value'] += $value['value'];
                    $totalPublicSector[$subkey]['value'] = calculatePercentage($summatoryPublicSector[$subkey]['value'], $denominator[$this->kpi], 2);
                }
                $summatoryGAR[$subkey]['value'] += $value['value'];
                $totalGAR[$subkey]['value'] = calculatePercentage($summatoryGAR[$subkey]['value'], $denominator[$this->kpi], 2);
            }
        }

        $items = [
            $this->createItem(__('GAR'), $totalGAR, 1, [
                'specsTitle' => ['class' => 'uppercase font-bold'],
                'singleRow' => false,
                'index' => true,
                'background' => '#E1E6EF'
            ])
        ];

        $items[] = $this->createItem(__('Loans and advances, debt securities and equity instruments not high-frequency trading (HfT) eligible for GAR calculation'), $totalLoansDebtEquity, 1);
        $items[] = $this->createItem(__('Financial corporations'), $totalFinancialCorporations, 1, [
            'specsTitle' => [
                'class' => 'font-bold',
                'color' => '#39B54A'
            ]
        ]);

        $items[] = $this->createItem(__('Credit institutions'), $totalCreditInstitutions, 2, [
            'specsTitle' => ['class' => 'uppercase']
        ]);

        $items[] = $this->createItem(__('Other financial corporations'), $totalOtherFinancialCorporations, 2, [
            'specsTitle' => ['class' => 'uppercase']
        ]);


        $items[] = $this->createItem(__('of which'), $totalInvestmentFirms, 3, [
            'subtitle' => [
                'text' => __('investment firms'),
                'color' => '#44724D',
                'class' => 'font-bold',
            ]
        ]);


        $items[] = $this->createItem(__('of which'), $totalManagementCompanies, 3, [
            'subtitle' => [
                'text' => __('management companies'),
                'color' => '#44724D',
                'class' => 'font-bold',
            ]
        ]);


        $items[] = $this->createItem(__('of which'), $totalInsuranceUndertakings, 3, [
            'subtitle' => [
                'text' => __('insurance undertakings'),
                'color' => '#44724D',
                'class' => 'font-bold',
            ]
        ]);

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


        $items[] = $this->createItem(__('Households'), $totalFamilies, 2, [
            'specsTitle' => ['class' => 'uppercase']
        ]);
        $items[] = $this->createItem(__('of which loans collateralised by residential immovable property'), $totalRealState, 3);
        $items[] = $this->createItem(__('of which building renovation loans'), $totalRenovation, 3);
        $items[] = $this->createItem(__('of which'), $totalVehicles, 3, [
            'subtitle' => [
                'text' => __('motor vehicle loans'),
                'color' => '#44724D',
                'class' => 'font-bold'
            ]
        ]);


        $items[] = $this->createItem(__('Local governments financing'), $totalPublicSector, 2, [
            'specsTitle' => ['class' => 'uppercase']
        ]);
        $items[] = $this->createItem(__('Housing financing'), $totalHousing, 3);
        $items[] = $this->createItem(__('Other local governments financing'), $totalOtherPublicSector, 3);


        $items[] = $this->createItem(__('Collateral obtained by taking possession: residential and commercial immovable properties'), $totalType9);


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

        $this->letters = ['A', 'B', 'C', 'D', 'E'];
        $this->formatToCurrency = false;

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
        return view('livewire.tenant.garbtarregulatory.garmitigate');
    }

    public function updatedKpi()
    {
        $this->fillData();
    }

    public function updatedFramework()
    {
        if ($this->framework === 'ccm') {
            $this->letters = ['A', 'B', 'C', 'D', 'E'];
        } else if ($this->framework === 'cca') {
            $this->letters = ['F', 'G', 'H', 'I', 'J'];
        } else if ($this->framework === 'total') {
            $this->letters = ['K', 'L', 'M', 'N', 'O', 'P'];
        }
        $this->fillData();
    }
}
