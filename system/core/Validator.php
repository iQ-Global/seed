<?php
/**
 * Validator - Input validation
 */

namespace Seed\Core;

class Validator {
    private $data = [];
    private $rules = [];
    private $errors = [];
    private $messages = [];
    
    public function __construct($data, $rules) {
        $this->data = $data;
        $this->rules = $rules;
    }
    
    // Run validation
    public function validate() {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            
            foreach ($rules as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        
        // Store errors in session for form helpers
        if (!empty($this->errors)) {
            Session::set('_validation_errors', $this->errors);
            Session::set('_validation_input', $this->data);
        } else {
            Session::remove('_validation_errors');
            Session::remove('_validation_input');
        }
        
        return empty($this->errors);
    }
    
    // Apply validation rule
    private function applyRule($field, $rule) {
        $value = $this->data[$field] ?? null;
        
        // Parse rule and parameters
        if (strpos($rule, ':') !== false) {
            list($ruleName, $params) = explode(':', $rule, 2);
            $params = explode(',', $params);
        } else {
            $ruleName = $rule;
            $params = [];
        }
        
        $method = 'validate' . ucfirst($ruleName);
        
        if (method_exists($this, $method)) {
            $this->$method($field, $value, $params);
        }
    }
    
    // Add error message
    private function addError($field, $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }
    
    // Validation: required
    private function validateRequired($field, $value) {
        if ($value === null || $value === '') {
            $this->addError($field, ucfirst($field) . ' is required.');
        }
    }
    
    // Validation: email
    private function validateEmail($field, $value) {
        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, ucfirst($field) . ' must be a valid email address.');
        }
    }
    
    // Validation: numeric
    private function validateNumeric($field, $value) {
        if ($value && !is_numeric($value)) {
            $this->addError($field, ucfirst($field) . ' must be a number.');
        }
    }
    
    // Validation: min length/value
    private function validateMin($field, $value, $params) {
        $min = $params[0] ?? 0;
        
        if (is_numeric($value)) {
            if ($value < $min) {
                $this->addError($field, ucfirst($field) . " must be at least {$min}.");
            }
        } else {
            if (strlen($value) < $min) {
                $this->addError($field, ucfirst($field) . " must be at least {$min} characters.");
            }
        }
    }
    
    // Validation: max length/value
    private function validateMax($field, $value, $params) {
        $max = $params[0] ?? 0;
        
        if (is_numeric($value)) {
            if ($value > $max) {
                $this->addError($field, ucfirst($field) . " must not exceed {$max}.");
            }
        } else {
            if (strlen($value) > $max) {
                $this->addError($field, ucfirst($field) . " must not exceed {$max} characters.");
            }
        }
    }
    
    // Validation: alpha (letters only)
    private function validateAlpha($field, $value) {
        if ($value && !ctype_alpha($value)) {
            $this->addError($field, ucfirst($field) . ' must contain only letters.');
        }
    }
    
    // Validation: alphanumeric
    private function validateAlphanumeric($field, $value) {
        if ($value && !ctype_alnum($value)) {
            $this->addError($field, ucfirst($field) . ' must contain only letters and numbers.');
        }
    }
    
    // Validation: URL
    private function validateUrl($field, $value) {
        if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($field, ucfirst($field) . ' must be a valid URL.');
        }
    }
    
    // Check if validation failed
    public function failed() {
        return !empty($this->errors);
    }
    
    // Get all errors
    public function errors() {
        return $this->errors;
    }
    
    // Get error for specific field
    public function error($field) {
        return $this->errors[$field][0] ?? '';
    }
    
    // Set custom error messages
    public function setMessages($messages) {
        $this->messages = $messages;
    }
}

