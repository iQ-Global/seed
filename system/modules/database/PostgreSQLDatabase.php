<?php
/**
 * PostgreSQLDatabase - PostgreSQL database driver
 */

namespace Seed\Modules\Database;

class PostgreSQLDatabase extends Database {
    // Build PostgreSQL DSN
    protected function buildDsn() {
        $host = $this->config['host'] ?? 'localhost';
        $port = $this->config['port'] ?? 5432;
        $database = $this->config['database'] ?? '';
        
        return "pgsql:host={$host};port={$port};dbname={$database}";
    }
}

