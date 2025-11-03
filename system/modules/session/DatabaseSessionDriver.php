<?php
/**
 * DatabaseSessionDriver - Database-backed session storage
 * 
 * Note: PHP 8 deprecation warnings are expected due to SessionHandlerInterface type hints.
 * We can't add type hints as we support PHP 7.0+. Warnings are harmless.
 */

namespace Seed\Modules\Session;

class DatabaseSessionDriver implements \SessionHandlerInterface {
    private $db;
    private $table;
    private $lifetime;
    
    public function __construct() {
        $this->table = env('SESSION_TABLE', 'sessions');
        $this->lifetime = env('SESSION_LIFETIME', 120) * 60;
    }
    
    // Open session
    public function open($path, $name) {
        $this->db = db();
        return true;
    }
    
    // Close session
    public function close() {
        return true;
    }
    
    // Read session data
    public function read($id) {
        $result = $this->db->queryOne(
            "SELECT payload FROM {$this->table} WHERE id = ? AND last_activity > ?",
            [$id, time() - $this->lifetime]
        );
        
        return $result ? $result->payload : '';
    }
    
    // Write session data
    public function write($id, $data) {
        $payload = [
            'id' => $id,
            'user_id' => $_SESSION['auth_user_id'] ?? null,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'payload' => $data,
            'last_activity' => time()
        ];
        
        // Check if session exists
        $exists = $this->db->queryOne(
            "SELECT id FROM {$this->table} WHERE id = ?",
            [$id]
        );
        
        if ($exists) {
            // Update existing session
            $this->db->update($this->table, $payload, ['id' => $id]);
        } else {
            // Insert new session
            $this->db->insert($this->table, $payload);
        }
        
        return true;
    }
    
    // Destroy session
    public function destroy($id) {
        $this->db->delete($this->table, ['id' => $id]);
        return true;
    }
    
    // Garbage collection
    public function gc($max_lifetime) {
        $expired = time() - $max_lifetime;
        $this->db->raw(
            "DELETE FROM {$this->table} WHERE last_activity < ?",
            [$expired]
        );
        return true;
    }
}

