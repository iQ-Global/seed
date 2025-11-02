<?php
/**
 * MySQLDatabase - MySQL database driver
 */

namespace Seed\Modules\Database;

class MySQLDatabase extends Database {
    // Build MySQL DSN
    protected function buildDsn() {
        $host = $this->config['host'] ?? 'localhost';
        $port = $this->config['port'] ?? 3306;
        $database = $this->config['database'] ?? '';
        $charset = $this->config['charset'] ?? 'utf8mb4';
        
        return "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";
    }
}

