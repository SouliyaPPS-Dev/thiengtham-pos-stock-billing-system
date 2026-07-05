<?php

namespace App\Controllers\Admin;

use App\Models\PaymentMethod;

class PaymentMethodController extends \App\Controllers\BaseController
{
    public function index()
    {
        $methods = (new PaymentMethod())->getAll();

        return view('pages.admin.payment_methods.index', [
            'title' => 'ວິທີຊຳລະ',
            'methods' => $methods,
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/settings#payment-methods');
        }

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $this->redirect('/admin/settings#payment-methods', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນຊື່ວິທີຊຳລະ',
            ]);
        }

        (new PaymentMethod())->create([
            'name' => $name,
            'details' => trim($_POST['details'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ]);

        $this->redirect('/admin/settings#payment-methods', [
            'success' => 1,
            'success_msg' => 'ເພີ່ມວິທີຊຳລະສຳເລັດ',
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/settings#payment-methods');
        }

        $method = (new PaymentMethod())->getById($id);
        if (!$method) {
            $this->redirect('/admin/settings#payment-methods', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບວິທີຊຳລະ',
            ]);
        }

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $this->redirect('/admin/settings#payment-methods', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນຊື່ວິທີຊຳລະ',
            ]);
        }

        (new PaymentMethod())->update($id, [
            'name' => $name,
            'details' => trim($_POST['details'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ]);

        $this->redirect('/admin/settings#payment-methods', [
            'success' => 1,
            'success_msg' => 'ອັບເດດວິທີຊຳລະສຳເລັດ',
        ]);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/settings#payment-methods');
        }

        (new PaymentMethod())->delete($id);

        $this->redirect('/admin/settings#payment-methods', [
            'success' => 1,
            'success_msg' => 'ລົບວິທີຊຳລະສຳເລັດ',
        ]);
    }
}
