<?php

namespace App\Controllers;

use App\Models\User;

class LoginController {
    public function index() {
        if (isset($_SESSION['user'])) {
            header('Location: ' . url('/admin'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->login();
        }

        return view('pages.login', [
            'title' => 'ເຂົ້າສູ່ລະບົບ',
            'no_nav' => true
        ]);
    }

    public function login() {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            return view('pages.login', [
                'title' => 'ເຂົ້າສູ່ລະບົບ',
                'error' => 'ກະລຸນາປ້ອນຊື່ຜູ້ໃຊ້ ແລະ ລະຫັດຜ່ານ',
                'no_nav' => true
            ]);
        }

        $userModel = new User();
        $user = $userModel->getByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'Active') {
                return view('pages.login', [
                    'title' => 'ເຂົ້າສູ່ລະບົບ',
                    'error' => 'ບັນຊີນີ້ຖືກປິດໃຊ້ງານ',
                    'no_nav' => true
                ]);
            }

            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['full_name'],
                'role' => $user['role']
            ];

            header('Location: ' . url('/admin'));
            exit;
        }

        return view('pages.login', [
            'title' => 'ເຂົ້າສູ່ລະບົບ',
            'error' => 'ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ',
            'no_nav' => true
        ]);
    }

    public function logout() {
        session_destroy();
        header('Location: ' . url('/admin/login'));
        exit;
    }
}
