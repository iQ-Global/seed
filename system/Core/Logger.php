<?php
/**
 * Logger - File-based logging
 */

namespace Seed\Core;

class Logger {
    private static $logPath;
    
    // Initialize logger
    public static function init() {
        self::$logPath = STORAGE_PATH . '/logs';
        
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0755, true);
        }
    }
    
    // Log info message
    public static function info($message, $context = []) {
        self::log('INFO', $message, $context);
    }
    
    // Log error message
    public static function error($message, $context = []) {
        self::log('ERROR', $message, $context);
    }
    
    // Log debug message
    public static function debug($message, $context = []) {
        self::log('DEBUG', $message, $context);
    }
    
    // Write log entry
    private static function log($level, $message, $context = []) {
        if (!self::$logPath) {
            self::init();
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        
        $logFile = self::$logPath . '/' . date('Y-m-d') . '.log';
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}

