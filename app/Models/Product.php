<?php

namespace App\Models;

use App\Core\Database;

class Product
{
    protected function db()
    {
        return Database::getInstance()->getConnection();
    }

    public function getAll($where = '', $params = [], $limit = 0, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON c.id = p.category_id";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $sql .= " ORDER BY p.id DESC";

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

    public function getTotalProducts($where = '', $params = [])
    {
        $sql = "SELECT COUNT(*) as total FROM products p";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetch()['total'];
    }

    public function getById($id)
    {
        $stmt = $this->db()->prepare("SELECT p.*, c.name as category_name
                                      FROM products p
                                      LEFT JOIN categories c ON c.id = p.category_id
                                      WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getLowStockCount($threshold = 10)
    {
        $stmt = $this->db()->prepare("SELECT COUNT(*) as total FROM products WHERE stock <= ?");
        $stmt->execute([$threshold]);
        return (int)$stmt->fetch()['total'];
    }

    public function create($data)
    {
        $sql = "INSERT INTO products (name, sku, category_id, cost_price, selling_price, stock, min_stock, unit, description, image, barcode, status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['sku'],
            $data['category_id'] ?? null,
            $data['cost_price'] ?? 0,
            $data['selling_price'] ?? 0,
            $data['stock'] ?? 0,
            $data['min_stock'] ?? 0,
            $data['unit'] ?? '',
            $data['description'] ?? '',
            $data['image'] ?? '',
            $data['barcode'] ?? '',
            $data['status'] ?? 'active',
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

        $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db()->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db()->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function search($query)
    {
        $q = "%{$query}%";
        $stmt = $this->db()->prepare("SELECT p.*, c.name as category_name
                                      FROM products p
                                      LEFT JOIN categories c ON c.id = p.category_id
                                      WHERE p.name LIKE ? OR p.sku LIKE ? OR p.barcode LIKE ?
                                      ORDER BY p.name ASC
                                      LIMIT 50");
        $stmt->execute([$q, $q, $q]);
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
        return $this->getTotalProducts();
    }

    public function countSearch($query)
    {
        $q = "%{$query}%";
        $stmt = $this->db()->prepare("SELECT COUNT(*) as total FROM products WHERE name LIKE ? OR sku LIKE ? OR barcode LIKE ?");
        $stmt->execute([$q, $q, $q]);
        return (int)$stmt->fetch()['total'];
    }

    public function decrementStock($id, $qty)
    {
        $stmt = $this->db()->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        return $stmt->execute([$qty, $id]);
    }
}
