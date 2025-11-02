<?php
/**
 * RateLimitMiddleware - Simple rate limiting
 */

namespace Seed\Core\Middleware;

use Seed\Core\Middleware;
use Seed\Core\Session;

class RateLimitMiddleware extends Middleware {
    private $maxAttempts = 60;  // Max requests
    private $decayMinutes = 1;   // Per minute
    
    // Handle request
    public function handle($request, $next) {
        $key = $this->getRateLimitKey($request);
        $attempts = Session::get($key, []);
        $now = time();
        
        // Remove old attempts
        $attempts = array_filter($attempts, function($timestamp) use ($now) {
            return ($now - $timestamp) < ($this->decayMinutes * 60);
        });
        
        // Check if limit exceeded
        if (count($attempts) >= $this->maxAttempts) {
            http_response_code(429);
            die('Too many requests. Please try again later.');
        }
        
        // Add current attempt
        $attempts[] = $now;
        Session::set($key, $attempts);
        
        return $next($request);
    }
    
    // Generate rate limit key
    private function getRateLimitKey($request) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $uri = $request->uri();
        return "_rate_limit_{$ip}_{$uri}";
    }
}

