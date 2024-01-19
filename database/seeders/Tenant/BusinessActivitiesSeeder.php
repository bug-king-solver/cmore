<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\BusinessActivities;
use Database\Seeders\Tenant\BusinessActivitiesDescriptionSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BusinessActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $folder = base_path() . '/database/data/taxonomy/';
        $pattern = $folder . '*.xlsx';
        $files = glob($pattern);

        foreach ($files as $file) {
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file);
            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $sheetName = Str::slug($worksheet->getTitle());
                // check if name  ( lower , trim , without accents ) is equal to string
                if (strtolower(trim($sheetName)) == 'indice') {
                    foreach ($worksheet->getRowIterator() as $rowNumber => $row) {
                        foreach ($row->getCellIterator() as $i => $cell) {
                            if (in_array($i, ['A', 'B']) || $rowNumber <= 1) {
                                continue;
                            }
                            $dados[$rowNumber][] = $cell->getCalculatedValue();
                        }
                    }
                }
            }
        }

        $this->createSectors($dados);
    }

    /**
     * Create sectors from a given row.
     *
     * @param mixed $row The row to create sectors from.
     *
     * @return void
     */
    public function createSectors($dados)
    {
        foreach ($dados as $data) {
            if (!empty($data[0]) && !empty($data[1])) {
                $code = "$data[0]";

                $businessActivitie = BusinessActivities::updateOrCreate(
                    [
                        'code' => $code,
                    ],
                    [
                        'parent_id' => null,
                        'name' => [
                            'pt-PT' => $data[0] . ' - ' . $data[1],
                            'pt-BR' => $data[0] . ' - ' . $data[1],
                            'en' => $data[0] . ' - ' . $data[1],
                            'es' => $data[0] . ' - ' . $data[1],
                            'fr' => $data[0] . ' - ' . $data[1],
                        ],
                        'code' => $code,
                    ]
                );
            }

            if (!empty($data[2]) && !empty($data[3])) {
                $code = "$data[2]";
                BusinessActivities::updateOrCreate(
                    [
                        'code' => $code,
                        'parent_id' => $businessActivitie->id ?? null,
                    ],
                    [
                        'name' => [
                            'pt-PT' => $data[2] . ' - ' . $data[3],
                            'pt-BR' => $data[2] . ' - ' . $data[3],
                            'en' => $data[2] . ' - ' . $data[3],
                            'es' => $data[2] . ' - ' . $data[3],
                            'fr' => $data[2] . ' - ' . $data[3],
                        ],
                        'code' => $code,
                        'parent_id' => $businessActivitie->id ?? null,
                    ]
                );
            }
        }
    }
}
