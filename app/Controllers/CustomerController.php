<?php

namespace App\Controllers;

use App\Models\Customer;

class CustomerController extends BaseController {
    public function index() {
        $customerModel = new Customer();

        $search = $_GET['search'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        if ($search) {
            $customers = $customerModel->search($search, $page, $perPage);
            $total = $customerModel->countSearch($search);
        } else {
            $customers = $customerModel->paginate($page, $perPage);
            $total = $customerModel->countAll();
        }

        $totalPages = max(1, ceil($total / $perPage));

        return view('pages.customers.index', [
            'title' => 'ລູກຄ້າ',
            'customers' => $customers,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function create() {
        return view('pages.customers.create', [
            'title' => 'ເພີ່ມລູກຄ້າໃໝ່',
        ]);
    }

    public function store() {
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $address = $_POST['address'] ?? '';

        if (empty($name)) {
            header('Location: ' . url('/customers/create') . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຊື່ລູກຄ້າ'));
            exit;
        }

        (new Customer())->create([
            'fullname' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
        ]);

        header('Location: ' . url('/customers') . '?success=1');
        exit;
    }

    public function edit($id) {
        $customer = (new Customer())->find($id);

        if (!$customer) {
            header('Location: ' . url('/customers') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບລູກຄ້າ'));
            exit;
        }

        return view('pages.customers.edit', [
            'title' => 'ແກ້ໄຂລູກຄ້າ',
            'customer' => $customer,
        ]);
    }

    public function update($id) {
        $customerModel = new Customer();

        $customer = $customerModel->find($id);
        if (!$customer) {
            header('Location: ' . url('/customers') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບລູກຄ້າ'));
            exit;
        }

        $name = $_POST['name'] ?? '';
        if (empty($name)) {
            header('Location: ' . url("/customers/{$id}/edit") . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຊື່ລູກຄ້າ'));
            exit;
        }

        $customerModel->update($id, [
            'fullname' => $name,
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
        ]);

        header('Location: ' . url('/customers') . '?updated=1');
        exit;
    }

    public function delete($id) {
        $customerModel = new Customer();

        $customer = $customerModel->find($id);
        if (!$customer) {
            header('Location: ' . url('/customers') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບລູກຄ້າ'));
            exit;
        }

        $customerModel->delete($id);

        header('Location: ' . url('/customers') . '?deleted=1');
        exit;
    }

    public function view($id) {
        $customer = (new Customer())->find($id);

        if (!$customer) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ບໍ່ພົບລູກຄ້າ']);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode($customer);
        exit;
    }
}
