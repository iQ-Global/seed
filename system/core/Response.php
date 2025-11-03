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
    
    // Download file (as attachment)
    public function download($file, $name = null) {
        if (!file_exists($file)) {
            return $this->notFound('File not found');
        }
        
        $name = $name ?? basename($file);
        $mimeType = $this->getMimeType($file);
        
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header('Content-Length: ' . filesize($file));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        readfile($file);
        exit;
    }
    
    // Display file inline (e.g., PDF in browser)
    public function file($file, $name = null) {
        if (!file_exists($file)) {
            return $this->notFound('File not found');
        }
        
        $name = $name ?? basename($file);
        $mimeType = $this->getMimeType($file);
        
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . $name . '"');
        header('Content-Length: ' . filesize($file));
        
        readfile($file);
        exit;
    }
    
    // Stream large file (memory efficient)
    public function stream($file, $name = null) {
        if (!file_exists($file)) {
            return $this->notFound('File not found');
        }
        
        $name = $name ?? basename($file);
        $mimeType = $this->getMimeType($file);
        $filesize = filesize($file);
        
        // Handle range requests for video streaming
        $rangeHeader = $_SERVER['HTTP_RANGE'] ?? null;
        
        if ($rangeHeader) {
            $this->streamRange($file, $filesize, $mimeType, $rangeHeader);
        } else {
            $this->streamFull($file, $filesize, $mimeType, $name);
        }
    }
    
    // Stream full file
    private function streamFull($file, $filesize, $mimeType, $name) {
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . $filesize);
        header('Content-Disposition: inline; filename="' . $name . '"');
        header('Accept-Ranges: bytes');
        
        $handle = fopen($file, 'rb');
        while (!feof($handle)) {
            echo fread($handle, 8192);
            flush();
        }
        fclose($handle);
        exit;
    }
    
    // Stream file range (for video seeking)
    private function streamRange($file, $filesize, $mimeType, $rangeHeader) {
        list($unit, $range) = explode('=', $rangeHeader, 2);
        list($start, $end) = explode('-', $range);
        
        $start = (int) $start;
        $end = $end ? (int) $end : $filesize - 1;
        $length = $end - $start + 1;
        
        header('HTTP/1.1 206 Partial Content');
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . $length);
        header('Content-Range: bytes ' . $start . '-' . $end . '/' . $filesize);
        header('Accept-Ranges: bytes');
        
        $handle = fopen($file, 'rb');
        fseek($handle, $start);
        
        $remaining = $length;
        while ($remaining > 0 && !feof($handle)) {
            $read = min(8192, $remaining);
            echo fread($handle, $read);
            $remaining -= $read;
            flush();
        }
        
        fclose($handle);
        exit;
    }
    
    // Get MIME type for file
    private function getMimeType($file) {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'zip' => 'application/zip',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'mp4' => 'video/mp4',
            'mp3' => 'audio/mpeg',
            'txt' => 'text/plain',
            'html' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
        ];
        
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}


