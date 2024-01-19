<?php

namespace Database\Seeders;

use App\Models\Benchmarking;
use DateTimeImmutable;
use Illuminate\Database\Seeder;
use MongoDB\BSON\UTCDateTime;

class BenchmarkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Benchmarking::truncate();

        $csvFile = fopen(base_path('database/data/benchmarking.csv'), 'r');

        $i = -1;
        $indicatorsIds = [];
        while (($data = fgetcsv($csvFile, null, ',')) !== false) {
            $i++;
            if (! $i) {
                foreach ($data as $key => $id) {
                    $indicatorsIds[$key] = $id ?: null;
                }

                continue;
            }

            $indicators = [];
            foreach ($data as $key => $value) {
                $id = (int) $indicatorsIds[$key];

                if (! $id) {
                    continue;
                }

                $value = (float) $value;
                $extra = [];

                $indicators[] = [
                    'indicator' => $id,
                    'business_sector' => (int) $data[1],
                    'headquarters' => $data[2],
                    'revenue' => (float) $data[6],
                    'employees' => (float) $data[5],
                    'reported_at' => new UTCDateTime((new DateTimeImmutable($data[3] . '-01-01 00:00'))->format('Uv')),
                    'value' => (float) $value,
                    'extra' => $extra,
                ];
            }

            Benchmarking::insert($indicators);
        }

        fclose($csvFile);
    }
}
