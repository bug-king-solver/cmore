<?php

namespace App\Nova\Tenant;

use App\Nova\CustomResource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ramsey\Uuid\Uuid;
use Slides\Saml2\Commands\RendersTenants;
use Symfony\Component\Console\Output\BufferedOutput;

class Saml2Tenants extends CustomResource
{
    use RendersTenants;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Tenant\Saml2Tenants::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'uuid';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'uuid', 'relay_state_url', 'tenant', 'key', 'idp_entity_id', 'idp_login_url', 'idp_logout_url', 'idp_x509_cert', 'metadata'
    ];

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
            Text::make(__('UUID'), 'uuid')->sortable()->default(function ($request) {
                return Uuid::uuid4();
            })->withMeta(['extraAttributes' => [
                'readonly' => true
            ]]),
            Text::make(__('Relay State URL'), 'relay_state_url')->sortable(),
            Text::make(__('Key'), 'key')->required()->sortable(),
            Text::make(__('Entity ID'), 'idp_entity_id')->required()->hideFromIndex(),
            Text::make(__('Login URL'), 'idp_login_url')->required()->hideFromIndex(),
            Text::make(__('Logout URL'), 'idp_logout_url')->hideFromIndex(),
            Textarea::make(__('Metadata'), 'metadata')->hideFromIndex(),
            Textarea::make(__('Certificate x590'), 'idp_x509_cert')->required()->hideFromIndex(),
            Boolean::make(__('Use This'), 'is_default'),
            Textarea::make(__('Credentials for the tenant'))->resolveUsing(function () {
                return implode("\n", [
                    'Identifier (Entity ID): ' . route('tenant.saml.metadata', ['uuid' => $this->resource->uuid]),
                    'Reply URL (Assertion Consumer Service URL): ' . route('tenant.saml.acs', ['uuid' => $this->resource->uuid]),
                    'Sign on URL: ' . route('tenant.saml.login', ['uuid' => $this->resource->uuid]),
                    'Logout URL: ' . route('tenant.saml.logout', ['uuid' => $this->resource->uuid]),
                    'Relay State: ' . ($this->resource->relay_state_url ?: config('tenant.saml2.loginRoute')) . ' (optional)'
                ]);
            })->onlyOnDetail(),
        ];
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            return $query->orderBy('is_default', 'desc');
        }
        return $query;
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
        return [];
    }
}
