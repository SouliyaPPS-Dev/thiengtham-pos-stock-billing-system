<?php

namespace App\Models;

class Expense {
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
    
    public function getByMonth($month) {
        if (!$this->db) return [];
        
        $stmt = $this->db->prepare("
            SELECT e.*, ec.name as category_name 
            FROM expenses e 
            LEFT JOIN expense_categories ec ON e.category_id = ec.id 
            WHERE DATE_FORMAT(e.expense_date, '%Y-%m') = ? 
            ORDER BY e.expense_date DESC, e.id DESC
        ");
        $stmt->bind_param("s", $month);
        $stmt->execute();
        $result = $stmt->get_result();
        $expenses = [];
        while ($row = $result->fetch_assoc()) {
            $expenses[] = $row;
        }
        return $expenses;
    }
    
    public function getCategories() {
        if (!$this->db) return [];
        $result = $this->db->query("SELECT * FROM expense_categories ORDER BY name ASC");
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        return $categories;
    }
    
    public function create($data) {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("INSERT INTO expenses (expense_date, category_id, amount, description, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sidsi", $data['expense_date'], $data['category_id'], $data['amount'], $data['description'], $data['created_by']);
        return $stmt->execute();
    }
    
    public function update($id, $data) {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("UPDATE expenses SET expense_date = ?, category_id = ?, amount = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sidsi", $data['expense_date'], $data['category_id'], $data['amount'], $data['description'], $id);
        return $stmt->execute();
    }
    
    public function delete($id) {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("DELETE FROM expenses WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function getTotalByMonth($month) {
        if (!$this->db) return 0;
        $stmt = $this->db->prepare("SELECT SUM(amount) as total FROM expenses WHERE DATE_FORMAT(expense_date, '%Y-%m') = ?");
        $stmt->bind_param("s", $month);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    public function addCategory($name) {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("INSERT INTO expense_categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    public function updateCategory($id, $name) {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("UPDATE expense_categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        return $stmt->execute();
    }

    public function deleteCategory($id) {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("DELETE FROM expense_categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
 