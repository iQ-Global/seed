<?php
/**
 * DatabaseSessionDriver - Database-backed session storage
 * 
 * Uses #[\ReturnTypeWillChange] attribute for PHP 7/8 compatibility.
 * PHP 7.x ignores attributes (treated as comments), PHP 8.0+ recognizes
 * them and suppresses the SessionHandlerInterface deprecation warnings.
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
    
    #[\ReturnTypeWillChange]
    public function open($path, $name) {
        $this->db = db();
        return true;
    }
    
    #[\ReturnTypeWillChange]
    public function close() {
        return true;
    }
    
    #[\ReturnTypeWillChange]
    public function read($id) {
        $result = $this->db->queryOne(
            "SELECT payload FROM {$this->table} WHERE id = ? AND last_activity > ?",
            [$id, time() - $this->lifetime]
        );
        
        return $result ? $result->payload : '';
    }
    
    #[\ReturnTypeWillChange]
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
    
    #[\ReturnTypeWillChange]
    public function destroy($id) {
        $this->db->delete($this->table, ['id' => $id]);
        return true;
    }
    
    #[\ReturnTypeWillChange]
    public function gc($max_lifetime) {
        $expired = time() - $max_lifetime;
        $this->db->raw(
            "DELETE FROM {$this->table} WHERE last_activity < ?",
            [$expired]
        );
        return true;
    }
}
