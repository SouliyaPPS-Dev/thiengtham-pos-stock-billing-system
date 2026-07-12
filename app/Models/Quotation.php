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
        $sql = "SELECT q.*, u.full_name AS created_by_name,
                c.fullname AS customer_name_resolved, c.phone AS customer_phone
                FROM quotations q
                LEFT JOIN users u ON u.id = q.created_by
                LEFT JOIN customers c ON c.id = q.customer_id";

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
        $stmt = $this->db()->prepare("SELECT q.*, u.full_name AS created_by_name,
                                      c.fullname AS customer_name_resolved, c.phone AS customer_phone, c.email AS customer_email
                                      FROM quotations q
                                      LEFT JOIN users u ON u.id = q.created_by
                                      LEFT JOIN customers c ON c.id = q.customer_id
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

            $stmtHistory = $this->db()->prepare("SELECT h.*, u.full_name AS performed_by_name
                                                  FROM quotation_history h
                                                  LEFT JOIN users u ON u.id = h.performed_by
                                                  WHERE h.quotation_id = ?
                                                  ORDER BY h.created_at DESC");
            $stmtHistory->execute([$id]);
            $q['history'] = $stmtHistory->fetchAll();
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

            $stmt = $this->db()->prepare("INSERT INTO quotations (quotation_number, company_template, supplier_id, supplier_name, supplier_contact, customer_id, customer_name, customer_contact, ref_no, date, expiry_date, subtotal, discount, tax_percent, tax_amount, grand_total, notes, terms, status, created_by, created_at)
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $number,
                $data['company_template'] ?? 'luang-prabarg',
                (!empty($data['supplier_id']) ? $data['supplier_id'] : null),
                $data['supplier_name'] ?? '',
                $data['supplier_contact'] ?? '',
                (!empty($data['customer_id']) ? $data['customer_id'] : null),
                $data['customer_name'] ?? '',
                $data['customer_contact'] ?? '',
                $data['ref_no'] ?? '',
                $data['date'] ?? date('Y-m-d'),
                (!empty($data['expiry_date']) ? $data['expiry_date'] : null),
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

            $this->addHistory($id, 'created', null, $data['status'] ?? 'Draft', 'ສ້າງໃບສະເໜີລາຄາໃໝ່');

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
            $old = $this->find($id);
            $oldStatus = $old['status'] ?? 'Draft';
            $newStatus = $data['status'] ?? 'Draft';

            $stmt = $this->db()->prepare("UPDATE quotations SET
                company_template = ?, supplier_id = ?, supplier_name = ?, supplier_contact = ?,
                customer_id = ?, customer_name = ?, customer_contact = ?,
                ref_no = ?, date = ?, expiry_date = ?, subtotal = ?, discount = ?, tax_percent = ?,
                tax_amount = ?, grand_total = ?, notes = ?, terms = ?, status = ?
                WHERE id = ?");
            $stmt->execute([
                $data['company_template'] ?? 'luang-prabarg',
                (!empty($data['supplier_id']) ? $data['supplier_id'] : null),
                $data['supplier_name'] ?? '',
                $data['supplier_contact'] ?? '',
                (!empty($data['customer_id']) ? $data['customer_id'] : null),
                $data['customer_name'] ?? '',
                $data['customer_contact'] ?? '',
                $data['ref_no'] ?? '',
                $data['date'] ?? date('Y-m-d'),
                (!empty($data['expiry_date']) ? $data['expiry_date'] : null),
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

            if ($oldStatus !== $newStatus) {
                $this->addHistory($id, 'status_changed', $oldStatus, $newStatus, 'ປ່ຽນສະຖານະ');
            } else {
                $this->addHistory($id, 'updated', null, null, 'ແກ້ໄຂຂໍ້ມູນ');
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

    public function duplicate($id)
    {
        $original = $this->find($id);
        if (!$original) {
            return false;
        }

        $this->db()->beginTransaction();

        try {
            $newNumber = $this->generateNumber();

            $stmt = $this->db()->prepare("INSERT INTO quotations (quotation_number, company_template, supplier_id, supplier_name, supplier_contact, customer_id, customer_name, customer_contact, ref_no, date, expiry_date, subtotal, discount, tax_percent, tax_amount, grand_total, notes, terms, status, created_by, created_at)
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Draft', ?, NOW())");
            $stmt->execute([
                $newNumber,
                $original['company_template'],
                $original['supplier_id'],
                $original['supplier_name'],
                $original['supplier_contact'],
                $original['customer_id'],
                $original['customer_name'],
                $original['customer_contact'],
                $original['ref_no'],
                date('Y-m-d'),
                $original['expiry_date'],
                $original['subtotal'],
                $original['discount'],
                $original['tax_percent'],
                $original['tax_amount'],
                $original['grand_total'],
                $original['notes'],
                $original['terms'],
                $_SESSION['user']['id'] ?? null,
            ]);

            $newId = $this->db()->lastInsertId();

            $stmtItem = $this->db()->prepare("INSERT INTO quotation_items (quotation_id, product_id, product_name, quantity, unit, unit_price, amount)
                                               VALUES (?, ?, ?, ?, ?, ?, ?)");

            foreach ($original['items'] as $item) {
                $stmtItem->execute([
                    $newId,
                    $item['product_id'],
                    $item['product_name'],
                    $item['quantity'],
                    $item['unit'],
                    $item['unit_price'],
                    $item['amount'],
                ]);
            }

            $this->addHistory($newId, 'created', null, 'Draft', 'ສຳເນົາຈາກໃບສະເໜີລາຄາ #' . $original['quotation_number']);

            $this->db()->commit();
            return $newId;
        } catch (\Exception $e) {
            $this->db()->rollBack();
            throw $e;
        }
    }

    public function convertToSale($id)
    {
        $quotation = $this->find($id);
        if (!$quotation) {
            return false;
        }

        if ($quotation['status'] !== 'Approved') {
            throw new \Exception('ຕ້ອງອະນຸມັດກ່ອນຈຶ່ງສາມາດປ່ຽນເປັນບິນໄດ້');
        }

        $this->db()->beginTransaction();

        try {
            $saleNumber = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $stmt = $this->db()->prepare("INSERT INTO sales (invoice_number, customer_id, customer_name, customer_address, subtotal, discount, tax_percent, tax_amount, grand_total, payment_method, status, notes, created_by, created_at)
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'cash', 'Completed', ?, ?, NOW())");
            $stmt->execute([
                $saleNumber,
                $quotation['customer_id'],
                $quotation['customer_name'] ?? $quotation['customer_name_resolved'] ?? '',
                $quotation['customer_contact'] ?? '',
                $quotation['subtotal'],
                $quotation['discount'],
                $quotation['tax_percent'],
                $quotation['tax_amount'],
                $quotation['grand_total'],
                'ປ່ຽນຈາກໃບສະເໜີລາຄາ #' . $quotation['quotation_number'],
                $_SESSION['user']['id'] ?? null,
            ]);

            $saleId = $this->db()->lastInsertId();

            $stmtItem = $this->db()->prepare("INSERT INTO sale_items (sale_id, product_id, product_name, quantity, unit_price, subtotal)
                                               VALUES (?, ?, ?, ?, ?, ?)");

            foreach ($quotation['items'] as $item) {
                $stmtItem->execute([
                    $saleId,
                    $item['product_id'],
                    $item['product_name'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['amount'],
                ]);
            }

            $this->db()->prepare("UPDATE quotations SET status = 'Approved', converted_to_sale_id = ? WHERE id = ?")->execute([$saleId, $id]);

            $this->addHistory($id, 'converted_to_sale', 'Approved', 'Approved', 'ປ່ຽນເປັນບິນຂາຍ #' . $saleNumber);

            $this->db()->commit();
            return $saleId;
        } catch (\Exception $e) {
            $this->db()->rollBack();
            throw $e;
        }
    }

    public function addHistory($quotationId, $action, $oldStatus, $newStatus, $notes = '')
    {
        $stmt = $this->db()->prepare("INSERT INTO quotation_history (quotation_id, action, old_status, new_status, notes, performed_by, created_at)
                                       VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $quotationId,
            $action,
            $oldStatus,
            $newStatus,
            $notes,
            $_SESSION['user']['id'] ?? null,
        ]);
    }

    public function getHistory($quotationId)
    {
        $stmt = $this->db()->prepare("SELECT h.*, u.full_name AS performed_by_name
                                       FROM quotation_history h
                                       LEFT JOIN users u ON u.id = h.performed_by
                                       WHERE h.quotation_id = ?
                                       ORDER BY h.created_at DESC");
        $stmt->execute([$quotationId]);
        return $stmt->fetchAll();
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
            $where = '(q.quotation_number LIKE ? OR q.supplier_name LIKE ? OR q.ref_no LIKE ? OR q.customer_name LIKE ?)';
            $q = "%{$search}%";
            $params = [$q, $q, $q, $q];
        }

        $offset = ($page - 1) * $perPage;
        return $this->getAll($where, $params, $perPage, $offset);
    }

    public function countAll($search = '')
    {
        $where = '';
        $params = [];

        if (!empty($search)) {
            $where = '(quotation_number LIKE ? OR supplier_name LIKE ? OR ref_no LIKE ? OR customer_name LIKE ?)';
            $q = "%{$search}%";
            $params = [$q, $q, $q, $q];
        }

        $sql = "SELECT COUNT(*) as total FROM quotations q";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetch()['total'];
    }

    public function exportCsv($search = '')
    {
        $quotations = $this->paginate(1, 99999, $search);

        $header = ['ID', 'ເລກທີ', 'ສົມທົບ', 'ລູກຄ້າ', 'ຜູ້ສະໜອງ', 'ວັນທີ', 'ລາຄາລວມ', 'ສະຖານະ'];

        $rows = [];
        foreach ($quotations as $q) {
            $rows[] = [
                $q['id'],
                $q['quotation_number'],
                $q['supplier_name'] ?? '',
                $q['customer_name'] ?? $q['customer_name_resolved'] ?? '',
                $q['supplier_name'] ?? '',
                $q['date'] ?? '',
                number_format($q['grand_total'], 2),
                $q['status'],
            ];
        }

        return ['header' => $header, 'rows' => $rows];
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
