<?php
/**
 * URL helper functions
 */

// Get full URL for a path
function url($path = '') {
    $baseUrl = env('APP_URL', 'http://localhost');
    $path = ltrim($path, '/');
    return rtrim($baseUrl, '/') . ($path ? '/' . $path : '');
}

// Get asset URL
function asset($path) {
    return url('assets/' . ltrim($path, '/'));
}

// Get current URL
function current_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    return $protocol . '://' . $host . $uri;
}

// Get base URL
function base_url() {
    return url();
}

// Check if current URL matches
function url_is($pattern) {
    $currentUri = $_SERVER['REQUEST_URI'] ?? '/';
    $currentUri = parse_url($currentUri, PHP_URL_PATH);
    
    // Simple wildcard matching
    $pattern = str_replace('*', '.*', $pattern);
    return preg_match('#^' . $pattern . '$#', $currentUri);
}

