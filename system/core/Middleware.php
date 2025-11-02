<?php
/**
 * Middleware - Base class for middleware
 */

namespace Seed\Core;

abstract class Middleware {
    // Handle incoming request
    abstract public function handle($request, $next);
}

