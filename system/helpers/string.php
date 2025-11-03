<?php
/**
 * String helper functions
 */

// Limit string to specified length
function str_limit($string, $limit = 100, $end = '...') {
    if (mb_strlen($string) <= $limit) {
        return $string;
    }
    
    return rtrim(mb_substr($string, 0, $limit)) . $end;
}

// Generate URL-friendly slug
function str_slug($string, $separator = '-') {
    // Convert to lowercase
    $string = strtolower($string);
    
    // Replace non-alphanumeric characters
    $string = preg_replace('/[^a-z0-9]+/i', $separator, $string);
    
    // Remove duplicate separators
    $string = preg_replace('/' . preg_quote($separator) . '{2,}/', $separator, $string);
    
    // Trim separators from ends
    return trim($string, $separator);
}

// Generate random string
function str_random($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';
    
    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $string;
}

// Check if string contains substring (PHP 8 polyfill)
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && strpos($haystack, $needle) !== false;
    }
}

// Check if string starts with substring (PHP 8 polyfill)
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return strpos($haystack, $needle) === 0;
    }
}

// Check if string ends with substring (PHP 8 polyfill)
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        if ($needle === '') {
            return true;
        }
        return substr($haystack, -strlen($needle)) === $needle;
    }
}

// Alias for str_contains
function str_has($haystack, $needle) {
    return str_contains($haystack, $needle);
}

// Alias for str_starts_with
function str_starts($haystack, $needle) {
    return str_starts_with($haystack, $needle);
}

// Alias for str_ends_with
function str_ends($haystack, $needle) {
    return str_ends_with($haystack, $needle);
}

// Convert string to title case
function str_title($string) {
    return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
}

// Convert string to camelCase
function str_camel($string) {
    return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string))));
}

// Convert string to StudlyCase (PascalCase)
function str_studly($string) {
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
}

