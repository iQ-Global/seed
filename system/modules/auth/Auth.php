<?php
/**
 * Auth - Simple authentication
 */

namespace Seed\Modules\Auth;

use Seed\Core\Session;

class Auth {
    private static $userKey = 'auth_user_id';
    
    // Hash password
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    // Verify password
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    // Log user in
    public static function login($userId) {
        Session::set(self::$userKey, $userId);
        Session::regenerate(true);
    }
    
    // Log user out
    public static function logout() {
        Session::remove(self::$userKey);
        Session::regenerate(true);
    }
    
    // Check if user is logged in
    public static function check() {
        return Session::has(self::$userKey);
    }
    
    // Get current user ID
    public static function id() {
        return Session::get(self::$userKey);
    }
    
    // Get current user (requires user model or callback)
    public static function user($callback = null) {
        $userId = self::id();
        
        if (!$userId) {
            return null;
        }
        
        if ($callback && is_callable($callback)) {
            return $callback($userId);
        }
        
        return $userId;
    }
    
    // Attempt login with credentials
    // $checkCallback should verify credentials and return user ID if valid, false otherwise
    public static function attempt($checkCallback) {
        $result = $checkCallback();
        
        if ($result) {
            self::login($result);
            return true;
        }
        
        return false;
    }
}

