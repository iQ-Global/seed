<?php
/**
 * ErrorHandler - Custom error and exception handling
 */

namespace Seed\Core;

class ErrorHandler {
    private static $debug = false;
    
    // Initialize error handling
    public static function init() {
        self::$debug = env('APP_DEBUG', false);
        
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }
    
    // Handle PHP errors
    public static function handleError($level, $message, $file = '', $line = 0) {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }
    
    // Handle uncaught exceptions
    public static function handleException($exception) {
        http_response_code(500);
        
        if (self::$debug) {
            self::displayDebugError($exception);
        } else {
            self::displayProductionError($exception);
        }
        
        exit(1);
    }
    
    // Handle fatal errors
    public static function handleShutdown() {
        $error = error_get_last();
        
        if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            self::handleException(
                new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line'])
            );
        }
    }
    
    // Display error in debug mode
    private static function displayDebugError($exception) {
        $errorView = SYSTEM_PATH . '/views/errors/debug.php';
        
        if (file_exists($errorView)) {
            $error = $exception;
            include $errorView;
        } else {
            echo "<h1>Error</h1>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($exception->getFile()) . "</p>";
            echo "<p><strong>Line:</strong> " . $exception->getLine() . "</p>";
            echo "<pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
        }
    }
    
    // Display error in production mode
    private static function displayProductionError($exception) {
        $errorView = VIEW_PATH . '/errors/500.php';
        
        if (!file_exists($errorView)) {
            $errorView = SYSTEM_PATH . '/views/errors/500.php';
        }
        
        if (file_exists($errorView)) {
            include $errorView;
        } else {
            echo "<h1>500 - Internal Server Error</h1>";
            echo "<p>Something went wrong. Please try again later.</p>";
        }
    }
}

