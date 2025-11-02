<?php
/**
 * Config - Configuration management
 */

namespace Seed\Core;

class Config {
    private static $items = [];
    private static $loaded = false;
    
    // Load all configuration files
    public static function load() {
        if (self::$loaded) {
            return;
        }
        
        $configPath = APP_PATH . '/config';
        
        if (!is_dir($configPath)) {
            self::$loaded = true;
            return;
        }
        
        // Load all PHP files in config directory
        $files = glob($configPath . '/*.php');
        
        foreach ($files as $file) {
            $key = basename($file, '.php');
            self::$items[$key] = require $file;
        }
        
        self::$loaded = true;
    }
    
    // Get configuration value using dot notation
    public static function get($key, $default = null) {
        self::load();
        
        // Support dot notation (e.g., 'app.name')
        $keys = explode('.', $key);
        $value = self::$items;
        
        foreach ($keys as $segment) {
            if (!isset($value[$segment])) {
                return $default;
            }
            $value = $value[$segment];
        }
        
        return $value;
    }
    
    // Set configuration value
    public static function set($key, $value) {
        self::load();
        
        $keys = explode('.', $key);
        $config = &self::$items;
        
        foreach ($keys as $i => $segment) {
            if ($i === count($keys) - 1) {
                $config[$segment] = $value;
            } else {
                if (!isset($config[$segment]) || !is_array($config[$segment])) {
                    $config[$segment] = [];
                }
                $config = &$config[$segment];
            }
        }
    }
    
    // Check if configuration key exists
    public static function has($key) {
        self::load();
        
        $keys = explode('.', $key);
        $value = self::$items;
        
        foreach ($keys as $segment) {
            if (!isset($value[$segment])) {
                return false;
            }
            $value = $value[$segment];
        }
        
        return true;
    }
    
    // Get all configuration
    public static function all() {
        self::load();
        return self::$items;
    }
}

