<?php

namespace App\Models;

class Setting {
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
    
    public function get() {
        if (!$this->db) {
            return null;
        }
        
        $result = $this->db->query("SELECT * FROM settings LIMIT 1");
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function update($data) {
        if (!$this->db) {
            return false;
        }
        
        $store_name = $this->db->real_escape_string($data['store_name']);
        $store_phone = $this->db->real_escape_string($data['store_phone']);
        $store_address = $this->db->real_escape_string($data['store_address']);
        $store_email = $this->db->real_escape_string($data['store_email']);
        $currency = $this->db->real_escape_string($data['currency']);
        $tax_percent = (float)($data['tax_percent'] ?? 0);
        $paper_size = $this->db->real_escape_string($data['paper_size'] ?? '80mm');
        $rental_terms = $this->db->real_escape_string($data['rental_terms'] ?? '');
        $receipt_footer = $this->db->real_escape_string($data['receipt_footer'] ?? '');
        
        // Handle store_logo if provided
        $logo_query = "";
        if (isset($data['store_logo'])) {
            $store_logo = $this->db->real_escape_string($data['store_logo']);
            $logo_query = ", store_logo = '$store_logo'";
        }

        $sql = "UPDATE settings SET 
                store_name = '$store_name', 
                store_phone = '$store_phone', 
                store_address = '$store_address', 
                store_email = '$store_email', 
                currency = '$currency', 
                tax_percent = $tax_percent, 
                paper_size = '$paper_size',
                rental_terms = '$rental_terms',
                receipt_footer = '$receipt_footer'
                $logo_query
                WHERE id = 1";
                
        return $this->db->query($sql);
    }
    
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
