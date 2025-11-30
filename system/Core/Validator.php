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
    
    // Validation: confirmed (for password_confirmation)
    private function validateConfirmed($field, $value) {
        $confirmField = $field . '_confirmation';
        $confirmValue = $this->data[$confirmField] ?? null;
        
        if ($value !== $confirmValue) {
            $this->addError($field, ucfirst($field) . ' confirmation does not match.');
        }
    }
    
    // Validation: matches another field
    private function validateMatches($field, $value, $params) {
        $otherField = $params[0] ?? '';
        $otherValue = $this->data[$otherField] ?? null;
        
        if ($value !== $otherValue) {
            $this->addError($field, ucfirst($field) . ' must match ' . $otherField . '.');
        }
    }
    
    // Validation: different from another field
    private function validateDifferent($field, $value, $params) {
        $otherField = $params[0] ?? '';
        $otherValue = $this->data[$otherField] ?? null;
        
        if ($value === $otherValue) {
            $this->addError($field, ucfirst($field) . ' must be different from ' . $otherField . '.');
        }
    }
    
    // Validation: date
    private function validateDate($field, $value) {
        if ($value && !strtotime($value)) {
            $this->addError($field, ucfirst($field) . ' must be a valid date.');
        }
    }
    
    // Validation: date format
    private function validateDateFormat($field, $value, $params) {
        $format = $params[0] ?? 'Y-m-d';
        
        if ($value) {
            $date = \DateTime::createFromFormat($format, $value);
            if (!$date || $date->format($format) !== $value) {
                $this->addError($field, ucfirst($field) . " must match the format {$format}.");
            }
        }
    }
    
    // Validation: after date
    private function validateAfter($field, $value, $params) {
        $compareDate = $params[0] ?? 'today';
        
        if ($value) {
            $valueTime = strtotime($value);
            $compareTime = strtotime($compareDate);
            
            if ($valueTime === false || $compareTime === false || $valueTime <= $compareTime) {
                $this->addError($field, ucfirst($field) . " must be after {$compareDate}.");
            }
        }
    }
    
    // Validation: before date
    private function validateBefore($field, $value, $params) {
        $compareDate = $params[0] ?? 'today';
        
        if ($value) {
            $valueTime = strtotime($value);
            $compareTime = strtotime($compareDate);
            
            if ($valueTime === false || $compareTime === false || $valueTime >= $compareTime) {
                $this->addError($field, ucfirst($field) . " must be before {$compareDate}.");
            }
        }
    }
    
    // Validation: between (numeric or string length)
    private function validateBetween($field, $value, $params) {
        $min = $params[0] ?? 0;
        $max = $params[1] ?? 0;
        
        if (is_numeric($value)) {
            if ($value < $min || $value > $max) {
                $this->addError($field, ucfirst($field) . " must be between {$min} and {$max}.");
            }
        } else {
            $length = strlen($value);
            if ($length < $min || $length > $max) {
                $this->addError($field, ucfirst($field) . " must be between {$min} and {$max} characters.");
            }
        }
    }
    
    // Validation: in list (whitelist)
    private function validateIn($field, $value, $params) {
        if ($value && !in_array($value, $params)) {
            $allowed = implode(', ', $params);
            $this->addError($field, ucfirst($field) . " must be one of: {$allowed}.");
        }
    }
    
    // Validation: not in list (blacklist)
    private function validateNotIn($field, $value, $params) {
        if ($value && in_array($value, $params)) {
            $this->addError($field, ucfirst($field) . ' contains an invalid value.');
        }
    }
    
    // Validation: regex pattern
    private function validateRegex($field, $value, $params) {
        $pattern = $params[0] ?? '';
        
        if ($value && !preg_match($pattern, $value)) {
            $this->addError($field, ucfirst($field) . ' format is invalid.');
        }
    }
    
    // Validation: unique in database
    private function validateUnique($field, $value, $params) {
        $table = $params[0] ?? '';
        $column = $params[1] ?? $field;
        $except = $params[2] ?? null;
        
        if ($value && $table) {
            $db = db();
            $query = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
            $bindings = [$value];
            
            if ($except !== null) {
                $query .= " AND id != ?";
                $bindings[] = $except;
            }
            
            $result = $db->queryOne($query, $bindings);
            
            if ($result && $result['count'] > 0) {
                $this->addError($field, ucfirst($field) . ' already exists.');
            }
        }
    }
    
    // Validation: exists in database
    private function validateExists($field, $value, $params) {
        $table = $params[0] ?? '';
        $column = $params[1] ?? $field;
        
        if ($value && $table) {
            $db = db();
            $query = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
            $result = $db->queryOne($query, [$value]);
            
            if (!$result || $result['count'] == 0) {
                $this->addError($field, ucfirst($field) . ' does not exist.');
            }
        }
    }
    
    // Validation: integer
    private function validateInteger($field, $value) {
        if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_INT)) {
            $this->addError($field, ucfirst($field) . ' must be an integer.');
        }
    }
    
    // Validation: boolean
    private function validateBoolean($field, $value) {
        if ($value !== null && $value !== '' && !in_array($value, [true, false, 0, 1, '0', '1', 'true', 'false', 'on', 'off', 'yes', 'no'], true)) {
            $this->addError($field, ucfirst($field) . ' must be true or false.');
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

