<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class CustomerType {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM customer_types ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $sql = "INSERT INTO customer_types (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function update($id, $data) {
        $sql = "UPDATE customer_types SET name = :name WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function delete($id) {
        // Check if there are customers with this type before deleting
        // We'll check by name since customer_type is a string in customers table
        $stmt = $this->db->prepare("SELECT name FROM customer_types WHERE id = ?");
        $stmt->execute([$id]);
        $typeName = $stmt->fetchColumn();
        
        if ($typeName) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM customers WHERE customer_type = ?");
            $stmt->execute([$typeName]);
            if ($stmt->fetchColumn() > 0) {
                return false; 
            }
        }
         
        $stmt = $this->db->prepare("DELETE FROM customer_types WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
