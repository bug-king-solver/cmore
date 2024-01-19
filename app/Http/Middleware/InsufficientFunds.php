<?php

namespace App\Http\Middleware;

use Closure;

class InsufficientFunds
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (tenant()->has_insufficient_funds) {
            return redirect(route('tenant.wallet'));
        }

        return $next($request);
    }
}
