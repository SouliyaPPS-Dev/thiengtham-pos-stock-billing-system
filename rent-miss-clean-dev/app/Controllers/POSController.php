<?php

namespace App\Controllers;

use App\Models\Product;

class POSController extends BaseController {
    public function index() {
        $productModel = new Product();
        $categoryModel = new \App\Models\Category();
        $customerModel = new \App\Models\Customer();
        $paymentMethodModel = new \App\Models\PaymentMethod();
        $settingModel = new \App\Models\Setting();

        $products = $productModel->getAll();
        $categories = $categoryModel->getAll();
        $customers = $customerModel->getCustomers("WHERE c.status = 'Active'", [], 1000, 0);
        $paymentMethods = $paymentMethodModel->getAll();
        $settings = $settingModel->get();

        return view('pages.pos', [
            'title' => 'Miss Clean POS ລະບົບຂາຍ',
            'products' => $products,
            'categories' => $categories,
            'customers' => $customers,
            'paymentMethods' => $paymentMethods,
            'settings' => $settings
        ]);
    }

    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid data']);
                exit;
            }

            $db = \App\Core\Database::getInstance()->getConnection();
            $db->beginTransaction();

            try {
                // 1. Generate invoice number
                $datePart = date('dm');
                $stmt = $db->query("SELECT COUNT(*) as cnt FROM rentals WHERE DATE(created_at) = CURDATE()");
                $todayCount = $stmt->fetch()['cnt'] + 1;
                $invoiceNumber = 'INV-' . $datePart . '-S1-' . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

                // 2. Calculate change amount
                $changeAmount = max(0, $data['paid_amount'] - $data['grand_total']);

                // 3. Insert into rentals table
                $stmt = $db->prepare("
                    INSERT INTO rentals (
                        invoice_number, customer_id, user_id, pickup_date, return_date, 
                        total_rental_fee, total_deposit, discount, grand_total, 
                        paid_amount, change_amount, payment_status, payment_method_id, 
                        guarantee_id_card, guarantee_passport, guarantee_family_book, guarantee_cash,
                        status, created_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', NOW())
                ");
                
                $stmt->execute([
                    $invoiceNumber,
                    $data['customer_id'],
                    $_SESSION['user']['id'] ?? 1,
                    $data['pickup_date'],
                    $data['return_date'],
                    $data['subtotal'],
                    $data['total_deposit'],
                    $data['discount'],
                    $data['grand_total'],
                    $data['paid_amount'],
                    $changeAmount,
                    $data['payment_status'],
                    $data['payment_method_id'],
                    $data['guarantee']['idCard'] ? 1 : 0,
                    $data['guarantee']['passport'] ? 1 : 0,
                    $data['guarantee']['familyBook'] ? 1 : 0,
                    $data['guarantee']['cash'] ? 1 : 0
                ]);
                
                $rentalId = $db->lastInsertId();

                // 2. Insert into rental_items and update product status
                $itemStmt = $db->prepare("
                    INSERT INTO rental_items (rental_id, product_id, rental_price, qty)
                    VALUES (?, ?, ?, ?)
                ");
                
                $updateProductStmt = $db->prepare("UPDATE products SET status = 'Rented' WHERE id = ?");
                $deductStockStmt = $db->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");

                foreach ($data['items'] as $item) {
                    $itemStmt->execute([
                        $rentalId,
                        $item['id'],
                        $item['rental_price'],
                        $item['qty']
                    ]);
                    
                    $updateProductStmt->execute([$item['id']]);
                    $deductStockStmt->execute([$item['qty'], $item['id'], $item['qty']]);
                }

                $db->commit();
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Rental processed successfully',
                    'rental_id' => $rentalId,
                    'invoice_number' => $invoiceNumber,
                    'change_amount' => $changeAmount
                ]);
                exit;

            } catch (\Exception $e) {
                $db->rollBack();
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
        }
    }
}
   