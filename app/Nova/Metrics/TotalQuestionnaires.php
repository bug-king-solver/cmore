<?php

namespace App\Nova\Metrics;

use App\Models\Tenant\Questionnaire as AppQuestionnaire;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Stancl\Tenancy\Database\Models\Tenant;

class TotalQuestionnaires extends Trend
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
     * Count the number of submitted questionnaires.
     * @param NovaRequest $request
     * @param array $data
     */
    public function countResource(NovaRequest $request, array $data): array
    {
        $trend = $this->countByMonths($request, AppQuestionnaire::class);
        $questionnaireCount = AppQuestionnaire::count();
        $data['value'] += $questionnaireCount;
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
            90 => __('90 Days'),
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
        return 'total-questionnaires';
    }
}
