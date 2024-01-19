<?php

namespace App\Nova\Tenant;

use App\Models\Tenant\Scopes\EnabledScope;
use App\Nova\CustomResource;
use App\Nova\Tenant\Category;
use App\Nova\Tenant\QuestionnaireType;
use App\Nova\Tenant\Source;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\NovaTranslatable\Translatable;

class Question extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Tenant\Question::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'description';

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
        'id',
        'description',
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

            Text::make(__('Ref'), 'ref')->readonly(),

            Translatable::make([
                Text::make(
                    __('Description'),
                    'description',
                    fn () => mb_strlen($this->description) <= 60
                        ? $this->description
                        : mb_substr($this->description, 0, 60) . '...'
                )->onlyOnIndex(),
                Text::make(__('Description'), 'description')->hideFromIndex(),
            ]),

            BelongsTo::make(__('Questionnaire'), 'questionnaire', QuestionnaireType::class)
            ->displayUsing(function ($questionnaire) {
                return $questionnaire->name . ' (' . $questionnaire->id . ')';
            })
            ->sortable(),

            BelongsTo::make(__('Source'), 'source', Source::class),

            BelongsTo::make(__('Category'), 'category', Category::class),
            BelongsTo::make(__('Parent'), 'parent', self::class)->nullable()->hideFromIndex(),
            HasMany::make(__('Answer options'), 'questionOptions', QuestionOption::class),
            HasMany::make(__('Children'), 'children', self::class),
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
