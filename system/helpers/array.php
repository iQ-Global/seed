<?php
/**
 * Array helper functions
 */

// Get value from array using dot notation
function array_get($array, $key, $default = null) {
    if (!is_array($array)) {
        return $default;
    }
    
    if (isset($array[$key])) {
        return $array[$key];
    }
    
    foreach (explode('.', $key) as $segment) {
        if (!is_array($array) || !array_key_exists($segment, $array)) {
            return $default;
        }
        $array = $array[$segment];
    }
    
    return $array;
}

// Set value in array using dot notation
function array_set(&$array, $key, $value) {
    $keys = explode('.', $key);
    
    while (count($keys) > 1) {
        $key = array_shift($keys);
        
        if (!isset($array[$key]) || !is_array($array[$key])) {
            $array[$key] = [];
        }
        
        $array = &$array[$key];
    }
    
    $array[array_shift($keys)] = $value;
}

// Check if array has key
function array_has($array, $key) {
    if (!is_array($array)) {
        return false;
    }
    
    foreach (explode('.', $key) as $segment) {
        if (!is_array($array) || !array_key_exists($segment, $array)) {
            return false;
        }
        $array = $array[$segment];
    }
    
    return true;
}

// Pluck values from array of arrays/objects
function array_pluck($array, $value, $key = null) {
    $results = [];
    
    foreach ($array as $item) {
        $itemValue = is_object($item) ? $item->{$value} : $item[$value];
        
        if ($key === null) {
            $results[] = $itemValue;
        } else {
            $itemKey = is_object($item) ? $item->{$key} : $item[$key];
            $results[$itemKey] = $itemValue;
        }
    }
    
    return $results;
}

// Filter array and keep only specified keys
function array_only($array, $keys) {
    return array_intersect_key($array, array_flip((array) $keys));
}

// Filter array and remove specified keys
function array_except($array, $keys) {
    return array_diff_key($array, array_flip((array) $keys));
}

