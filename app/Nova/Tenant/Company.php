<?php

namespace App\Nova\Tenant;

use App\Models\Tenant\Scopes\EnabledScope;
use App\Nova\CustomResource;
use App\Nova\Tenant\BusinessSector;
use App\Nova\Tenant\User;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\ExportAsCsv;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Symfony\Component\Intl\Countries;

class Company extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Tenant\Company::class;

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

            Text::make(__('Name'), 'name')->sortable(),
            BelongsTo::make(__('Owner'), 'owner', User::class),
            BelongsTo::make(__('Business Sector'), 'business_sector', BusinessSector::class),
            Select::make(__('Country'), 'country')->options($countries)->displayUsingLabels(),
            Select::make(__('VAT Country'), 'vat_country')->options($countries)->displayUsingLabels()->hideFromIndex(),
            Text::make(__('VAT number'), 'vat_number')->hideFromIndex(),
            Date::make(__('Founded at'), 'founded_at')->hideFromIndex(),
            File::make(__('Logo'), 'logo')->nullable()->hideFromIndex(),
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
        return [
            ExportAsCsv::make()->nameable()->withFormat(function ($model) {
                return [
                    'ID' => $model->getKey(),
                    'Name' => $model->name,
                    'Country' => $model->country,
                    'Business Sector' => $model->business_sector->name,
                ];
            }),
        ];
    }
}
