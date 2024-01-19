<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

/**
 * Class FrameworksJsonSeeder
 */
class FrameworksJsonSeeder extends Seeder
{
    public string $fileStorePath;
    public $excelPath;

    /**
     * FrameworksJsonSeeder constructor.
     */
    public function __construct()
    {
        $this->fileStorePath = base_path() . '/database/data/frameworks/data';

        $this->excelPath = [
            [
                'file' => base_path() . '/database/data/frameworks/Mapeamento_Frameworks_20230522.xlsx',
                'sheet' => 'gri (pt-pt)',
                'output' => 'gri'
            ],
            [
                'file' => base_path() . '/database/data/frameworks/Mapeamento_Indicadores_CSRD.xlsx',
                'sheet' => 'import',
                'output' => 'csrd'
            ]
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        exec("rm -rf {$this->fileStorePath}/*");

        $default = [
            "category" => "",
            "subcategory" => "",
            "reference" => "",
            "location" => "",
            "comment" => "",
            "external_assurance" => false,
            'title' => '',
            'subtitle' => '',
            'description' => '',
            'questions' => [],
            'indicators' => [],
        ];

        $map = [
            'A' => 'category',
            'B' => 'subcategory',
            'C' => 'reference',
            'D' => 'title',
            'E' => 'subtitle',
            'F' => 'description',
            'G' => 'questions',
            'H' => 'indicators',
            'I' => 'question_id',
        ];

        $files = $this->excelPath;

        foreach ($files as $file) {
            $indicators = [];
            $toAdd = false;
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file['file']);
            $withAll = [];

            foreach ($spreadsheet->getWorksheetIterator() as $i => $worksheet) {
                $all = [];
                $title = trim(strtolower($worksheet->getTitle()));
                $data = [];
                // group by reference

                if ($title !== $file['sheet']) {
                    continue;
                }

                foreach ($worksheet->getRowIterator() as $rowNumber => $row) {
                    if ($rowNumber === 1) {
                        continue;
                    }

                    foreach ($row->getCellIterator() as $i => $cell) {
                        if (!isset($map[$i])) { // Remove the last validation after add data
                            continue;
                        }

                        $cellvalue = $cell->getCalculatedValue() ?? null;

                        if (empty($cellvalue)) {
                            $cellvalue = $data[$rowNumber - 1][$map[$i]] ?? null;
                        }

                        if ($i === "H") {
                            if (is_string($cellvalue)) {
                                $cellvalue = explode(';', $cellvalue);
                            }

                            $cellH = $worksheet->getCellByColumnAndRow(10, $rowNumber)->getCalculatedValue() ?? null;
                            $cellvalue = [
                                'questions' => $cellvalue,
                                'action' => $cellH
                            ];
                        } elseif ($i === 'G') {
                            $data[$rowNumber][$map[$i]] = $cellvalue;
                        }

                        $data[$rowNumber][$map[$i]] = $cellvalue;
                    }
                }
                
                $all = [];
                foreach ($data as $i => $row) {
                    $reference = trim($row['reference']);
                    $category = trim($row['category']);
                    $subcategory = trim($row['subcategory']);
                    $arr = explode("-", $reference);

                    if (count($arr) >= 2) {
                        $arr = explode(" ", $reference);
                        $code = $arr[0] ?? '';
                    } else {
                        $code = str_replace(' ', '-', $reference);
                    }

                    $all[$category][$subcategory][$reference] = array_merge($all[$category][$subcategory][$reference] ?? [], $row);
                    $all[$category][$subcategory][$reference]['reference'] = $reference;
                    $all[$category][$subcategory][$reference]['source_code'] = $code;

                    if (empty($all[$category][$subcategory][$reference]['source_indicator'])) {
                        $all[$category][$subcategory][$reference]['source_indicator'] = [];
                    }

                    $questions = $row['indicators']['questions'];

                    $all[$category][$subcategory][$reference]['source_indicator'][$i] = [
                        'question' => $row['questions'],
                        'indicator' => $questions,
                        'action' => trim($row['indicators']['action'] ?? ''),
                    ];

                    unset($all[$category][$subcategory][$reference]['indicators']);
                    $all[$category][$subcategory][$reference]['source_indicator'] = $all[$category][$subcategory][$reference]['source_indicator'];
                }
                $this->saveFile("{$this->fileStorePath}/", $all, $file['output']);
            }
        }
    }

    /**
     * Saves the given data to a JSON file in the specified directory.
     * @param string $dir The directory to save the file in.
     * @param array $data The data to save to the file.
     * @param string $name The name of the file to save.
     * @return void
     */
    public function saveFile($dir, $data, $name)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        // echo "Saving {$name} to {$dir}\n";
        file_put_contents("{$dir}{$name}.json", json_encode($data, true));
    }
}
