<?php

namespace App\Nova\Central;

use App\Nova\CustomResource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\FormData;

class BenchmarkData extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\BenchmarkData::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'benchmark_report.benchmark_period.name',
        'benchmark_report.benchmark_company.name',
        'indicator.name',
        'value',
        'value_usd',
        'value_eur',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $financialIndicators = \App\Models\Indicator::where(
            [
                ['is_financial', '=', '1'],
                ['has_benchmarking', '=', '1'],
            ]
        )->pluck('id')->toArray();

        return [
            ID::make()->sortable(),

            Text::make('Note')
                ->onlyOnForms()
                ->rules('max:255'),

            Boolean::make('Enabled')->withMeta(['value' => $this->enabled ?? true]),

            BelongsTo::make(__('Report'), 'benchmark_report', BenchmarkReport::class),

            //BelongsTo::make(__('Company'), 'benchmark_company', BenchmarkCompany::class),

            BelongsTo::make(__('Indicator'), 'indicator', Indicator::class),

            Text::make('Value'),

            Text::make('Unit', function () {
                return $this->indicator->unit_default ?? '';
            })->onlyOnIndex(),

            Text::make('Value USD')
            ->hide()
            ->readOnly()
            ->dependsOn('indicator', function (Text $field, NovaRequest $request, FormData $formData) use ($financialIndicators) {
                if (in_array($formData->indicator, $financialIndicators)) {
                    $field->show();
                }
            })->displayUsing(function($amount){
                return formatToCurrency($amount, false, 'USD');
            }),

            Text::make('Value EUR')
            ->hide()
            ->readOnly()
            ->dependsOn('indicator', function (Text $field, NovaRequest $request, FormData $formData) use ($financialIndicators) {
                if (in_array($formData->indicator, $financialIndicators)) {
                    $field->show();
                }
            })->displayUsing(function($amount){
                return formatToCurrency($amount, false, 'EUR');
            }),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new Filters\Enabled(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            new Actions\Enabled(),
        ];
    }

    public static function availableForNavigation(Request $request)
    {
        return auth()->user()->is_reporter != '1';
    }
}
