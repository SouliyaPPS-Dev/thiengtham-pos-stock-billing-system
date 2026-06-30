<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Inventory {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            ORDER BY p.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getCategories() {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $sql = "INSERT INTO products (code, name, category_id, size, bust, waist, hips, color, rental_price, stock, image, status) 
                VALUES (:code, :name, :category_id, :size, :bust, :waist, :hips, :color, :rental_price, :stock, :image, :status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateStock($id, $stock) {
        $stmt = $this->db->prepare("UPDATE products SET stock = ? WHERE id = ?");
        return $stmt->execute([$stock, $id]);
    }
    
    public function update($id, $data) {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
 