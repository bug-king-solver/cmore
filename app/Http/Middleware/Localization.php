<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Session::has('locale')) {
            $supportedLocales = config('app.locales');
            $locale = $request->getPreferredLanguage($supportedLocales);
            setUserLocale($locale, false);
        }

        App::setLocale(Session::get('locale'));

        return $next($request);
    }
}
