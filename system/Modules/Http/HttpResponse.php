<?php
/**
 * HttpResponse - HTTP response wrapper
 */

namespace Seed\Modules\Http;

class HttpResponse {
    private $statusCode;
    private $body;
    private $error;
    
    public function __construct($statusCode, $body, $meta = []) {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->error = $meta['error'] ?? null;
    }
    
    // Get status code
    public function status() {
        return $this->statusCode;
    }
    
    // Get raw body
    public function body() {
        return $this->body;
    }
    
    // Get JSON decoded body
    public function json() {
        return json_decode($this->body, true);
    }
    
    // Get JSON as object
    public function object() {
        return json_decode($this->body);
    }
    
    // Check if successful (2xx status)
    public function ok() {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }
    
    // Check if failed
    public function failed() {
        return !$this->ok() || $this->error !== null;
    }
    
    // Get error message
    public function error() {
        return $this->error;
    }
}

