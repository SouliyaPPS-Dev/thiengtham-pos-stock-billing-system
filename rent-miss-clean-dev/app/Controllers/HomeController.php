<?php

namespace App\Controllers;

use App\Models\Product;

class HomeController extends BaseController {
    public function index() {
        $productModel = new Product();
        $fromDate = $_GET['from_date'] ?? null;
        $toDate = $_GET['to_date'] ?? null;
        $stats = $productModel->getStats($fromDate, $toDate);

        return view('pages.home', [
            'title' => 'ສະຫຼຸບລາຍຮັບ-ລາຍຈ່າຍ - Miss Clean',
            'stats' => $stats,
            'fromDate' => $fromDate,
            'toDate' => $toDate
        ]);
    }
}
 