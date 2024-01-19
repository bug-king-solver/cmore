<?php

namespace App\Http\Livewire\GarBtarRegulatory;

use App\Models\Tenant\GarBtar\BankAssets;
use Livewire\Component;

class BaseMitigate extends Component
{

    public $data;

    public function createItem($title, array $values, $level = 2, $options = [])
    {
        $item = [
            'title' => $options['specsTitle'] ?? [],
            'singleRow' => $options['singleRow'] ?? false,
            'index' => $options['index'] ?? true,
            'values' => $values
        ];
        $item['title']['text'] = $title;
        $item['title']['level'] = $level;
        if (isset($options['background'])) {
            $item['background'] = $options['background'];
        }
        if (isset($options['subtitle'])) {
            $item['title']['subtitle'] = $options['subtitle'];
        }
        return $item;
    }

    public function getTotalValues($total, $includeTotal = true, $sumOnlyFirstRow = false, $kpi = null, $framework = null, $backgrounds = null): array
    {
        if (!isset($kpi)) {
            $kpi = $this->kpi;
        }
        if (!isset($framework)) {
            $framework = $this->framework;
        }
        if (!isset($backgrounds)) {
            $backgrounds = [null, null, null, null, null, null];
        }
        $data =  [
            [
                'value' => $total[$kpi],
                'background' => $backgrounds[0],
            ],
            [
                'value' => $sumOnlyFirstRow ? '' : ($framework === 'total' ? $total[BankAssets::CCA][$kpi][BankAssets::ELIGIBLE] + $total[BankAssets::CCM][$kpi][BankAssets::ELIGIBLE] : $total[$framework][$kpi][BankAssets::ELIGIBLE]),
                'background' => $backgrounds[1],
            ],
            [
                'value' => $sumOnlyFirstRow ? '' : ($framework === 'total' ? $total[BankAssets::CCA][$kpi][BankAssets::ALIGNED] + $total[BankAssets::CCM][$kpi][BankAssets::ALIGNED] : $total[$framework][$kpi][BankAssets::ALIGNED]),
                'background' => $backgrounds[2],
            ],
            [
                'value' => $sumOnlyFirstRow ? '' : ($framework === 'total' ? $total[BankAssets::CCA][$kpi][BankAssets::SPECIALIZED_CREDIT] + $total[BankAssets::CCM][$kpi][BankAssets::SPECIALIZED_CREDIT] : $total[$framework][$kpi][BankAssets::SPECIALIZED_CREDIT]),
                'background' => $backgrounds[3],
            ],
            [
                'value' => $sumOnlyFirstRow ? '' : ($framework === 'total' ? $total[BankAssets::CCA][$kpi][BankAssets::TRANSITIONAL_ADAPTING] + $total[BankAssets::CCM][$kpi][BankAssets::TRANSITIONAL_ADAPTING] : $total[$framework][$kpi][BankAssets::TRANSITIONAL_ADAPTING]),
                'background' => $backgrounds[4],
            ],
            [
                'value' => $sumOnlyFirstRow ? '' : ($framework === 'total' ? $total[BankAssets::CCA][$kpi][BankAssets::ENABLING] + $total[BankAssets::CCM][$kpi][BankAssets::ENABLING] : $total[$framework][$kpi][BankAssets::ENABLING]),
                'background' => $backgrounds[5],
            ]
        ];
        if (!$includeTotal) {
            array_splice($data, 0, 1);
        }
        return $data;
    }

    public function getSumatoryTotalValues($totals, $sumOnlyFirstRow = false, $backgrounds = null): array
    {
        if (!isset($backgrounds)) {
            $backgrounds = [null, null, null, null, null, null];
        }
        return [
            [
                'value' => array_sum(array_column(array_column($totals, 0), 'value')),
                'background' => $backgrounds[0],
            ],
            [
                'value' => $sumOnlyFirstRow ? '' : array_sum(array_column(array_column($totals, 1), 'value')),
                'background' => $backgrounds[1],
            ],
            [
                'value' => $sumOnlyFirstRow ? '' : array_sum(array_column(array_column($totals, 2), 'value')),
                'background' => $backgrounds[2],
            ],
            [
                'value' => $sumOnlyFirstRow ? '' : array_sum(array_column(array_column($totals, 3), 'value')),
                'background' => $backgrounds[3],
            ],
            [
                'value' => $sumOnlyFirstRow ? '' : array_sum(array_column(array_column($totals, 4), 'value')),
                'background' => $backgrounds[4],
            ],
            [
                'value' => $sumOnlyFirstRow ? '' : array_sum(array_column(array_column($totals, 5), 'value')),
                'background' => $backgrounds[5],
            ],
        ];
    }

    public function searchData()
    {
        $this->data = [];
        $re = '/^([A-Z]{1,2})([0-9]{1,2})$/m';
        preg_match_all($re, $this->search, $cell);
        $isCellSearch = !empty($cell[0]);
        $cellFound = false;
        $letters = array_flip(["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P"]);
        foreach ($this->rows as $item) {
            if ($isCellSearch) {
                if (!$cellFound && isset($item['rowNumber'])) {
                    if (intval($item['rowNumber']) === intval($cell[2][0])) {
                        $item['values'][$letters[$cell[1][0]]] = [
                            'text' => $item['values'][$letters[$cell[1][0]]],
                            'highlighted' => true,
                        ];
                    }
                }
                $this->data[] = $item;
            }
            if (str_contains(mb_strtolower($item['title']['text']), mb_strtolower($this->search))) {
                $this->data[] = $item;
            }
        }
    }
}
