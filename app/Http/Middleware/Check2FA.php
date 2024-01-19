<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class Check2FA
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
        if (! auth()->user()->has2FAActive()) {
            Session::put('user_2fa', auth()->user()->id);
        }
        if (auth()->user()->has2FAActive() && ! Session::has('user_2fa')) {
            $this->generateCode(auth()->user());
            return redirect()->route('tenant.2fa.index');
        }

        return $next($request);
    }

    private function generateCode($user)
    {
        if ($user->isToSend2FAEmail() || $user->isToSend2FAPhone()) {
            $user->generateCode();
        }
    }
}
