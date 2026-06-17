<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Customer;

class POSController extends BaseController {
    public function index() {
        $products = (new Product())->all();
        $customers = (new Customer())->all();

        return view('pages.pos.index', [
            'title' => 'POS ຂາຍສິນຄ້າ',
            'products' => $products,
            'customers' => $customers,
        ]);
    }

    public function checkout() {
        header('Content-Type: application/json');

        $items = json_decode(file_get_contents('php://input'), true)['items'] ?? $_POST['items'] ?? [];
        if (is_string($items)) {
            $items = json_decode($items, true) ?: [];
        }

        if (empty($items)) {
            http_response_code(400);
            echo json_encode(['error' => 'ກະລຸນາເລືອກສິນຄ້າ']);
            exit;
        }

        $productModel = new Product();
        $lineItems = [];
        $subtotal = 0;

        foreach ($items as $item) {
            $productId = $item['product_id'] ?? 0;
            $quantity = (int)($item['quantity'] ?? 1);

            if ($quantity <= 0) continue;

            $product = $productModel->find($productId);
            if (!$product) {
                http_response_code(400);
                echo json_encode(['error' => "ບໍ່ພົບສິນຄ້າ ID: {$productId}"]);
                exit;
            }

            if ($product['stock'] < $quantity) {
                http_response_code(400);
                echo json_encode(['error' => "ສິນຄ້າ {$product['name']} ມີພຽງ {$product['stock']} {$product['unit']}"]);
                exit;
            }

            $price = (float)($item['price'] ?? $product['price']);
            $total = $price * $quantity;
            $subtotal += $total;

            $lineItems[] = [
                'product_id' => $productId,
                'product_name' => $product['name'],
                'quantity' => $quantity,
                'price' => $price,
                'total' => $total,
            ];
        }

        if (empty($lineItems)) {
            http_response_code(400);
            echo json_encode(['error' => 'ກະລຸນາເລືອກສິນຄ້າ']);
            exit;
        }

        $discount = (float)($_POST['discount'] ?? 0);
        $tax = (float)($_POST['tax'] ?? 0);
        $grandTotal = $subtotal - $discount + $tax;
        $customerId = $_POST['customer_id'] ?? null;
        $paymentMethod = $_POST['payment_method'] ?? 'cash';
        $amountPaid = (float)($_POST['amount_paid'] ?? $grandTotal);
        $change = max(0, $amountPaid - $grandTotal);

        $saleModel = new Sale();
        $saleId = $saleModel->create([
            'customer_id' => $customerId ?: null,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'grand_total' => $grandTotal,
            'payment_method' => $paymentMethod,
            'paid_amount' => $amountPaid,
            'change_amount' => $change,
        ], $lineItems);

        echo json_encode([
            'success' => true,
            'sale_id' => $saleId,
            'invoice_url' => url('/invoices/' . $saleId),
        ]);
        exit;
    }
}
