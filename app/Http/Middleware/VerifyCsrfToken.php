<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * https://laravel.com/docs/9.x/csrf#csrf-excluding-uris
     *
     * @var array
     */
    protected $except = [
        'stripe/*',
        '/api/*',
        'api',
        '/payment-update'
    ];
}
