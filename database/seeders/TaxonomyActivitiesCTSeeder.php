<?php

namespace Database\Seeders;

use App\Models\Traits\Filesystem\FileManagerTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class TaxonomyQuestionsSeeder
 */
class TaxonomyActivitiesCTSeeder extends Seeder
{
    use FileManagerTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        exec('rm -rf database/data/taxonomy/c_t/*');

        $map = [
            'A' => 'nace',
            'C' => 'activity_Code',
            'D' => 'activity',
            'E' => 'contribution',
        ];

        $file = base_path() . '/database/data/taxonomy/Taxonomy_C_T.xlsx';
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file);

        $activities = [];
        foreach ($spreadsheet->getWorksheetIterator() as $worksheetNUmber => $worksheet) {
            $workSheetTitle = $worksheet->getTitle();
            $workSheetTitle = Str::lower($workSheetTitle);
            $workSheetTitle = Str::slug($workSheetTitle, '_');

            foreach ($worksheet->getRowIterator() as $rowNumber => $row) {
                $data = [];

                if ($rowNumber === 1) {
                    continue;
                }

                foreach ($row->getCellIterator() as $i => $cell) {
                    if (!isset($map[$i])) { // Remove the last validation after add data
                        continue;
                    }

                    $cellvalue = $cell->getCalculatedValue();
                    $data[$map[$i]] = $cellvalue;
                    if ($i === 'C') {
                        $data[$map[$i]] = "$cellvalue";
                    } elseif ($i === 'E') {
                        if ($cellvalue == 'Enabling') {
                            $data[$map[$i]] = "C";
                        } elseif ($cellvalue == 'Transitional') {
                            $data[$map[$i]] = "T";
                        } else {
                            $data[$map[$i]] = "";
                        }
                    }
                }

                $activities[$workSheetTitle][] = $data;
            }
        }

        $this->saveFileLocally(base_path() . '/database/data/taxonomy/c_t/', 'c_t_data', $activities);
    }
}
