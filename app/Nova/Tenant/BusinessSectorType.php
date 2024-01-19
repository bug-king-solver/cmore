<?php

namespace App\Nova\Tenant;

use App\Models\Tenant\Scopes\EnabledScope;
use App\Nova\CustomResource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\NovaTranslatable\Translatable;

class BusinessSectorType extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Tenant\BusinessSectorType::class;

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
        'id', 'name', 'name_secondary',
    ];

    /**
     * Modify the index query used to retrieve models for the resource.
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->withoutGlobalScope(EnabledScope::class);
        return parent::indexQuery($request, $query);
    }

    /**
     * Modify the query using when visiting the resource .
     * @param NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return Model|Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        return $request->model()->whereId($request->resourceId)
            ->withoutGlobalScope(EnabledScope::class)
            ->firstOrFail();
    }

    /**
     * Modify the query when its editing the resource.
     * @param NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder query
     * @return Model|Builder
     */
    public static function editQuery(NovaRequest $request, $query)
    {
        return $request->model()->whereId($request->resourceId)
            ->withoutGlobalScope(EnabledScope::class)
            ->firstOrFail();
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
            ID::make(__('ID'), 'id')->sortable(),

            Translatable::make([
                Text::make(__('Name'), 'name'),
            ]),

            Translatable::make([
                Text::make(__('Name Secondary'), 'name_secondary'),
            ]),

            Text::make(__('Note'), 'note'),

            Boolean::make(__('Enabled'), 'enabled')
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
