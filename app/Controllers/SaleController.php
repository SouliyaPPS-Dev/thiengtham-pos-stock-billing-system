<?php

namespace App\Controllers;

use App\Models\Sale;

class SaleController extends BaseController {
    public function index() {
        $saleModel = new Sale();

        $search = $_GET['search'] ?? '';
        $fromDate = $_GET['from_date'] ?? '';
        $toDate = $_GET['to_date'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        $sales = $saleModel->paginate($page, $perPage, $search, $fromDate, $toDate);
        $total = $saleModel->countAll($search, $fromDate, $toDate);
        $totalPages = max(1, ceil($total / $perPage));

        return view('pages.sales.index', [
            'title' => 'ປະຫວັດການຂາຍ',
            'sales' => $sales,
            'search' => $search,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function show($id) {
        $sale = (new Sale())->find($id);

        if (!$sale) {
            header('Location: ' . url('/sales') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບບິນ'));
            exit;
        }

        $items = (new Sale())->getItems($id);

        return view('pages.sales.show', [
            'title' => 'ໃບເກັບເງິນ #' . htmlspecialchars($sale['invoice_no']),
            'sale' => $sale,
            'items' => $items,
        ]);
    }
}
