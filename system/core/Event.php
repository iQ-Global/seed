<?php
/**
 * Event - Simple event system
 */

namespace Seed\Core;

class Event {
    private static $listeners = [];
    
    // Register event listener
    public static function listen($event, $callback) {
        if (!isset(self::$listeners[$event])) {
            self::$listeners[$event] = [];
        }
        
        self::$listeners[$event][] = $callback;
    }
    
    // Dispatch event
    public static function dispatch($event, $data = []) {
        if (!isset(self::$listeners[$event])) {
            return [];
        }
        
        $responses = [];
        
        foreach (self::$listeners[$event] as $listener) {
            $responses[] = call_user_func($listener, $data);
        }
        
        return $responses;
    }
    
    // Check if event has listeners
    public static function hasListeners($event) {
        return isset(self::$listeners[$event]) && count(self::$listeners[$event]) > 0;
    }
    
    // Remove all listeners for an event
    public static function forget($event) {
        unset(self::$listeners[$event]);
    }
    
    // Remove all listeners
    public static function flush() {
        self::$listeners = [];
    }
    
    // Get all listeners for an event
    public static function getListeners($event) {
        return self::$listeners[$event] ?? [];
    }
}

