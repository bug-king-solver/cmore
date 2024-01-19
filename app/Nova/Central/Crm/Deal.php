<?php

namespace App\Nova\Central\Crm;

use App\Nova\Central\Actions\Crm\Sync;
use App\Nova\Central\Actions\Crm\Update;
use App\Nova\Central\Invoicing\Document;
use App\Nova\Central\Payment;
use App\Nova\Central\Tenant;
use App\Nova\CustomResource;
use App\Services\HubSpot\HubSpot;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Deal extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Crm\Deal::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'dealname';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'data->dealname', 'id_hubspot'
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

            BelongsTo::make('Tenant', 'tenant', Tenant::class)
                ->withoutTrashed(),

            BelongsTo::make('Company', 'company', Company::class)
                ->withoutTrashed(),

            Text::make('Name', 'dealname')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('ID Hubspot')
                ->sortable()
                ->hideWhenCreating()->hideWhenUpdating(),

            Textarea::make('Description'),

            Currency::make('Amount')->sortable(),

            Text::make('Type', 'dealtype')->sortable()->hideWhenCreating()->hideWhenUpdating(),

            Text::make('Priority', 'hs_priority')->sortable()->hideWhenCreating()->hideWhenUpdating(),

            Text::make('Stage', 'dealstage')->sortable()->hideWhenCreating()->hideWhenUpdating(),

            HasMany::make('Invoicing Documents', 'invoicing_documents', Document::class),

            HasMany::make('Payments', 'payments', Payment::class),

            Code::make('Data')->json()->rules('json', 'nullable')->hideWhenCreating(),

            Boolean::make('Enabled')->hideWhenCreating()->hideWhenUpdating(),
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
                (new Sync(HubSpot::DEALS))->showInLine(),
            ];
            if (!$request->query->get('resourceId', null)) {
                $actions[] = new Update(HubSpot::DEALS);
            }
        }
        return $actions;
    }
}
