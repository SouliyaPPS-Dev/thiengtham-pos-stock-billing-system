<?php

namespace App\Controllers\Ecommerce;

use App\Core\Database;
use App\Models\Product;

class CheckoutController
{
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

    private function redirect($path, $params = [])
    {
        $query = http_build_query($params);
        $url = url($path);
        if ($query) $url .= '?' . $query;
        header('Location: ' . $url);
        exit;
    }

    public function index()
    {
        if (!isset($_SESSION['customer'])) {
            $_SESSION['checkout_redirect'] = '/checkout';
            $this->redirect('/login-customer');
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $this->redirect('/cart');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 0);
        }

        $customer = $_SESSION['customer'];

        $shipping = 0;
        $grandTotal = $subtotal + $shipping;

        $cartCount = $this->getCartCount();

        return view('pages.ecommerce.checkout', [
            'layout' => 'ecommerce',
            'title' => 'ຊຳລະເງິນ',
            'cart' => $cart,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'grandTotal' => $grandTotal,
            'customer' => $customer,
            'cartCount' => $cartCount,
            'hero' => false,
        ]);
    }

    public function process()
    {
        if (!isset($_SESSION['customer'])) {
            $this->json(['success' => false, 'message' => 'ກະລຸນາເຂົ້າສູ່ລະບົບກ່ອນ'], 401);
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $this->json(['success' => false, 'message' => 'ກະຕ່າສິນຄ້າວ່າງເປົ່າ'], 400);
        }

        $customerId = $_SESSION['customer']['id'];
        $customerName = trim($_POST['customer_name'] ?? $_SESSION['customer']['fullname'] ?? '');
        $customerPhone = trim($_POST['customer_phone'] ?? $_SESSION['customer']['phone'] ?? '');
        $customerEmail = trim($_POST['customer_email'] ?? $_SESSION['customer']['email'] ?? '');
        $shippingAddress = trim($_POST['shipping_address'] ?? $_SESSION['customer']['address'] ?? '');
        $shippingProvince = trim($_POST['shipping_province'] ?? $_SESSION['customer']['province'] ?? '');
        $shippingDistrict = trim($_POST['shipping_district'] ?? $_SESSION['customer']['district'] ?? '');
        $shippingVillage = trim($_POST['shipping_village'] ?? $_SESSION['customer']['village'] ?? '');
        $shippingLatitude = !empty($_POST['shipping_latitude']) ? $_POST['shipping_latitude'] : null;
        $shippingLongitude = !empty($_POST['shipping_longitude']) ? $_POST['shipping_longitude'] : null;
        $paymentMethod = $_POST['payment_method'] ?? 'cod';
        $notes = trim($_POST['notes'] ?? '');

        if (empty($customerName) || empty($customerPhone) || empty($shippingAddress)) {
            $this->json(['success' => false, 'message' => 'ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ'], 400);
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 0);
        }

        $shippingFee = 0;
        $discount = 0;
        $grandTotal = $subtotal + $shippingFee - $discount;

        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            $stmt = $db->prepare("INSERT INTO orders (order_number, customer_id, customer_name, customer_phone, customer_email,
                                                       shipping_address, shipping_province, shipping_district, shipping_village,
                                                       shipping_latitude, shipping_longitude,
                                                       shipping_fee, subtotal, discount, grand_total, payment_method, payment_status,
                                                       order_status, notes, created_at, updated_at)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', 'Pending', ?, NOW(), NOW())");
            $stmt->execute([
                $orderNumber,
                $customerId,
                $customerName,
                $customerPhone,
                $customerEmail,
                $shippingAddress,
                $shippingProvince,
                $shippingDistrict,
                $shippingVillage,
                $shippingLatitude,
                $shippingLongitude,
                $shippingFee,
                $subtotal,
                $discount,
                $grandTotal,
                $paymentMethod,
                $notes,
            ]);

            $orderId = $db->lastInsertId();

            $itemStmt = $db->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, subtotal)
                                       VALUES (?, ?, ?, ?, ?, ?)");
            $stockStmt = $db->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

            foreach ($cart as $item) {
                $itemSubtotal = (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 0);

                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['name'],
                    $item['quantity'],
                    $item['price'],
                    $itemSubtotal,
                ]);

                $stockStmt->execute([$item['quantity'], $item['product_id']]);
            }

            $db->commit();

            $updateStmt = $db->prepare("UPDATE customers SET fullname = ?, phone = ?, email = ?, address = ?, province = ?, district = ?, village = ?, latitude = ?, longitude = ? WHERE id = ?");
            $updateStmt->execute([
                $customerName,
                $customerPhone,
                $customerEmail,
                $shippingAddress,
                $shippingProvince,
                $shippingDistrict,
                $shippingVillage,
                $shippingLatitude,
                $shippingLongitude,
                $customerId,
            ]);

            $_SESSION['customer']['fullname'] = $customerName;
            $_SESSION['customer']['phone'] = $customerPhone;
            $_SESSION['customer']['email'] = $customerEmail;
            $_SESSION['customer']['address'] = $shippingAddress;
            $_SESSION['customer']['province'] = $shippingProvince;
            $_SESSION['customer']['district'] = $shippingDistrict;
            $_SESSION['customer']['village'] = $shippingVillage;
            $_SESSION['customer']['latitude'] = $shippingLatitude;
            $_SESSION['customer']['longitude'] = $shippingLongitude;

            unset($_SESSION['cart']);

            $this->redirect('/order/' . $orderId);

        } catch (\Exception $e) {
            $db->rollBack();
            $this->json(['success' => false, 'message' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage()], 500);
        }
    }

    public function orderStatusJson($id)
    {
        if (!$this->isAjax()) {
            http_response_code(400);
            exit;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, customer_id, order_status, payment_status, updated_at FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch();

        if (!$order) {
            $this->json(['error' => true, 'message' => 'ບໍ່ພົບຄຳສັ່ງຊື້'], 404);
        }

        if (isset($_SESSION['customer']) && (int)$order['customer_id'] !== (int)$_SESSION['customer']['id']) {
            $this->json(['error' => true, 'message' => 'ບໍ່ມີສິດເຂົ້າເຖິງ'], 403);
        }

        $statusSteps = ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered'];
        $currentIdx = array_search($order['order_status'], $statusSteps);

        $statusLabels = ['Pending' => 'ລໍຖ້າ', 'Confirmed' => 'ຢືນຢັນ', 'Processing' => 'ກຳລັງດຳເນີນ', 'Shipped' => 'ຈັດສົ່ງ', 'Delivered' => 'ສົ່ງແລ້ວ', 'Cancelled' => 'ຍົກເລີກ'];

        $this->json([
            'success' => true,
            'id' => $order['id'],
            'order_status' => $order['order_status'],
            'payment_status' => $order['payment_status'],
            'updated_at' => $order['updated_at'],
            'current_step' => $currentIdx !== false ? $currentIdx : -1,
            'total_steps' => count($statusSteps),
            'steps' => $statusSteps,
            'status_label' => $statusLabels[$order['order_status']] ?? $order['order_status'],
        ]);
    }

    public function orderDetail($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch();

        if (!$order) {
            http_response_code(404);
            echo 'ບໍ່ພົບຄຳສັ່ງຊື້';
            exit;
        }

        if (isset($_SESSION['customer']) && $order['customer_id'] != $_SESSION['customer']['id']) {
            http_response_code(403);
            echo 'ທ່ານບໍ່ມີສິດເຂົ້າເບິ່ງຄຳສັ່ງຊື້ນີ້';
            exit;
        }

        $stmtItems = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmtItems->execute([$id]);
        $items = $stmtItems->fetchAll();

        $cartCount = $this->getCartCount();

        return view('pages.ecommerce.order-confirm', [
            'layout' => 'ecommerce',
            'title' => 'ຄຳສັ່ງຊື້ #' . $order['order_number'],
            'order' => $order,
            'items' => $items,
            'cartCount' => $cartCount,
            'hero' => false,
        ]);
    }
}
