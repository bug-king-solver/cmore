<?php

namespace App\Nova\Tenant;

use App\Nova\CustomResource;
use App\Nova\Tenant\Company;
use App\Nova\Tenant\QuestionnaireType;
use App\Nova\Tenant\User;
use Auth;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\MultiselectField\Multiselect;
use Symfony\Component\Intl\Countries;

class Questionnaire extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model =  \App\Models\Tenant\Questionnaire::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $countries = Countries::getAlpha3Names();

        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('Company'), 'company', Company::class),
            BelongsTo::make(__('Questionnaire Type'), 'type', QuestionnaireType::class)->withoutTrashed(),
            Multiselect::make(__('Country'), 'countries')->options($countries)
                ->saveAsJSON(true)
                ->clearOnSelect(),
            Date::make(__('From'), 'from')->required(),
            Date::make(__('To'), 'to')->required(),
            BelongsTo::make(__('User'), 'createdBy', User::class),
            DateTime::make('created_at')->onlyOnIndex(),
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
        return [];
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
        return [];
    }
}
