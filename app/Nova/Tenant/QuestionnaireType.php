<?php

namespace App\Nova\Tenant;

use App\Models\Tenant\QuestionnaireType as TenantQuestionnaireType;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Tenant\Scopes\InstancesEnableScope;
use App\Nova\CustomResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\NovaTranslatable\Translatable;

class QuestionnaireType extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Tenant\QuestionnaireType::class;

    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
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
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Boolean::make(__('Enabled'), 'enabled')->sortable(),

            Boolean::make(__('Dashboards Mini'), 'dashboardMini'),

            Translatable::make([
                Text::make(__('Name'), 'name')->sortable(),
            ]),

            Text::make(__('Slug'), 'slug')
                ->sortable()
                ->rules('nullable', 'string', 'max:255')
                ->creationRules('unique:questionnaire_types,slug')
                ->updateRules('unique:questionnaire_types,slug,{{resourceId}}'),

            Text::make(__('Note'), 'note'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new Actions\Enabled(),
        ];
    }
}
