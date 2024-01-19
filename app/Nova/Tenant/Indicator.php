<?php

namespace App\Nova\Tenant;

use App\Models\Tenant\Scopes\EnabledScope;
use App\Nova\CustomResource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\NovaTranslatable\Translatable;

class Indicator extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Tenant\Indicator::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Modify the index query used to retrieve models for the resource.
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->withoutGlobalScope(EnabledScope::class);
        return parent::indexQuery($request, $query);
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Boolean::make(__('Enabled'), 'enabled'),
            Text::make(__('Note'), 'note'),
            BelongsTo::make(__('Category'), 'category', Category::class),
            Number::make(__('Unit Qty'), 'unit_qty'),
            Text::make(__('Unit Default'), 'unit_default'),
            Text::make(__('Calc'), 'calc'),
            Translatable::make([
                Text::make(__('Name'), 'name'),
                Text::make(__('Description'), 'description'),
            ]),
            Boolean::make(__('Has Benchmarking'), 'has_benchmarking')
            ->trueValue(1)
            ->falseValue(0)
            ->default(1),
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
}
