<?php

namespace App\Nova\Metrics;

use App\Models\Tenant;
use App\Models\Tenant\Company;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class TotalCompanies extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $tenants = Tenant::all();

        $data = [
            'value' => 0,
            'trend' => [],
        ];

        $meta = $this->meta();
        $tenantId = $meta['tenantId'] ?? null;

        if (!tenancy()->initialized) {
            if ($tenantId == null) {
                $result = [];
                foreach ($tenants as $tenant) {
                    $tenantDbName = $tenant['tenancy_db_name'];
                    if ($tenantDbName == null) {
                        continue;
                    }
                    config(['database.connections.central.database' => $tenantDbName]);
                    DB::reconnect('central');
                    $arr = $this->countResource($request, $data);

                    if (empty($result)) {
                        $result = $arr;
                    } else {
                        $result["value"] += $arr["value"] ?? 0;
                        foreach ($arr['trend'] as $j => $trend) {
                            $result["trend"][$j] += $trend ?? 0;
                        }
                    }
                }
                return $result;
            } else {
                $tenant = Tenant::find($tenantId);
                $tenantDbName = $tenant['tenancy_db_name'];
                config(['database.connections.central.database' => $tenantDbName]);
                DB::reconnect('central');
                return $this->countResource($request, $data);
            }
        }

        return $this->countResource($request, $data);
    }


    /**
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  array  $data
     * @return array
     */
    public function countResource(NovaRequest $request, array $data): array
    {
        $trend = $this->countByMonths($request, Company::class);
        $companyCount = Company::count();
        $data['value'] += $companyCount;
        foreach ($trend->trend as $month => $value) {
            if (!isset($data['trend'][$month])) {
                $data['trend'][$month] = 0;
            }
            $data['trend'][$month] += $value;
        }

        return $data;
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => __('30 Days'),
            60 => __('60 Days'),
            365 => __('365 Days'),
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'total-companies';
    }
}
