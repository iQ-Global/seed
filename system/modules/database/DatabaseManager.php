<?php
/**
 * DatabaseManager - Manage database connections
 */

namespace Seed\Modules\Database;

class DatabaseManager {
    private static $instances = [];
    
    // Get database instance
    public static function getInstance($driver = null) {
        $driver = $driver ?? env('DB_CONNECTION', 'mysql');
        
        if (!isset(self::$instances[$driver])) {
            self::$instances[$driver] = self::createConnection($driver);
        }
        
        return self::$instances[$driver];
    }
    
    // Create database connection
    private static function createConnection($driver) {
        // MongoDB uses different config
        if ($driver === 'mongodb' || $driver === 'mongo') {
            $config = [
                'host' => env('MONGODB_HOST', 'localhost'),
                'port' => env('MONGODB_PORT', '27017'),
                'database' => env('MONGODB_DATABASE', 'seed'),
                'username' => env('MONGODB_USERNAME', ''),
                'password' => env('MONGODB_PASSWORD', ''),
            ];
            return new MongoDBDatabase($config);
        }
        
        // SQL databases config
        $config = [
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', $driver === 'pgsql' ? 5432 : 3306),
            'database' => env('DB_DATABASE', ''),
            'username' => env('DB_USERNAME', ''),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
        ];
        
        switch ($driver) {
            case 'mysql':
                return new MySQLDatabase($config);
            case 'pgsql':
            case 'postgresql':
                return new PostgreSQLDatabase($config);
            default:
                throw new \Exception("Unsupported database driver: {$driver}");
        }
    }
}

