<?php
/**
 * Response - HTTP response handling
 */

namespace Seed\Core;

class Response {
    private $statusCode = 200;
    private $headers = [];
    private $content = '';
    
    // Set status code
    public function status($code) {
        $this->statusCode = $code;
        return $this;
    }
    
    // Set header
    public function header($key, $value) {
        $this->headers[$key] = $value;
        return $this;
    }
    
    // Send response
    public function send($content = null, $status = null) {
        if ($status !== null) {
            $this->statusCode = $status;
        }
        
        if ($content !== null) {
            $this->content = $content;
        }
        
        // Set status code
        http_response_code($this->statusCode);
        
        // Send headers
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
        
        // Send content
        echo $this->content;
    }
    
    // Send JSON response
    public function json($data, $status = 200) {
        $this->header('Content-Type', 'application/json');
        $this->send(json_encode($data), $status);
    }
    
    // Redirect to URL
    public function redirect($url, $status = 302) {
        header("Location: {$url}", true, $status);
        exit;
    }
    
    // Send 404 response
    public function notFound($message = 'Page Not Found') {
        $this->status(404);
        
        // Try to load error view
        $errorView = VIEW_PATH . '/errors/404.php';
        
        if (!file_exists($errorView)) {
            $errorView = SYSTEM_PATH . '/views/errors/404.php';
        }
        
        if (file_exists($errorView)) {
            ob_start();
            include $errorView;
            $content = ob_get_clean();
            $this->send($content);
        } else {
            $this->send("<h1>404 - Not Found</h1><p>{$message}</p>");
        }
    }
    
    // Send error response
    public function error($message = 'Internal Server Error', $status = 500) {
        $this->status($status);
        
        // Try to load error view
        $errorView = VIEW_PATH . "/errors/{$status}.php";
        
        if (!file_exists($errorView)) {
            $errorView = SYSTEM_PATH . "/views/errors/{$status}.php";
        }
        
        if (file_exists($errorView)) {
            ob_start();
            $error = $message;
            include $errorView;
            $content = ob_get_clean();
            $this->send($content);
        } else {
            $this->send("<h1>{$status} - Error</h1><p>{$message}</p>");
        }
    }
}

