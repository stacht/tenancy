<?php

namespace Stacht\Tenancy\Middleware;

use Closure;
use Stacht\Tenancy\TenantManager;

class EagerIdentification
{
    /**
     * @var App\Services\TenantManager
     */
    protected $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->tenantManager->loadTenant($request->route('tenant'))) {
            $request->route()->forgetParameter('tenant');
            return $next($request);
        }

        // Otherwise continue with the flow and throw 404
        return abort(404);
    }
}
