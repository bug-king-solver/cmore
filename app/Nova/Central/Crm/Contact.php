<?php

namespace App\Nova\Central\Crm;

use App\Nova\Central\Actions\Crm\Sync;
use App\Nova\Central\Actions\Crm\Update;
use App\Nova\Central\Tenant;
use App\Nova\CustomResource;
use App\Services\HubSpot\HubSpot;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Contact extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Crm\Contact::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'data->firstname', 'data->lastname',
    ];

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->firstname . ' ' . $this->lastname;
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

            Text::make('First Name', 'firstname')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Last Name', 'lastname')
                ->sortable()
                ->rules('max:255'),

            Text::make('ID Hubspot')
                ->sortable()
                ->hideWhenCreating()->hideWhenUpdating(),

            Email::make('Email')
                ->sortable(),

            Email::make('Work Email', 'work_email'),

            Text::make('Phone')
                ->sortable(),

            Boolean::make('Enabled')->hideWhenCreating()->hideWhenUpdating(),

            Code::make('Data')->json()->rules('json', 'nullable')->hideWhenCreating(),

            MorphToMany::make('Companies', 'companies', Company::class),
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
                (new Sync(HubSpot::CONTACTS))->showInLine(),
            ];
            if (!$request->query->get('resourceId', null)) {
                $actions[] = new Update(HubSpot::CONTACTS);
            }
        }
        return $actions;
    }
}
