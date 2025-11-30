<?php
/**
 * Router - Handle HTTP routing and middleware
 * 
 * Supports multi-domain routing with domain groups and subdomain parameters.
 */

namespace Seed\Core;

class Router {
    private $request;
    private $response;
    private $routes = [];
    private $middleware = [];
    private $groupStack = [];
    
    // Multi-domain support
    private $currentDomain = null;      // Current domain during group definition
    private $defaultRoute = null;       // Global default route
    private $domainDefaults = [];       // Per-domain default routes
    private $domainParams = [];         // Extracted subdomain parameters
    
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }
    
    /**
     * Create a domain group for domain-specific routes
     * 
     * @param string $domain Domain pattern (e.g., 'example.com', '{tenant}.app.example.com')
     * @param callable $callback Route definitions
     */
    public function domain($domain, $callback) {
        $this->currentDomain = $domain;
        call_user_func($callback, $this);
        $this->currentDomain = null;
    }
    
    /**
     * Set default route (global or per-domain if inside domain group)
     * 
     * @param string $action Controller/method (e.g., 'homeController/index')
     */
    public function setDefault($action) {
        if ($this->currentDomain !== null) {
            $this->domainDefaults[$this->currentDomain] = $action;
        } else {
            $this->defaultRoute = $action;
        }
    }
    
    /**
     * Get extracted domain parameters (e.g., tenant from {tenant}.example.com)
     * 
     * @return array
     */
    public function getDomainParams() {
        return $this->domainParams;
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
            'domain' => $this->currentDomain,  // Track domain constraint
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
        $requestDomain = $this->normalizeDomain($this->request->host());
        
        // Reset domain params
        $this->domainParams = [];
        
        // First pass: Check domain-specific routes
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            // Skip if route has no domain constraint (shared routes checked in second pass)
            if ($route['domain'] === null) {
                continue;
            }
            
            // Check if domain matches
            $domainMatch = $this->matchDomain($requestDomain, $route['domain']);
            if (!$domainMatch['match']) {
                continue;
            }
            
            // Check if URI matches
            $params = $this->matchRoute($route['uri'], $uri);
            if ($params !== false) {
                // Merge domain params (e.g., {tenant}) with route params
                $this->domainParams = $domainMatch['params'];
                $allParams = array_merge($domainMatch['params'], $params);
                return $this->handleRoute($route, $allParams);
            }
        }
        
        // Second pass: Check shared routes (no domain constraint)
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            // Only check routes without domain constraint
            if ($route['domain'] !== null) {
                continue;
            }
            
            $params = $this->matchRoute($route['uri'], $uri);
            if ($params !== false) {
                return $this->handleRoute($route, $params);
            }
        }
        
        // No explicit route found - try default route
        if ($uri === '/' || $uri === '') {
            $defaultAction = $this->getDefaultRoute($requestDomain);
            if ($defaultAction !== null) {
                return $this->executeAction($defaultAction, $this->domainParams);
            }
        }
        
        // No route found - 404
        $this->response->notFound();
    }
    
    /**
     * Normalize domain: strip www, remove port, lowercase
     */
    private function normalizeDomain($domain) {
        // Remove port
        if (strpos($domain, ':') !== false) {
            $domain = explode(':', $domain)[0];
        }
        
        // Strip www
        if (strpos($domain, 'www.') === 0) {
            $domain = substr($domain, 4);
        }
        
        return strtolower($domain);
    }
    
    /**
     * Match request domain against pattern
     * Supports exact match, wildcards (*), and parameters ({tenant})
     * 
     * @return array ['match' => bool, 'params' => array]
     */
    private function matchDomain($requestDomain, $pattern) {
        $pattern = $this->normalizeDomain($pattern);
        
        // Exact match
        if ($requestDomain === $pattern) {
            return ['match' => true, 'params' => []];
        }
        
        // Parameterized match: {tenant}.example.com
        if (strpos($pattern, '{') !== false) {
            // Convert {param} to named capture groups
            $regex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^.]+)', $pattern);
            $regex = '/^' . str_replace('.', '\.', $regex) . '$/';
            
            if (preg_match($regex, $requestDomain, $matches)) {
                // Extract only named parameters (string keys)
                $params = array_filter($matches, function($key) {
                    return is_string($key);
                }, ARRAY_FILTER_USE_KEY);
                return ['match' => true, 'params' => $params];
            }
        }
        
        // Wildcard match: *.example.com
        if (strpos($pattern, '*.') === 0) {
            $baseDomain = substr($pattern, 2); // Remove *.
            $baseLen = strlen($baseDomain);
            
            // Check if request domain ends with base domain
            if (strlen($requestDomain) > $baseLen + 1 &&
                substr($requestDomain, -$baseLen) === $baseDomain) {
                $subdomain = substr($requestDomain, 0, -(strlen($baseDomain) + 1));
                return ['match' => true, 'params' => ['subdomain' => $subdomain]];
            }
        }
        
        return ['match' => false, 'params' => []];
    }
    
    /**
     * Get the appropriate default route for the request domain
     */
    private function getDefaultRoute($requestDomain) {
        // Check domain-specific defaults first
        foreach ($this->domainDefaults as $pattern => $action) {
            $match = $this->matchDomain($requestDomain, $pattern);
            if ($match['match']) {
                $this->domainParams = $match['params'];
                return $action;
            }
        }
        
        // Fall back to global default
        return $this->defaultRoute;
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

