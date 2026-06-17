<?php

namespace App\Controllers;

use App\Models\Category;

class CategoryController extends BaseController {
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryModel = new Category();
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? null
            ];
            
            if ($categoryModel->create($data)) {
                header('Location: ' . url('/inventory?success=1'));
                exit;
            }
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $categoryModel = new Category();
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? null
            ];
            
            if ($categoryModel->update($id, $data)) {
                header('Location: ' . url('/inventory?updated=1'));
                exit;
            }
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $categoryModel = new Category();
            if ($categoryModel->delete($id)) {
                header('Location: ' . url('/inventory?deleted=1'));
                exit;
            } else {
                // If delete fails (e.g. products exist), redirect with error
                header('Location: ' . url('/inventory?error=1&error_msg=' . urlencode('ໝວດໝູ່່ນີ້ມີສິນຄ້າຢູ່ພາຍໃນ, ກະລຸນາລຶບສິນຄ້າອອກກ່ອນ ຫຼື ຍ້າຍໄປໝວດໝູ່້ອື່ນ.')));
                exit;
            }
        }
    }
}
