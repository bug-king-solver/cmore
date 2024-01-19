<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
    private $unwantedHeaders = ['X-Powered-By', 'server', 'Server'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');

        // XSS Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Expect-CT
        $response->headers->set('Expect-CT', 'enforce, max-age=30');

        // Permissions-Policy
        $response->headers->set('Permissions-Policy', 'autoplay=(self), camera=(), encrypted-media=(self), fullscreen=(), geolocation=(self), gyroscope=(self), magnetometer=(), microphone=(), midi=(), payment=(), sync-xhr=(self), usb=()');

        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Frame Options
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN', false);

        $this->removeUnwantedHeaders($this->unwantedHeaders);

        return $response;
    }

    /**
     * Remove unwanted headers
     *
     * @param $headers
     */
    private function removeUnwantedHeaders($headers): void
    {
        foreach ($headers as $header) {
            header_remove($header);
        }
    }
}
