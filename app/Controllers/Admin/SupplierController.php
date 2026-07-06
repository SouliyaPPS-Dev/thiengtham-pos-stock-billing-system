<?php

namespace App\Controllers\Admin;

use App\Models\Supplier;

class SupplierController extends \App\Controllers\BaseController
{
    public function index()
    {
        $supplierModel = new Supplier();

        $search = $_GET['search'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        if ($search) {
            $suppliers = $supplierModel->search($search, $page, $perPage);
            $total = $supplierModel->countSearch($search);
        } else {
            $suppliers = $supplierModel->paginate($page, $perPage);
            $total = $supplierModel->countAll();
        }

        $totalPages = max(1, ceil($total / $perPage));

        return view('pages.admin.suppliers.index', [
            'title' => 'ຜູ້ສະໜອງ',
            'suppliers' => $suppliers,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function create()
    {
        return view('pages.admin.suppliers.create', [
            'title' => 'ເພີ່ມຜູ້ສະໜອງໃໝ່',
        ]);
    }

    public function store()
    {
        $name = $_POST['name'] ?? '';

        if (empty($name)) {
            $this->redirect('/admin/suppliers/create', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນຊື່ຜູ້ສະໜອງ',
            ]);
        }

        (new Supplier())->create([
            'name' => $name,
            'contact_person' => $_POST['contact_person'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'notes' => $_POST['notes'] ?? '',
            'tax_percent' => $_POST['tax_percent'] ?? 0,
            'status' => $_POST['status'] ?? 'Active',
        ]);

        $this->redirect('/admin/suppliers', ['success' => 1]);
    }

    public function edit($id)
    {
        $supplier = (new Supplier())->find($id);

        if (!$supplier) {
            $this->redirect('/admin/suppliers', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບຜູ້ສະໜອງ',
            ]);
        }

        return view('pages.admin.suppliers.edit', [
            'title' => 'ແກ້ໄຂຜູ້ສະໜອງ',
            'supplier' => $supplier,
        ]);
    }

    public function update($id)
    {
        $supplier = (new Supplier())->find($id);

        if (!$supplier) {
            $this->redirect('/admin/suppliers', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບຜູ້ສະໜອງ',
            ]);
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'contact_person' => $_POST['contact_person'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'notes' => $_POST['notes'] ?? '',
            'tax_percent' => $_POST['tax_percent'] ?? 0,
            'status' => $_POST['status'] ?? 'Active',
        ];

        if (empty($data['name'])) {
            $this->redirect("/admin/suppliers/{$id}/edit", [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນຊື່ຜູ້ສະໜອງ',
            ]);
        }

        (new Supplier())->update($id, $data);
        $this->redirect('/admin/suppliers', ['updated' => 1]);
    }

    public function updateTax($id)
    {
        $supplier = (new Supplier())->find($id);

        if (!$supplier) {
            $this->json(['error' => true, 'message' => 'ບໍ່ພົບຜູ້ສະໜອງ']);
            return;
        }

        $taxPercent = $_POST['tax_percent'] ?? 0;
        (new Supplier())->update($id, ['tax_percent' => $taxPercent]);

        $this->json(['success' => true, 'tax_percent' => $taxPercent]);
    }

    public function delete($id)
    {
        $supplier = (new Supplier())->find($id);

        if (!$supplier) {
            $this->redirect('/admin/suppliers', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບຜູ້ສະໜອງ',
            ]);
        }

        (new Supplier())->delete($id);
        $this->redirect('/admin/suppliers', ['deleted' => 1]);
    }
}
