<?php
/**
 * Event helper functions
 */

use Seed\Core\Event;

// Dispatch an event
function event($name, $data = []) {
    return Event::dispatch($name, $data);
}

// Register event listener
function listen($event, $callback) {
    Event::listen($event, $callback);
}

// Check if event has listeners
function has_listeners($event) {
    return Event::hasListeners($event);
}

