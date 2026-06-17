<?php

namespace App\Models;

class User {
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
        
        $result = $this->db->query("SELECT * FROM users ORDER BY id DESC");
        $users = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }
    
    public function getById($id) {
        if (!$this->db) return null;
        
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function create($data) {
        if (!$this->db) return false;
        
        $stmt = $this->db->prepare("INSERT INTO users (username, password, full_name, phone, role, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $data['username'], $data['password'], $data['full_name'], $data['phone'], $data['role'], $data['status']);
        return $stmt->execute();
    }
    
    public function update($id, $data) {
        if (!$this->db) return false;
        
        if (isset($data['password']) && !empty($data['password'])) {
            $stmt = $this->db->prepare("UPDATE users SET username = ?, password = ?, full_name = ?, phone = ?, role = ?, status = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $data['username'], $data['password'], $data['full_name'], $data['phone'], $data['role'], $data['status'], $id);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET username = ?, full_name = ?, phone = ?, role = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $data['username'], $data['full_name'], $data['phone'], $data['role'], $data['status'], $id);
        }
        return $stmt->execute();
    }
    
    public function delete($id) {
        if (!$this->db) return false;
        
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
