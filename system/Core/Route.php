<?php
/**
 * Route - Represents a single route for method chaining
 */

namespace Seed\Core;

class Route {
    private $route;
    private $index;
    private $router;
    
    public function __construct($route, $index, $router) {
        $this->route = $route;
        $this->index = $index;
        $this->router = $router;
    }
    
    // Add middleware to this route
    public function middleware($middleware) {
        $this->router->updateRouteMiddleware($this->index, $middleware);
        return $this;
    }
}

