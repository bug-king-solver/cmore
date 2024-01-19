<?php

namespace App\Nova\Tenant;

use App\Nova\CustomResource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\NovaTranslatable\Translatable;

class Answer extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Tenant\Answer::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'description';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'description',
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

            Text::make(__('Answer'), 'value')->sortable(),
            BelongsTo::make(__('Questionnaire'), 'questionnaire', Questionnaire::class)->hideFromIndex(),
            BelongsTo::make(__('Questionnaire'), 'questionnaire', Questionnaire::class)->onlyOnIndex()->display('id'),
            BelongsTo::make(__('Question'), 'question', Question::class)->hideFromIndex(),
            BelongsTo::make(__('Question'), 'question', Question::class)->onlyOnIndex()->display('id')->sortable(),
            Number::make(__('Weight'), 'weight')->hideFromIndex(),
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

    /**
     * Determine if the current user can  update the given resource.
     */
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
}
