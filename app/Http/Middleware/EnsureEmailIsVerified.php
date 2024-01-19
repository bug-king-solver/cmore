<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        $user = $request->user();

        if (
            tenant()->hasUserEmailVerificationEnabled()
            && ! $user->isOwner()
            && (! $user || ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()))
        ) {
            $redirectRoute = $redirectToRoute ?: 'verification.notice';

            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : Redirect::guest(URL::route($redirectRoute));
        }

        return $next($request);
    }
}
