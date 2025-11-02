<?php
/**
 * Request - HTTP request handling
 */

namespace Seed\Core;

class Request {
    private $method;
    private $uri;
    private $get;
    private $post;
    private $files;
    private $headers;
    
    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $this->parseUri();
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->headers = $this->parseHeaders();
    }
    
    // Parse request URI
    private function parseUri() {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove query string
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        
        // Remove index.php if present
        $uri = str_replace('/index.php', '', $uri);
        
        return '/' . trim($uri, '/');
    }
    
    // Parse request headers
    private function parseHeaders() {
        $headers = [];
        
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace('HTTP_', '', $key);
                $header = str_replace('_', '-', $header);
                $header = strtolower($header);
                $headers[$header] = $value;
            }
        }
        
        return $headers;
    }
    
    // Get request method
    public function method() {
        return $this->method;
    }
    
    // Get request URI
    public function uri() {
        return $this->uri;
    }
    
    // Get GET parameter
    public function get($key = null, $default = null) {
        if ($key === null) {
            return $this->get;
        }
        
        return $this->get[$key] ?? $default;
    }
    
    // Get POST parameter
    public function post($key = null, $default = null) {
        if ($key === null) {
            return $this->post;
        }
        
        return $this->post[$key] ?? $default;
    }
    
    // Get input from GET or POST
    public function input($key, $default = null) {
        return $this->post($key) ?? $this->get($key) ?? $default;
    }
    
    // Get uploaded file
    public function file($key) {
        return $this->files[$key] ?? null;
    }
    
    // Get header
    public function header($key, $default = null) {
        $key = strtolower($key);
        return $this->headers[$key] ?? $default;
    }
    
    // Get all headers
    public function headers() {
        return $this->headers;
    }
    
    // Check if request is AJAX
    public function isAjax() {
        return strtolower($this->header('x-requested-with', '')) === 'xmlhttprequest';
    }
    
    // Check if request is JSON
    public function isJson() {
        return strpos($this->header('content-type', ''), 'application/json') !== false;
    }
    
    // Get JSON body
    public function json() {
        $body = file_get_contents('php://input');
        return json_decode($body, true);
    }
}

