<?php
/**
 * User Model - Example model
 */

namespace App\Models;

use Seed\Core\Model;

class userModel extends Model {
    protected $table = 'users';
    
    // Get all active users
    public function getActive() {
        return $this->query("SELECT * FROM {$this->table} WHERE status = ?", ['active']);
    }
    
    // Get user by ID
    public function find($id) {
        $result = $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
        return $result[0] ?? null;
    }
    
    // Create new user
    public function create($data) {
        return $this->insert($data);
    }
    
    // Update user
    public function updateUser($id, $data) {
        return $this->update($data, ['id' => $id]);
    }
}

