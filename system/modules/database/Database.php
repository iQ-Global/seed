<?php
/**
 * Database - Base database class with PDO
 */

namespace Seed\Modules\Database;

use PDO;
use PDOException;

class Database {
    protected $connection;
    protected $config = [];
    
    public function __construct($config = []) {
        $this->config = $config;
    }
    
    // Connect to database
    public function connect() {
        try {
            $dsn = $this->buildDsn();
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO(
                $dsn,
                $this->config['username'] ?? '',
                $this->config['password'] ?? '',
                $options
            );
            
            return $this->connection;
        } catch (PDOException $e) {
            log_error('Database connection failed', ['error' => $e->getMessage()]);
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }
    
    // Build DSN string (override in drivers)
    protected function buildDsn() {
        return '';
    }
    
    // Get connection (lazy connect)
    protected function getConnection() {
        if (!$this->connection) {
            $this->connect();
        }
        return $this->connection;
    }
    
    // Execute query with parameters
    public function query($sql, $params = []) {
        try {
            $conn = $this->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            log_error('Query failed', ['sql' => $sql, 'error' => $e->getMessage()]);
            throw new \Exception('Query failed: ' . $e->getMessage());
        }
    }
    
    // Execute query and return single row
    public function queryOne($sql, $params = []) {
        $results = $this->query($sql, $params);
        return $results[0] ?? null;
    }
    
    // Insert row
    public function insert($table, $data) {
        $keys = array_keys($data);
        $values = array_values($data);
        
        $columns = implode(', ', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        try {
            $conn = $this->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($values);
            
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            log_error('Insert failed', ['table' => $table, 'error' => $e->getMessage()]);
            throw new \Exception('Insert failed: ' . $e->getMessage());
        }
    }
    
    // Bulk insert rows
    public function bulkInsert($table, $data) {
        if (empty($data)) {
            return 0;
        }
        
        $keys = array_keys($data[0]);
        $columns = implode(', ', $keys);
        $placeholders = '(' . implode(', ', array_fill(0, count($keys), '?')) . ')';
        
        $allPlaceholders = [];
        $allValues = [];
        
        foreach ($data as $row) {
            $allPlaceholders[] = $placeholders;
            $allValues = array_merge($allValues, array_values($row));
        }
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES " . implode(', ', $allPlaceholders);
        
        try {
            $conn = $this->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($allValues);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            log_error('Bulk insert failed', ['table' => $table, 'error' => $e->getMessage()]);
            throw new \Exception('Bulk insert failed: ' . $e->getMessage());
        }
    }
    
    // Update rows
    public function update($table, $data, $where) {
        $sets = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $sets[] = "{$key} = ?";
            $values[] = $value;
        }
        
        $whereClauses = [];
        foreach ($where as $key => $value) {
            $whereClauses[] = "{$key} = ?";
            $values[] = $value;
        }
        
        $sql = "UPDATE {$table} SET " . implode(', ', $sets) . " WHERE " . implode(' AND ', $whereClauses);
        
        try {
            $conn = $this->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($values);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            log_error('Update failed', ['table' => $table, 'error' => $e->getMessage()]);
            throw new \Exception('Update failed: ' . $e->getMessage());
        }
    }
    
    // Delete rows
    public function delete($table, $where) {
        $whereClauses = [];
        $values = [];
        
        foreach ($where as $key => $value) {
            $whereClauses[] = "{$key} = ?";
            $values[] = $value;
        }
        
        $sql = "DELETE FROM {$table} WHERE " . implode(' AND ', $whereClauses);
        
        try {
            $conn = $this->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($values);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            log_error('Delete failed', ['table' => $table, 'error' => $e->getMessage()]);
            throw new \Exception('Delete failed: ' . $e->getMessage());
        }
    }
    
    // Execute raw query (explicit opt-in)
    public function raw($sql, $params = []) {
        try {
            $conn = $this->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            
            // Return different things based on query type
            $sqlUpper = strtoupper(trim($sql));
            if (strpos($sqlUpper, 'SELECT') === 0 || strpos($sqlUpper, 'SHOW') === 0) {
                return $stmt->fetchAll();
            }
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            log_error('Raw query failed', ['sql' => $sql, 'error' => $e->getMessage()]);
            throw new \Exception('Raw query failed: ' . $e->getMessage());
        }
    }
    
    // Load and execute SQL file
    public function loadSQL($filePath) {
        if (!file_exists($filePath)) {
            throw new \Exception("SQL file not found: {$filePath}");
        }
        
        $sql = file_get_contents($filePath);
        
        try {
            $conn = $this->getConnection();
            $conn->exec($sql);
            log_info('SQL file loaded', ['file' => $filePath]);
            return true;
        } catch (PDOException $e) {
            log_error('SQL file load failed', ['file' => $filePath, 'error' => $e->getMessage()]);
            throw new \Exception('SQL file load failed: ' . $e->getMessage());
        }
    }
    
    // Begin transaction
    public function beginTransaction() {
        $this->getConnection()->beginTransaction();
    }
    
    // Commit transaction
    public function commit() {
        $this->getConnection()->commit();
    }
    
    // Rollback transaction
    public function rollback() {
        $this->getConnection()->rollBack();
    }
}

