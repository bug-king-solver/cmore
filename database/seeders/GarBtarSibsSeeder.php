<?php

namespace Database\Seeders;

use App\Models\Tenant\GarBtar\BankAssets;
use App\Models\Traits\Filesystem\FileManagerTrait;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class GarBtarSibsSeeder
 */
class GarBtarSibsSeeder extends Seeder
{
    use FileManagerTrait;

    /**
     * Run the database seeds.
     *
     * php artisan db:seed GarBtarSibsSeeder
     * php artisan tenants:seed --class=Database\\Seeders\\GarBtarSibsSeeder
     *
     * @return void
     */
    public function run()
    {
        exec('rm -rf database/data/gar-btar-sibs/explanatory-notes/*');

        $file = base_path() . '/database/data/gar-btar-sibs/gar-btar-notas-explicativas.xlsx';
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getSheetByName('Query Base_R_Draft - N NFRD'); // Sheet with last changes by João and GAR and BTAR different
        // $worksheet = $spreadsheet->getSheetByName('Query Base_R_Draft'); // Sheet with GAR and BTAR equals
        // $worksheet = $spreadsheet->getSheetByName('Query Base'); // Sheet use to validate calculations with João
        $explicativas = [];
        $rows = $worksheet->toArray();

        $map = [
            'B' => BankAssets::STOCK_FIELD,
            'C' => BankAssets::FLOW_FIELD,
            'D' => BankAssets::NIPC,
            'E' => BankAssets::NAME_COMPANY,
            'F' => BankAssets::TYPE,
            'G' => BankAssets::ENTITY_TYPE,
            'H' => BankAssets::NACE_CODE,
            'I' => BankAssets::SUBJECT_NFDR,
            'J' => BankAssets::EUROPEAN_COMPANY,
            'K' => BankAssets::SPECIFIC_PURPOSE,

            'L' => BankAssets::VN_CCM_ELIGIBLE,
            'N' => BankAssets::VN_CCM_ALIGNED,
            'P' => BankAssets::VN_CCM_TRANSITIONAL,
            'R' => BankAssets::VN_CCM_ENABLING,
            'T' => BankAssets::VN_CCA_ELIGIBLE,
            'V' => BankAssets::VN_CCA_ALIGNED,
            'X' => BankAssets::VN_CCA_ADAPTING,
            'Z' => BankAssets::VN_CCA_ENABLING,

            'AB' => BankAssets::CAPEX_CCM_ELIGIBLE,
            'AD' => BankAssets::CAPEX_CCM_ALIGNED,
            'AF' => BankAssets::CAPEX_CCM_TRANSITIONAL,
            'AH' => BankAssets::CAPEX_CCM_ENABLING,
            'AJ' => BankAssets::CAPEX_CCA_ELIGIBLE,
            'AL' => BankAssets::CAPEX_CCA_ALIGNED,
            'AN' => BankAssets::CAPEX_CCA_ADAPTING,
            'AP' => BankAssets::CAPEX_CCA_ENABLING,

            'AR' => BankAssets::OPEX_CCM_ELIGIBLE,
            'AT' => BankAssets::OPEX_CCM_ALIGNED,
            'AV' => BankAssets::OPEX_CCM_TRANSITIONAL,
            'AX' => BankAssets::OPEX_CCM_ENABLING,
            'AZ' => BankAssets::OPEX_CCA_ELIGIBLE,
            'BB' => BankAssets::OPEX_CCA_ALIGNED,
            'BD' => BankAssets::OPEX_CCA_ADAPTING,
            'BF' => BankAssets::OPEX_CCA_ENABLING,
        ];

        foreach ($worksheet->getRowIterator() as $rowNumber => $row) {
            $data = [];

            if ($rowNumber < 6) {
                continue;
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $i => $cell) {
                if (!isset($map[$i])) { // Remove the last validation after add data
                    continue;
                }
                $cellvalue = $cell->getCalculatedValue();
                $data[$map[$i]] = $cellvalue;
            }
            if (!empty(array_filter($data))) {
                $explicativas[] = $data;
            }
        }

        $this->saveFileLocally('database/data/gar-btar-sibs/explanatory-notes', 'processed_results', $explicativas);
        $this->calculateDashboardBanco();
    }

    public function getDetailedByData($ativoBancarioNewArray, $array_key, $total_value, $stockFlowKey)
    {
        $data = [];
        foreach ($ativoBancarioNewArray as $item) {
            if (!isset($data[$item[$array_key]])) {
                $data[$item[$array_key]] = [
                    BankAssets::ABSOLUTE_VALUE => 0,
                    BankAssets::PERCENT => 0,
                ];
            }
            $data[$item[$array_key]][BankAssets::ABSOLUTE_VALUE] += $this->getValueFromRow($item[$stockFlowKey]);
            if ($total_value > 0) {
                $data[$item[$array_key]][BankAssets::PERCENT] = calculatePercentage($data[$item[$array_key]][BankAssets::ABSOLUTE_VALUE], $total_value, 2);
            }
        }
        return $data;
    }

    public function completeArray($currentData, array $values)
    {
        foreach ($values as $value) {
            if (!isset($currentData[$value])) {
                $currentData[$value] = [
                    BankAssets::ABSOLUTE_VALUE => 0,
                    BankAssets::PERCENT => 0,
                ];
            }
        }
        return $currentData;
    }

    public function hasTaxonomicInformation($ativoBancarioNewArray, $prefix)
    {
        return isset($ativoBancarioNewArray[$prefix . BankAssets::CCM_ALIGNED]) &&
            isset($ativoBancarioNewArray[$prefix . BankAssets::CCA_ALIGNED]);
    }

    public function getTaxonomicInformationArray($ativoBancarioNewArray, $stockFlowOptionField)
    {
        $data = [
            BankAssets::WITH_DATA => 0,
            BankAssets::NO_DATA => 0,
        ];
        foreach ($ativoBancarioNewArray as $item) {
            if ($item[BankAssets::VN_CCM_ELIGIBLE] === null && $item[BankAssets::VN_CCA_ELIGIBLE] === null && $item[BankAssets::CAPEX_CCM_ELIGIBLE] === null && $item[BankAssets::CAPEX_CCA_ELIGIBLE] === null) {
                $data[BankAssets::NO_DATA] += $item[$stockFlowOptionField];
            } else {
                $data[BankAssets::WITH_DATA] += $item[$stockFlowOptionField];
            }
        }
        return $data;
    }

    public function calculateDashboardBanco()
    {
        $jsonFile = \File::get(base_path() . '/database/data/gar-btar-sibs/explanatory-notes/processed_results.json');
        $jsonArray = json_decode($jsonFile, true);

        $stockFlowOptions = [
            [
                'keyName' => BankAssets::STOCK,
                'field' => BankAssets::STOCK_FIELD,
            ],
            [
                'keyName' => BankAssets::FLOW,
                'field' => BankAssets::FLOW_FIELD,
            ],
        ];

        $volumeCapexOptions = [
            [
                'keyName' => BankAssets::VN,
                'prefix' => BankAssets::VN_PREFIX,
            ],
            [
                'keyName' => BankAssets::CAPEX,
                'prefix' => BankAssets::CAPEX_PREFIX,
            ],
        ];

        $dataFinal = [];
        $naceCodes = getRowsByValue($jsonArray, BankAssets::NACE_CODE);
        foreach ($stockFlowOptions as $stockFlowOption) {
            $resultArray = [];
            // Tab "Balanço do banco"

            // Ativo bancário
            $resultArray[BankAssets::BANK_ASSETS] = 0;
            $ativoBancarioNewArray = filterArrayByKeyValue($jsonArray, BankAssets::TYPE, '1-16', true);
            $resultArray[BankAssets::BANK_ASSETS] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));

            // Ativo excluído do numerador e denominador
            $resultArray[BankAssets::ASSETS_EXCLUDED_NUMERATOR_DENOMINATOR] = 0;
            $ativoBancarioNewArray = filterArrayByKeyValue($jsonArray, BankAssets::TYPE, '14-16', true);
            $resultArray[BankAssets::ASSETS_EXCLUDED_NUMERATOR_DENOMINATOR] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));
            $resultArray[BankAssets::ASSETS_EXCLUDED_NUMERATOR_DENOMINATOR_PERCENT] = calculatePercentage($resultArray[BankAssets::ASSETS_EXCLUDED_NUMERATOR_DENOMINATOR], $resultArray[BankAssets::BANK_ASSETS], 2);

            // Ativo relevante para rácios
            $resultArray[BankAssets::ASSETS_RELEVANT_FOR_RATIOS] = $resultArray[BankAssets::BANK_ASSETS] - $resultArray[BankAssets::ASSETS_EXCLUDED_NUMERATOR_DENOMINATOR];

            // Ativo excluído do numerador (GAR e BTAR)
            $resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR] = 0;
            $ativoBancarioNewArray = filterArrayByKeyValue($jsonArray, BankAssets::TYPE, '10-13', true);
            $resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));
            $resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_PERCENT] = calculatePercentage($resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR], $resultArray[BankAssets::BANK_ASSETS], 2);
            $resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED] = $this->getDetailedByData($ativoBancarioNewArray, BankAssets::TYPE, $resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR], $stockFlowOption['field']);
            $resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED] = $this->completeArray($resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED], [10, 11, 12, 13]);

            // Ativo Abrangido BTAR (relevância de numerador)
            $resultArray[BankAssets::ASSETS_COVERED_BTAR] = $resultArray[BankAssets::ASSETS_RELEVANT_FOR_RATIOS] - $resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR];
            $resultArray[BankAssets::ASSETS_COVERED_BTAR_PERCENT] = calculatePercentage($resultArray[BankAssets::ASSETS_COVERED_BTAR], $resultArray[BankAssets::BANK_ASSETS], 2);

            // Ativo excluído do numerador GAR - Empresas
            $resultArray[BankAssets::ASSETS_EXCLUDED_NUMERATOR_COMPANIES_GAR] = 0;
            $searchItems = [BankAssets::TYPE => [1, 2, 3], BankAssets::ENTITY_TYPE => 5, BankAssets::SUBJECT_NFDR => BankAssets::NO];
            $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
            $resultArray[BankAssets::ASSETS_EXCLUDED_NUMERATOR_COMPANIES_GAR] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));
            $resultArray[BankAssets::ASSETS_EXCLUDED_NUMERATOR_COMPANIES_GAR_PERCENT] = calculatePercentage($resultArray[BankAssets::ASSETS_EXCLUDED_NUMERATOR_COMPANIES_GAR], $resultArray[BankAssets::BANK_ASSETS], 2);

            // Ativo Abrangido GAR (relevância de numerador)
            $resultArray[BankAssets::ASSETS_COVERED_GAR] = $resultArray[BankAssets::ASSETS_COVERED_BTAR] - $resultArray[BankAssets::ASSETS_EXCLUDED_NUMERATOR_COMPANIES_GAR];
            $resultArray[BankAssets::ASSETS_COVERED_GAR_PERCENT] = calculatePercentage($resultArray[BankAssets::ASSETS_COVERED_GAR], $resultArray[BankAssets::BANK_ASSETS], 2);

            // Empresas não-financeiras não sujeitas a NFRD
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD] = 0;
            $searchItems = [BankAssets::TYPE => [1, 2, 3], BankAssets::ENTITY_TYPE => 5, BankAssets::SUBJECT_NFDR => BankAssets::NO];
            $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED] = $this->getDetailedByData($ativoBancarioNewArray, BankAssets::TYPE, $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD], $stockFlowOption['field']);
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED] = $this->completeArray($resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED], [1, 2, 3]);
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_TAXONOMIC_INFORMATION] = $this->getTaxonomicInformationArray($ativoBancarioNewArray, $stockFlowOption['field']);

            // Bens imóveis
            $resultArray[BankAssets::REAL_STATE] = 0;
            $ativoBancarioNewArray = filterArrayByKeyValue($jsonArray, BankAssets::TYPE, '9');
            $resultArray[BankAssets::REAL_STATE] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));

            // Setor público local
            $resultArray[BankAssets::PUBLIC_SECTOR_LOCAL] = 0;
            $ativoBancarioNewArray = filterArrayByKeyValue($jsonArray, BankAssets::TYPE, '7-8', true);
            $resultArray[BankAssets::PUBLIC_SECTOR_LOCAL] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));
            $resultArray[BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED] = $this->getDetailedByData($ativoBancarioNewArray, BankAssets::TYPE, $resultArray[BankAssets::PUBLIC_SECTOR_LOCAL] + $resultArray[BankAssets::REAL_STATE], $stockFlowOption['field']);
            $resultArray[BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED]['9'][BankAssets::ABSOLUTE_VALUE] = $resultArray[BankAssets::REAL_STATE];
            $resultArray[BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED]['9'][BankAssets::PERCENT] = 0;
            if ($resultArray[BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED]['9'][BankAssets::ABSOLUTE_VALUE] > 0) {
                $resultArray[BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED]['9'][BankAssets::PERCENT] = calculatePercentage($resultArray[BankAssets::REAL_STATE], ($resultArray[BankAssets::PUBLIC_SECTOR_LOCAL] + $resultArray[BankAssets::REAL_STATE]), 2);
            }
            $resultArray[BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED] = $this->completeArray($resultArray[BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED], [7, 8]);

            // Famílias
            $resultArray[BankAssets::FAMILIES] = 0;
            $ativoBancarioNewArray = filterArrayByKeyValue($jsonArray, BankAssets::TYPE, '4-6', true);
            $resultArray[BankAssets::FAMILIES] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));
            $resultArray[BankAssets::FAMILIES_DETAILED] = $this->getDetailedByData($ativoBancarioNewArray, BankAssets::TYPE, $resultArray[BankAssets::FAMILIES], $stockFlowOption['field']);
            $resultArray[BankAssets::FAMILIES_DETAILED] = $this->completeArray($resultArray[BankAssets::FAMILIES_DETAILED], [4, 5, 6]);

            // Empresas (para numerador GAR)
            $subqueries = [];
            $resultArray[BankAssets::COMPANIES] = 0;
            $searchItems = [BankAssets::TYPE => [1, 2, 3], BankAssets::ENTITY_TYPE => [1, 2, 3, 4]];
            $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
            $subqueries[] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));
            $partialData = [];
            foreach ($ativoBancarioNewArray as $item) {
                if (!isset($partialData[$item[BankAssets::TYPE]])) {
                    $partialData[$item[BankAssets::TYPE]] = [
                        BankAssets::ABSOLUTE_VALUE => 0,
                        BankAssets::PERCENT => 0,
                    ];
                }
                $partialData[$item[BankAssets::TYPE]][BankAssets::ABSOLUTE_VALUE] += $this->getValueFromRow($item[$stockFlowOption['field']]);
            }

            $searchItems = [BankAssets::TYPE => [1, 2, 3], BankAssets::ENTITY_TYPE => 5, BankAssets::SUBJECT_NFDR => BankAssets::YES];
            $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
            $subqueries[] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));
            foreach ($ativoBancarioNewArray as $item) {
                if (!isset($partialData[$item[BankAssets::TYPE]])) {
                    $partialData[$item[BankAssets::TYPE]] = [
                        BankAssets::ABSOLUTE_VALUE => 0,
                        BankAssets::PERCENT => 0,
                    ];
                }
                $partialData[$item[BankAssets::TYPE]][BankAssets::ABSOLUTE_VALUE] += $this->getValueFromRow($item[$stockFlowOption['field']]);
            }

            $resultArray[BankAssets::COMPANIES] = array_sum($subqueries);
            foreach ($partialData as $key => $value) {
                $partialData[$key][BankAssets::PERCENT] = calculatePercentage($partialData[$key][BankAssets::ABSOLUTE_VALUE], $resultArray[BankAssets::COMPANIES], 2);
            }
            $resultArray[BankAssets::COMPANIES_DETAILED] = $this->completeArray($partialData, [1, 2, 3]);
            $subqueries = [];

            // E - Empresas não financeiras
            $resultArray[BankAssets::NON_FINANCIAL_COMPANIES] = 0;
            $searchItems = [BankAssets::TYPE => [1, 2, 3], BankAssets::ENTITY_TYPE => 5, BankAssets::SUBJECT_NFDR => BankAssets::YES];
            $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
            $resultArray[BankAssets::NON_FINANCIAL_COMPANIES] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));
            $resultArray[BankAssets::NON_FINANCIAL_COMPANIES_TAXONOMIC_INFORMATION] = $this->getTaxonomicInformationArray($ativoBancarioNewArray, $stockFlowOption['field']);

            // D - Empresas de seguros
            $resultArray[BankAssets::INSURANCE_COMPANIES] = 0;
            $searchItems = [BankAssets::TYPE => [1, 2, 3], BankAssets::ENTITY_TYPE => 4];
            $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
            $resultArray[BankAssets::INSURANCE_COMPANIES] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));

            // C - Sociedades gestoras
            $resultArray[BankAssets::MANAGEMENT_COMPANIES] = 0;
            $searchItems = [BankAssets::TYPE => [1, 2, 3], BankAssets::ENTITY_TYPE => 3];
            $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
            $resultArray[BankAssets::MANAGEMENT_COMPANIES] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));

            // B - Empresas de investimento
            $resultArray[BankAssets::INVESTMENT_COMPANIES] = 0;
            $searchItems = [BankAssets::TYPE => [1, 2, 3], BankAssets::ENTITY_TYPE => 2];
            $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
            $resultArray[BankAssets::INVESTMENT_COMPANIES] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));

            // A - Instituições de crédito
            $resultArray[BankAssets::CREDIT_INSTITUTIONS] = 0;
            $searchItems = [BankAssets::TYPE => [1, 2, 3], BankAssets::ENTITY_TYPE => 1];
            $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
            $resultArray[BankAssets::CREDIT_INSTITUTIONS] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));

            // Segmentação dos tipos de activos em balanço - 16 rubricas diferentes (valor absoluto)
            $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE] = [];
            $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_PERCENT] = [];
            foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16] as $index) {
                $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE][$index] = 0;
                $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_PERCENT][$index] = 0;
            }

            $ativoBancarioNewArray = filterArrayByKeyValue($jsonArray, BankAssets::TYPE, '1-16', true);
            foreach ($ativoBancarioNewArray as $item) {
                $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE][$item[BankAssets::TYPE]] += $item[$stockFlowOption['field']];
            }

            // Segmentação dos tipos de activos em balanço - 16 rubricas diferentes (percentagem)
            foreach ($resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE] as $key => $value) {
                $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_PERCENT][$key] = calculatePercentage($value, $resultArray[BankAssets::BANK_ASSETS], 2);
            }

            // Segmentação por sectores de actividade económica [código NACE] (valor absoluto)
            $totalNACE = 0;
            $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ABSOLUTE_VALUE] = [];
            $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_PERCENT] = [];
            foreach ($naceCodes as $item) {
                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ABSOLUTE_VALUE][$item[BankAssets::NACE_CODE]] = 0;
                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_PERCENT][$item[BankAssets::NACE_CODE]] = 0;
            }
            $totalNACE = array_sum(array_column($naceCodes, $stockFlowOption['field']));

            foreach ($naceCodes as $item) {
                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ABSOLUTE_VALUE][$item[BankAssets::NACE_CODE]] += $item[$stockFlowOption['field']];
            }

            // Segmentação por sectores de actividade económica [código NACE] (percentagem)
            foreach ($resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ABSOLUTE_VALUE] as $key => $value) {
                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_PERCENT][$key] = calculatePercentage($value, $totalNACE, 2);
            }

            // Empresas não sujeitas a NFRD (UE)
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU] = 0;
            $searchItems = [BankAssets::ENTITY_TYPE => 5, BankAssets::SUBJECT_NFDR => BankAssets::NO, BankAssets::EUROPEAN_COMPANY => BankAssets::YES, BankAssets::TYPE => [1, 2, 3]];
            $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU_DETAILED] = $this->getDetailedByData($ativoBancarioNewArray, BankAssets::TYPE, $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU], $stockFlowOption['field']);
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU_TAXONOMIC_INFORMATION] = $this->getTaxonomicInformationArray($ativoBancarioNewArray, $stockFlowOption['field']);

            // Empresas não sujeitas a NFRD (ex-UE)
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU] = 0;
            $searchItems = [BankAssets::ENTITY_TYPE => 5, BankAssets::SUBJECT_NFDR => BankAssets::NO, BankAssets::EUROPEAN_COMPANY => BankAssets::NO, BankAssets::TYPE => [1, 2, 3]];
            $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU_DETAILED] = $this->getDetailedByData($ativoBancarioNewArray, BankAssets::TYPE, $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU], $stockFlowOption['field']);
            $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU_TAXONOMIC_INFORMATION] = $this->getTaxonomicInformationArray($ativoBancarioNewArray, $stockFlowOption['field']);

            // Ativo excluído do numerador GAR
            $resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR] = $resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR] + $resultArray[BankAssets::ASSETS_EXCLUDED_NUMERATOR_COMPANIES_GAR];
            $resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_PERCENT] = calculatePercentage($resultArray[BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR], $resultArray[BankAssets::BANK_ASSETS], 2);

            $ativoBancarioNewArray = filterArrayByKeyValue($jsonArray, BankAssets::TYPE, '1-9', true);
            $resultArray[BankAssets::TAXONOMIC_INFORMATION] = $this->getTaxonomicInformationArray($ativoBancarioNewArray, $stockFlowOption['field']);

            // Empréstimos especializados
            $ativoBancarioNewArray = filterArrayByKeyValue($jsonArray, BankAssets::SPECIFIC_PURPOSE, BankAssets::YES);
            $resultArray[BankAssets::SPECIALIZED_LOANS] = array_sum(array_column($ativoBancarioNewArray, $stockFlowOption['field']));

            $finalArray[BankAssets::BANK_BALANCE] = $resultArray;

            foreach ($volumeCapexOptions as $volumeCapexOption) {
                $resultArray = [];
                // Tab "GAR - tabela resumo"
                $tipos_empresas = [
                    // 1. Instituções de crédito
                    [
                        'name' => BankAssets::CREDIT_INSTITUTIONS,
                        'filter' => [
                            BankAssets::ENTITY_TYPE => 1,
                            BankAssets::TYPE => [1, 2, 3],
                        ],
                        'is_empresa' => true,
                    ],
                    // 2. Empresas de investimento
                    [
                        'name' => BankAssets::INVESTMENT_COMPANIES,
                        'filter' => [
                            BankAssets::ENTITY_TYPE => 2,
                            BankAssets::TYPE => [1, 2, 3],
                        ],
                        'is_empresa' => true,
                    ],
                    // 3. Sociedades gestoras
                    [
                        'name' => BankAssets::MANAGEMENT_COMPANIES,
                        'filter' => [
                            BankAssets::ENTITY_TYPE => 3,
                            BankAssets::TYPE => [1, 2, 3],
                        ],
                        'is_empresa' => true,
                    ],
                    // 4. Empresas de seguros
                    [
                        'name' => BankAssets::INSURANCE_COMPANIES,
                        'filter' => [
                            BankAssets::ENTITY_TYPE => 4,
                            BankAssets::TYPE => [1, 2, 3],
                        ],
                        'is_empresa' => true,
                    ],
                    // 5. Empresas não-financeiras (sujeitas a NFRD)
                    [
                        'name' => BankAssets::NON_FINANCIAL_COMPANIES_SUBJECT_NFRD,
                        'filter' => [
                            BankAssets::ENTITY_TYPE => 5,
                            BankAssets::TYPE => [1, 2, 3],
                            BankAssets::SUBJECT_NFDR => BankAssets::YES,
                        ],
                        'is_empresa' => true,
                    ],
                    // Famílias
                    [
                        'name' => BankAssets::FAMILIES,
                        'filter' => [
                            BankAssets::TYPE => [4, 5, 6],
                        ],
                        'is_empresa' => false,
                    ],
                    // Bens imóveis
                    [
                        'name' => BankAssets::REAL_STATE,
                        'filter' => [
                            BankAssets::TYPE => 9,
                        ],
                        'is_empresa' => false,
                    ],
                    // Sector público
                    [
                        'name' => BankAssets::PUBLIC_SECTOR,
                        'filter' => [
                            BankAssets::TYPE => [7, 8],
                        ],
                        'is_empresa' => false,
                    ],
                    // Crédito especializado
                    [
                        'name' => BankAssets::SPECIALIZED_CREDIT,
                        'filter' => [
                            BankAssets::SPECIFIC_PURPOSE => BankAssets::YES,
                            BankAssets::ENTITY_TYPE => 5,
                            BankAssets::SUBJECT_NFDR => BankAssets::YES,
                        ],
                        'is_empresa' => false,
                    ],
                ];

                $total_empresas = [
                    BankAssets::ELIGIBLE => 0,
                    BankAssets::ALIGNED => 0,
                    BankAssets::TRANSITIONAL_ADAPTING => 0,
                    BankAssets::ENABLING => 0,
                    BankAssets::NO_DATA => 0,
                    BankAssets::WITH_DATA => 0,
                ];

                foreach ($tipos_empresas as $tipo_empresa) {
                    $resultArray[$tipo_empresa['name']] = [
                        BankAssets::ELIGIBLE => 0,
                        BankAssets::ALIGNED => 0,
                        BankAssets::TRANSITIONAL_ADAPTING => 0,
                        BankAssets::ENABLING => 0,
                        BankAssets::NO_DATA => 0,
                        BankAssets::WITH_DATA => 0,
                    ];

                    $searchItems = $tipo_empresa['filter'];
                    $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
                    // Tipo Empresa -> Elegibilidade
                    $resultArray[$tipo_empresa['name']][BankAssets::ELIGIBLE] = $this->getAbsoluteValueForRows($ativoBancarioNewArray, $stockFlowOption['field'], $volumeCapexOption['prefix'], BankAssets::CCM_ELIGIBLE, BankAssets::CCA_ELIGIBLE);
                    // Tipo Empresa -> Alinhamento
                    $resultArray[$tipo_empresa['name']][BankAssets::ALIGNED] = $this->getAbsoluteValueForRows($ativoBancarioNewArray, $stockFlowOption['field'], $volumeCapexOption['prefix'], BankAssets::CCM_ALIGNED, BankAssets::CCA_ALIGNED);
                    // Tipo Empresa -> Transição/Adaptação
                    $resultArray[$tipo_empresa['name']][BankAssets::TRANSITIONAL_ADAPTING] = $this->getAbsoluteValueForRows($ativoBancarioNewArray, $stockFlowOption['field'], $volumeCapexOption['prefix'], BankAssets::CCM_TRANSITIONAL, BankAssets::CCA_ADAPTING);
                    // Tipo Empresa -> Capacitante
                    $resultArray[$tipo_empresa['name']][BankAssets::ENABLING] = $this->getAbsoluteValueForRows($ativoBancarioNewArray, $stockFlowOption['field'], $volumeCapexOption['prefix'], BankAssets::CCM_ENABLING, BankAssets::CCA_ENABLING);
                    foreach ($ativoBancarioNewArray as $item) {
                        if (!$this->hasTaxonomicInformation($item, $volumeCapexOption['prefix'])) {
                            // Tipo Empresa -> Sem Dados
                            $resultArray[$tipo_empresa['name']][BankAssets::NO_DATA] += $item[$stockFlowOption['field']];
                        } else {
                            // Tipo Empresa -> Com Dados
                            $resultArray[$tipo_empresa['name']][BankAssets::WITH_DATA] += $item[$stockFlowOption['field']];
                        }
                    }
                    if ($tipo_empresa['is_empresa']) {
                        // Empresas (para efeitos de GAR) -> Elegibilidade
                        $total_empresas[BankAssets::ELIGIBLE] += $resultArray[$tipo_empresa['name']][BankAssets::ELIGIBLE];
                        // Empresas (para efeitos de GAR) -> Alinhamento
                        $total_empresas[BankAssets::ALIGNED] += $resultArray[$tipo_empresa['name']][BankAssets::ALIGNED];
                        // Empresas (para efeitos de GAR) -> Transição/Adaptação
                        $total_empresas[BankAssets::TRANSITIONAL_ADAPTING] += $resultArray[$tipo_empresa['name']][BankAssets::TRANSITIONAL_ADAPTING];
                        // Empresas (para efeitos de GAR) -> Capacitante
                        $total_empresas[BankAssets::ENABLING] += $resultArray[$tipo_empresa['name']][BankAssets::ENABLING];
                        // Empresas (para efeitos de GAR) -> Sem Dados
                        $total_empresas[BankAssets::NO_DATA] += $resultArray[$tipo_empresa['name']][BankAssets::NO_DATA];
                        // Empresas (para efeitos de GAR) -> Com Dados
                        $total_empresas[BankAssets::WITH_DATA] += $resultArray[$tipo_empresa['name']][BankAssets::WITH_DATA];
                    }
                }

                // Empresas (para efeitos de GAR)
                $resultArray[BankAssets::COMPANIES_GAR] = $total_empresas;

                // Ativos elegíveis (GAR)
                $resultArray[BankAssets::ELIGIBLE_ASSETS] = $total_empresas[BankAssets::ELIGIBLE] + $resultArray[BankAssets::FAMILIES][BankAssets::ELIGIBLE]
                    + $resultArray[BankAssets::REAL_STATE][BankAssets::ELIGIBLE] + $resultArray[BankAssets::PUBLIC_SECTOR][BankAssets::ELIGIBLE];
                $resultArray[BankAssets::ELIGIBLE_ASSETS_PERCENT] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Ativos elegíveis e alinhados (GAR)
                $resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS] = $total_empresas[BankAssets::ALIGNED] + $resultArray[BankAssets::FAMILIES][BankAssets::ALIGNED]
                    + $resultArray[BankAssets::REAL_STATE][BankAssets::ALIGNED] + $resultArray[BankAssets::PUBLIC_SECTOR][BankAssets::ALIGNED];
                $resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS_PERCENT] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Ativos elegíveis e não alinhados (GAR)
                $resultArray[BankAssets::ELIGIBLE_AND_NOT_ALIGNED_ASSETS] = $resultArray[BankAssets::ELIGIBLE_ASSETS] - $resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS];
                $resultArray[BankAssets::ELIGIBLE_AND_NOT_ALIGNED_ASSETS_PERCENT] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_AND_NOT_ALIGNED_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Ativos com dados (GAR)
                $resultArray[BankAssets::ASSETS_WITH_DATA] = $total_empresas[BankAssets::WITH_DATA] + $resultArray[BankAssets::FAMILIES][BankAssets::WITH_DATA]
                    + $resultArray[BankAssets::REAL_STATE][BankAssets::WITH_DATA] + $resultArray[BankAssets::PUBLIC_SECTOR][BankAssets::WITH_DATA];
                $resultArray[BankAssets::ASSETS_WITH_DATA_PERCENT] = calculatePercentage($resultArray[BankAssets::ASSETS_WITH_DATA], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Ativos sem dados (GAR)
                $resultArray[BankAssets::ASSETS_WITHOUT_DATA] = $total_empresas[BankAssets::NO_DATA] + $resultArray[BankAssets::FAMILIES][BankAssets::NO_DATA]
                    + $resultArray[BankAssets::REAL_STATE][BankAssets::NO_DATA] + $resultArray[BankAssets::PUBLIC_SECTOR][BankAssets::NO_DATA];
                $resultArray[BankAssets::ASSETS_WITHOUT_DATA_PERCENT] = calculatePercentage($resultArray[BankAssets::ASSETS_WITHOUT_DATA], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Ativos não elegíveis (GAR)
                $resultArray[BankAssets::NOT_ELIGIBLE_ASSETS] = (($finalArray[BankAssets::BANK_BALANCE][BankAssets::COMPANIES] + $finalArray[BankAssets::BANK_BALANCE][BankAssets::FAMILIES]
                    + $finalArray[BankAssets::BANK_BALANCE][BankAssets::REAL_STATE] + $finalArray[BankAssets::BANK_BALANCE][BankAssets::PUBLIC_SECTOR_LOCAL]) - $resultArray[BankAssets::ELIGIBLE_ASSETS]) - $resultArray[BankAssets::ASSETS_WITHOUT_DATA];
                $resultArray[BankAssets::NOT_ELIGIBLE_ASSETS_PERCENT] = calculatePercentage($resultArray[BankAssets::NOT_ELIGIBLE_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Denominador (GAR)
                $resultArray[BankAssets::DENOMINATOR] = $finalArray[BankAssets::BANK_BALANCE][BankAssets::ASSETS_COVERED_GAR] + $finalArray[BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD] + $finalArray[BankAssets::BANK_BALANCE][BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR];
                $resultArray[BankAssets::DENOMINATOR_PERCENT] = calculatePercentage($resultArray[BankAssets::DENOMINATOR], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // GAR
                $resultArray[BankAssets::GAR] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS], $resultArray[BankAssets::DENOMINATOR], 2);

                // Abrangência
                $resultArray[BankAssets::COVERAGE] = calculatePercentage($finalArray[BankAssets::BANK_BALANCE][BankAssets::ASSETS_COVERED_GAR], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                $resultArray[BankAssets::ELIGIBLE] = 0;
                $resultArray[BankAssets::ALIGNED] = 0;
                if ($finalArray[BankAssets::BANK_BALANCE][BankAssets::ASSETS_COVERED_GAR] > 0) {
                    // Elegibilidade
                    $resultArray[BankAssets::ELIGIBLE] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::ASSETS_COVERED_GAR], 2);

                    // Alinhamento
                    $resultArray[BankAssets::ALIGNED] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::ASSETS_COVERED_GAR], 2);
                }

                // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - abrangidos em GAR
                $ativoBancarioNewArray = filterArrayWithArray($jsonArray, [
                    BankAssets::ENTITY_TYPE => 5,
                    BankAssets::SUBJECT_NFDR => BankAssets::YES,
                ]);

                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_COVERED] = [];
                foreach ($ativoBancarioNewArray as $item) {
                    if (!isset($resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_COVERED][$item[BankAssets::NACE_CODE]])) {
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_COVERED][$item[BankAssets::NACE_CODE]] = 0;
                    }
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_COVERED][$item[BankAssets::NACE_CODE]] += $item[$stockFlowOption['field']];
                }

                // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - não abrangidos em GAR
                $ativoBancarioNewArray = filterArrayWithArray($jsonArray, [
                    BankAssets::ENTITY_TYPE => 5,
                    BankAssets::SUBJECT_NFDR => BankAssets::NO,
                ]);

                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_NOT_COVERED] = [];
                foreach ($ativoBancarioNewArray as $item) {
                    if (!isset($resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_NOT_COVERED][$item[BankAssets::NACE_CODE]])) {
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_NOT_COVERED][$item[BankAssets::NACE_CODE]] = 0;
                    }
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_NOT_COVERED][$item[BankAssets::NACE_CODE]] += $item[$stockFlowOption['field']];
                }

                $tipos_empresas = [
                    [
                        BankAssets::TYPE => [1, 2, 3],
                        BankAssets::ENTITY_TYPE => 5,
                        BankAssets::SUBJECT_NFDR => BankAssets::YES,
                    ],
                    [
                        BankAssets::TYPE => [4, 5, 6, 7, 8, 9],
                    ],
                ];

                foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $index) {
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_TRANSITIONAL][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ADAPTING][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ENABLING][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_COVERED][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_WITHOUT_DATA][$index] = 0;
                    $resultArray[BankAssets::CCM_ALIGNED_LONG][$index] = 0;
                    $resultArray[BankAssets::CCA_ALIGNED_LONG][$index] = 0;
                }

                foreach ($tipos_empresas as $tipo_empresa) {
                    $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $tipo_empresa);
                    foreach ($ativoBancarioNewArray as $item) {
                        // Segmentação dos tipos de activos em balanço - elegíveis - 9 rubricas diferentes
                        $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ELIGIBLE, $volumeCapexOption['prefix'] . BankAssets::CCA_ELIGIBLE]);

                        // Segmentação dos tipos de activos em balanço - alinhados - 9 rubricas diferentes
                        $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ALIGNED, $volumeCapexOption['prefix'] . BankAssets::CCA_ALIGNED]);

                        // Segmentação dos tipos de activos em balanço - transição (CCM) - 9 rubricas diferentes
                        $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_TRANSITIONAL][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_TRANSITIONAL]);

                        // Segmentação dos tipos de activos em balanço - adaptação (CCA) - 9 rubricas diferentes
                        $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ADAPTING][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCA_ADAPTING]);

                        // Segmentação dos tipos de activos em balanço - capacitante - 9 rubricas diferentes
                        $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ENABLING][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ENABLING, $volumeCapexOption['prefix'] . BankAssets::CCA_ENABLING]);

                        // Segmentação dos tipos de activos em balanço - abrangidos GAR - 9 rubricas diferentes
                        $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_COVERED][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field']);

                        if (!$this->hasTaxonomicInformation($item, $volumeCapexOption['prefix'])) {
                            // Segmentação dos tipos de activos em balanço - sem dados GAR - 9 rubricas diferentes
                            $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_WITHOUT_DATA][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field']);
                        }
                    }
                }

                $ativoBancarioNewArray = filterArrayByKeyValue($jsonArray, BankAssets::TYPE, '1-9', true);
                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_WITHOUT_DATA] = [];
                foreach ($ativoBancarioNewArray as $item) {
                    if (in_array($item[BankAssets::TYPE], [1, 2, 3])) {
                        if ($item[BankAssets::ENTITY_TYPE] == 5 && $item[BankAssets::SUBJECT_NFDR] === BankAssets::NO) {
                            continue;
                        }
                    }

                    if (!$this->hasTaxonomicInformation($item, $volumeCapexOption['prefix'])) {
                        if (!empty($item[BankAssets::NACE_CODE])) {
                            // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - Sem dados
                            if (!isset($resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_WITHOUT_DATA][$item[BankAssets::NACE_CODE]])) {
                                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_WITHOUT_DATA][$item[BankAssets::NACE_CODE]] = 0;
                            }
                            $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_WITHOUT_DATA][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field']);
                        }
                    }

                    // Mitigação das alterações climáticas - alinhamento
                    $resultArray[BankAssets::CCM_ALIGNED_LONG][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ALIGNED]);

                    // Adaptação às alterações climáticas - alinhamento
                    $resultArray[BankAssets::CCA_ALIGNED_LONG][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCA_ALIGNED]);
                }

                // Segmentação dos tipos de activos em balanço - não alinhados - 9 rubricas diferentes
                $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_NOT_ALIGNED] = [];
                foreach ($resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE] as $key => $value) {
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_NOT_ALIGNED][$key] = $value - $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED][$key];
                }

                $ativoBancarioNewArray = filterArrayWithArray($jsonArray, [
                    BankAssets::ENTITY_TYPE => 5,
                    BankAssets::SUBJECT_NFDR => BankAssets::YES,
                ]);

                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ELIGIBLE] = [];
                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ALIGNED] = [];
                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_TRANSITIONAL] = [];
                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ADAPTING] = [];
                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ENABLING] = [];
                foreach ($ativoBancarioNewArray as $item) {
                    if ($this->hasTaxonomicInformation($item, $volumeCapexOption['prefix'])) {
                        // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - elegíveis
                        if (!isset($resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ELIGIBLE][$item[BankAssets::NACE_CODE]])) {
                            $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ELIGIBLE][$item[BankAssets::NACE_CODE]] = 0;
                        }
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ELIGIBLE][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ELIGIBLE, $volumeCapexOption['prefix'] . BankAssets::CCA_ELIGIBLE]);

                        // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - alinhados
                        if (!isset($resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ALIGNED][$item[BankAssets::NACE_CODE]])) {
                            $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ALIGNED][$item[BankAssets::NACE_CODE]] = 0;
                        }
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ALIGNED][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ALIGNED, $volumeCapexOption['prefix'] . BankAssets::CCA_ALIGNED]);

                        // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - transição (CCM)
                        if (!isset($resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_TRANSITIONAL][$item[BankAssets::NACE_CODE]])) {
                            $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_TRANSITIONAL][$item[BankAssets::NACE_CODE]] = 0;
                        }
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_TRANSITIONAL][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_TRANSITIONAL]);

                        // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - adaptação (CCA)
                        if (!isset($resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ADAPTING][$item[BankAssets::NACE_CODE]])) {
                            $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ADAPTING][$item[BankAssets::NACE_CODE]] = 0;
                        }
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ADAPTING][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCA_ADAPTING]);

                        // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - capacitante
                        if (!isset($resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ENABLING][$item[BankAssets::NACE_CODE]])) {
                            $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ENABLING][$item[BankAssets::NACE_CODE]] = 0;
                        }
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ENABLING][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ENABLING, $volumeCapexOption['prefix'] . BankAssets::CCA_ENABLING]);
                    }
                }

                // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - não alinhados
                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_NOT_ALIGNED] = [];
                foreach ($resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ELIGIBLE] as $key => $value) {
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_NOT_ALIGNED][$key] = $value - $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ALIGNED][$key];
                }

                // Atividades de transição/adaptação GAR
                $resultArray[BankAssets::ACTIVITIES_TRANSITIONAL_ADAPTING] = $resultArray[BankAssets::COMPANIES_GAR][BankAssets::TRANSITIONAL_ADAPTING] + $resultArray[BankAssets::FAMILIES][BankAssets::TRANSITIONAL_ADAPTING]
                    + $resultArray[BankAssets::REAL_STATE][BankAssets::TRANSITIONAL_ADAPTING] + $resultArray[BankAssets::PUBLIC_SECTOR][BankAssets::TRANSITIONAL_ADAPTING];

                // Atividades de capacitação GAR
                $resultArray[BankAssets::ACTIVITIES_CAPACITING] = $resultArray[BankAssets::COMPANIES_GAR][BankAssets::ENABLING] + $resultArray[BankAssets::FAMILIES][BankAssets::ENABLING]
                    + $resultArray[BankAssets::REAL_STATE][BankAssets::ENABLING] + $resultArray[BankAssets::PUBLIC_SECTOR][BankAssets::ENABLING];

                $finalArray[$volumeCapexOption['keyName']][BankAssets::GAR] = $resultArray;
                $resultArray = [];


                // Tab "BTAR - tabela resumo"
                $tipos_empresas = [
                    // Empresas não sujeitas a NFRD (UE)
                    [
                        'name' => BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU,
                        'filter' => [
                            BankAssets::ENTITY_TYPE => 5,
                            BankAssets::SUBJECT_NFDR => BankAssets::NO,
                            BankAssets::EUROPEAN_COMPANY => BankAssets::YES,
                            BankAssets::TYPE => [1, 2, 3],
                        ],
                        'is_empresa' => true,
                    ],
                    // Empresas não sujeitas a NFRD (ex-UE)
                    [
                        'name' => BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU,
                        'filter' => [
                            BankAssets::ENTITY_TYPE => 5,
                            BankAssets::SUBJECT_NFDR => BankAssets::NO,
                            BankAssets::EUROPEAN_COMPANY => BankAssets::NO,
                            BankAssets::TYPE => [1, 2, 3],
                        ],
                        'is_empresa' => true,
                    ],
                    // Crédito especializado
                    [
                        'name' => BankAssets::SPECIALIZED_CREDIT,
                        'filter' => [
                            BankAssets::SPECIFIC_PURPOSE => BankAssets::YES,
                        ],
                        'is_empresa' => false,
                    ],
                ];

                $total_empresas = [
                    BankAssets::ELIGIBLE => 0,
                    BankAssets::ALIGNED => 0,
                    BankAssets::TRANSITIONAL_ADAPTING => 0,
                    BankAssets::ENABLING => 0,
                    BankAssets::NO_DATA => 0,
                    BankAssets::WITH_DATA => 0,
                ];

                foreach ($tipos_empresas as $tipo_empresa) {
                    $resultArray[$tipo_empresa['name']] = [
                        BankAssets::ELIGIBLE => 0,
                        BankAssets::ALIGNED => 0,
                        BankAssets::TRANSITIONAL_ADAPTING => 0,
                        BankAssets::ENABLING => 0,
                        BankAssets::NO_DATA => 0,
                        BankAssets::WITH_DATA => 0
                    ];

                    $searchItems = $tipo_empresa['filter'];
                    $ativoBancarioNewArray = filterArrayWithArray($jsonArray, $searchItems);
                    // Tipo Empresa -> Elegibilidade
                    $resultArray[$tipo_empresa['name']][BankAssets::ELIGIBLE] = $this->getAbsoluteValueForRows($ativoBancarioNewArray, $stockFlowOption['field'], $volumeCapexOption['prefix'], BankAssets::CCM_ELIGIBLE, BankAssets::CCA_ELIGIBLE);
                    // Tipo Empresa -> Alinhamento
                    $resultArray[$tipo_empresa['name']][BankAssets::ALIGNED] = $this->getAbsoluteValueForRows($ativoBancarioNewArray, $stockFlowOption['field'], $volumeCapexOption['prefix'], BankAssets::CCM_ALIGNED, BankAssets::CCA_ALIGNED);
                    // Tipo Empresa -> Transição/Adaptação
                    $resultArray[$tipo_empresa['name']][BankAssets::TRANSITIONAL_ADAPTING] = $this->getAbsoluteValueForRows($ativoBancarioNewArray, $stockFlowOption['field'], $volumeCapexOption['prefix'], BankAssets::CCM_TRANSITIONAL, BankAssets::CCA_ADAPTING);
                    // Tipo Empresa -> Capacitante
                    $resultArray[$tipo_empresa['name']][BankAssets::ENABLING] = $this->getAbsoluteValueForRows($ativoBancarioNewArray, $stockFlowOption['field'], $volumeCapexOption['prefix'], BankAssets::CCM_ENABLING, BankAssets::CCA_ENABLING);
                    foreach ($ativoBancarioNewArray as $item) {
                        if (!$this->hasTaxonomicInformation($item, $volumeCapexOption['prefix'])) {
                            // Tipo Empresa -> Sem Dados
                            $resultArray[$tipo_empresa['name']][BankAssets::NO_DATA] += $item[$stockFlowOption['field']];
                        } else {
                            // Tipo Empresa -> Com Dados
                            $resultArray[$tipo_empresa['name']][BankAssets::WITH_DATA] += $item[$stockFlowOption['field']];
                        }
                    }
                    if ($tipo_empresa['is_empresa']) {
                        // Empresas não sujeitas a NFRD -> Elegibilidade
                        $total_empresas[BankAssets::ELIGIBLE] += $resultArray[$tipo_empresa['name']][BankAssets::ELIGIBLE];
                        // Empresas não sujeitas a NFRD -> Alinhamento
                        $total_empresas[BankAssets::ALIGNED] += $resultArray[$tipo_empresa['name']][BankAssets::ALIGNED];
                        // Empresas não sujeitas a NFRD -> Transição/Adaptação
                        $total_empresas[BankAssets::TRANSITIONAL_ADAPTING] += $resultArray[$tipo_empresa['name']][BankAssets::TRANSITIONAL_ADAPTING];
                        // Empresas não sujeitas a NFRD -> Capacitante
                        $total_empresas[BankAssets::ENABLING] += $resultArray[$tipo_empresa['name']][BankAssets::ENABLING];
                        // Empresas não sujeitas a NFRD -> Sem Dados
                        $total_empresas[BankAssets::NO_DATA] += $resultArray[$tipo_empresa['name']][BankAssets::NO_DATA];
                        // Empresas não sujeitas a NFRD -> Com Dados
                        $total_empresas[BankAssets::WITH_DATA] += $resultArray[$tipo_empresa['name']][BankAssets::WITH_DATA];
                    }
                }

                // Empresas não sujeitas a NFRD
                $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD] = $total_empresas;

                // Ativos elegíveis (BTAR)
                $resultArray[BankAssets::ELIGIBLE_ASSETS] = $finalArray[$volumeCapexOption['keyName']][BankAssets::GAR][BankAssets::ELIGIBLE_ASSETS] + $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD][BankAssets::ELIGIBLE];
                $resultArray[BankAssets::ELIGIBLE_ASSETS_PERCENT] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Ativos elegíveis e alinhados (BTAR)
                $resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS] = $finalArray[$volumeCapexOption['keyName']][BankAssets::GAR][BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS] + $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD][BankAssets::ALIGNED];
                $resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS_PERCENT] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Ativos elegíveis e não alinhados (BTAR)
                $resultArray[BankAssets::ELIGIBLE_AND_NOT_ALIGNED_ASSETS] = $resultArray[BankAssets::ELIGIBLE_ASSETS] - $resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS];
                $resultArray[BankAssets::ELIGIBLE_AND_NOT_ALIGNED_ASSETS_PERCENT] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_AND_NOT_ALIGNED_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Ativos com dados (BTAR)
                $resultArray[BankAssets::ASSETS_WITH_DATA] = 0;
                $resultArray[BankAssets::ASSETS_WITH_DATA_PERCENT] = 0;

                // Ativos sem dados (BTAR)
                $resultArray[BankAssets::ASSETS_WITHOUT_DATA] = 0;
                $resultArray[BankAssets::ASSETS_WITHOUT_DATA_PERCENT] = 0;

                // Ativos não elegíveis (BTAR)
                $resultArray[BankAssets::NOT_ELIGIBLE_ASSETS] = 0;
                $resultArray[BankAssets::NOT_ELIGIBLE_ASSETS_PERCENT] = 0;

                // Denominador (BTAR)
                $resultArray[BankAssets::DENOMINATOR] = $finalArray[$volumeCapexOption['keyName']][BankAssets::GAR][BankAssets::DENOMINATOR];
                $resultArray[BankAssets::DENOMINATOR_PERCENT] = calculatePercentage($resultArray[BankAssets::DENOMINATOR], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // BTAR
                $resultArray[BankAssets::BTAR] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS], $resultArray[BankAssets::DENOMINATOR], 2);

                // Abrangência
                $resultArray[BankAssets::COVERAGE] = calculatePercentage($finalArray[BankAssets::BANK_BALANCE][BankAssets::ASSETS_COVERED_BTAR], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Elegibilidade
                $resultArray[BankAssets::ELIGIBLE] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::ASSETS_COVERED_BTAR], 2);

                // Alinhamento
                $resultArray[BankAssets::ALIGNED] = calculatePercentage($resultArray[BankAssets::ELIGIBLE_AND_ALIGNED_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::ASSETS_COVERED_BTAR], 2);

                foreach ($naceCodes as $item) {
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_COVERED][$item[BankAssets::NACE_CODE]] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ELIGIBLE][$item[BankAssets::NACE_CODE]] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ALIGNED][$item[BankAssets::NACE_CODE]] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_TRANSITIONAL][$item[BankAssets::NACE_CODE]] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ADAPTING][$item[BankAssets::NACE_CODE]] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ENABLING][$item[BankAssets::NACE_CODE]] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_WITHOUT_DATA][$item[BankAssets::NACE_CODE]] = 0;
                }
                foreach ($naceCodes as $item) {
                    // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - abrangidos em BTAR
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_COVERED][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field']);
                    if ($this->hasTaxonomicInformation($item, $volumeCapexOption['prefix'])) {
                        // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - elegíveis BTAR
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ELIGIBLE][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ELIGIBLE, $volumeCapexOption['prefix'] . BankAssets::CCA_ELIGIBLE]);

                        // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - alinhados BTAR
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ALIGNED][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ALIGNED, $volumeCapexOption['prefix'] . BankAssets::CCA_ALIGNED]);

                        // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - transição (CCM) BTAR
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_TRANSITIONAL][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_TRANSITIONAL]);

                        // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - adaptação (CCA) BTAR
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ADAPTING][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCA_ADAPTING]);

                        // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - capacitante BTAR
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ENABLING][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ENABLING, $volumeCapexOption['prefix'] . BankAssets::CCA_ENABLING]);
                    } else {
                        $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_WITHOUT_DATA][$item[BankAssets::NACE_CODE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field']);
                    }
                }

                // Segmentação por sectores de actividade económica [código NACE] (valor absoluto) - não alinhados
                $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_NOT_ALIGNED] = [];
                foreach ($resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ELIGIBLE] as $key => $value) {
                    $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_NOT_ALIGNED][$key] = $value - $resultArray[BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ALIGNED][$key];
                }

                // Atividades de transição/adaptação BTAR
                $resultArray[BankAssets::ACTIVITIES_TRANSITIONAL_ADAPTING] = $finalArray[$volumeCapexOption['keyName']][BankAssets::GAR][BankAssets::ACTIVITIES_TRANSITIONAL_ADAPTING] + $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD][BankAssets::TRANSITIONAL_ADAPTING];

                // Atividades de capacitação BTAR
                $resultArray[BankAssets::ACTIVITIES_CAPACITING] = $finalArray[$volumeCapexOption['keyName']][BankAssets::GAR][BankAssets::ACTIVITIES_CAPACITING] + $resultArray[BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD][BankAssets::ENABLING];

                foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $index) {
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_TRANSITIONAL][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ADAPTING][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ENABLING][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_COVERED][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_WITH_DATA][$index] = 0;
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_WITHOUT_DATA][$index] = 0;
                    $resultArray[BankAssets::CCM_ALIGNED_LONG][$index] = 0;
                    $resultArray[BankAssets::CCA_ALIGNED_LONG][$index] = 0;
                }
                $ativoBancarioNewArray = filterArrayByKeyValue($jsonArray, BankAssets::TYPE, '1-9', true);
                foreach ($ativoBancarioNewArray as $item) {
                    // Segmentação dos tipos de activos em balanço - elegíveis - 9 rubricas diferentes
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ELIGIBLE, $volumeCapexOption['prefix'] . BankAssets::CCA_ELIGIBLE]);

                    // Segmentação dos tipos de activos em balanço - alinhados - 9 rubricas diferentes
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ALIGNED, $volumeCapexOption['prefix'] . BankAssets::CCA_ALIGNED]);

                    // Segmentação dos tipos de activos em balanço - transição (CCM) - 9 rubricas diferentes
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_TRANSITIONAL][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_TRANSITIONAL]);

                    // Segmentação dos tipos de activos em balanço - adaptação (CCA) - 9 rubricas diferentes
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ADAPTING][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCA_ADAPTING]);

                    // Segmentação dos tipos de activos em balanço - capacitante - 9 rubricas diferentes
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ENABLING][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ENABLING, $volumeCapexOption['prefix'] . BankAssets::CCA_ENABLING]);

                    // Segmentação dos tipos de activos em balanço - abrangidos BTAR - 9 rubricas diferentes
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_COVERED][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field']);

                    // Mitigação das alterações climáticas - alinhamento BTAR
                    $resultArray[BankAssets::CCM_ALIGNED_LONG][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCM_ALIGNED]);

                    // Adaptação às alterações climáticas - alinhamento BTAR
                    $resultArray[BankAssets::CCA_ALIGNED_LONG][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field'], [$volumeCapexOption['prefix'] . BankAssets::CCA_ALIGNED]);
                    if (!$this->hasTaxonomicInformation($item, $volumeCapexOption['prefix'])) {
                        // Segmentação dos tipos de activos em balanço - sem dados BTAR - 9 rubricas diferentes
                        $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_WITHOUT_DATA][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field']);
                    } else {
                        $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_WITH_DATA][$item[BankAssets::TYPE]] += $this->getAbsoluteValueForRow($item, $stockFlowOption['field']);
                    }
                }

                // Segmentação dos tipos de activos em balanço - não alinhados - 9 rubricas diferentes
                $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_NOT_ALIGNED] = [];
                foreach ($resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE] as $key => $value) {
                    $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_NOT_ALIGNED][$key] = $value - $resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED][$key];
                }

                // Ativos com dados (BTAR)
                $resultArray[BankAssets::ASSETS_WITH_DATA] = array_sum($resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_WITH_DATA]);
                $resultArray[BankAssets::ASSETS_WITH_DATA_PERCENT] = calculatePercentage($resultArray[BankAssets::ASSETS_WITH_DATA], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Ativos sem dados (BTAR)
                $resultArray[BankAssets::ASSETS_WITHOUT_DATA] = array_sum($resultArray[BankAssets::SEGMENTATION_ASSETS_TYPE_WITHOUT_DATA]);
                $resultArray[BankAssets::ASSETS_WITHOUT_DATA_PERCENT] = calculatePercentage($resultArray[BankAssets::ASSETS_WITHOUT_DATA], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                // Ativos não elegíveis (BTAR)
                $resultArray[BankAssets::NOT_ELIGIBLE_ASSETS] = ($finalArray[BankAssets::BANK_BALANCE][BankAssets::COMPANIES] + $finalArray[BankAssets::BANK_BALANCE][BankAssets::FAMILIES]
                    + $finalArray[BankAssets::BANK_BALANCE][BankAssets::REAL_STATE] + $finalArray[BankAssets::BANK_BALANCE][BankAssets::PUBLIC_SECTOR_LOCAL]
                    + $finalArray[BankAssets::BANK_BALANCE][BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD] - $resultArray[BankAssets::ELIGIBLE_ASSETS]) - $resultArray[BankAssets::ASSETS_WITHOUT_DATA];
                $resultArray[BankAssets::NOT_ELIGIBLE_ASSETS_PERCENT] = calculatePercentage($resultArray[BankAssets::NOT_ELIGIBLE_ASSETS], $finalArray[BankAssets::BANK_BALANCE][BankAssets::BANK_ASSETS], 2);

                $finalArray[$volumeCapexOption['keyName']][BankAssets::BTAR] = $resultArray;
            }
            $dataFinal[$stockFlowOption['keyName']] = $finalArray;
            $finalArray = [];
        }

        // recursive function to loop all values ( if is array , back to the funcio ) and use the roundeValues()
        foreach ($dataFinal as $key => $value) {
            $dataFinal[$key] = $this->roundeValues($value);
        }

        $dataFinal['nace'] = [];
        $ativoBancarioNewArray = $naceCodes;
        foreach ($ativoBancarioNewArray as $item) {
            if (!in_array($item[BankAssets::NACE_CODE], $dataFinal['nace'])) {
                $dataFinal['nace'][] = $item[BankAssets::NACE_CODE];
            }
        }

        $this->saveFileLocally('database/data/gar-btar-sibs/explanatory-notes', 'dashboard_banco', $dataFinal);
    }

    public function getValueFromRow($value)
    {
        if (isset($value) && !empty($value)) {
            return $value;
        }
        return 0;
    }

    public function getAbsoluteValueForRows($rows, $totalValueKeyName, $prefix, $ccmFieldName, $ccaFieldName)
    {
        $total = 0;
        foreach ($rows as $row) {
            $total += $this->getAbsoluteValueForRow($row, $totalValueKeyName, [$prefix . $ccmFieldName, $prefix . $ccaFieldName]);
        }
        return $total;
    }

    public function getAbsoluteValueForRow($row, $totalValueKeyName, $fieldsToSum = [])
    {
        if (!empty($fieldsToSum)) {
            $resultSum = 0;
            foreach ($fieldsToSum as $value) {
                $resultSum += $row[$value];
            }
        } else {
            $resultSum = 1;
        }
        return roundValues($resultSum * $row[$totalValueKeyName], 2);
    }

    public function roundeValues($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->roundeValues($value);
            } else {
                $array[$key] = roundValues($value, 2);
            }
        }
        return $array;
    }
}
