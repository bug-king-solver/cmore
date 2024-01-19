<?php

namespace Database\Seeders;

use App\Models\Traits\Filesystem\FileManagerTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class TaxonomyQuestionsSeeder
 */
class EmissionsGEESeeder extends Seeder
{
    use FileManagerTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        exec('rm -rf database/data/emissions/');


        $file = base_path() . '/database/data/EmissõesGEE.xlsx';
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file);

        $this->extractDataFromExcel(
            $spreadsheet,
            $map = [
                'B' => 'label',
                'C' => 'unit_qty',
                'D' => 'format',
                'E' => 'unit',
            ],
            $worksheetName = 'CONVERSIONS',
            $startRow = 2,
            $callback = "parseConversionsDataAndSave"
        );

        $this->extractDataFromExcel(
            $spreadsheet,
            $map = [
                'B' => 'label',
                'C' => 'unit',
                'D' => 'emission_factor',
            ],
            $worksheetName = 'EF_TO IMPORT',
            $startRow = 1,
            $callback = "parseEmissionFactorAndSave"
        );
    }

    /**
     * Extract emission convertor from excel and save it locally in json
     * @param $spreadsheet
     * @return void
     */
    public function extractDataFromExcel($spreadsheet, $mapColumns, $worksheetName, $startRow, $callback)
    {
        foreach ($spreadsheet->getWorksheetIterator() as $i => $worksheet) {
            $worksheetTitle = $worksheet->getTitle();

            if ($worksheetTitle === $worksheetName) {
                $all = [];

                foreach ($worksheet->getRowIterator() as $rowNumber => $row) {
                    if ($rowNumber <= $startRow) {
                        continue;
                    }

                    foreach ($row->getCellIterator() as $i => $cell) {
                        if (!isset($mapColumns[$i])) { // Remove the last validation after add data
                            continue;
                        }

                        $cellvalue = $cell->getCalculatedValue() ?? $all[$rowNumber - 1][$mapColumns[$i]] ?? null;
                        $cellvalue = strtolower($cellvalue);
                        $all[$rowNumber][$mapColumns[$i]] = $cellvalue;
                    }
                }
                $this->$callback($all);
                break;
            }
        }
    }

    /**
     * prepare data to be imported
     * @param $loadedActivities
     */
    public function parseConversionsDataAndSave($all)
    {
        // group all by conversion key
        $emissions = collect($all)->groupBy('label')->toArray();

        // slug all emissions key
        foreach ($emissions as $key => $emission) {
            foreach ($emission as $i => $value) {
                $value['format'] = $value['format'] == 'litres'
                    ? 'l'
                    : $value['format'];

                $emission[$value['format']]['label'] = ucfirst($value['format']);
                $emission[$value['format']]['format'] = '1,0.00 ' . $value['format'];
                $emission[$value['format']]['unit'] = 1 / $value['unit'];
                unset($emission[$i]);
            }

            $emission = array_merge([
                'kwh' => [
                    'id'     => $key,
                    'label'  => 'Kwh',
                    'format' => '1 kwh',
                    'unit'   => 1,
                ]
            ], $emission);

            $key2  = $emissions[$key][0]['unit_qty'];
            $emissions[Str::slug($key2)] = $emission;
            unset($emissions[$key]);
        }
        $this->saveFileLocally('database/data/emissions/', 'conversions', $emissions);
    }

    /**
     * prepare data to be imported
     * @param $loadedActivities
     */
    public function parseEmissionFactorAndSave($all)
    {
        $fileName = 'emission_factor';
        $data = collect($all)->groupBy('label')->toArray();
        foreach ($data as $key => $emission) {
            $data["fuel-" . Str::slug($key) ] = $emission;
            if ($key != "fuel-" . Str::slug($key)) {
                unset($data[$key]);
            }
        }

        $this->saveFileLocally('database/data/emissions/', $fileName, $data);
    }
}
