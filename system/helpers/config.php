<?php
/**
 * Configuration helper functions
 */

use Seed\Core\Config;

// Get configuration value
function config($key = null, $default = null) {
    if ($key === null) {
        return Config::all();
    }
    
    return Config::get($key, $default);
}

// Set configuration value
function config_set($key, $value) {
    Config::set($key, $value);
}

// Check if configuration exists
function config_has($key) {
    return Config::has($key);
}

