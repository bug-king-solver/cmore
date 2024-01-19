<?php

namespace App\Nova\Metrics;

use App\Models\Admin;
use App\Models\BenchmarkReport;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class ReadyReportsPerDayPerReporter extends Trend
{
    public Admin | int $admin;

    public function __construct($admin)
    {
        parent::__construct();
        $this->admin = Admin::find($admin);
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, BenchmarkReport::ready()->where('reporter_id', $this->admin->id), 'ready_at');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            7 => __('7 Days'),
            14 => __('14 Days'),
            30 => __('30 Days'),
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
        return 'ready-reports-per-day-per-reporter' . $this->admin->id;
    }

    public function name()
    {
        return 'Ready Reports Per Day - ' . $this->admin->name;
    }
}
