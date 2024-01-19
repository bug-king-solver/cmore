<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoggingContextMiddleware
{
    /**
     * Handle an incoming request.
     * @param Illuminate\Http\Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $requestId = (string) Str::uuid();
        $traceID = $request->header('X-Trace-ID') ?? $requestId;

        Log::shareContext(array_filter([
            'X-Trace-ID' => $traceID,
            'URI' => $request->getUri() ?? null,
            'METHOD' => $request->getMethod() ?? null,
            "REQUEST_URI" => $request->getRequestUri() ?? null
        ]));

        $response->headers->set('X-Trace-ID', $traceID);
        return $response;
    }
}
