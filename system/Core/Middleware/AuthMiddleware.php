<?php
/**
 * AuthMiddleware - Require authentication
 */

namespace Seed\Core\Middleware;

use Seed\Core\Middleware;
use Seed\Modules\Auth\Auth;

class AuthMiddleware extends Middleware {
    // Handle request
    public function handle($request, $next) {
        if (!Auth::check()) {
            // User not authenticated - redirect to login
            redirect('/login');
        }
        
        return $next($request);
    }
}

