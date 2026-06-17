<?php

namespace App\Controllers;

use App\Models\Category;

class CategoryController extends BaseController {
    public function index() {
        $categories = (new Category())->all();

        return view('pages.categories.index', [
            'title' => 'ໝວດສິນຄ້າ',
            'categories' => $categories,
        ]);
    }

    public function store() {
        $name = $_POST['name'] ?? '';

        if (empty($name)) {
            header('Location: ' . url('/categories') . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຊື່ໝວດສິນຄ້າ'));
            exit;
        }

        (new Category())->create([
            'name' => $name,
            'description' => $_POST['description'] ?? '',
        ]);

        header('Location: ' . url('/categories') . '?success=1');
        exit;
    }

    public function update($id) {
        $categoryModel = new Category();

        $category = $categoryModel->find($id);
        if (!$category) {
            header('Location: ' . url('/categories') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບໝວດສິນຄ້າ'));
            exit;
        }

        $name = $_POST['name'] ?? '';
        if (empty($name)) {
            header('Location: ' . url('/categories') . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຊື່ໝວດສິນຄ້າ'));
            exit;
        }

        $categoryModel->update($id, [
            'name' => $name,
            'description' => $_POST['description'] ?? '',
        ]);

        header('Location: ' . url('/categories') . '?updated=1');
        exit;
    }

    public function delete($id) {
        $categoryModel = new Category();

        $category = $categoryModel->find($id);
        if (!$category) {
            header('Location: ' . url('/categories') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບໝວດສິນຄ້າ'));
            exit;
        }

        $categoryModel->delete($id);

        header('Location: ' . url('/categories') . '?deleted=1');
        exit;
    }
}
