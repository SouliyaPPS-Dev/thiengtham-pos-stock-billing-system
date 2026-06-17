<?php

namespace App\Models;

use App\Core\Database;

class User
{
    protected function db()
    {
        return Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db()->prepare("SELECT id, username, full_name, role, status, created_at FROM users ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db()->prepare("SELECT id, username, full_name, role, status, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $this->db()->prepare("INSERT INTO users (username, password, full_name, role, status, created_at, updated_at)
                                      VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            $data['username'],
            $password,
            $data['full_name'] ?? '',
            $data['role'] ?? 'cashier',
            $data['status'] ?? 'active',
        ]);

        return $this->db()->lastInsertId();
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'created_at' && $key !== 'username') {
                if ($key === 'password') {
                    $fields[] = "$key = ?";
                    $params[] = password_hash($value, PASSWORD_DEFAULT);
                } else {
                    $fields[] = "$key = ?";
                    $params[] = $value;
                }
            }
        }

        $fields[] = "updated_at = NOW()";
        $params[] = $id;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db()->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db()->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByUsername($username)
    {
        $stmt = $this->db()->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function all()
    {
        return $this->getAll();
    }

    public function find($id)
    {
        return $this->getById($id);
    }

    public function findByUsername($username)
    {
        return $this->getByUsername($username);
    }

    public function updateByUsername($username, $data)
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if ($key !== 'username' && $key !== 'created_at') {
                if ($key === 'password') {
                    $fields[] = "$key = ?";
                    $params[] = password_hash($value, PASSWORD_DEFAULT);
                } else {
                    $fields[] = "$key = ?";
                    $params[] = $value;
                }
            }
        }

        $fields[] = "updated_at = NOW()";
        $params[] = $username;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE username = ?";
        $stmt = $this->db()->prepare($sql);
        return $stmt->execute($params);
    }
}
