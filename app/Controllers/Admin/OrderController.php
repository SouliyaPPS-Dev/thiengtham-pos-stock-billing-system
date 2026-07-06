<?php

namespace App\Controllers\Admin;

use App\Models\Order;

class OrderController extends \App\Controllers\BaseController
{
    public function index()
    {
        $orderModel = new Order();

        $search = $_GET['search'] ?? '';
        $fromDate = $_GET['from_date'] ?? '';
        $toDate = $_GET['to_date'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        $orders = $orderModel->paginate($page, $perPage, $search, $fromDate, $toDate);
        $total = $orderModel->countAll($search, $fromDate, $toDate);
        $totalPages = max(1, ceil($total / $perPage));

        $orderIds = array_map('strval', array_column($orders, 'id'));

        return view('pages.admin.orders.index', [
            'title' => 'ລາຍການສັ່ງຊື້',
            'orders' => $orders,
            'orderIds' => $orderIds,
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
        $order = (new Order())->find($id);

        if (!$order) {
            $this->redirect('/admin/orders', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບຄຳສັ່ງຊື້',
            ]);
        }

        return view('pages.admin.orders.show', [
            'title' => 'ຄຳສັ່ງຊື້ #' . htmlspecialchars($order['order_number'] ?? $order['id']),
            'order' => $order,
        ]);
    }

    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/orders');
        }

        $order = (new Order())->find($id);
        if (!$order) {
            $this->redirect('/admin/orders', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບຄຳສັ່ງຊື້',
            ]);
        }

        $status = $_POST['order_status'] ?? '';
        $allowed = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];

        if (!in_array($status, $allowed)) {
            $this->redirect('/admin/orders/' . $id, [
                'error' => 1,
                'error_msg' => 'ສະຖານະບໍ່ຖືກຕ້ອງ',
            ]);
        }

        (new Order())->updateStatus($id, $status);

        $this->redirect('/admin/orders/' . $id, [
            'success' => 1,
            'success_msg' => 'ອັບເດດສະຖານະສຳເລັດ',
        ]);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/orders');
        }

        $order = (new Order())->find($id);
        if (!$order) {
            $this->redirect('/admin/orders', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບຄຳສັ່ງຊື້',
            ]);
        }

        (new Order())->delete($id);

        $this->redirect('/admin/orders', [
            'success' => 1,
            'success_msg' => 'ລົບຄຳສັ່ງຊື້ສຳເລັດ',
        ]);
    }

    public function bulkDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/orders');
        }

        $ids = $_POST['ids'] ?? [];
        if (empty($ids) || !is_array($ids)) {
            $this->redirect('/admin/orders', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາເລືອກລາຍການທີ່ຕ້ອງການລົບ',
            ]);
        }

        $ids = array_map('intval', $ids);
        $db = \App\Core\Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $db->prepare("DELETE FROM orders WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $db->commit();

            $this->redirect('/admin/orders', [
                'success' => 1,
                'success_msg' => 'ລົບ ' . count($ids) . ' ລາຍການສຳເລັດ',
            ]);
        } catch (\Exception $e) {
            $db->rollBack();
            $this->redirect('/admin/orders', [
                'error' => 1,
                'error_msg' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage(),
            ]);
        }
    }
}
