<?php

namespace App\Controllers\Admin;

use App\Models\Category;

class CategoryController extends \App\Controllers\BaseController
{
    public function index()
    {
        $categories = (new Category())->all();

        return view('pages.admin.categories.index', [
            'title' => 'ໝວດສິນຄ້າ',
            'categories' => $categories,
        ]);
    }

    public function store()
    {
        $name = $_POST['name'] ?? '';

        if (empty($name)) {
            $this->redirect('/admin/categories', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນຊື່ໝວດ',
            ]);
        }

        (new Category())->create([
            'name' => $name,
            'description' => $_POST['description'] ?? '',
        ]);

        $this->redirect('/admin/categories', ['success' => 1]);
    }

    public function update($id)
    {
        $name = $_POST['name'] ?? '';

        if (empty($name)) {
            $this->redirect('/admin/categories', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນຊື່ໝວດ',
            ]);
        }

        (new Category())->update($id, [
            'name' => $name,
            'description' => $_POST['description'] ?? '',
        ]);

        $this->redirect('/admin/categories', ['updated' => 1]);
    }

    public function delete($id)
    {
        (new Category())->delete($id);
        $this->redirect('/admin/categories', ['deleted' => 1]);
    }

    public function bulkDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories');
        }

        $ids = $_POST['ids'] ?? [];
        if (empty($ids) || !is_array($ids)) {
            $this->redirect('/admin/categories', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາເລືອກລາຍການທີ່ຕ້ອງການລົບ',
            ]);
        }

        $ids = array_map('intval', $ids);
        $db = \App\Core\Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $db->prepare("DELETE FROM categories WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $db->commit();

            $this->redirect('/admin/categories', [
                'success' => 1,
                'success_msg' => 'ລົບ ' . count($ids) . ' ລາຍການສຳເລັດ',
            ]);
        } catch (\Exception $e) {
            $db->rollBack();
            $this->redirect('/admin/categories', [
                'error' => 1,
                'error_msg' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage(),
            ]);
        }
    }
}
