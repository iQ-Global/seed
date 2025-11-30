<?php
/**
 * Seed - Main framework class
 */

namespace Seed\Core;

class Seed {
    private $router;
    private $request;
    private $response;
    
    public function __construct() {
        // Define framework paths
        $this->definePaths();
        
        // Load environment configuration
        $this->loadEnvironment();
        
        // Dispatch booting event
        Event::dispatch('seed.booting');
        
        // Initialize error handling
        $this->initializeErrorHandling();
        
        // Initialize core components
        $this->initializeCore();
        
        // Dispatch booted event
        Event::dispatch('seed.booted');
    }
    
    // Define framework path constants
    private function definePaths() {
        define('ROOT_PATH', dirname(__DIR__, 2));
        define('APP_PATH', ROOT_PATH . '/app');
        define('SYSTEM_PATH', ROOT_PATH . '/system');
        define('STORAGE_PATH', APP_PATH . '/storage');
        define('VIEW_PATH', APP_PATH . '/views');
    }
    
    // Load environment variables from .env file
    private function loadEnvironment() {
        $envFile = ROOT_PATH . '/.env';
        
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($lines as $line) {
                // Skip comments
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                
                // Parse key=value
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    
                    // Set environment variable
                    if (!array_key_exists($key, $_ENV)) {
                        $_ENV[$key] = $value;
                        putenv("$key=$value");
                    }
                }
            }
        }
    }
    
    // Initialize error and exception handling
    private function initializeErrorHandling() {
        $debug = env('APP_DEBUG', false);
        
        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
        
        // Set custom error and exception handlers
        ErrorHandler::init();
    }
    
    // Initialize core components
    private function initializeCore() {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }
    
    // Run the application
    public function run() {
        // Dispatch request received event
        Event::dispatch('request.received', ['request' => $this->request]);
        
        // Load routes
        $routesFile = APP_PATH . '/routes.php';
        
        if (file_exists($routesFile)) {
            $router = $this->router;
            require_once $routesFile;
        }
        
        // Dispatch the request
        $this->router->dispatch();
    }
    
    // Get router instance
    public function router() {
        return $this->router;
    }
}

