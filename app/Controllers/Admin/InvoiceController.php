<?php

namespace App\Controllers\Admin;

use App\Models\Sale;
use App\Models\Settings;

class InvoiceController extends \App\Controllers\BaseController
{
    public function __construct()
    {
    }

    private function requireAuth()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . url('/admin/login'));
            exit;
        }
    }

    public function show($id)
    {
        $this->requireAuth();

        $sale = (new Sale())->find($id);

        if (!$sale) {
            $this->redirect('/admin/sales', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບບິນ',
            ]);
        }

        return view('pages.admin.invoices.show', [
            'title' => 'ໃບເກັບເງິນ #' . htmlspecialchars($sale['invoice_number'] ?? $sale['id']),
            'sale' => $sale,
        ]);
    }

    public function print($id)
    {
        $this->requireAuth();

        $sale = (new Sale())->find($id);

        if (!$sale) {
            $this->redirect('/admin/sales', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບບິນ',
            ]);
        }

        $settings = (new Settings())->getAll();

        return view('pages.invoices.print', [
            'title' => 'ພິມໃບບິນ',
            'invoice' => $sale,
            'store_name' => $settings['store_name'] ?? '',
            'store_phone' => $settings['store_phone'] ?? '',
            'store_address' => $settings['store_address'] ?? '',
            'layout' => false,
        ]);
    }

    public function printPublic($id)
    {
        $sale = (new Sale())->find($id);

        if (!$sale) {
            http_response_code(404);
            echo 'Invoice not found';
            exit;
        }

        $settings = (new Settings())->getAll();

        return view('pages.invoices.print', [
            'invoice' => $sale,
            'store_name' => $settings['store_name'] ?? '',
            'store_phone' => $settings['store_phone'] ?? '',
            'store_address' => $settings['store_address'] ?? '',
            'layout' => false,
        ]);
    }
}
