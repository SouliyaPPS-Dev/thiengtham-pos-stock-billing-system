<?php

namespace App\Controllers;

use App\Models\Inventory;
use App\Models\Category;
use App\Helpers\ImageKit;

class InventoryController extends BaseController {
    public function index() {
        $inventoryModel = new Inventory();
        $categoryModel = new Category();
        $inventory = $inventoryModel->getAll();
        $categories = $categoryModel->getAll();

        return view('pages.inventory', [
            'title' => 'ສາງຊຸດໄໝ - Inventory',
            'inventory' => $inventory,
            'categories' => $categories
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventoryModel = new Inventory();
            
            $imageUrl = null;
            if (!empty($_FILES['image']['name'])) {
                $imageUrl = ImageKit::upload('image', '/rent_miss_clean/products');
            }

            $data = [
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                'size' => $_POST['size'],
                'bust' => $_POST['bust'],
                'waist' => $_POST['waist'],
                'hips' => $_POST['hips'],
                'color' => $_POST['color'],
                'rental_price' => $_POST['rental_price'],
                'stock' => (int)($_POST['stock'] ?? 0),
                'image' => $imageUrl,
                'status' => $_POST['status'] ?? 'Available'
            ];
            
            if ($inventoryModel->create($data)) {
                header('Location: ' . url('/inventory?success=1'));
                exit;
            }
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $inventoryModel = new Inventory();
            
            $data = [
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                'size' => $_POST['size'],
                'bust' => $_POST['bust'],
                'waist' => $_POST['waist'],
                'hips' => $_POST['hips'],
                'color' => $_POST['color'],
                'rental_price' => $_POST['rental_price'],
                'stock' => (int)($_POST['stock'] ?? 0),
                'status' => $_POST['status']
            ];

            // Only update image if a new one is uploaded
            if (!empty($_FILES['image']['name'])) {
                $imageUrl = ImageKit::upload('image', '/rent_miss_clean/products');
                if ($imageUrl) {
                    $data['image'] = $imageUrl;
                }
            }
            
            if ($inventoryModel->update($id, $data)) {
                header('Location: ' . url('/inventory?updated=1'));
                exit;
            }
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $inventoryModel = new Inventory();
            if ($inventoryModel->delete($id)) {
                header('Location: ' . url('/inventory?deleted=1'));
                exit;
            }
        }
    }

    public function updateStock() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $stock = (int)$_POST['stock'];
            $inventoryModel = new Inventory();
            if ($inventoryModel->updateStock($id, $stock)) {
                header('Location: ' . url('/inventory?stock_updated=1'));
                exit;
            }
        }
    }

    public function rentalHistory($id) {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT r.id, r.invoice_number, r.pickup_date, r.return_date, r.created_at, r.status,
                   c.fullname as customer_name, c.phone as customer_phone, ri.qty
            FROM rental_items ri
            JOIN rentals r ON ri.rental_id = r.id
            LEFT JOIN customers c ON r.customer_id = c.id
            WHERE ri.product_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$id]);
        $history = $stmt->fetchAll();

        header('Content-Type: application/json');
        echo json_encode($history);
        exit;
    }
}
  