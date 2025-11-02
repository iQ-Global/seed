<?php
/**
 * Authentication helper functions
 */

use Seed\Modules\Auth\Auth;

// Get auth instance or current user
function auth($callback = null) {
    return Auth::user($callback);
}

// Check if user is authenticated
function is_logged_in() {
    return Auth::check();
}

// Get current user ID
function user_id() {
    return Auth::id();
}

