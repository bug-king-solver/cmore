<?php

namespace App\Models\Traits;

use App\Http\Livewire\Compliance\DocumentAnalysis\Modals\Report;
use App\Models\Tenant\ReportingPeriod;
use Schema;

trait ReportingPeriodsTrait
{
    /**
     * Boot function from Laravel.
     */
    protected static function bootReportingPeriodsTrait()
    {
        static::creating(function ($model) {

            // get the columns of the model
            $table = $model->getTable();
            $columns = Schema::getColumnListing($table);

            if (in_array('reporting_period_id', $columns)) {
                if (!$model->reporting_period_id) {
                    throw new \Exception(__('Reporting period is required'));
                }

                $reportingPeriod = ReportingPeriod::find($model->reporting_period_id);

                // check if the model has the columns
                if (in_array('from', $columns)) {
                    $model->from = $reportingPeriod->startDate;
                }

                if (in_array('to', $columns)) {
                    $model->to = $reportingPeriod->endDate;
                }

                if (in_array('reported_at', $columns)) {
                    $model->reported_at = $model->questionnaire
                        ? $model->questionnaire->from
                        : $reportingPeriod->startDate;
                    $model->reported_at .= ' 00:00:00';
                }
            }
        });
    }
}
