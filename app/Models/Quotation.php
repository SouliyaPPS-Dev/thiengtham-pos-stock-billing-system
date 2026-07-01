<?php

namespace App\Models;

use App\Core\Database;

class Quotation
{
    protected function db()
    {
        return Database::getInstance()->getConnection();
    }

    public function getAll($where = '', $params = [], $limit = 0, $offset = 0)
    {
        $sql = "SELECT q.*, u.full_name AS created_by_name
                FROM quotations q
                LEFT JOIN users u ON u.id = q.created_by";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $sql .= " ORDER BY q.id DESC";

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
        $stmt = $this->db()->prepare("SELECT q.*, u.full_name AS created_by_name
                                       FROM quotations q
                                       LEFT JOIN users u ON u.id = q.created_by
                                       WHERE q.id = ?");
        $stmt->execute([$id]);
        $q = $stmt->fetch();

        if ($q) {
            $stmtItems = $this->db()->prepare("SELECT qi.*, p.image AS product_image
                                                FROM quotation_items qi
                                                LEFT JOIN products p ON p.id = qi.product_id
                                                WHERE qi.quotation_id = ?
                                                ORDER BY qi.id ASC");
            $stmtItems->execute([$id]);
            $q['items'] = $stmtItems->fetchAll();
        }

        return $q;
    }

    public function find($id)
    {
        return $this->getById($id);
    }

    public function create($data, $items)
    {
        $this->db()->beginTransaction();

        try {
            $number = $this->generateNumber();

            $stmt = $this->db()->prepare("INSERT INTO quotations (quotation_number, company_template, supplier_id, supplier_name, supplier_contact, ref_no, date, subtotal, discount, tax_percent, tax_amount, grand_total, notes, terms, status, created_by, created_at)
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $number,
                $data['company_template'] ?? 'luang-prabarg',
                $data['supplier_id'] ?? null,
                $data['supplier_name'] ?? '',
                $data['supplier_contact'] ?? '',
                $data['ref_no'] ?? '',
                $data['date'] ?? date('Y-m-d'),
                $data['subtotal'] ?? 0,
                $data['discount'] ?? 0,
                $data['tax_percent'] ?? 10.00,
                $data['tax_amount'] ?? 0,
                $data['grand_total'] ?? 0,
                $data['notes'] ?? '',
                $data['terms'] ?? '',
                $data['status'] ?? 'Draft',
                $data['created_by'] ?? ($_SESSION['user']['id'] ?? null),
            ]);

            $id = $this->db()->lastInsertId();

            $stmtItem = $this->db()->prepare("INSERT INTO quotation_items (quotation_id, product_id, product_name, quantity, unit, unit_price, amount)
                                               VALUES (?, ?, ?, ?, ?, ?, ?)");

            foreach ($items as $item) {
                $qty = (float)($item['quantity'] ?? 1);
                $price = (float)($item['unit_price'] ?? $item['price'] ?? 0);
                $amount = $qty * $price;

                $stmtItem->execute([
                    $id,
                    $item['product_id'] ?? null,
                    $item['product_name'] ?? $item['name'] ?? '',
                    $qty,
                    $item['unit'] ?? 'SET',
                    $price,
                    $amount,
                ]);
            }

            $this->db()->commit();
            return $id;
        } catch (\Exception $e) {
            $this->db()->rollBack();
            throw $e;
        }
    }

    public function update($id, $data, $items)
    {
        $this->db()->beginTransaction();

        try {
            $stmt = $this->db()->prepare("UPDATE quotations SET
                company_template = ?, supplier_id = ?, supplier_name = ?, supplier_contact = ?,
                ref_no = ?, date = ?, subtotal = ?, discount = ?, tax_percent = ?,
                tax_amount = ?, grand_total = ?, notes = ?, terms = ?, status = ?
                WHERE id = ?");
            $stmt->execute([
                $data['company_template'] ?? 'luang-prabarg',
                $data['supplier_id'] ?? null,
                $data['supplier_name'] ?? '',
                $data['supplier_contact'] ?? '',
                $data['ref_no'] ?? '',
                $data['date'] ?? date('Y-m-d'),
                $data['subtotal'] ?? 0,
                $data['discount'] ?? 0,
                $data['tax_percent'] ?? 10.00,
                $data['tax_amount'] ?? 0,
                $data['grand_total'] ?? 0,
                $data['notes'] ?? '',
                $data['terms'] ?? '',
                $data['status'] ?? 'Draft',
                $id,
            ]);

            $this->db()->prepare("DELETE FROM quotation_items WHERE quotation_id = ?")->execute([$id]);

            $stmtItem = $this->db()->prepare("INSERT INTO quotation_items (quotation_id, product_id, product_name, quantity, unit, unit_price, amount)
                                               VALUES (?, ?, ?, ?, ?, ?, ?)");

            foreach ($items as $item) {
                $qty = (float)($item['quantity'] ?? 1);
                $price = (float)($item['unit_price'] ?? $item['price'] ?? 0);
                $amount = $qty * $price;

                $stmtItem->execute([
                    $id,
                    $item['product_id'] ?? null,
                    $item['product_name'] ?? $item['name'] ?? '',
                    $qty,
                    $item['unit'] ?? 'SET',
                    $price,
                    $amount,
                ]);
            }

            $this->db()->commit();
            return true;
        } catch (\Exception $e) {
            $this->db()->rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        $stmt = $this->db()->prepare("DELETE FROM quotations WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function generateNumber()
    {
        $prefix = 'QTN-' . date('Ymd') . '-';
        $stmt = $this->db()->prepare("SELECT COUNT(*) as cnt FROM quotations WHERE quotation_number LIKE ? AND DATE(created_at) = CURDATE()");
        $stmt->execute([$prefix . '%']);
        $count = (int)$stmt->fetch()['cnt'];
        return $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function paginate($page, $perPage, $search = '')
    {
        $where = '';
        $params = [];

        if (!empty($search)) {
            $where = '(q.quotation_number LIKE ? OR q.supplier_name LIKE ? OR q.ref_no LIKE ?)';
            $q = "%{$search}%";
            $params = [$q, $q, $q];
        }

        $offset = ($page - 1) * $perPage;
        return $this->getAll($where, $params, $perPage, $offset);
    }

    public function countAll($search = '')
    {
        $where = '';
        $params = [];

        if (!empty($search)) {
            $where = '(quotation_number LIKE ? OR supplier_name LIKE ? OR ref_no LIKE ?)';
            $q = "%{$search}%";
            $params = [$q, $q, $q];
        }

        $sql = "SELECT COUNT(*) as total FROM quotations q";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetch()['total'];
    }

    public static function templates()
    {
        return [
            'luang-prabarg' => [
                'name' => 'Luang Prabarg',
                'label' => 'ຫຼວງພະບາງ',
                'company' => 'CH.KARNCHANG(LAO)COMPANY LIMITED',
                'address' => '215 Lane xang Avenue, Ban Xieng yeun, Muang Chanthabouly, Vientiane, Lao P D R',
                'terms' => ['ເສສະໂນ ລາຄາ ພາຍໃນ 60 ວັນ', 'ສົ່ງສີນຄ້າພາຍໃນ 14 ວັນ ຫຼັງອອກ PO', 'ເຄດີດ 30 ວັນ'],
                'currency' => 'THB',
                'logo_color' => '#0ea5e9',
            ],
            'num-ngum-2' => [
                'name' => 'Num Ngum 2',
                'label' => 'ນຳງຶມ 2',
                'company' => 'NAM NGUM 2 POWER COMPANY LIMITED',
                'address' => '215 Lane xang Avenue, Ban Xieng yeun, Muang Chanthabouly, Vientiane, Lao P D R',
                'terms' => ['ສົ່ງສີນຄ້າພາຍໃນ 14 ວັນ ຫຼັງອອກ PO', 'ເຄດິດ 30 ວັນ'],
                'currency' => 'THB',
                'logo_color' => '#059669',
            ],
            'sorkarnsarg' => [
                'name' => 'Sorkarnsarg',
                'label' => 'ສອກຄຳສາກ',
                'company' => 'XAYABURI POWER COMPANY LIMITED',
                'address' => '215 Lane xang Avenue, Ban Xieng yuen, Muang Chantabouly, Vientiane, LAO P.D.R',
                'terms' => ['ສົ່ງສີນຄ້າພາຍໃນ 14 ວັນ ຫຼັງອອກ PO', 'ເຄດິດ 30 ວັນ'],
                'currency' => 'THB',
                'logo_color' => '#7c3aed',
            ],
            'xaya' => [
                'name' => 'Xaya',
                'label' => 'ຊ່າຍາ',
                'company' => 'XAYABURI POWER COMPANY LIMITED',
                'address' => '215 Lane xang Avenue, Ban Xieng yeun, Muang Chanthabouly, Vientiane, Lao P D R',
                'terms' => ['ເສສະໂນ ລາຄາ ພາຍໃນ 60 ວັນ', 'ສົ່ງສີນຄ້າພາຍໃນ 14 ວັນ ຫຼັງອອກ PO', 'ເຄດີດ 30 ວັນ'],
                'currency' => 'THB',
                'logo_color' => '#d97706',
            ],
        ];
    }
}
