<?php

namespace App\Models;

use App\Core\Database;

class Sale
{
    protected function db()
    {
        return Database::getInstance()->getConnection();
    }

    public function getAll($where = '', $params = [], $limit = 0, $offset = 0)
    {
        $sql = "SELECT s.*, c.fullname as customer_name, c.phone as customer_phone
                FROM sales s
                LEFT JOIN customers c ON c.id = s.customer_id";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $sql .= " ORDER BY s.id DESC";

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
        $stmt = $this->db()->prepare("SELECT s.*, c.fullname as customer_name, c.phone as customer_phone
                                      FROM sales s
                                      LEFT JOIN customers c ON c.id = s.customer_id
                                      WHERE s.id = ?");
        $stmt->execute([$id]);
        $sale = $stmt->fetch();

        if ($sale) {
            $stmtItems = $this->db()->prepare("SELECT si.*, p.name as product_name, p.sku
                                               FROM sale_items si
                                               LEFT JOIN products p ON p.id = si.product_id
                                               WHERE si.sale_id = ?");
            $stmtItems->execute([$id]);
            $sale['items'] = $stmtItems->fetchAll();
        }

        return $sale;
    }

    public function getTodayTotal()
    {
        $stmt = $this->db()->prepare("SELECT COALESCE(SUM(grand_total), 0) as total
                                      FROM sales
                                      WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        return (float)$stmt->fetch()['total'];
    }

    public function getMonthTotal($fromDate = null, $toDate = null)
    {
        if ($fromDate === null) {
            $fromDate = date('Y-m-01');
        }
        if ($toDate === null) {
            $toDate = date('Y-m-t');
        }

        $stmt = $this->db()->prepare("SELECT COALESCE(SUM(grand_total), 0) as total
                                      FROM sales
                                      WHERE DATE(created_at) BETWEEN ? AND ?");
        $stmt->execute([$fromDate, $toDate]);
        return (float)$stmt->fetch()['total'];
    }

    public function getRecent($limit = 10)
    {
        $stmt = $this->db()->prepare("SELECT s.*, c.fullname as customer_name
                                      FROM sales s
                                      LEFT JOIN customers c ON c.id = s.customer_id
                                      ORDER BY s.id DESC
                                      LIMIT ?");
        $stmt->execute([(int)$limit]);
        return $stmt->fetchAll();
    }

    public function getPopularProducts($limit = 10)
    {
        $stmt = $this->db()->prepare("SELECT p.id, p.name, p.sku, SUM(si.quantity) as total_qty, SUM(si.subtotal) as total_revenue
                                      FROM sale_items si
                                      JOIN products p ON p.id = si.product_id
                                      GROUP BY p.id, p.name, p.sku
                                      ORDER BY total_qty DESC
                                      LIMIT ?");
        $stmt->execute([(int)$limit]);
        return $stmt->fetchAll();
    }

    public function getSalesByDay($fromDate, $toDate)
    {
        $stmt = $this->db()->prepare("SELECT DATE(created_at) as date, COUNT(*) as total_orders, SUM(grand_total) as total_revenue
                                      FROM sales
                                      WHERE DATE(created_at) BETWEEN ? AND ?
                                      GROUP BY DATE(created_at)
                                      ORDER BY date ASC");
        $stmt->execute([$fromDate, $toDate]);
        return $stmt->fetchAll();
    }

    public function create($data, $items)
    {
        $this->db()->beginTransaction();

        try {
            $invoiceNo = $this->generateInvoiceNumber();

            $stmt = $this->db()->prepare("INSERT INTO sales (invoice_no, customer_id, subtotal, tax, discount, grand_total, payment_method, paid_amount, change_amount, status, notes, created_at, updated_at)
                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                $invoiceNo,
                $data['customer_id'] ?? null,
                $data['subtotal'] ?? 0,
                $data['tax'] ?? 0,
                $data['discount'] ?? 0,
                $data['grand_total'] ?? 0,
                $data['payment_method'] ?? 'cash',
                $data['paid_amount'] ?? 0,
                $data['change_amount'] ?? 0,
                $data['status'] ?? 'completed',
                $data['notes'] ?? '',
            ]);

            $saleId = $this->db()->lastInsertId();

            $stmtItem = $this->db()->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, price, subtotal)
                                              VALUES (?, ?, ?, ?, ?)");
            $stmtStock = $this->db()->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

            foreach ($items as $item) {
                $stmtItem->execute([
                    $saleId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price'],
                    $item['subtotal'] ?? ($item['quantity'] * $item['price']),
                ]);

                $stmtStock->execute([$item['quantity'], $item['product_id']]);
            }

            $this->db()->commit();
            return $saleId;
        } catch (\Exception $e) {
            $this->db()->rollBack();
            throw $e;
        }
    }

    public function generateInvoiceNumber()
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $stmt = $this->db()->prepare("SELECT COUNT(*) as cnt FROM sales WHERE invoice_no LIKE ? AND DATE(created_at) = CURDATE()");
        $stmt->execute([$prefix . '%']);
        $count = (int)$stmt->fetch()['cnt'];
        $seq = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $seq;
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
            $where = '(s.invoice_no LIKE ? OR c.fullname LIKE ?)';
            $q = "%{$search}%";
            $params = [$q, $q];
        }

        if (!empty($fromDate) && !empty($toDate)) {
            $dateCond = 'DATE(s.created_at) BETWEEN ? AND ?';
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
            $where = '(s.invoice_no LIKE ? OR c.fullname LIKE ?)';
            $q = "%{$search}%";
            $params = [$q, $q];
        }

        if (!empty($fromDate) && !empty($toDate)) {
            $dateCond = 'DATE(s.created_at) BETWEEN ? AND ?';
            $where = empty($where) ? $dateCond : "$where AND $dateCond";
            $params = array_merge($params, [$fromDate, $toDate]);
        }

        $sql = "SELECT COUNT(*) as total
                FROM sales s
                LEFT JOIN customers c ON c.id = s.customer_id";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetch()['total'];
    }

    public function getItems($id)
    {
        $stmt = $this->db()->prepare("SELECT si.*, p.name as product_name, p.sku
                                      FROM sale_items si
                                      LEFT JOIN products p ON p.id = si.product_id
                                      WHERE si.sale_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }
}
