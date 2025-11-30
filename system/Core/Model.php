<?php
/**
 * Model - Base model class
 */

namespace Seed\Core;

class Model {
    protected $table;
    protected $db;
    
    public function __construct() {
        // Models can access database via db() helper or $this->db
        $this->db = db();
    }
    
    // Quick access to query
    protected function query($sql, $params = []) {
        return $this->db->query($sql, $params);
    }
    
    // Quick access to insert
    protected function insert($data) {
        return $this->db->insert($this->table, $data);
    }
    
    // Quick access to update
    protected function update($data, $where) {
        return $this->db->update($this->table, $data, $where);
    }
    
    // Quick access to delete
    protected function delete($where) {
        return $this->db->delete($this->table, $where);
    }
}

