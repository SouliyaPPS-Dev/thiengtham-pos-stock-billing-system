<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Helpers\ImageKit;

class ProductController extends BaseController {
    public function index() {
        $productModel = new Product();

        $search = $_GET['search'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        if ($search) {
            $products = $productModel->search($search, $page, $perPage);
            $total = $productModel->countSearch($search);
        } else {
            $products = $productModel->paginate($page, $perPage);
            $total = $productModel->countAll();
        }

        $totalPages = max(1, ceil($total / $perPage));

        return view('pages.products.index', [
            'title' => 'ສິນຄ້າ',
            'products' => $products,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function create() {
        $categories = (new Category())->all();

        return view('pages.products.create', [
            'title' => 'ເພີ່ມສິນຄ້າໃໝ່',
            'categories' => $categories,
        ]);
    }

    public function store() {
        $name = $_POST['name'] ?? '';
        $sku = $_POST['sku'] ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $price = $_POST['price'] ?? 0;
        $cost_price = $_POST['cost_price'] ?? 0;
        $stock = $_POST['stock'] ?? 0;
        $min_stock = $_POST['min_stock'] ?? 5;
        $unit = $_POST['unit'] ?? 'ຊິ້ນ';
        $barcode = $_POST['barcode'] ?? '';
        $description = $_POST['description'] ?? '';

        if (empty($name)) {
            header('Location: ' . url('/products/create') . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຊື່ສິນຄ້າ'));
            exit;
        }

        $imageUrl = null;
        if (!empty($_FILES['image']['name'])) {
            $imageUrl = ImageKit::upload('image', '/pos-stock/products');
        }

        (new Product())->create([
            'name' => $name,
            'sku' => $sku,
            'category_id' => $category_id ?: null,
            'selling_price' => $price,
            'cost_price' => $cost_price,
            'stock' => $stock,
            'min_stock' => $min_stock,
            'unit' => $unit,
            'barcode' => $barcode,
            'description' => $description,
            'image' => $imageUrl,
        ]);

        header('Location: ' . url('/products') . '?success=1');
        exit;
    }

    public function edit($id) {
        $product = (new Product())->find($id);

        if (!$product) {
            header('Location: ' . url('/products') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບສິນຄ້າ'));
            exit;
        }

        $categories = (new Category())->all();

        return view('pages.products.edit', [
            'title' => 'ແກ້ໄຂສິນຄ້າ',
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update($id) {
        $productModel = new Product();

        $product = $productModel->find($id);
        if (!$product) {
            header('Location: ' . url('/products') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບສິນຄ້າ'));
            exit;
        }

        $name = $_POST['name'] ?? '';
        $sku = $_POST['sku'] ?? '';
        if (empty($name)) {
            header('Location: ' . url("/products/{$id}/edit") . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຊື່ສິນຄ້າ'));
            exit;
        }

        $data = [
            'name' => $name,
            'sku' => $sku ?? '',
            'category_id' => ($_POST['category_id'] ?? '') ?: null,
            'selling_price' => $_POST['price'] ?? 0,
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

        header('Location: ' . url('/products') . '?updated=1');
        exit;
    }

    public function delete($id) {
        $productModel = new Product();

        $product = $productModel->find($id);
        if (!$product) {
            header('Location: ' . url('/products') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບສິນຄ້າ'));
            exit;
        }

        $productModel->delete($id);

        header('Location: ' . url('/products') . '?deleted=1');
        exit;
    }

    public function show($id) {
        $product = (new Product())->find($id);

        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'ບໍ່ພົບສິນຄ້າ']);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode($product);
        exit;
    }
}
