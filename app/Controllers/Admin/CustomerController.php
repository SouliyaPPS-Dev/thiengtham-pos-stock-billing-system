<?php

namespace App\Controllers\Admin;

use App\Models\Customer;

class CustomerController extends \App\Controllers\BaseController
{
    public function index()
    {
        $customerModel = new Customer();

        $search = $_GET['search'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        if ($search) {
            $customers = $customerModel->search($search);
            $total = $customerModel->countSearch($search);
        } else {
            $customers = $customerModel->paginate($page, $perPage);
            $total = $customerModel->countAll();
        }

        $totalPages = max(1, ceil($total / $perPage));

        return view('pages.admin.customers.index', [
            'title' => 'ລູກຄ້າ',
            'customers' => $customers,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'] ?? '';

            if (empty($fullname)) {
                $this->redirect('/admin/customers/create', [
                    'error' => 1,
                    'error_msg' => 'ກະລຸນາປ້ອນຊື່ລູກຄ້າ',
                ]);
            }

            (new Customer())->create([
                'fullname' => $fullname,
                'phone' => $_POST['phone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'address' => $_POST['address'] ?? '',
                'notes' => $_POST['notes'] ?? '',
            ]);

            $this->redirect('/admin/customers', ['success' => 1]);
        }

        return view('pages.admin.customers.create', [
            'title' => 'ເພີ່ມລູກຄ້າໃໝ່',
        ]);
    }

    public function edit($id)
    {
        $customer = (new Customer())->find($id);

        if (!$customer) {
            $this->redirect('/admin/customers', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບລູກຄ້າ',
            ]);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'fullname' => $_POST['fullname'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'address' => $_POST['address'] ?? '',
                'notes' => $_POST['notes'] ?? '',
            ];

            if (empty($data['fullname'])) {
                $this->redirect("/admin/customers/{$id}/edit", [
                    'error' => 1,
                    'error_msg' => 'ກະລຸນາປ້ອນຊື່ລູກຄ້າ',
                ]);
            }

            (new Customer())->update($id, $data);
            $this->redirect('/admin/customers', ['updated' => 1]);
        }

        return view('pages.admin.customers.edit', [
            'title' => 'ແກ້ໄຂລູກຄ້າ',
            'customer' => $customer,
        ]);
    }

    public function delete($id)
    {
        $customer = (new Customer())->find($id);

        if (!$customer) {
            $this->redirect('/admin/customers', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບລູກຄ້າ',
            ]);
        }

        (new Customer())->delete($id);
        $this->redirect('/admin/customers', ['deleted' => 1]);
    }

    public function view($id)
    {
        $customer = (new Customer())->find($id);

        if (!$customer) {
            $this->json(['error' => 'ບໍ່ພົບລູກຄ້າ'], 404);
        }

        $this->json($customer);
    }
}
