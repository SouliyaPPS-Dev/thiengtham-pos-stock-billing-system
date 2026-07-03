<?php

namespace App\Controllers\Admin;

use App\Models\Quotation;
use App\Models\Product;
use App\Models\Supplier;

class QuotationController extends \App\Controllers\BaseController
{
    public function index()
    {
        $model = new Quotation();
        $search = $_GET['search'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        $quotations = $model->paginate($page, $perPage, $search);
        $total = $model->countAll($search);
        $totalPages = max(1, ceil($total / $perPage));

        return view('pages.admin.quotations.index', [
            'title' => 'ໃບສະເໜີລາຄາ',
            'quotations' => $quotations,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'templates' => Quotation::templates(),
        ]);
    }

    public function create()
    {
        $products = (new Product())->getAll('', [], 0, 0);
        $suppliers = (new Supplier())->all();
        $templates = Quotation::templates();
        $settings = (new \App\Models\Settings())->getAll();

        return view('pages.admin.quotations.create', [
            'title' => 'ສ້າງໃບສະເໜີລາຄາໃໝ່',
            'products' => $products,
            'suppliers' => $suppliers,
            'templates' => $templates,
            'quotation' => null,
            'settings' => $settings,
        ]);
    }

    public function store()
    {
        $model = new Quotation();
        $data = $_POST;
        $items = [];

        if (!empty($data['supplier_id'])) {
            $supplier = (new Supplier())->find($data['supplier_id']);
            if ($supplier) {
                $data['supplier_name'] = $supplier['name'];
                $data['supplier_contact'] = ($supplier['contact_person'] ?? '') . ($supplier['phone'] ? ' | ' . $supplier['phone'] : '');
            }
        }

        // Parse items from form
        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!empty($item['product_name'])) {
                    $items[] = $item;
                }
            }
        }

        // Parse items from JSON string
        if (empty($items) && !empty($_POST['items_json'])) {
            $parsed = json_decode($_POST['items_json'], true);
            if (is_array($parsed)) {
                $items = $parsed;
            }
        }

        try {
            $id = $model->create($data, $items);

            if (!empty($_POST['action']) && $_POST['action'] === 'save_print') {
                $this->redirect('/admin/quotations/' . $id . '/print');
            }

            $this->redirect('/admin/quotations', ['success' => 1]);
        } catch (\Exception $e) {
            $this->redirect('/admin/quotations/create', [
                'error' => 1,
                'error_msg' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        $quotation = (new Quotation())->find($id);

        if (!$quotation) {
            $this->redirect('/admin/quotations', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບໃບສະເໜີລາຄາ',
            ]);
        }

        $templates = Quotation::templates();
        $template = $templates[$quotation['company_template']] ?? $templates['luang-prabarg'];

        return view('pages.admin.quotations.show', [
            'title' => 'ໃບສະເໜີລາຄາ #' . $quotation['quotation_number'],
            'quotation' => $quotation,
            'template' => $template,
            'templates' => $templates,
        ]);
    }

    public function edit($id)
    {
        $quotation = (new Quotation())->find($id);

        if (!$quotation) {
            $this->redirect('/admin/quotations', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບໃບສະເໜີລາຄາ',
            ]);
        }

        $products = (new Product())->getAll('', [], 0, 0);
        $suppliers = (new Supplier())->all();
        $templates = Quotation::templates();
        $settings = (new \App\Models\Settings())->getAll();

        return view('pages.admin.quotations.create', [
            'title' => 'ແກ້ໄຂໃບສະເໜີລາຄາ',
            'products' => $products,
            'suppliers' => $suppliers,
            'templates' => $templates,
            'quotation' => $quotation,
            'settings' => $settings,
        ]);
    }

    public function update($id)
    {
        $model = new Quotation();
        $quotation = $model->find($id);

        if (!$quotation) {
            $this->redirect('/admin/quotations', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບໃບສະເໜີລາຄາ',
            ]);
        }

        $data = $_POST;
        $items = [];

        if (!empty($data['supplier_id'])) {
            $supplier = (new Supplier())->find($data['supplier_id']);
            if ($supplier) {
                $data['supplier_name'] = $supplier['name'];
                $data['supplier_contact'] = ($supplier['contact_person'] ?? '') . ($supplier['phone'] ? ' | ' . $supplier['phone'] : '');
            }
        }

        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!empty($item['product_name'])) {
                    $items[] = $item;
                }
            }
        }

        if (empty($items) && !empty($_POST['items_json'])) {
            $parsed = json_decode($_POST['items_json'], true);
            if (is_array($parsed)) {
                $items = $parsed;
            }
        }

        try {
            $model->update($id, $data, $items);

            if (!empty($_POST['action']) && $_POST['action'] === 'save_print') {
                $this->redirect('/admin/quotations/' . $id . '/print');
            }

            $this->redirect('/admin/quotations', ['updated' => 1]);
        } catch (\Exception $e) {
            $this->redirect('/admin/quotations/' . $id . '/edit', [
                'error' => 1,
                'error_msg' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        $model = new Quotation();
        $quotation = $model->find($id);

        if (!$quotation) {
            $this->redirect('/admin/quotations', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບໃບສະເໜີລາຄາ',
            ]);
        }

        $model->delete($id);
        $this->redirect('/admin/quotations', ['deleted' => 1]);
    }

    public function printView($id)
    {
        $quotation = (new Quotation())->find($id);

        if (!$quotation) {
            http_response_code(404);
            echo 'ໃບສະເໜີລາຄາບໍ່ພົບ';
            exit;
        }

        $templates = Quotation::templates();
        $template = $templates[$quotation['company_template']] ?? $templates['luang-prabarg'];

        $settings = (new \App\Models\Settings())->getAll();

        return view('pages.admin.quotations.print', [
            'title' => 'ພິມໃບສະເໜີລາຄາ',
            'quotation' => $quotation,
            'template' => $template,
            'settings' => $settings,
            'layout' => false,
        ]);
    }
}
