<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\AuditService;

class AuditMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $sensitiveRoutes = [
            'login', 'logout', 'export', 'import'
        ];
        
        $route = $request->route();
        if ($route && in_array($route->getName(), $sensitiveRoutes)) {
            AuditService::log(
                $route->getName(),
                null,
                $response->isSuccessful() ? 'success' : 'failure'
            );
        }
        
        return $response;
    }
}