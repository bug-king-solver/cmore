<?php

namespace App\Nova\Central;

use Alexwenzel\DependencyContainer\DependencyContainer;
use App\Nova\CustomResource;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Select;
use App\Models\Enums\Territory\Currency;

class BenchmarkReport extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\BenchmarkReport::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'currency',
        'benchmark_period.name',
        'benchmark_company.name',
        'reporter.name',
        'validator.name',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (auth()->user()->is_reporter == '1') {
            return $query->where('reporter_id', auth()->user()->id);
        } else {
            return $query;
        }
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Note')
                ->onlyOnForms()
                ->rules('max:255'),

            Boolean::make('Enabled')->withMeta(['value' => $this->enabled ?? true]),

            Boolean::make('Validated')->onlyOnIndex(),

            BelongsTo::make(__('Period'), 'benchmark_period', BenchmarkPeriod::class),

            BelongsTo::make(__('Company'), 'benchmark_company', BenchmarkCompany::class),

            BelongsTo::make(__('Reporter'), 'reporter', Admin::class),

            BelongsTo::make(__('Validator'), 'validator', Admin::class),

            Select::make('Currency')->searchable()->options(Currency::casesArray('value'))->rules('required'),

            Boolean::make('Ready')->hideFromIndex(),

            DependencyContainer::make([
                DateTime::make('Ready At', 'ready_at'),
            ])->dependsOn('ready', 1),

            Text::make('Time to ready', function () {
                return $this->time_to_ready ? CarbonInterval::minutes($this->time_to_ready)->cascade()->forHumans() : '';
            })->onlyOnIndex(),

            HasMany::make('Data', 'benchmark_data', BenchmarkData::class),
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
            new Actions\Validated(),
            new Actions\Reporter(),
            new Actions\Validator(),
            new Actions\Ready(),
        ];
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->benchmark_period->name . ' ' . $this->benchmark_company->name;
    }
}
