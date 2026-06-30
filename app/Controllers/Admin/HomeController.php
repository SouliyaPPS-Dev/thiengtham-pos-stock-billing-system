<?php

namespace App\Controllers\Admin;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Supplier;

class HomeController extends \App\Controllers\BaseController
{
    public function index()
    {
        $productModel = new Product();
        $saleModel = new Sale();
        $customerModel = new Customer();
        $supplierModel = new Supplier();

        $fromDate = $_GET['from_date'] ?? date('Y-m-01');
        $toDate = $_GET['to_date'] ?? date('Y-m-t');
        $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));

        $chartFrom = $fromDate && $_GET['from_date'] ? $fromDate : $thirtyDaysAgo;
        $chartTo = $toDate && $_GET['to_date'] ? $toDate : date('Y-m-d');

        $stats = [
            'total_products' => $productModel->getTotalProducts(),
            'low_stock' => $productModel->getLowStockCount(),
            'sales_today' => $saleModel->getTodayTotal(),
            'total_customers' => $customerModel->getTotalCustomers(),
            'total_suppliers' => $supplierModel->getTotalSuppliers(),
            'recent_sales' => $saleModel->getRecent(10),
            'popular_products' => $saleModel->getPopularProducts(5),
            'sales_by_day' => $saleModel->getSalesByDay($chartFrom, $chartTo),
            'monthly_revenue' => $saleModel->getMonthTotal($fromDate, $toDate),
        ];

        return view('pages.admin.home', [
            'title' => 'ໜ້າຫຼັກ',
            'stats' => $stats,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }
}
