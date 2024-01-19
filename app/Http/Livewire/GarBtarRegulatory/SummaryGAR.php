<?php

namespace App\Http\Livewire\GarBtarRegulatory;

use App\Models\Tenant\GarBtar\BankAssets;
use Illuminate\View\View;
use Livewire\Component;

class SummaryGAR extends Component
{

    public $data;

    public function render(): View
    {
        $bankAssets = new BankAssets();
        $kpi = [BankAssets::VN_PREFIX, BankAssets::CAPEX_PREFIX];
        $dataTable = [
            BankAssets::VN_PREFIX => [],
            BankAssets::CAPEX_PREFIX => [],
        ];
        foreach ($kpi as $prefix) {
            $dataGAR = [
                $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1, 2, 3], [1, 2, 3, 4]), false, $prefix),
                $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1, 2, 3], [5])->where('data->sujeição_nfdr', 'S'), false, $prefix),
                $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([4, 5, 6, 7, 8, 9]), false, $prefix),
            ];
            $valueGARStock = array_sum(array_column($dataGAR, 'stock'));
            $valueGARFlow = array_sum(array_column($dataGAR, 'flow'));
            $ccmFlow = $ccmStock = $ccaFlow = $ccaStock = 0;
            foreach ($dataGAR as $item) {
                $ccmFlow += $item['ccm']['flow']['alinhamento'];
                $ccaFlow += $item['cca']['flow']['alinhamento'];
                $ccmStock += $item['ccm']['stock']['alinhamento'];
                $ccaStock += $item['cca']['stock']['alinhamento'];
            }
            $dataNonFinancialCorporations = $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByTypeAndEntity([1, 2, 3], [5])->where('data->sujeição_nfdr', 'N'), false, $prefix);
            $dataOthers = $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([10, 11, 12, 13]), false, $prefix);

            // TOTAL DOS ATIVOS NO DENOMINADOR (RAE)
            $denominatorStock = $valueGARStock + $dataNonFinancialCorporations['stock'] + $dataOthers['stock'];
            $denominatorFlow = $valueGARFlow + $dataNonFinancialCorporations['flow'] + $dataOthers['flow'];

            $dataExcluded = $bankAssets->getDataForRegulatoryTables($bankAssets->getAssetsByType([14, 15, 16]), false, $prefix);
            $denominatorTotalStock = $denominatorStock + $dataExcluded['stock'];
            $denominatorTotalFlow = $denominatorFlow + $dataExcluded['flow'];

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


        $this->data = [
            [
                'title' => __('Business volume'),
                'data' => [
                    [
                        'id' => 1,
                        'rowTitle' => __('GAR Stock'),
                        'values' => [
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::STOCK]['ccm'],
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::STOCK]['cca'],
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::STOCK]['ccm'] + $dataTable[BankAssets::VN_PREFIX][BankAssets::STOCK]['cca'],
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::STOCK]['last'],
                        ],
                    ],
                    [
                        'id' => 2,
                        'rowTitle' => __('GAR Flow'),
                        'values' => [
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::FLOW]['ccm'],
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::FLOW]['cca'],
                            $dataTable[BankAssets::VN_PREFIX][BankAssets::FLOW]['ccm'] + $dataTable[BankAssets::VN_PREFIX][BankAssets::FLOW]['cca'],
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
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::STOCK]['ccm'],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::STOCK]['cca'],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::STOCK]['ccm'] + $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::STOCK]['cca'],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::STOCK]['last'],
                        ],
                    ],
                    [
                        'id' => 2,
                        'rowTitle' => __('GAR Flow'),
                        'values' => [
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::FLOW]['ccm'],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::FLOW]['cca'],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::FLOW]['ccm'] + $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::FLOW]['cca'],
                            $dataTable[BankAssets::CAPEX_PREFIX][BankAssets::FLOW]['last'],
                        ],
                    ]
                ],
            ],
        ];
        return view('livewire.tenant.garbtarregulatory.summary');
    }
}
