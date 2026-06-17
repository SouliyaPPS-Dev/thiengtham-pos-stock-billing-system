<?php

namespace App\Models;

class PaymentMethod {
    private $db;
    
    public function __construct() {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $username = $_ENV['DB_USERNAME'] ?? 'root';
        $password = $_ENV['DB_PASSWORD'] ?? 'Admin123';
        $database = $_ENV['DB_DATABASE'] ?? 'if0_41710498_rent';
        
        $this->db = new \mysqli($host, $username, $password, $database);
        
        if ($this->db->connect_error) {
            $this->db = null;
            return;
        }
        
        $this->db->set_charset("utf8mb4");
        $this->db->query("SET time_zone = '+07:00'");
    }
    
    public function getAll() {
        if (!$this->db) return [];
        $result = $this->db->query("SELECT * FROM payment_methods ORDER BY id DESC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    public function create($name, $details = '') {
        if (!$this->db) return false;
        $name = $this->db->real_escape_string($name);
        $details = $this->db->real_escape_string($details);
        return $this->db->query("INSERT INTO payment_methods (name, details) VALUES ('$name', '$details')");
    }

    public function update($id, $name, $details = '') {
        if (!$this->db) return false;
        $id = (int)$id;
        $name = $this->db->real_escape_string($name);
        $details = $this->db->real_escape_string($details);
        return $this->db->query("UPDATE payment_methods SET name = '$name', details = '$details' WHERE id = $id");
    }
    
    public function delete($id) {
        if (!$this->db) return false;
        $id = (int)$id;
        return $this->db->query("DELETE FROM payment_methods WHERE id = $id");
    }
    
    public function __destruct() {
        if ($this->db) $this->db->close();
    }
}
