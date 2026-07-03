<?php

namespace App\Models;

use App\Core\Database;

class Supplier
{
    protected function db()
    {
        return Database::getInstance()->getConnection();
    }

    public function getAll($where = '', $params = [], $limit = 0, $offset = 0)
    {
        $sql = "SELECT * FROM suppliers";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $sql .= " ORDER BY name ASC";

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

    public function getTotalSuppliers($where = '', $params = [])
    {
        $sql = "SELECT COUNT(*) as total FROM suppliers";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetch()['total'];
    }

    public function getById($id)
    {
        $stmt = $this->db()->prepare("SELECT * FROM suppliers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db()->prepare("INSERT INTO suppliers (name, contact_person, phone, email, address, notes, tax_percent, created_at, updated_at)
                                      VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            $data['name'],
            $data['contact_person'] ?? '',
            $data['phone'] ?? '',
            $data['email'] ?? '',
            $data['address'] ?? '',
            $data['notes'] ?? '',
            $data['tax_percent'] ?? 0,
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

        $sql = "UPDATE suppliers SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db()->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db()->prepare("DELETE FROM suppliers WHERE id = ?");
        return $stmt->execute([$id]);
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
        return $this->getTotalSuppliers();
    }

    public function search($query, $page = 1, $perPage = 20)
    {
        $q = "%{$query}%";
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db()->prepare("SELECT * FROM suppliers WHERE name LIKE ? OR contact_person LIKE ? OR phone LIKE ?
                                      ORDER BY name ASC LIMIT ? OFFSET ?");
        $stmt->execute([$q, $q, $q, (int)$perPage, (int)$offset]);
        return $stmt->fetchAll();
    }

    public function countSearch($query)
    {
        $q = "%{$query}%";
        $stmt = $this->db()->prepare("SELECT COUNT(*) as total FROM suppliers WHERE name LIKE ? OR contact_person LIKE ? OR phone LIKE ?");
        $stmt->execute([$q, $q, $q]);
        return (int)$stmt->fetch()['total'];
    }
}
