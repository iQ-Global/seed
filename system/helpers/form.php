<?php
/**
 * Form helper functions
 */

use Seed\Core\Session;

// Get input value for form repopulation
function input_value($key, $default = '') {
    $input = Session::get('_validation_input', []);
    return $input[$key] ?? $_POST[$key] ?? $_GET[$key] ?? $default;
}

// Get form error
function form_error($key) {
    $errors = Session::get('_validation_errors', []);
    return $errors[$key][0] ?? '';
}

// Check if field has error
function has_error($key) {
    $errors = Session::get('_validation_errors', []);
    return isset($errors[$key]);
}

// Show formatted error
function show_error($key) {
    if (!has_error($key)) {
        return '';
    }
    
    $error = form_error($key);
    return "<span class=\"error\">" . esc($error) . "</span>";
}

