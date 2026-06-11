<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\AuditService;

class AuditMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $modifyingMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];
        
        if (in_array($request->method(), $modifyingMethods)) {
            $routeName = $request->route()?->getName() ?? $request->path();
            
            $isSuccess = $response->getStatusCode() < 400;
            
            AuditService::log(
                $routeName,
                null,
                $isSuccess ? 'success' : 'failure'
            );
        }
        
        return $response;
    }
}