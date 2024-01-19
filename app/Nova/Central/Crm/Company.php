<?php

namespace App\Nova\Central\Crm;

use App\Nova\Central\Actions\Crm\Sync;
use App\Nova\Central\Actions\Crm\Update;
use App\Nova\CustomResource;
use App\Services\HubSpot\HubSpot;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphedByMany;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;

class Company extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Crm\Company::class;

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
        'id', 'data->name', 'id_hubspot'
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
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('ID Hubspot')
                ->sortable()->hideWhenCreating()->hideWhenUpdating(),

            Boolean::make('Enabled')->hideWhenCreating()->hideWhenUpdating(),

            Textarea::make('Description'),

            Textarea::make('About Us'),

            Text::make('Phone'),

            Text::make('Type')->hideWhenCreating()->hideWhenUpdating(),

            URL::make('Linkedin'),

            Text::make('Twitter'),

            URL::make('Facebook'),

            URL::make('Google Plus', 'googleplus'),

            Code::make('Data')->json()->rules('json', 'nullable')->hideWhenCreating(),

            MorphedByMany::make('Contacts', 'contacts', Contact::class),

            HasMany::make('Deals', 'deals', Deal::class),
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
        $actions = [];
        if (config('app.hubspot_key')) {
            $actions = [
                (new Sync(HubSpot::COMPANIES))->showInLine(),
            ];
            if (!$request->query->get('resourceId', null)) {
                $actions[] = new Update(HubSpot::COMPANIES);
            }
        }
        return $actions;
    }
}
