<?php

namespace App\Controllers;

class LoginController {
    public function index() {
        if (isset($_SESSION['user'])) {
            header('Location: ' . url('/'));
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
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === 'admin' && $password === '123456') {
            $_SESSION['user'] = [
                'username' => 'admin',
                'name' => 'Admin User',
                'role' => 'admin'
            ];
            header('Location: ' . url('/'));
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
        header('Location: ' . url('/login'));
        exit;
    }
}
