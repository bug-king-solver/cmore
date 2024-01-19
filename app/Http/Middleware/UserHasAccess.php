<?php

namespace App\Http\Middleware;

use Closure;

class UserHasAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string $modelParam - The model name to check access
     * @return mixed
     */
    public function handle($request, Closure $next, $modelParam)
    {
        $model = makeResourcAble($modelParam);
        $model = new $model();
        $resource = null;

        $parameters = $request->route()->parameters;
        $modelId = $parameters[$modelParam] ?? null;

        if ($modelId) {
            $user = auth()->user();
            $resource = method_exists($model, 'scopeOnlyOwnData')
                ? $model->onlyOwnData()->find($modelId)
                : $model->find($modelId);

            if (!$user || !$resource) {
                abort(403, __('You are not allowed to access this resource'));
            }
        }

        return $next($request);
    }
}
