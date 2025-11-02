<?php
/**
 * Session helper functions
 */

use Seed\Core\Session;

// Get or set session data
function session($key = null, $value = null) {
    if ($key === null) {
        Session::start();
        return $_SESSION;
    }
    
    if ($value !== null) {
        Session::set($key, $value);
        return $value;
    }
    
    return Session::get($key);
}

// Remove session key
function session_remove($key) {
    Session::remove($key);
}

// Destroy all session data
function destroy_session() {
    Session::destroy();
}

// Set flash message
function flash($key, $value) {
    Session::flash($key, $value);
}

// Get flash message (auto-removes after retrieval)
function get_flash($key, $default = null) {
    return Session::getFlash($key, $default);
}

// Check if flash exists
function has_flash($key) {
    return Session::hasFlash($key);
}

// Show formatted flash message
function show_flash($key, $class = null) {
    if (!has_flash($key)) {
        return '';
    }
    
    $message = get_flash($key);
    $cssClass = $class ?? "flash-{$key}";
    
    return "<div class=\"{$cssClass}\">" . esc($message) . "</div>";
}

