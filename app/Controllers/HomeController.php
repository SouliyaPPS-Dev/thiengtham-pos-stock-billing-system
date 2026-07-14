<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Customer;

class HomeController extends BaseController {
    public function index() {
        $productModel = new Product();
        $saleModel = new Sale();

        $fromDate = $_GET['from_date'] ?? null;
        $toDate = $_GET['to_date'] ?? null;

        $stats = [
            'total_products' => $productModel->getTotalProducts(),
            'low_stock' => $productModel->getLowStockCount(),
            'sales_today' => $saleModel->getTodayTotal(),
            'sales_month' => $saleModel->getMonthTotal($fromDate, $toDate),
            'recent_sales' => $saleModel->getRecent(10),
            'popular_products' => $saleModel->getPopularProducts(5),
            'sales_by_day' => $saleModel->getSalesByDay($fromDate, $toDate),
            'total_customers' => (new Customer())->getTotalCustomers(),
        ];

        return view('pages.home', [
            'title' => 'ໜ້າຫຼັກ - ' . $settings.site_name,
            'stats' => $stats,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }
}
