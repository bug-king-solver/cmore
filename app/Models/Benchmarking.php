<?php

namespace App\Models;

use App\Models\BenchmarkData;
use App\Models\Tenant\Company;
use App\Models\Tenant\Data;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Benchmarking extends Model
{
    use HasFactory;

    public static $revenueIndicatorId = 168;

    public static function getCompaniesData($chart, $companies, $where)
    {
        extract($where);

        return Company::list()->whereIn('id', $companies)->get()->map(
            function ($company) use ($chart, $indicator, $years) {
                switch ($chart) {
                    case 'yearMain':
                        $data = Data::mainBenchmarkingYearly($indicator, $company->id, $years);
                        break;
                    case 'distribution':
                        $data = [Data::distributionBenchmarking($indicator, $company->id, $years[0])];
                        break;
                    default:
                        $data = [];
                        break;
                }

                return ! $data ? [] : [
                    'name' => $company->name,
                    'color' => $company->color,
                    'data' => $data,
                ];
            }
        )->filter();
    }

    public static function dataForMain($companies, $where, $groupBy)
    {
        $companies = self::getCompaniesData("{$groupBy}Main", $companies, $where);
        $values = ['min', 'max', 'avg'];
        $data = [];
        if ($where['compareWith'] == 'global') {
            $data = self::aggregattedDataForMain($values, $where);
        }

        $medianAndDaviationData = Data::getMedianAndDaviationData($where);
        $data['median'] = $medianAndDaviationData['median'];
        $data['daviation'] = $medianAndDaviationData['daviation'];

        return array_merge($data, ['companies' => $companies]);
    }

    public static function dataForDistribution($companies, $where)
    {
        // Only data from the highest year
        $years = max($where['years']);
        $companies = self::getCompaniesData('distribution', $companies, $where);
        if (! $companies) {
            return null;
        }
        $benchmarkReports = BenchmarkReport::whereBetween('revenue', [$where['revenue']['min'], $where['revenue']['max']])->whereBetween('employees', [$where['employee']['min'], $where['employee']['max']])->with(['benchmark_period', 'benchmark_company'])->whereHas('benchmark_period', function( $query ) use ( $years ){
            $query->where('from', 'like', "{$years}%");
        });
        if (! empty($where['countries'])) {
            $benchmarkReports = $benchmarkReports->whereHas('benchmark_company', function ($query) use ($where) {
                $query->whereIn('headquarters', $where['countries']);
            });
        }
        if (! empty($where['businessSectors'])) {
            $benchmarkReports = $benchmarkReports->whereHas('benchmark_company', function ($query) use ($where) {
                $query->whereIn('business_sector_id', $where['businessSectors']);
            });
        }
        $benchmarkReports = $benchmarkReports->pluck('id');

        $benchmarkData = BenchmarkData::whereIn('benchmark_report_id', $benchmarkReports)
            ->whereIn('indicator_id', [self::$revenueIndicatorId, $where['indicator']])
            ->get();

        $data = [];
        foreach ($benchmarkData as $row) {
            $indicatorValue = str_replace("\u{A0}", '', $row->value);
            $indicatorValue = trim($indicatorValue) != '' ? $row->value : 0;

            $indicatorValue = (float) $indicatorValue;

            if (self::$revenueIndicatorId === $row->indicator_id) {
                $data[$row->benchmark_report_id]['x'] = $indicatorValue;
                if (self::$revenueIndicatorId === $where['indicator']) {
                    $data[$row->benchmark_report_id]['y'] = $indicatorValue;
                }
            } else {
                $data[$row->benchmark_report_id]['y'] = $indicatorValue;
            }
        }
        $data = collect($data);
        $yMin = $data->pluck('y')->min();
        $yMax = $data->pluck('y')->max();

        // Filter the data
        $data = $data->filter(function ($row) use ($where) {
            $returnMin = is_null($where['y']['min']) || $row['y'] >= $where['y']['min'] ? true : false;
            $returnMax = is_null($where['y']['max']) || $row['y'] <= $where['y']['max'] ? true : false;
            return $returnMin && $returnMax;
        })->toArray();
        return ['data' => array_values($data), 'y' => ['min' => $yMin, 'max' => $yMax], 'companies' => $companies];
    }

    /**
     * Get data for main chart (histogram)
     */
    public static function aggregattedDataForMain($values, $where)
    {
        $reportData = DB::connection('central')->table('benchmark_companies')
            ->leftJoin('benchmark_reports', 'benchmark_companies.id', '=', 'benchmark_reports.benchmark_company_id')
            ->leftJoin('benchmark_periods', 'benchmark_periods.id', '=', 'benchmark_reports.benchmark_period_id')
            ->leftJoin('benchmark_data', 'benchmark_data.benchmark_report_id', '=', 'benchmark_reports.id')

            ->select(DB::raw('MIN(`value`) as `minValue`'), DB::raw('MAX(`value`) as `maxValue`'), DB::raw('AVG(`value`) as `avgValue`'), DB::raw('YEAR(`from`) as date'))
            ->where('benchmark_data.indicator_id', $where['indicator'])
            ->where(
                function ($query) use ($where) {
                    foreach ($where['years'] as $year) {
                        $query->orWhere('benchmark_periods.from', 'like', "{$year}%");
                    }
                }
            )
            ->when(! empty($where['countries']), function ($query) use ($where) {
                return $query->where('benchmark_companies.headquarters', '=', $where['countries']);
            })
            ->when(! empty($where['businessSectors']), function ($query) use ($where) {
                return $query->where('benchmark_companies.business_sector_id', '=', $where['businessSectors']);
            })
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->toArray();

        $returnArr = [];
        if (! empty($reportData)) {
            foreach ($reportData as $data) {
                foreach ($values as $aggValue) {
                    $indicatorVal = $data->{$aggValue . 'Value'};
                    $returnArr[$aggValue][] = $indicatorVal == '' ? 0 : $indicatorVal;
                }
            }
        } else {
            foreach ($values as $aggValue) {
                foreach ($where['years'] as $year) {
                    $returnArr[$aggValue][] = 0;
                }
            }
        }

        return $returnArr;
    }
}
