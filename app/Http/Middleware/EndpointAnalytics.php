<?php

namespace App\Http\Middleware;

use App\Models\Analytics\EndpointAnalytics as EndpointAnalyticsModel;
use Closure;
use Illuminate\Http\Request;

class EndpointAnalytics
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        EndpointAnalyticsModel::create([
            EndpointAnalyticsModel::PATH => $request->getPathInfo(),
            EndpointAnalyticsModel::NAME => $request->route()->name,
            EndpointAnalyticsModel::METHOD => $request->method(),
            EndpointAnalyticsModel::USER_UUID => $request->method(),
        ]);

        return $next($request);
    }
}
