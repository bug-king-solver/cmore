<?php

namespace App\Nova\Metrics;

use App\Models\Tenant\Questionnaire as AppQuestionnaire;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Stancl\Tenancy\Database\Models\Tenant;

class SubmittedQuestionnaires extends Trend
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
                foreach ($tenants as $tenant) {
                    $tenantDbName = $tenant['tenancy_db_name'];
                    if ($tenantDbName == null) {
                        continue;
                    }
                    config(['database.connections.central.database' => $tenantDbName]);
                    DB::reconnect('central');
                    return $this->countQuestionnaires($request, $data);
                }
            } else {
                $tenant = Tenant::find($tenantId);
                $tenantDbName = $tenant['tenancy_db_name'];
                config(['database.connections.central.database' => $tenantDbName]);
                DB::reconnect('central');
                return $this->countQuestionnaires($request, $data);
            }
        }

        return $this->countQuestionnaires($request, $data);
    }

    /**
     * Count the number of submitted questionnaires.
     * @param NovaRequest $request
     * @param array $data
     */
    public function countQuestionnaires(NovaRequest $request, array $data): array
    {
        $submittedQuestionnaireCount = AppQuestionnaire::whereNotNull('submitted_at')->count();
        $data['value'] += $submittedQuestionnaireCount;
        $trend = $this->countByMonths($request, AppQuestionnaire::class, 'submitted_at');
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
        return 'submitted-questionnaires';
    }
}
