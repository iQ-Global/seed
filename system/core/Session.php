<?php
/**
 * Session - Session management
 */

namespace Seed\Core;

class Session {
    private static $started = false;
    
    // Start session if not already started
    public static function start() {
        if (!self::$started && session_status() === PHP_SESSION_NONE) {
            $driver = env('SESSION_DRIVER', 'file');
            $lifetime = env('SESSION_LIFETIME', 120) * 60; // Convert to seconds
            
            ini_set('session.gc_maxlifetime', $lifetime);
            session_set_cookie_params($lifetime);
            
            if ($driver === 'file') {
                $sessionPath = STORAGE_PATH . '/sessions';
                if (!is_dir($sessionPath)) {
                    mkdir($sessionPath, 0755, true);
                }
                session_save_path($sessionPath);
            }
            
            session_start();
            self::$started = true;
        }
    }
    
    // Get session value
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    // Set session value
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    // Check if session key exists
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    // Remove session key
    public static function remove($key) {
        self::start();
        unset($_SESSION[$key]);
    }
    
    // Get and remove (flash behavior)
    public static function pull($key, $default = null) {
        $value = self::get($key, $default);
        self::remove($key);
        return $value;
    }
    
    // Set flash message
    public static function flash($key, $value) {
        self::set("_flash_{$key}", $value);
    }
    
    // Get flash message
    public static function getFlash($key, $default = null) {
        return self::pull("_flash_{$key}", $default);
    }
    
    // Check if flash exists
    public static function hasFlash($key) {
        return self::has("_flash_{$key}");
    }
    
    // Destroy session
    public static function destroy() {
        self::start();
        session_unset();
        session_destroy();
        self::$started = false;
    }
    
    // Regenerate session ID
    public static function regenerate($deleteOld = false) {
        self::start();
        session_regenerate_id($deleteOld);
    }
}

