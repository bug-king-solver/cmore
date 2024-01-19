<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\BusinessActivities;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BusinessActivitiesDescriptionSeeder extends Seeder
{
    /** @var array<string,mixed> */
    public array $businessActivities = [];

    /** @var array<string,mixed> */
    public array $businessActivitiesParent = [];

    /**
     * Create a new TaxonomySeeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->businessActivities = BusinessActivities::select('id', 'code')
            ->get()
            ->toArray();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = [
            'A' => 'sector',
            'C' => 'activity',
            'D' => 'activity_code',
            'E' => 'activity_description',
        ];

        $count = 0;
        $reader = IOFactory::createReader('Xlsx');
        $file = base_path() . '/database/data/taxonomy/taxonomy.xlsx';
        $spreadsheet = $reader->load($file);
        foreach ($spreadsheet->getWorksheetIterator() as $i => $worksheet) {
            $all = [];

            // Ignore "Apendices" e "ÍNDICE"
            if ($i < 2) {
                continue;
            }

            foreach ($worksheet->getRowIterator() as $rowNumber => $row) {
                $data = [];

                if ($rowNumber === 1) {
                    continue;
                }

                foreach ($row->getCellIterator() as $j => $cell) {
                    if (!isset($map[$j])) { // Remove the last validation after add data
                        continue;
                    }

                    $cellvalue = $cell->getCalculatedValue();
                    $data[$map[$j]] = $cellvalue;
                    if ($i == 0) {
                        $data[$map[$j]] = sprintf('%s', $cellvalue);
                    }
                }

                // check if all keys are empty
                if (array_filter($data) === []) {
                    continue;
                }

                $all[] = $data;
                ++$count;
            }

            foreach ($all as $activity) {
                if ($activity['activity_code'] == null) {
                    continue;
                }

                $this->updateActivitiesDescription($activity);
            }
        }
    }

    /**
     * Parses the given data to group answers, links and actions by question.
     *
     * @param array<string,mixed> $activity The data to parse.
     * @return void The parsed data.
     */
    public function updateActivitiesDescription(array $activity): void
    {
        $code = $activity['activity_code'];
        $code = sprintf('%s', $code);

        $description = $activity['activity_description'] ?? null;
        BusinessActivities::whereCode($code)
            ->update(['description' => $description]);
    }
}
