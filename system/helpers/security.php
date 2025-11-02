<?php
/**
 * Security helper functions
 */

use Seed\Core\CSRF;

// Escape output for HTML
function esc($string, $context = 'html') {
    if ($string === null) {
        return '';
    }
    
    switch ($context) {
        case 'html':
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        case 'js':
            return json_encode($string);
        case 'url':
            return rawurlencode($string);
        default:
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

// Generate CSRF token
function csrf_token() {
    return CSRF::token();
}

// Output CSRF field
function csrf_field() {
    $token = csrf_token();
    return '<input type="hidden" name="_token" value="' . $token . '">';
}

// Verify CSRF token
function verify_csrf($token = null) {
    $token = $token ?? CSRF::getRequestToken();
    return CSRF::verify($token);
}

