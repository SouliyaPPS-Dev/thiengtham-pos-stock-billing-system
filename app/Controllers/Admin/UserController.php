<?php

namespace App\Controllers\Admin;

use App\Models\User;

class UserController extends \App\Controllers\BaseController
{
    public function index()
    {
        $users = (new User())->getAll();

        return view('pages.admin.users.index', [
            'title' => 'ພະນັກງານ',
            'users' => $users,
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($username) || empty($password)) {
                $this->redirect('/admin/users/create', [
                    'error' => 1,
                    'error_msg' => 'ກະລຸນາປ້ອນຊື່ຜູ້ໃຊ້ ແລະ ລະຫັດຜ່ານ',
                ]);
            }

            if ($password !== $confirmPassword) {
                $this->redirect('/admin/users/create', [
                    'error' => 1,
                    'error_msg' => 'ລະຫັດຜ່ານບໍ່ກົງກັນ',
                ]);
            }

            $existing = (new User())->getByUsername($username);
            if ($existing) {
                $this->redirect('/admin/users/create', [
                    'error' => 1,
                    'error_msg' => 'ຊື່ຜູ້ໃຊ້ນີ້ມີແລ້ວ',
                ]);
            }

            (new User())->create([
                'username' => $username,
                'password' => $password,
                'full_name' => $_POST['full_name'] ?? '',
                'role' => $_POST['role'] ?? 'cashier',
                'status' => $_POST['status'] ?? 'active',
            ]);

            $this->redirect('/admin/users', ['success' => 1]);
        }

        return view('pages.admin.users.create', [
            'title' => 'ເພີ່ມພະນັກງານໃໝ່',
        ]);
    }

    public function edit($id)
    {
        $user = (new User())->find($id);

        if (!$user) {
            $this->redirect('/admin/users', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບຜູ້ໃຊ້',
            ]);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name' => $_POST['full_name'] ?? '',
                'role' => $_POST['role'] ?? 'cashier',
                'status' => $_POST['status'] ?? 'active',
            ];

            $password = $_POST['password'] ?? '';
            if (!empty($password)) {
                $confirmPassword = $_POST['confirm_password'] ?? '';
                if ($password !== $confirmPassword) {
                    $this->redirect("/admin/users/{$id}/edit", [
                        'error' => 1,
                        'error_msg' => 'ລະຫັດຜ່ານບໍ່ກົງກັນ',
                    ]);
                }
                $data['password'] = $password;
            }

            (new User())->update($id, $data);
            $this->redirect('/admin/users', ['updated' => 1]);
        }

        return view('pages.admin.users.edit', [
            'title' => 'ແກ້ໄຂພະນັກງານ',
            'user' => $user,
        ]);
    }

    public function delete($id)
    {
        if ((int)$id === (int)($_SESSION['user']['id'] ?? 0)) {
            $this->redirect('/admin/users', [
                'error' => 1,
                'error_msg' => 'ບໍ່ສາມາດລົບຜູ້ໃຊ້ຂອງຕົນເອງໄດ້',
            ]);
        }

        $user = (new User())->find($id);
        if (!$user) {
            $this->redirect('/admin/users', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບຜູ້ໃຊ້',
            ]);
        }

        (new User())->delete($id);
        $this->redirect('/admin/users', ['deleted' => 1]);
    }
}
