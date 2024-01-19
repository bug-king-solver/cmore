<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureAccountIsEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (tenant()->hasUserManualActivationEnabled() && ! $user->isOwner() && ! auth()->user()->isEnabled()) {
            return $request->expectsJson()
                    ? abort(403, 'Your account is not enabled.')
                    : Redirect::guest(URL::route('tenant.auth.disabled'));
        }

        return $next($request);
    }
}
