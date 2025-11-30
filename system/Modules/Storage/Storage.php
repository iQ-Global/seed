<?php
/**
 * Storage - File and directory operations
 */

namespace Seed\Modules\Storage;

class Storage {
    private $basePath;
    private $disk = 'private';
    
    public function __construct($disk = 'private') {
        $this->disk = $disk;
        $this->basePath = $this->getDiskPath($disk);
        
        // Ensure storage directory exists
        if (!is_dir($this->basePath)) {
            mkdir($this->basePath, 0755, true);
        }
    }
    
    // Get path for disk type
    private function getDiskPath($disk) {
        switch ($disk) {
            case 'public':
                return STORAGE_PATH . '/public';
            case 'uploads':
                return STORAGE_PATH . '/uploads';
            case 'cache':
                return STORAGE_PATH . '/cache';
            case 'logs':
                return STORAGE_PATH . '/logs';
            default:
                return STORAGE_PATH;
        }
    }
    
    // Put contents into file
    public function put($path, $contents) {
        $fullPath = $this->basePath . '/' . ltrim($path, '/');
        $directory = dirname($fullPath);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        return file_put_contents($fullPath, $contents) !== false;
    }
    
    // Get file contents
    public function get($path, $default = null) {
        $fullPath = $this->basePath . '/' . ltrim($path, '/');
        
        if (!file_exists($fullPath)) {
            return $default;
        }
        
        return file_get_contents($fullPath);
    }
    
    // Check if file exists
    public function exists($path) {
        $fullPath = $this->basePath . '/' . ltrim($path, '/');
        return file_exists($fullPath);
    }
    
    // Delete file
    public function delete($path) {
        $fullPath = $this->basePath . '/' . ltrim($path, '/');
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        
        return false;
    }
    
    // Append to file
    public function append($path, $contents) {
        $fullPath = $this->basePath . '/' . ltrim($path, '/');
        $directory = dirname($fullPath);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        return file_put_contents($fullPath, $contents, FILE_APPEND) !== false;
    }
    
    // Get file size
    public function size($path) {
        $fullPath = $this->basePath . '/' . ltrim($path, '/');
        
        if (file_exists($fullPath)) {
            return filesize($fullPath);
        }
        
        return 0;
    }
    
    // Get last modified time
    public function lastModified($path) {
        $fullPath = $this->basePath . '/' . ltrim($path, '/');
        
        if (file_exists($fullPath)) {
            return filemtime($fullPath);
        }
        
        return null;
    }
    
    // Copy file
    public function copy($from, $to) {
        $fromPath = $this->basePath . '/' . ltrim($from, '/');
        $toPath = $this->basePath . '/' . ltrim($to, '/');
        $directory = dirname($toPath);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        return copy($fromPath, $toPath);
    }
    
    // Move file
    public function move($from, $to) {
        $fromPath = $this->basePath . '/' . ltrim($from, '/');
        $toPath = $this->basePath . '/' . ltrim($to, '/');
        $directory = dirname($toPath);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        return rename($fromPath, $toPath);
    }
    
    // Create directory
    public function makeDirectory($path, $recursive = true) {
        $fullPath = $this->basePath . '/' . ltrim($path, '/');
        
        if (!is_dir($fullPath)) {
            return mkdir($fullPath, 0755, $recursive);
        }
        
        return true;
    }
    
    // Delete directory
    public function deleteDirectory($path) {
        $fullPath = $this->basePath . '/' . ltrim($path, '/');
        
        if (!is_dir($fullPath)) {
            return false;
        }
        
        return $this->removeDirectory($fullPath);
    }
    
    // Recursively remove directory
    private function removeDirectory($dir) {
        if (!is_dir($dir)) {
            return false;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        
        return rmdir($dir);
    }
    
    // List files in directory
    public function files($directory = '', $recursive = false) {
        $fullPath = $this->basePath . '/' . ltrim($directory, '/');
        
        if (!is_dir($fullPath)) {
            return [];
        }
        
        if ($recursive) {
            return $this->allFiles($fullPath);
        }
        
        $files = [];
        foreach (scandir($fullPath) as $file) {
            if ($file !== '.' && $file !== '..' && is_file($fullPath . '/' . $file)) {
                $files[] = $file;
            }
        }
        
        return $files;
    }
    
    // Get all files recursively
    private function allFiles($directory) {
        $result = [];
        $files = scandir($directory);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $path = $directory . '/' . $file;
            
            if (is_file($path)) {
                $result[] = str_replace($this->basePath . '/', '', $path);
            } elseif (is_dir($path)) {
                $result = array_merge($result, $this->allFiles($path));
            }
        }
        
        return $result;
    }
    
    // Get URL for public file
    public function url($path) {
        if ($this->disk !== 'public') {
            return null;
        }
        
        $baseUrl = env('APP_URL', 'http://localhost');
        return rtrim($baseUrl, '/') . '/storage/public/' . ltrim($path, '/');
    }
    
    // Get full path
    public function path($path = '') {
        return $this->basePath . '/' . ltrim($path, '/');
    }
}

