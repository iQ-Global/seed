<?php
/**
 * Router - Handle HTTP routing and middleware
 */

namespace Seed\Core;

class Router {
    private $request;
    private $response;
    private $routes = [];
    private $middleware = [];
    private $groupStack = [];
    
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }
    
    // Register GET route
    public function get($uri, $action) {
        return $this->addRoute('GET', $uri, $action);
    }
    
    // Register POST route
    public function post($uri, $action) {
        return $this->addRoute('POST', $uri, $action);
    }
    
    // Register PUT route
    public function put($uri, $action) {
        return $this->addRoute('PUT', $uri, $action);
    }
    
    // Register DELETE route
    public function delete($uri, $action) {
        return $this->addRoute('DELETE', $uri, $action);
    }
    
    // Register PATCH route
    public function patch($uri, $action) {
        return $this->addRoute('PATCH', $uri, $action);
    }
    
    // Add route to collection
    private function addRoute($method, $uri, $action) {
        $uri = $this->applyGroupPrefix($uri);
        
        $route = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middleware' => $this->getGroupMiddleware(),
        ];
        
        $this->routes[] = $route;
        
        // Return route object for chaining
        return new Route($route, count($this->routes) - 1, $this);
    }
    
    // Apply group prefix to URI
    private function applyGroupPrefix($uri) {
        if (!empty($this->groupStack)) {
            $prefix = '';
            foreach ($this->groupStack as $group) {
                if (isset($group['prefix'])) {
                    $prefix .= '/' . trim($group['prefix'], '/');
                }
            }
            $uri = $prefix . '/' . trim($uri, '/');
        }
        
        return '/' . trim($uri, '/');
    }
    
    // Get middleware from group stack
    private function getGroupMiddleware() {
        $middleware = [];
        
        foreach ($this->groupStack as $group) {
            if (isset($group['middleware'])) {
                $groupMiddleware = is_array($group['middleware']) 
                    ? $group['middleware'] 
                    : [$group['middleware']];
                
                $middleware = array_merge($middleware, $groupMiddleware);
            }
        }
        
        return $middleware;
    }
    
    // Create route group
    public function group($attributes, $callback) {
        $this->groupStack[] = $attributes;
        
        call_user_func($callback, $this);
        
        array_pop($this->groupStack);
    }
    
    // Update route middleware
    public function updateRouteMiddleware($index, $middleware) {
        if (isset($this->routes[$index])) {
            $current = $this->routes[$index]['middleware'];
            $new = is_array($middleware) ? $middleware : [$middleware];
            $this->routes[$index]['middleware'] = array_merge($current, $new);
        }
    }
    
    // Dispatch request to matching route
    public function dispatch() {
        $method = $this->request->method();
        $uri = $this->request->uri();
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $params = $this->matchRoute($route['uri'], $uri);
                
                if ($params !== false) {
                    return $this->handleRoute($route, $params);
                }
            }
        }
        
        // No route found - 404
        $this->response->notFound();
    }
    
    // Match route pattern against URI
    private function matchRoute($pattern, $uri) {
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Remove full match
            return $matches;
        }
        
        return false;
    }
    
    // Handle matched route
    private function handleRoute($route, $params) {
        // Run middleware chain
        if (!empty($route['middleware'])) {
            foreach ($route['middleware'] as $middlewareName) {
                $result = $this->runMiddleware($middlewareName);
                
                if ($result !== null) {
                    return $result; // Middleware stopped request
                }
            }
        }
        
        // Execute controller action
        return $this->executeAction($route['action'], $params);
    }
    
    // Run middleware
    private function runMiddleware($name) {
        // Check if middleware class exists
        $className = "\\Seed\\Core\\Middleware\\{$name}";
        
        if (!class_exists($className)) {
            // Try app middleware
            $className = "App\\Middleware\\{$name}";
        }
        
        if (class_exists($className)) {
            $middleware = new $className();
            return $middleware->handle($this->request, function() {
                return null; // Continue to next middleware
            });
        }
        
        return null;
    }
    
    // Execute controller action
    private function executeAction($action, $params) {
        if (is_string($action)) {
            // Parse controller/method format
            list($controller, $method) = explode('/', $action);
            
            // Build controller class name
            $controllerClass = "App\\Controllers\\{$controller}";
            
            if (!class_exists($controllerClass)) {
                $this->response->error("Controller not found: {$controller}");
                return;
            }
            
            $controllerInstance = new $controllerClass();
            
            if (!method_exists($controllerInstance, $method)) {
                $this->response->error("Method not found: {$method}");
                return;
            }
            
            // Call controller method with parameters
            return call_user_func_array([$controllerInstance, $method], $params);
        }
        
        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }
    }
}

