<?php

use App\Http\Policies\NovaCsp;
use Laravel\Nova\Actions\ActionResource;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Http\Middleware\Authorize;
use Laravel\Nova\Http\Middleware\BootTools;
use Laravel\Nova\Http\Middleware\DispatchServingNovaEvent;
use Laravel\Nova\Http\Middleware\HandleInertiaRequests;
use Spatie\Csp\AddCspHeaders;
use Visanduma\NovaTwoFactor\Http\Middleware\TwoFa;

return [

    'license_key' => env('NOVA_LICENSE_KEY', ''),
    /*
    |--------------------------------------------------------------------------
    | Nova App Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to display the name of the application within the UI
    | or in other locations. Of course, you're free to change the value.
    |
    */

    'name' => env('NOVA_APP_NAME', env('APP_NAME')),

    /*
    |--------------------------------------------------------------------------
    | Nova Domain Name
    |--------------------------------------------------------------------------
    |
    | This value is the "domain name" associated with your application. This
    | can be used to prevent Nova's internal routes from being registered
    | on subdomains which do not need access to your admin application.
    |
    */

    'domain' => env('NOVA_DOMAIN_NAME', null),

    /*
    |--------------------------------------------------------------------------
    | Nova App URL
    |--------------------------------------------------------------------------
    |
    | This URL is where users will be directed when clicking the application
    | name in the Nova navigation bar. You are free to change this URL to
    | any location you wish depending on the needs of your application.
    |
    */

    'url' => env('APP_URL', '/'),

    /*
    |--------------------------------------------------------------------------
    | Nova Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Nova will be accessible from. Feel free to
    | change this path to anything you like. Note that this URI will not
    | affect Nova's internal API routes which aren't exposed to users.
    |
    */

    'path' => '/bo',

    /*
    |--------------------------------------------------------------------------
    | Nova Authentication Guard
    |--------------------------------------------------------------------------
    |
    | This configuration option defines the authentication guard that will
    | be used to protect your Nova routes. This option should match one
    | of the authentication guards defined in the "auth" config file.
    |
    */

    'guard' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Nova Password Reset Broker
    |--------------------------------------------------------------------------
    |
    | This configuration option defines the password broker that will be
    | used when passwords are reset. This option should mirror one of
    | the password reset options defined in the "auth" config file.
    |
    */

    'passwords' => env('NOVA_PASSWORDS', 'admins'),

    /*
    |--------------------------------------------------------------------------
    | Nova Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Nova route, giving you the
    | chance to add your own middleware to this stack or override any of
    | the existing middleware. Or, you can just stick with this stack.
    |
    */

    'middleware' => [
        'tenant',
        'universal',
        'web',
        HandleInertiaRequests::class,
        DispatchServingNovaEvent::class,
        BootTools::class,
        AddCspHeaders::class . ':' . NovaCsp::class,
        TwoFa::class,
    ],

    'api_middleware' => [
        'tenant',
        'universal',
        'nova',
        Authenticate::class,
        Authorize::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Nova Pagination Type
    |--------------------------------------------------------------------------
    |
    | This option defines the visual style used in Nova's resource pagination
    | views. You may select between "simple", "load-more", and "links" for
    | your applications. Feel free to adjust this option to your choice.
    |
    */

    'pagination' => 'simple',

    /*
    |--------------------------------------------------------------------------
    | Nova Action Resource Class
    |--------------------------------------------------------------------------
    |
    | This configuration option allows you to specify a custom resource class
    | to use instead of the type that ships with Nova. You may use this to
    | define any extra form fields or other custom behavior as required.
    |
    */

    'storage_disk' => env('NOVA_STORAGE_DISK', 'public'),

    'actions' => [
        'resource' => ActionResource::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Nova Currency
    |--------------------------------------------------------------------------
    |
    | This configuration option allows you to define the default currency
    | used by the Currency field within Nova. You may change this to a
    | valid ISO 4217 currency code to suit your application's needs.
    |
    */

    'currency' => 'EUR',

    'brand' => [
        'logo' => 'images/logos/logo1.svg', // What ever your logo path in your project
    ],

];