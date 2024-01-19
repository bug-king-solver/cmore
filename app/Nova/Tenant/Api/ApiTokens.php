<?php

namespace App\Nova\Tenant\Api;

use App\Models\Enums\SanctumAbilities;
use App\Models\Tenant\Api\ApiTokens as SanctumApiTokens;
use App\Nova\CustomResource;
use App\Nova\Tenant\User;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\MultiselectField\Multiselect;

class ApiTokens extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = SanctumApiTokens::class;

    /**
     * The logical group associated with the resource.
     */
    public static $group = 'Server';

    /**
     * get the resource name for the sidebar
     */
    public static function label()
    {
        return __('Api Tokens');
    }

    /**
     * Set the uri key for the resource.
     */
    public static function uriKey()
    {
        return 'api-tokens';
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
        $abilities = self::getAbilities();

        return [

            ID::make()->sortable(),

            /** tokenable */
            MorphTo::make(__('Tokenable'), 'tokenable')->types([
                User::class,
            ])->sortable()
                ->rules('required'),

            /** token name */
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            /** abilities */
            Multiselect::make(__('Abilities'))
                ->options($abilities),

            /** last used */
            Text::make(__('Last Used At'), 'last_used_at')->hideWhenCreating()->hideWhenUpdating(),
            /** expires at */
            Text::make(__('Expires At'), 'expires_at')->hideWhenCreating()->hideWhenUpdating(),
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
