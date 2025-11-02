<?php
/**
 * Cookie helper functions
 */

// Set cookie
function cookie_set($name, $value, $minutes = 60) {
    $expire = time() + ($minutes * 60);
    setcookie($name, $value, $expire, '/');
    $_COOKIE[$name] = $value;
}

// Get cookie value
function cookie($name, $default = null) {
    return $_COOKIE[$name] ?? $default;
}

// Check if cookie exists
function has_cookie($name) {
    return isset($_COOKIE[$name]);
}

// Delete cookie
function cookie_forget($name) {
    if (isset($_COOKIE[$name])) {
        unset($_COOKIE[$name]);
        setcookie($name, '', time() - 3600, '/');
    }
}

