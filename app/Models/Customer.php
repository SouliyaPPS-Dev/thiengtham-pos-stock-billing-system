<?php

namespace App\Models;

use App\Core\Database;

class Customer
{
    protected function db()
    {
        return Database::getInstance()->getConnection();
    }

    public function getAll($where = '', $params = [], $limit = 0, $offset = 0)
    {
        $sql = "SELECT * FROM customers";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $sql .= " ORDER BY id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit;
        }

        if ($offset > 0) {
            $sql .= " OFFSET " . (int)$offset;
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getTotalCustomers()
    {
        $stmt = $this->db()->prepare("SELECT COUNT(*) as total FROM customers");
        $stmt->execute();
        return (int)$stmt->fetch()['total'];
    }

    public function getById($id)
    {
        $stmt = $this->db()->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db()->prepare("INSERT INTO customers (fullname, phone, email, customer_type, address, notes, created_at, updated_at)
                                      VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            $data['fullname'],
            $data['phone'] ?? '',
            $data['email'] ?? '',
            $data['customer_type'] ?? 'regular',
            $data['address'] ?? '',
            $data['notes'] ?? '',
        ]);

        return $this->db()->lastInsertId();
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'created_at') {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
        }

        $fields[] = "updated_at = NOW()";
        $params[] = $id;

        $sql = "UPDATE customers SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db()->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db()->prepare("DELETE FROM customers WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function search($query)
    {
        $q = "%{$query}%";
        $stmt = $this->db()->prepare("SELECT * FROM customers WHERE fullname LIKE ? OR phone LIKE ? ORDER BY fullname ASC LIMIT 50");
        $stmt->execute([$q, $q]);
        return $stmt->fetchAll();
    }

    public function all()
    {
        return $this->getAll();
    }

    public function find($id)
    {
        return $this->getById($id);
    }

    public function paginate($page, $perPage)
    {
        $offset = ($page - 1) * $perPage;
        return $this->getAll('', [], $perPage, $offset);
    }

    public function countAll()
    {
        return $this->getTotalCustomers();
    }

    public function countSearch($query)
    {
        $q = "%{$query}%";
        $stmt = $this->db()->prepare("SELECT COUNT(*) as total FROM customers WHERE fullname LIKE ? OR phone LIKE ?");
        $stmt->execute([$q, $q]);
        return (int)$stmt->fetch()['total'];
    }

    public function countWhere($where, $params)
    {
        $sql = "SELECT COUNT(*) as total FROM customers";
        if (!empty($where)) $sql .= " WHERE $where";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetch()['total'];
    }

    public function findByEmail($email)
    {
        $stmt = $this->db()->prepare("SELECT * FROM customers WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findByPhone($phone)
    {
        $stmt = $this->db()->prepare("SELECT * FROM customers WHERE phone = ? LIMIT 1");
        $stmt->execute([$phone]);
        return $stmt->fetch();
    }
}
