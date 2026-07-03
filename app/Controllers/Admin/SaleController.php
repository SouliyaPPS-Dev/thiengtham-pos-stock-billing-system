<?php

namespace App\Controllers\Admin;

use App\Models\Sale;

class SaleController extends \App\Controllers\BaseController
{
    public function index()
    {
        $saleModel = new Sale();

        $search = $_GET['search'] ?? '';
        $fromDate = $_GET['from_date'] ?? '';
        $toDate = $_GET['to_date'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        $sales = $saleModel->paginate($page, $perPage, $search, $fromDate, $toDate);
        $total = $saleModel->countAll($search, $fromDate, $toDate);
        $totalPages = max(1, ceil($total / $perPage));

        $saleIds = array_map('strval', array_column($sales, 'id'));

        return view('pages.admin.sales.index', [
            'title' => 'ປະຫວັດການຂາຍ',
            'sales' => $sales,
            'saleIds' => $saleIds,
            'search' => $search,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function show($id)
    {
        $sale = (new Sale())->find($id);

        if (!$sale) {
            $this->redirect('/admin/sales', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບບິນ',
            ]);
        }

        return view('pages.admin.sales.show', [
            'title' => 'ໃບເກັບເງິນ #' . htmlspecialchars($sale['invoice_number'] ?? $sale['id']),
            'sale' => $sale,
        ]);
    }

    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/sales');
        }

        $sale = (new Sale())->find($id);
        if (!$sale) {
            $this->redirect('/admin/sales', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບບິນ',
            ]);
        }

        $status = $_POST['status'] ?? '';
        $allowed = ['Completed', 'Refunded', 'Cancelled'];

        if (!in_array($status, $allowed)) {
            $this->redirect('/admin/sales/' . $id, [
                'error' => 1,
                'error_msg' => 'ສະຖານະບໍ່ຖືກຕ້ອງ',
            ]);
        }

        (new Sale())->updateStatus($id, $status);

        $this->redirect('/admin/sales/' . $id, [
            'success' => 1,
            'success_msg' => 'ອັບເດດສະຖານະສຳເລັດ',
        ]);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/sales');
        }

        $sale = (new Sale())->find($id);
        if (!$sale) {
            $this->redirect('/admin/sales', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບບິນ',
            ]);
        }

        (new Sale())->delete($id);

        $this->redirect('/admin/sales', [
            'success' => 1,
            'success_msg' => 'ລົບບິນສຳເລັດ',
        ]);
    }

    public function bulkDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/sales');
        }

        $ids = $_POST['ids'] ?? [];
        if (empty($ids) || !is_array($ids)) {
            $this->redirect('/admin/sales', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາເລືອກບິນທີ່ຕ້ອງການລົບ',
            ]);
        }

        $ids = array_map('intval', $ids);
        $db = \App\Core\Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $db->prepare("DELETE FROM sales WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $db->commit();

            $this->redirect('/admin/sales', [
                'success' => 1,
                'success_msg' => 'ລົບ ' . count($ids) . ' ບິນສຳເລັດ',
            ]);
        } catch (\Exception $e) {
            $db->rollBack();
            $this->redirect('/admin/sales', [
                'error' => 1,
                'error_msg' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage(),
            ]);
        }
    }
}
