<?php

namespace Database\Seeders;

use App\Models\BenchmarkCompany;
use App\Models\BenchmarkData;
use App\Models\BenchmarkPeriod;
use App\Models\BenchmarkReport;
use App\Models\BusinessSector;
use App\Models\Indicator;
use App\Services\Currency\Exchanger;
use DateTimeImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class BenchmarkReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Indicator::truncate();
        BenchmarkReport::truncate();
        BenchmarkCompany::truncate();
        BenchmarkData::truncate();
        BenchmarkPeriod::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $jobs[] = function () {
            $csvFileIndicators = fopen(base_path('database/data/questionnaire-v1/indicators.csv'), 'r');
            try {
                while (($indData = fgetcsv($csvFileIndicators, null, ',')) !== false) {
                    $id = trim($indData[0]);
                    if (!$id || !is_numeric($id)) {
                        continue;
                    }
                    $indRow[] = [
                        'id' => $id,
                        'enabled' => 0,
                        'unit_qty' => $indData[5] ?: null,
                        'unit_default' => $indData[6] ?: null,
                        'calc' => $indData[7] ?: null,
                        'has_benchmarking' => $indData[11] ? true : false,
                        'name' => $indData[8],
                        'description' => $indData[9],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }

                $indRow = collect($indRow)->unique('id')->toArray();
                fclose($csvFileIndicators);
            } catch (Exception | QueryException $ex) {
                fclose($csvFileIndicators);
                throw $ex;
            }

            return Indicator::insert($indRow);
        };

        $jobs[] = function () {
            $indicatorFinancials = Indicator::pluck('is_financial', 'id')->toArray();
            $exchanger = new Exchanger();
            $datas = [];
            $indicatorsIds = [];
            $i = -1;

            try {
                $csvFile = fopen(base_path('database/data/benchmark-report.csv'), 'r');
                while (($row = fgetcsv($csvFile, null, ',')) !== false) {
                    $i++;
                    if (!$i) {
                        foreach ($row as $key => $id) {
                            $indicatorsIds[$key] = $id ?: null;
                        }
                        continue;
                    }

                    $datas[] = $row;
                }
                fclose($csvFile);

                $datas = array_chunk($datas, 1000);

                foreach ($datas as $key => $reports) {
                    $jobs[] = function () use ($reports, $exchanger, $indicatorFinancials, $indicatorsIds) {

                        foreach ($reports as $data) {
                            $businessSectorArr = [
                                'name' => trim($data[6]),
                            ];

                            $businessSector = BusinessSector::where('name->en', trim($data[6]))->first();
                            if (!$businessSector) {
                                $businessSector = BusinessSector::create($businessSectorArr);
                            }

                            $company = BenchmarkCompany::firstOrCreate(
                                ['name' => trim($data[0])],
                                [
                                    'name' => trim($data[0]),
                                    'headquarters' => trim($data[1]),
                                    'ticker' => trim($data[4]),
                                    'business_sector_id' => $businessSector->id,
                                    'enabled' => 1,
                                ]
                            );

                            $pediod = BenchmarkPeriod::firstOrCreate(
                                ['name->en' => trim($data[2])],
                                [
                                    'name' => trim($data[2]),
                                    'from' => $data[2] . '-01-01',
                                    'to' => $data[2] . '-12-31',
                                    'enabled' => 1,
                                ]
                            );

                            $report = BenchmarkReport::firstOrCreate(
                                [
                                    'benchmark_period_id' => $pediod->id,
                                    'benchmark_company_id' => $company->id,
                                ],
                                [
                                    'currency' => trim($data[3]),
                                    'enabled' => 1,
                                    'validated' => 1,
                                    'benchmark_period_id' => $pediod->id,
                                    'benchmark_company_id' => $company->id,
                                ]
                            );

                            $reportData = [];
                            foreach ($data as $key => $value) {
                                $id = (int) $indicatorsIds[$key];

                                if (!$id) {
                                    continue;
                                }

                                $indicatorArr = $indicatorFinancials[$id];

                                $valueUsd = null;
                                $valueEur = null;
                                $value = str_replace(' ', '', str_replace("\u{A0}", '', $value));
                                $value = is_numeric($value)
                                    ? round($value, 2)
                                    : $value;


                                if (isset($indicatorArr['is_financial']) && $indicatorArr['is_financial']) {
                                    $value = floatval($value);
                                    $valueUsd = $exchanger->convert($report->currency, 'USD', $value, $pediod->from);
                                    $valueEur = $exchanger->convert($report->currency, 'EUR', $value, $pediod->from);
                                }

                                $reportData[] = [
                                    'benchmark_report_id' => $report->id,
                                    'indicator_id' => $id,
                                    'value' => trim($value),
                                    'value_usd' => $valueUsd,
                                    'value_eur' => $valueEur,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'enabled' => 1,
                                ];

                                if ($id == '64') {
                                    $report->employees = trim($value);
                                    $report->save();
                                }
                                if ($id == '168') {
                                    $report->revenue = trim($value);
                                    $report->save();
                                }
                            }

                            BenchmarkData::insert($reportData);
                        }
                        return true;
                    };
                }
            } catch (Exception | QueryException $ex) {
                fclose($csvFile);
                throw $ex;
            }

            Bus::chain($jobs)->dispatch();
        };

        Bus::chain($jobs)->dispatch();
    }
}
