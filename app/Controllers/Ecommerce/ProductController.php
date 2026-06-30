<?php

namespace App\Controllers\Ecommerce;

use App\Models\Product;
use App\Models\Category;

class ProductController
{
    protected function redirect($path, $params = [])
    {
        $query = http_build_query($params);
        $url = url($path);
        if ($query) $url .= '?' . $query;
        header('Location: ' . $url);
        exit;
    }

    private function getCartCount()
    {
        $count = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += (int)($item['quantity'] ?? 0);
            }
        }
        return $count;
    }

    public function index()
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $search = trim($_GET['search'] ?? '');
        $categoryId = $_GET['category_id'] ?? '';
        $sort = $_GET['sort'] ?? 'newest';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 12;

        $categories = $categoryModel->all();

        $products = $productModel->searchPublic($search, $categoryId ?: null, $page, $perPage);
        $total = $productModel->countSearchPublic($search, $categoryId ?: null);
        $totalPages = max(1, ceil($total / $perPage));

        if ($sort === 'price_asc') {
            usort($products, function ($a, $b) {
                return $a['selling_price'] <=> $b['selling_price'];
            });
        } elseif ($sort === 'price_desc') {
            usort($products, function ($a, $b) {
                return $b['selling_price'] <=> $a['selling_price'];
            });
        }

        $cartCount = $this->getCartCount();

        return view('pages.ecommerce.products', [
            'layout' => 'ecommerce',
            'title' => 'ສິນຄ້າທັງໝົດ',
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
            'sort' => $sort,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'cartCount' => $cartCount,
            'hero' => false,
        ]);
    }

    public function show($slug)
    {
        $productModel = new Product();
        $product = $productModel->getBySlug($slug);

        if (!$product) {
            http_response_code(404);
            return view('pages.ecommerce.product-detail', [
                'layout' => 'ecommerce',
                'title' => 'ບໍ່ພົບສິນຄ້າ',
                'product' => null,
                'cartCount' => $this->getCartCount(),
                'hero' => false,
            ]);
        }

        $images = $productModel->getProductImages($product['id']);
        $related = $productModel->getRelated($product['id'], $product['category_id'], 4);

        $cartCount = $this->getCartCount();

        return view('pages.ecommerce.product-detail', [
            'layout' => 'ecommerce',
            'title' => $product['name'],
            'product' => $product,
            'images' => $images,
            'related' => $related,
            'cartCount' => $cartCount,
            'hero' => false,
        ]);
    }

    public function category($slug)
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $categories = $categoryModel->all();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 12;

        $products = $productModel->getByCategorySlug($slug, $page, $perPage);
        $total = $productModel->countByCategorySlug($slug);
        $totalPages = max(1, ceil($total / $perPage));

        $currentCategory = null;
        foreach ($categories as $cat) {
            if ($cat['slug'] === $slug) {
                $currentCategory = $cat;
                break;
            }
        }

        $cartCount = $this->getCartCount();

        return view('pages.ecommerce.products', [
            'layout' => 'ecommerce',
            'title' => ($currentCategory ? $currentCategory['name'] : 'ໝວດ') . ' - ສິນຄ້າ',
            'products' => $products,
            'categories' => $categories,
            'search' => '',
            'categoryId' => $currentCategory ? $currentCategory['id'] : '',
            'categorySlug' => $slug,
            'currentCategory' => $currentCategory,
            'sort' => 'newest',
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'cartCount' => $cartCount,
            'hero' => false,
        ]);
    }
}
