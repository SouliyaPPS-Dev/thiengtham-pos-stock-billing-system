<?php

namespace App\Controllers\Ecommerce;

use App\Core\Database;
use App\Models\Customer;

class AccountController
{
    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    protected function json($data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($path, $params = [])
    {
        $query = http_build_query($params);
        $url = url($path);
        if ($query) $url .= '?' . $query;
        header('Location: ' . $url);
        exit;
    }

    private function getCartCount()
    {
        $count = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += (int)($item['quantity'] ?? 0);
            }
        }
        return $count;
    }

    public function index()
    {
        if (!isset($_SESSION['customer'])) {
            $_SESSION['login_redirect'] = '/account';
            $this->redirect('/login-customer');
        }

        $customer = $_SESSION['customer'];
        $db = Database::getInstance()->getConnection();

        // Fetch fresh customer data from DB
        $stmt = $db->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->execute([$customer['id']]);
        $customerData = $stmt->fetch();

        if ($customerData) {
            // Merge session with fresh DB data for latitude/longitude
            $customerData['fullname'] = $customerData['fullname'] ?? $customer['fullname'];
            $customerData['phone'] = $customerData['phone'] ?? $customer['phone'];
            $customerData['email'] = $customerData['email'] ?? $customer['email'];
            $customer = $customerData;
        }

        // Fetch orders for this customer
        $stmtOrders = $db->prepare("SELECT o.*,
                                     (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) AS items_count
                                     FROM orders o
                                     WHERE o.customer_id = ?
                                     ORDER BY o.created_at DESC");
        $stmtOrders->execute([$customer['id']]);
        $orders = $stmtOrders->fetchAll();

        $cartCount = $this->getCartCount();

        return view('pages.ecommerce.account', [
            'layout' => 'ecommerce',
            'title' => 'ບັນຊີຂອງຂ້ອຍ',
            'customer' => $customer,
            'orders' => $orders,
            'cartCount' => $cartCount,
            'hero' => false,
        ]);
    }

    public function ordersStatusJson()
    {
        if (!$this->isAjax()) {
            http_response_code(400);
            exit;
        }

        if (!isset($_SESSION['customer'])) {
            $this->json(['error' => true, 'message' => 'ກະລຸນາເຂົ້າສູ່ລະບົບ'], 401);
        }

        $customerId = $_SESSION['customer']['id'];
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT id, order_number, order_status, payment_status, updated_at FROM orders WHERE customer_id = ? ORDER BY created_at DESC");
        $stmt->execute([$customerId]);
        $orders = $stmt->fetchAll();

        $statusLabels = ['Pending' => 'ລໍຖ້າ', 'Confirmed' => 'ຢືນຢັນ', 'Processing' => 'ກຳລັງດຳເນີນ', 'Shipped' => 'ຈັດສົ່ງ', 'Delivered' => 'ສົ່ງແລ້ວ', 'Cancelled' => 'ຍົກເລີກ'];
        $statusSteps = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered'];

        $result = [];
        foreach ($orders as $o) {
            $idx = array_search($o['order_status'], $statusSteps);
            $result[] = [
                'id' => $o['id'],
                'order_number' => $o['order_number'],
                'order_status' => $o['order_status'],
                'payment_status' => $o['payment_status'],
                'updated_at' => $o['updated_at'],
                'status_label' => $statusLabels[$o['order_status']] ?? $o['order_status'],
                'current_step' => $idx !== false ? $idx : -1,
            ];
        }

        $this->json(['success' => true, 'orders' => $result]);
    }

    public function update()
    {
        if (!isset($_SESSION['customer'])) {
            $this->json(['success' => false, 'message' => 'ກະລຸນາເຂົ້າສູ່ລະບົບ'], 401);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/account');
        }

        $customerId = $_SESSION['customer']['id'];
        $fullname = trim($_POST['fullname'] ?? '');
        $phone = trim(($_POST['phone_prefix'] ?? '+856') . ' ' . ($_POST['phone'] ?? ''));
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $province = trim($_POST['province'] ?? '');
        $district = trim($_POST['district'] ?? '');
        $village = trim($_POST['village'] ?? '');
        $latitude = !empty($_POST['latitude']) ? $_POST['latitude'] : null;
        $longitude = !empty($_POST['longitude']) ? $_POST['longitude'] : null;

        $db = Database::getInstance()->getConnection();

        try {
            $stmt = $db->prepare("UPDATE customers SET fullname = ?, phone = ?, email = ?, address = ?, province = ?, district = ?, village = ?, latitude = ?, longitude = ? WHERE id = ?");
            $stmt->execute([$fullname, $phone, $email, $address, $province, $district, $village, $latitude, $longitude, $customerId]);

            // Update session
            $_SESSION['customer'] = [
                'id' => $customerId,
                'fullname' => $fullname,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'province' => $province,
                'district' => $district,
                'village' => $village,
                'latitude' => $latitude ?? '',
                'longitude' => $longitude ?? '',
            ];

            $this->redirect('/account', ['success' => 1, 'success_msg' => 'ອັບເດດຂໍ້ມູນສຳເລັດ']);
        } catch (\Exception $e) {
            $this->redirect('/account', ['error' => 1, 'error_msg' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage()]);
        }
    }
}
