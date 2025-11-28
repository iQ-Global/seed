<?php
/**
 * General helper functions
 */

// Get environment variable
function env($key, $default = null) {
    $value = $_ENV[$key] ?? getenv($key);
    
    if ($value === false) {
        return $default;
    }
    
    // Convert string booleans
    if (strtolower($value) === 'true') return true;
    if (strtolower($value) === 'false') return false;
    if (strtolower($value) === 'null') return null;
    
    return $value;
}

// Render view
function view($template, $data = []) {
    echo \Seed\Core\View::render($template, $data);
}

// JSON response
function json($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// Redirect to URL
function redirect($url) {
    header("Location: {$url}");
    exit;
}

// Redirect back
function redirect_back() {
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
    redirect($referer);
}

// Dump and die
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

// Debug output
function output($data) {
    echo '<pre>';
    if (is_array($data) || is_object($data)) {
        print_r($data);
    } else {
        var_dump($data);
    }
    echo '</pre>';
}

// Log info message
function log_info($message, $context = []) {
    \Seed\Core\Logger::info($message, $context);
}

// Log error message
function log_error($message, $context = []) {
    \Seed\Core\Logger::error($message, $context);
}

// Log debug message
function log_debug($message, $context = []) {
    \Seed\Core\Logger::debug($message, $context);
}

// Create response instance
function response() {
    return new \Seed\Core\Response();
}

// Get domain parameters (e.g., tenant from {tenant}.example.com)
function domain_param($key = null, $default = null) {
    global $seed;
    
    if (!isset($seed) || !method_exists($seed, 'router')) {
        return $key === null ? [] : $default;
    }
    
    $params = $seed->router()->getDomainParams();
    
    if ($key === null) {
        return $params;
    }
    
    return $params[$key] ?? $default;
}

// Get current domain (normalized)
function current_domain() {
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
    
    // Remove port
    if (strpos($host, ':') !== false) {
        $host = explode(':', $host)[0];
    }
    
    // Strip www
    if (strpos($host, 'www.') === 0) {
        $host = substr($host, 4);
    }
    
    return strtolower($host);
}

