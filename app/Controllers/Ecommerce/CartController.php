<?php

namespace App\Controllers\Ecommerce;

use App\Models\Product;

class CartController
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

    private function json($data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
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
        $cart = $_SESSION['cart'] ?? [];

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 0);
        }

        $cartCount = $this->getCartCount();

        return view('pages.ecommerce.cart', [
            'layout' => 'ecommerce',
            'title' => 'ກະຕ່າສິນຄ້າ',
            'cart' => $cart,
            'subtotal' => $subtotal,
            'cartCount' => $cartCount,
            'hero' => false,
        ]);
    }

    public function add()
    {
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = max(1, (int)($_POST['quantity'] ?? 1));

        if ($productId <= 0) {
            $this->json(['success' => false, 'message' => 'ບໍ່ພົບສິນຄ້າ'], 400);
        }

        $product = (new Product())->find($productId);
        if (!$product || $product['status'] !== 'Active') {
            $this->json(['success' => false, 'message' => 'ບໍ່ພົບສິນຄ້າ'], 404);
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'product_id' => $productId,
                'name' => $product['name'],
                'price' => (float)$product['selling_price'],
                'compare_price' => $product['compare_price'] ? (float)$product['compare_price'] : null,
                'quantity' => $quantity,
                'image' => $product['image'] ?? '',
                'slug' => $product['slug'] ?? '',
            ];
        }

        $cartCount = $this->getCartCount();

        $this->json([
            'success' => true,
            'message' => 'ເພີ່ມສິນຄ້າໃສ່ກະຕ່າແລ້ວ',
            'cartCount' => $cartCount,
        ]);
    }

    public function update()
    {
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = max(0, (int)($_POST['quantity'] ?? 0));

        if (!isset($_SESSION['cart'][$productId])) {
            $this->json(['success' => false, 'message' => 'ບໍ່ພົບສິນຄ້າໃນກະຕ່າ'], 404);
        }

        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        }

        $cart = $_SESSION['cart'] ?? [];
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 0);
        }

        $this->json([
            'success' => true,
            'message' => 'ອັບເດດຈຳນວນສິນຄ້າແລ້ວ',
            'cartCount' => $this->getCartCount(),
            'subtotal' => $subtotal,
        ]);
    }

    public function remove()
    {
        $productId = (int)($_POST['product_id'] ?? 0);

        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }

        $cart = $_SESSION['cart'] ?? [];
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 0);
        }

        $this->json([
            'success' => true,
            'message' => 'ລຶບສິນຄ້າອອກຈາກກະຕ່າແລ້ວ',
            'cartCount' => $this->getCartCount(),
            'subtotal' => $subtotal,
        ]);
    }
}
