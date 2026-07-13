<?php

namespace App\Controllers\Admin;

use App\Models\BidCustomer;

class BidCustomerController extends \App\Controllers\BaseController
{
    public function index()
    {
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

        return view('pages.admin.bid-customers.index', [
            'title' => 'ລູກຄ້າທີ່ສະເໜີລາຄາ',
            'bidCustomers' => $bidCustomers,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function create()
    {
        return view('pages.admin.bid-customers.create', [
            'title' => 'ເພີ່ມລູກຄ້າທີ່ສະເໜີລາຄາໃໝ່',
        ]);
    }

    public function store()
    {
        $name = $_POST['name'] ?? '';

        if (empty($name)) {
            $this->redirect('/admin/bid-customers/create', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນຊື່ລູກຄ້າທີ່ສະເໜີລາຄາ',
            ]);
        }

        (new BidCustomer())->create([
            'name' => $name,
            'contact_person' => $_POST['contact_person'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'notes' => $_POST['notes'] ?? '',
            'tax_percent' => $_POST['tax_percent'] ?? 0,
            'status' => $_POST['status'] ?? 'Active',
        ]);

        $this->redirect('/admin/bid-customers', ['success' => 1]);
    }

    public function edit($id)
    {
        $bidCustomer = (new BidCustomer())->find($id);

        if (!$bidCustomer) {
            $this->redirect('/admin/bid-customers', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບລູກຄ້າທີ່ສະເໜີລາຄາ',
            ]);
        }

        return view('pages.admin.bid-customers.edit', [
            'title' => 'ແກ້ໄຂລູກຄ້າທີ່ສະເໜີລາຄາ',
            'bidCustomer' => $bidCustomer,
        ]);
    }

    public function update($id)
    {
        $bidCustomer = (new BidCustomer())->find($id);

        if (!$bidCustomer) {
            $this->redirect('/admin/bid-customers', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບລູກຄ້າທີ່ສະເໜີລາຄາ',
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
            $this->redirect("/admin/bid-customers/{$id}/edit", [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນຊື່ລູກຄ້າທີ່ສະເໜີລາຄາ',
            ]);
        }

        (new BidCustomer())->update($id, $data);
        $this->redirect('/admin/bid-customers', ['updated' => 1]);
    }

    public function updateTax($id)
    {
        $bidCustomer = (new BidCustomer())->find($id);

        if (!$bidCustomer) {
            $this->json(['error' => true, 'message' => 'ບໍ່ພົບລູກຄ້າທີ່ສະເໜີລາຄາ']);
            return;
        }

        $taxPercent = $_POST['tax_percent'] ?? 0;
        (new BidCustomer())->update($id, ['tax_percent' => $taxPercent]);

        $this->json(['success' => true, 'tax_percent' => $taxPercent]);
    }

    public function delete($id)
    {
        $bidCustomer = (new BidCustomer())->find($id);

        if (!$bidCustomer) {
            $this->redirect('/admin/bid-customers', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບລູກຄ້າທີ່ສະເໜີລາຄາ',
            ]);
        }

        (new BidCustomer())->delete($id);
        $this->redirect('/admin/bid-customers', ['deleted' => 1]);
    }

    public function bulkDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/bid-customers');
        }

        $ids = $_POST['ids'] ?? [];
        if (empty($ids) || !is_array($ids)) {
            $this->redirect('/admin/bid-customers', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາເລືອກລາຍການທີ່ຕ້ອງການລົບ',
            ]);
        }

        $ids = array_map('intval', $ids);
        $db = \App\Core\Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $db->prepare("DELETE FROM bid_customers WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $db->commit();

            $this->redirect('/admin/bid-customers', [
                'success' => 1,
                'success_msg' => 'ລົບ ' . count($ids) . ' ລາຍການສຳເລັດ',
            ]);
        } catch (\Exception $e) {
            $db->rollBack();
            $this->redirect('/admin/bid-customers', [
                'error' => 1,
                'error_msg' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage(),
            ]);
        }
    }
}
