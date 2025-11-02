<?php
/**
 * CSRF - Cross-Site Request Forgery protection
 */

namespace Seed\Core;

class CSRF {
    // Generate CSRF token
    public static function token() {
        if (!Session::has('_csrf_token')) {
            Session::set('_csrf_token', bin2hex(random_bytes(32)));
        }
        
        return Session::get('_csrf_token');
    }
    
    // Verify CSRF token
    public static function verify($token) {
        if (!env('CSRF_ENABLED', true)) {
            return true;
        }
        
        $sessionToken = Session::get('_csrf_token');
        
        return $sessionToken && hash_equals($sessionToken, $token);
    }
    
    // Get token from request
    public static function getRequestToken() {
        return $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
    }
    
    // Validate request
    public static function validateRequest() {
        $token = self::getRequestToken();
        
        if (!self::verify($token)) {
            http_response_code(419);
            die('CSRF token mismatch');
        }
    }
}

