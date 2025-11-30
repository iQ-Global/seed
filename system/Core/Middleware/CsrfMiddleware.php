<?php
/**
 * CsrfMiddleware - Verify CSRF tokens
 */

namespace Seed\Core\Middleware;

use Seed\Core\Middleware;
use Seed\Core\CSRF;

class CsrfMiddleware extends Middleware {
    // Handle request
    public function handle($request, $next) {
        $method = $request->method();
        
        // Only check POST, PUT, DELETE, PATCH requests
        if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            CSRF::validateRequest();
        }
        
        return $next($request);
    }
}

