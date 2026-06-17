<?php

namespace App\Controllers;

use App\Models\Sale;
use App\Models\Settings;

class InvoiceController extends BaseController {
    public function show($id) {
        $sale = (new Sale())->find($id);

        if (!$sale) {
            header('Location: ' . url('/sales') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບບິນ'));
            exit;
        }

        $items = (new Sale())->getItems($id);
        $settings = (new Settings())->getAll();

        return view('pages.invoices.show', [
            'title' => 'ໃບເກັບເງິນ #' . htmlspecialchars($sale['invoice_no']),
            'sale' => $sale,
            'items' => $items,
            'settings' => $settings,
        ]);
    }

    public function print($id) {
        $sale = (new Sale())->find($id);

        if (!$sale) {
            header('Location: ' . url('/sales') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບບິນ'));
            exit;
        }

        $items = (new Sale())->getItems($id);
        $settings = (new Settings())->getAll();

        return view('pages.invoices.print', [
            'title' => 'ພິມໃບເກັບເງິນ #' . htmlspecialchars($sale['invoice_no']),
            'sale' => $sale,
            'items' => $items,
            'settings' => $settings,
            'layout' => false,
        ]);
    }
}
