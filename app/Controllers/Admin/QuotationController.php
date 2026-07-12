<?php

namespace App\Controllers\Admin;

use App\Models\Quotation;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;

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
        $customers = (new Customer())->all();
        $templates = Quotation::templates();
        $settings = (new \App\Models\Settings())->getAll();

        return view('pages.admin.quotations.create', [
            'title' => 'ສ້າງໃບສະເໜີລາຄາໃໝ່',
            'products' => $products,
            'suppliers' => $suppliers,
            'customers' => $customers,
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

        if (!empty($data['customer_id'])) {
            $customer = (new Customer())->find($data['customer_id']);
            if ($customer) {
                $data['customer_name'] = $customer['fullname'];
                $data['customer_contact'] = ($customer['phone'] ?? '') . ($customer['email'] ? ' | ' . $customer['email'] : '');
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
        $customers = (new Customer())->all();
        $templates = Quotation::templates();
        $settings = (new \App\Models\Settings())->getAll();

        return view('pages.admin.quotations.create', [
            'title' => 'ແກ້ໄຂໃບສະເໜີລາຄາ',
            'products' => $products,
            'suppliers' => $suppliers,
            'customers' => $customers,
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

        if (!empty($data['customer_id'])) {
            $customer = (new Customer())->find($data['customer_id']);
            if ($customer) {
                $data['customer_name'] = $customer['fullname'];
                $data['customer_contact'] = ($customer['phone'] ?? '') . ($customer['email'] ? ' | ' . $customer['email'] : '');
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

        $supplier = null;
        if (!empty($quotation['supplier_id'])) {
            $supplier = (new \App\Models\Supplier())->find($quotation['supplier_id']);
        }

        return view('pages.admin.quotations.print', [
            'title' => 'ພິມໃບສະເໜີລາຄາ',
            'quotation' => $quotation,
            'template' => $template,
            'settings' => $settings,
            'supplier' => $supplier,
            'layout' => false,
        ]);
    }

    public function duplicate($id)
    {
        $model = new Quotation();
        $newId = $model->duplicate($id);

        if (!$newId) {
            $this->redirect('/admin/quotations', [
                'error' => 1,
                'error_msg' => 'ບໍ່ສາມາດສຳເນົາໄດ້',
            ]);
        }

        $this->redirect('/admin/quotations/' . $newId . '/edit', ['duplicated' => 1]);
    }

    public function convertToSale($id)
    {
        $model = new Quotation();

        try {
            $saleId = $model->convertToSale($id);

            if (!$saleId) {
                $this->redirect('/admin/quotations', [
                    'error' => 1,
                    'error_msg' => 'ບໍ່ສາມາດປ່ຽນເປັນບິນໄດ້',
                ]);
            }

            $this->redirect('/admin/sales', ['converted' => 1]);
        } catch (\Exception $e) {
            $this->redirect('/admin/quotations/' . $id, [
                'error' => 1,
                'error_msg' => $e->getMessage(),
            ]);
        }
    }

    public function exportCsv()
    {
        $search = $_GET['search'] ?? '';
        $model = new Quotation();
        $data = $model->exportCsv($search);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=quotations_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, $data['header']);

        foreach ($data['rows'] as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    public function updateStatus($id)
    {
        $model = new Quotation();
        $quotation = $model->find($id);

        if (!$quotation) {
            $this->redirect('/admin/quotations', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບໃບສະເໜີລາຄາ',
            ]);
        }

        $newStatus = $_POST['status'] ?? '';
        $validStatuses = ['Draft', 'Sent', 'Approved', 'Rejected'];

        if (!in_array($newStatus, $validStatuses)) {
            $this->redirect('/admin/quotations/' . $id, [
                'error' => 1,
                'error_msg' => 'ສະຖານະບໍ່ຖືກຕ້ອງ',
            ]);
        }

        $model->addHistory($id, 'status_changed', $quotation['status'], $newStatus, 'ປ່ຽນສະຖານະເປັນ ' . $newStatus);

        $stmt = $model->db()->prepare("UPDATE quotations SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $id]);

        $this->redirect('/admin/quotations/' . $id, ['status_updated' => 1]);
    }
}
