<?php

namespace App\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Helpers\ImageKit;

class ProductController extends \App\Controllers\BaseController
{
    public function index()
    {
        $productModel = new Product();

        $search = $_GET['search'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        if ($search) {
            $products = $productModel->search($search);
            $total = $productModel->countSearch($search);
        } else {
            $products = $productModel->paginate($page, $perPage);
            $total = $productModel->countAll();
        }

        $totalPages = max(1, ceil($total / $perPage));

        return view('pages.admin.products.index', [
            'title' => 'ສິນຄ້າ',
            'products' => $products,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function create()
    {
        $categories = (new Category())->all();

        return view('pages.admin.products.create', [
            'title' => 'ເພີ່ມສິນຄ້າໃໝ່',
            'categories' => $categories,
        ]);
    }

    public function store()
    {
        $name = $_POST['name'] ?? '';
        $sku = $_POST['sku'] ?? '';

        if (empty($name)) {
            $this->redirect('/admin/products/create', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນຊື່ສິນຄ້າ',
            ]);
        }

        $imageUrl = null;
        if (!empty($_FILES['image']['name'])) {
            $imageUrl = ImageKit::upload('image', '/pos-stock/products');
        }

        (new Product())->create([
            'name' => $name,
            'sku' => $sku,
            'category_id' => ($_POST['category_id'] ?? '') ?: null,
            'selling_price' => $_POST['selling_price'] ?? $_POST['price'] ?? 0,
            'cost_price' => $_POST['cost_price'] ?? 0,
            'stock' => $_POST['stock'] ?? 0,
            'min_stock' => $_POST['min_stock'] ?? 5,
            'unit' => $_POST['unit'] ?? 'ຊິ້ນ',
            'barcode' => $_POST['barcode'] ?? '',
            'description' => $_POST['description'] ?? '',
            'image' => $imageUrl,
        ]);

        $this->redirect('/admin/products', ['success' => 1]);
    }

    public function edit($id)
    {
        $product = (new Product())->find($id);

        if (!$product) {
            $this->redirect('/admin/products', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບສິນຄ້າ',
            ]);
        }

        $categories = (new Category())->all();

        return view('pages.admin.products.edit', [
            'title' => 'ແກ້ໄຂສິນຄ້າ',
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update($id)
    {
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            $this->redirect('/admin/products', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບສິນຄ້າ',
            ]);
        }

        $name = $_POST['name'] ?? '';
        if (empty($name)) {
            $this->redirect("/admin/products/{$id}/edit", [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນຊື່ສິນຄ້າ',
            ]);
        }

        $data = [
            'name' => $name,
            'sku' => $_POST['sku'] ?? '',
            'category_id' => ($_POST['category_id'] ?? '') ?: null,
            'selling_price' => $_POST['selling_price'] ?? $_POST['price'] ?? 0,
            'cost_price' => $_POST['cost_price'] ?? 0,
            'stock' => $_POST['stock'] ?? 0,
            'min_stock' => $_POST['min_stock'] ?? 5,
            'unit' => $_POST['unit'] ?? 'ຊິ້ນ',
            'barcode' => $_POST['barcode'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];

        if (!empty($_FILES['image']['name'])) {
            $imageUrl = ImageKit::upload('image', '/pos-stock/products');
            if ($imageUrl) {
                $data['image'] = $imageUrl;
            }
        }

        $productModel->update($id, $data);
        $this->redirect('/admin/products', ['updated' => 1]);
    }

    public function delete($id)
    {
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            $this->redirect('/admin/products', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບສິນຄ້າ',
            ]);
        }

        $productModel->delete($id);
        $this->redirect('/admin/products', ['deleted' => 1]);
    }

    public function show($id)
    {
        $product = (new Product())->find($id);

        if (!$product) {
            $this->json(['error' => 'ບໍ່ພົບສິນຄ້າ'], 404);
        }

        $this->json($product);
    }

    public function search()
    {
        $query = $_GET['q'] ?? '';
        if (empty($query)) {
            $this->json([]);
        }

        $products = (new Product())->search($query);
        $this->json($products);
    }
}
