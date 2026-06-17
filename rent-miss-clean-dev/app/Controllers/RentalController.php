<?php

namespace App\Controllers;

class RentalController extends BaseController
{
    public function index()
    {
        $db = \App\Core\Database::getInstance()->getConnection();

        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
 
        $where = 'WHERE 1=1';
        $params = [];

        if (!empty($search)) {
            $where .= " AND (c.fullname LIKE ? OR r.invoice_number LIKE ? OR c.phone LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($status)) {
            $where .= " AND r.status = ?";
            $params[] = $status;
        }

        $stmt = $db->prepare("
            SELECT r.*, c.fullname as customer_name, c.phone as customer_phone, 
                   u.full_name as created_by_name, pm.name as payment_method_name
            FROM rentals r
            LEFT JOIN customers c ON r.customer_id = c.id
            LEFT JOIN users u ON r.user_id = u.id
            LEFT JOIN payment_methods pm ON r.payment_method_id = pm.id
            $where
            ORDER BY r.created_at DESC
        ");
        $stmt->execute($params);
        $rentals = $stmt->fetchAll();

        return view('pages.rentals.index', [
            'title' => 'ປະຫວັດບິນເຊົ່າເຄື່ອງ',
            'rentals' => $rentals,
            'search' => $search,
            'status' => $status
        ]);
    }

    public function show($id)
    {
        $db = \App\Core\Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT r.*, c.fullname as customer_name, c.phone as customer_phone,
                   c.address as customer_address, c.id_card_no as customer_id_card,
                   u.full_name as created_by_name, pm.name as payment_method_name,
                   s.store_name, s.store_phone, s.store_address, s.store_email, s.currency,
                   s.store_logo, s.receipt_footer, s.paper_size, s.rental_terms
            FROM rentals r
            LEFT JOIN customers c ON r.customer_id = c.id
            LEFT JOIN users u ON r.user_id = u.id
            LEFT JOIN payment_methods pm ON r.payment_method_id = pm.id
            LEFT JOIN settings s ON s.id = 1
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        $rental = $stmt->fetch();

        if (!$rental) {
            http_response_code(404);
            require __DIR__ . '/../../views/pages/404.php';
            return;
        }

        $stmt = $db->prepare("
            SELECT ri.*, p.name as product_name, p.code as product_code, p.size
            FROM rental_items ri
            LEFT JOIN products p ON ri.product_id = p.id
            WHERE ri.rental_id = ?
        ");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll();

        return view('pages.rentals.print_invoice', [
            'title' => 'ບິນເລກທີ: ' . $rental['invoice_number'],
            'rental' => $rental,
            'items' => $items,
            'layout' => false
        ]);
    }

    public function getItems($id)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT ri.*, p.name as product_name, p.code as product_code, p.size
            FROM rental_items ri
            LEFT JOIN products p ON ri.product_id = p.id
            WHERE ri.rental_id = ?
        ");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll();

        header('Content-Type: application/json');
        echo json_encode($items);
        exit;
    }

    public function getData($id)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT r.*, c.fullname as customer_name, c.phone as customer_phone
            FROM rentals r
            LEFT JOIN customers c ON r.customer_id = c.id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        $rental = $stmt->fetch();

        if (!$rental) {
            http_response_code(404);
            echo json_encode(['error' => 'Rental not found']);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode($rental);
        exit;
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('/rentals'));
            exit;
        }

        $id = $_POST['id'] ?? 0;
        if (!$id) {
            header('Location: ' . url('/rentals?error=1'));
            exit;
        }

        $db = \App\Core\Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("SELECT product_id, qty FROM rental_items WHERE rental_id = ?");
            $stmt->execute([$id]);
            $items = $stmt->fetchAll();

            $updateProductStmt = $db->prepare("UPDATE products SET status = 'Available' WHERE id = ?");
            $restoreStockStmt = $db->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            foreach ($items as $item) {
                $updateProductStmt->execute([$item['product_id']]);
                $restoreStockStmt->execute([$item['qty'], $item['product_id']]);
            }

            $stmt = $db->prepare("DELETE FROM rental_items WHERE rental_id = ?");
            $stmt->execute([$id]);

            $stmt = $db->prepare("DELETE FROM rentals WHERE id = ?");
            $stmt->execute([$id]);

            $db->commit();
            header('Location: ' . url('/rentals?deleted=1'));
        } catch (\Exception $e) {
            $db->rollBack();
            header('Location: ' . url('/rentals?error=1'));
        }
        exit;
    }

    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? '';

        if (!$id || !$status) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        $db = \App\Core\Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("UPDATE rentals SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);

            // If status is 'Returned' or 'Cancelled', restore product status and stock
            if ($status === 'Returned' || $status === 'Cancelled') {
                $stmt = $db->prepare("SELECT product_id, qty FROM rental_items WHERE rental_id = ?");
                $stmt->execute([$id]);
                $items = $stmt->fetchAll();

                $updateProductStmt = $db->prepare("UPDATE products SET status = 'Available' WHERE id = ?");
                $restoreStockStmt = $db->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
                foreach ($items as $item) {
                    $updateProductStmt->execute([$item['product_id']]);
                    $restoreStockStmt->execute([$item['qty'], $item['product_id']]);
                }
            }

            $db->commit();
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        } catch (\Exception $e) {
            $db->rollBack();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    public function notifications()
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT r.*, c.fullname as customer_name 
            FROM rentals r 
            JOIN customers c ON r.customer_id = c.id 
            ORDER BY r.created_at DESC 
            LIMIT 5
        ");
        $rentals = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'notifications' => $rentals]);
        exit;
    }
}
        