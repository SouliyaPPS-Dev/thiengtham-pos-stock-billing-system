<?php

namespace App\Models;

use App\Core\Database;

class PaymentMethod
{
    protected function db()
    {
        return Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db()->prepare("SELECT * FROM payment_methods ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getActive()
    {
        $stmt = $this->db()->prepare("SELECT * FROM payment_methods WHERE is_active = 1 ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db()->prepare("SELECT * FROM payment_methods WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db()->prepare("INSERT INTO payment_methods (name, details, is_active) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['details'] ?? '',
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
        ]);
        return $this->db()->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db()->prepare("UPDATE payment_methods SET name = ?, details = ?, is_active = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['details'] ?? '',
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            $id,
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db()->prepare("DELETE FROM payment_methods WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
