<?php

namespace App\Controllers;

use App\Models\BidCustomer;

class BidCustomerController extends BaseController {
    public function index() {
        $bidCustomerModel = new BidCustomer();

        $search = $_GET['search'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        if ($search) {
            $bidCustomers = $bidCustomerModel->search($search, $page, $perPage);
            $total = $bidCustomerModel->countSearch($search);
        } else {
            $bidCustomers = $bidCustomerModel->paginate($page, $perPage);
            $total = $bidCustomerModel->countAll();
        }

        $totalPages = max(1, ceil($total / $perPage));

        return view('pages.bid-customers.index', [
            'title' => 'ລູກຄ້າທີ່ສະເໜີລາຄາ',
            'bidCustomers' => $bidCustomers,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function create() {
        return view('pages.bid-customers.create', [
            'title' => 'ເພີ່ມລູກຄ້າທີ່ສະເໜີລາຄາໃໝ່',
        ]);
    }

    public function store() {
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $address = $_POST['address'] ?? '';
        $contact_person = $_POST['contact_person'] ?? '';

        if (empty($name)) {
            header('Location: ' . url('/bid-customers/create') . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຊື່ລູກຄ້າທີ່ສະເໜີລາຄາ'));
            exit;
        }

        (new BidCustomer())->create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'contact_person' => $contact_person,
        ]);

        header('Location: ' . url('/bid-customers') . '?success=1');
        exit;
    }

    public function edit($id) {
        $bidCustomer = (new BidCustomer())->find($id);

        if (!$bidCustomer) {
            header('Location: ' . url('/bid-customers') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບລູກຄ້າທີ່ສະເໜີລາຄາ'));
            exit;
        }

        return view('pages.bid-customers.edit', [
            'title' => 'ແກ້ໄຂລູກຄ້າທີ່ສະເໜີລາຄາ',
            'bidCustomer' => $bidCustomer,
        ]);
    }

    public function update($id) {
        $bidCustomerModel = new BidCustomer();

        $bidCustomer = $bidCustomerModel->find($id);
        if (!$bidCustomer) {
            header('Location: ' . url('/bid-customers') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບລູກຄ້າທີ່ສະເໜີລາຄາ'));
            exit;
        }

        $name = $_POST['name'] ?? '';
        if (empty($name)) {
            header('Location: ' . url("/bid-customers/{$id}/edit") . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຊື່ລູກຄ້າທີ່ສະເໜີລາຄາ'));
            exit;
        }

        $bidCustomerModel->update($id, [
            'name' => $name,
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'contact_person' => $_POST['contact_person'] ?? '',
        ]);

        header('Location: ' . url('/bid-customers') . '?updated=1');
        exit;
    }

    public function delete($id) {
        $bidCustomerModel = new BidCustomer();

        $bidCustomer = $bidCustomerModel->find($id);
        if (!$bidCustomer) {
            header('Location: ' . url('/bid-customers') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບລູກຄ້າທີ່ສະເໜີລາຄາ'));
            exit;
        }

        $bidCustomerModel->delete($id);

        header('Location: ' . url('/bid-customers') . '?deleted=1');
        exit;
    }
}
