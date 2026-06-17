<?php

namespace App\Models;

use App\Core\Database;

class Category
{
    protected function db()
    {
        return Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db()->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db()->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db()->prepare("INSERT INTO categories (name, description, created_at, updated_at)
                                      VALUES (?, ?, NOW(), NOW())");
        $stmt->execute([
            $data['name'],
            $data['description'] ?? '',
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

        $sql = "UPDATE categories SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db()->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db()->prepare("DELETE FROM categories WHERE id = ?");
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
}
