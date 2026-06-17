<?php

namespace App\Controllers;

use App\Models\Supplier;

class SupplierController extends BaseController {
    public function index() {
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

        return view('pages.suppliers.index', [
            'title' => 'ຜູ້ສະໜອງ',
            'suppliers' => $suppliers,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function create() {
        return view('pages.suppliers.create', [
            'title' => 'ເພີ່ມຜູ້ສະໜອງໃໝ່',
        ]);
    }

    public function store() {
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $address = $_POST['address'] ?? '';
        $contact_person = $_POST['contact_person'] ?? '';

        if (empty($name)) {
            header('Location: ' . url('/suppliers/create') . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຊື່ຜູ້ສະໜອງ'));
            exit;
        }

        (new Supplier())->create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'contact_person' => $contact_person,
        ]);

        header('Location: ' . url('/suppliers') . '?success=1');
        exit;
    }

    public function edit($id) {
        $supplier = (new Supplier())->find($id);

        if (!$supplier) {
            header('Location: ' . url('/suppliers') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບຜູ້ສະໜອງ'));
            exit;
        }

        return view('pages.suppliers.edit', [
            'title' => 'ແກ້ໄຂຜູ້ສະໜອງ',
            'supplier' => $supplier,
        ]);
    }

    public function update($id) {
        $supplierModel = new Supplier();

        $supplier = $supplierModel->find($id);
        if (!$supplier) {
            header('Location: ' . url('/suppliers') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບຜູ້ສະໜອງ'));
            exit;
        }

        $name = $_POST['name'] ?? '';
        if (empty($name)) {
            header('Location: ' . url("/suppliers/{$id}/edit") . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຊື່ຜູ້ສະໜອງ'));
            exit;
        }

        $supplierModel->update($id, [
            'name' => $name,
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'contact_person' => $_POST['contact_person'] ?? '',
        ]);

        header('Location: ' . url('/suppliers') . '?updated=1');
        exit;
    }

    public function delete($id) {
        $supplierModel = new Supplier();

        $supplier = $supplierModel->find($id);
        if (!$supplier) {
            header('Location: ' . url('/suppliers') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບຜູ້ສະໜອງ'));
            exit;
        }

        $supplierModel->delete($id);

        header('Location: ' . url('/suppliers') . '?deleted=1');
        exit;
    }
}
