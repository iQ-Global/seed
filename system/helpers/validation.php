<?php
/**
 * Validation helper functions
 */

use Seed\Core\Validator;

// Simple validation (returns boolean)
function validate($data, $rules) {
    $validator = new Validator($data, $rules);
    return $validator->validate();
}

// Get validator instance
function validator($data, $rules) {
    return new Validator($data, $rules);
}

