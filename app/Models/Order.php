<?php

namespace App\Models;

use App\Core\Database;

class Order
{
    protected function db()
    {
        return Database::getInstance()->getConnection();
    }

    public function getAll($where = '', $params = [], $limit = 0, $offset = 0)
    {
        $sql = "SELECT o.*,
                       (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) AS items_count
                FROM orders o";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $sql .= " ORDER BY o.id DESC";

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

    public function getById($id)
    {
        $stmt = $this->db()->prepare("SELECT o.* FROM orders o WHERE o.id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch();

        if ($order) {
            $stmtItems = $this->db()->prepare("SELECT oi.*, p.name AS product_name, p.sku
                                                FROM order_items oi
                                                LEFT JOIN products p ON p.id = oi.product_id
                                                WHERE oi.order_id = ?");
            $stmtItems->execute([$id]);
            $order['items'] = $stmtItems->fetchAll();
        }

        return $order;
    }

    public function find($id)
    {
        return $this->getById($id);
    }

    public function paginate($page, $perPage, $search = '', $fromDate = '', $toDate = '')
    {
        $where = '';
        $params = [];

        if (!empty($search)) {
            $where = '(o.order_number LIKE ? OR o.customer_name LIKE ? OR o.customer_phone LIKE ?)';
            $q = "%{$search}%";
            $params = [$q, $q, $q];
        }

        if (!empty($fromDate) && !empty($toDate)) {
            $dateCond = 'DATE(o.created_at) BETWEEN ? AND ?';
            $where = empty($where) ? $dateCond : "$where AND $dateCond";
            $params = array_merge($params, [$fromDate, $toDate]);
        }

        $offset = ($page - 1) * $perPage;
        return $this->getAll($where, $params, $perPage, $offset);
    }

    public function countAll($search = '', $fromDate = '', $toDate = '')
    {
        $where = '';
        $params = [];

        if (!empty($search)) {
            $where = '(o.order_number LIKE ? OR o.customer_name LIKE ? OR o.customer_phone LIKE ?)';
            $q = "%{$search}%";
            $params = [$q, $q, $q];
        }

        if (!empty($fromDate) && !empty($toDate)) {
            $dateCond = 'DATE(o.created_at) BETWEEN ? AND ?';
            $where = empty($where) ? $dateCond : "$where AND $dateCond";
            $params = array_merge($params, [$fromDate, $toDate]);
        }

        $sql = "SELECT COUNT(*) as total FROM orders o";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetch()['total'];
    }

    public function updateStatus($id, $orderStatus)
    {
        $stmt = $this->db()->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
        return $stmt->execute([$orderStatus, $id]);
    }

    public function updatePaymentStatus($id, $paymentStatus)
    {
        $stmt = $this->db()->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
        return $stmt->execute([$paymentStatus, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db()->prepare("DELETE FROM orders WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
