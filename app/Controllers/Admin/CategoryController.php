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
}
