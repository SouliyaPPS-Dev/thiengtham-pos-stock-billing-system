<?php

namespace App\Models;

use App\Core\Database;

class Customer {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getCustomers($where = "WHERE 1=1", $params = [], $limit = 10, $offset = 0) {
        $sql = "SELECT c.*, u.full_name as created_by_name 
                FROM customers c 
                LEFT JOIN users u ON c.created_by = u.id 
                $where 
                ORDER BY c.created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getTotalCustomers($where = "WHERE 1=1", $params = []) {
        $sql = "SELECT COUNT(*) as total FROM customers c $where";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    public function getCustomerById($id) {
        $sql = "SELECT c.*, u.full_name as created_by_name 
                FROM customers c 
                LEFT JOIN users u ON c.created_by = u.id 
                WHERE c.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function createCustomer($data) {
        $sql = "INSERT INTO customers (fullname, phone, email, id_card_no, address, province, district, village, 
                occupation, gender, date_of_birth, contact_person, contact_phone, 
                customer_type, status, notes, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['fullname'], $data['phone'], $data['email'], $data['id_card_no'],
            $data['address'], $data['province'], $data['district'], $data['village'],
            $data['occupation'], $data['gender'], $data['date_of_birth'],
            $data['contact_person'], $data['contact_phone'], $data['customer_type'],
            $data['status'], $data['notes'], $data['created_by']
        ]);
    }
    
    public function updateCustomer($id, $data) {
        $sql = "UPDATE customers SET fullname=?, phone=?, email=?, id_card_no=?, address=?, province=?, 
                district=?, village=?, occupation=?, gender=?, date_of_birth=?, 
                contact_person=?, contact_phone=?, customer_type=?, status=?, notes=? 
                WHERE id=?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['fullname'], $data['phone'], $data['email'], $data['id_card_no'],
            $data['address'], $data['province'], $data['district'], $data['village'],
            $data['occupation'], $data['gender'], $data['date_of_birth'],
            $data['contact_person'], $data['contact_phone'], $data['customer_type'],
            $data['status'], $data['notes'], $id
        ]);
    }
    
    public function deleteCustomer($id) {
        $sql = "DELETE FROM customers WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function validateCustomer($data, $excludeId = null) {
        $errors = [];
        
        if (empty($data['fullname'])) {
            $errors[] = 'Please enter customer name';
        }
        
        if (empty($data['phone'])) {
            $errors[] = 'Please enter phone number';
        } elseif (!preg_match('/^[0-9]{8,15}$/', $data['phone'])) {
            $errors[] = 'Phone must be 8-15 digits';
        }
        
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        // Check for duplicate phone
        $sql = "SELECT id FROM customers WHERE phone = ?";
        $params = [$data['phone']];
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors[] = 'Phone number already exists';
        }
        
        return $errors;
    }
    
    public function getCustomerRentalHistory($customerId) {
        $sql = "SELECT r.*, r.status as rental_status
                FROM rentals r
                WHERE r.customer_id = ?
                ORDER BY r.created_at DESC
                LIMIT 20";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getCustomerNotes($customerId) {
        $sql = "SELECT cn.*, u.full_name as author_name
                FROM customer_notes cn
                JOIN users u ON cn.created_by = u.id
                WHERE cn.customer_id = ?
                ORDER BY cn.created_at DESC
                LIMIT 20";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>