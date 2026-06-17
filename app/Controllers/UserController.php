<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends BaseController {
    public function index() {
        $users = (new User())->all();

        return view('pages.users.index', [
            'title' => 'ພະນັກງານ',
            'users' => $users,
        ]);
    }

    public function create() {
        return view('pages.users.create', [
            'title' => 'ເພີ່ມພະນັກງານໃໝ່',
        ]);
    }

    public function store() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $name = $_POST['name'] ?? '';
        $role = $_POST['role'] ?? 'staff';

        if (empty($username) || empty($password) || empty($name)) {
            header('Location: ' . url('/users/create') . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບ'));
            exit;
        }

        $existing = (new User())->findByUsername($username);
        if ($existing) {
            header('Location: ' . url('/users/create') . '?error=1&error_msg=' . urlencode('ຊື່ຜູ້ໃຊ້ນີ້ມີແລ້ວ'));
            exit;
        }

        (new User())->create([
            'username' => $username,
            'password' => $password,
            'full_name' => $name,
            'role' => $role,
        ]);

        header('Location: ' . url('/users') . '?success=1');
        exit;
    }

    public function edit($id) {
        $user = (new User())->find($id);

        if (!$user) {
            header('Location: ' . url('/users') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບຜູ້ໃຊ້'));
            exit;
        }

        return view('pages.users.edit', [
            'title' => 'ແກ້ໄຂພະນັກງານ',
            'user' => $user,
        ]);
    }

    public function update($id) {
        $userModel = new User();

        $user = $userModel->find($id);
        if (!$user) {
            header('Location: ' . url('/users') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບຜູ້ໃຊ້'));
            exit;
        }

        $data = [
            'full_name' => $_POST['name'] ?? $user['full_name'],
            'role' => $_POST['role'] ?? $user['role'],
        ];

        $password = $_POST['password'] ?? '';
        if (!empty($password)) {
            $data['password'] = $password;
        }

        $username = $_POST['username'] ?? $user['username'];
        $existing = $userModel->findByUsername($username);
        if ($existing && $existing['id'] != $id) {
            header('Location: ' . url("/users/{$id}/edit") . '?error=1&error_msg=' . urlencode('ຊື່ຜູ້ໃຊ້ນີ້ມີແລ້ວ'));
            exit;
        }
        $data['username'] = $username;

        $userModel->update($id, $data);

        header('Location: ' . url('/users') . '?updated=1');
        exit;
    }

    public function delete($id) {
        $userModel = new User();

        $user = $userModel->find($id);
        if (!$user) {
            header('Location: ' . url('/users') . '?error=1&error_msg=' . urlencode('ບໍ່ພົບຜູ້ໃຊ້'));
            exit;
        }

        if ($id == $_SESSION['user']['id']) {
            header('Location: ' . url('/users') . '?error=1&error_msg=' . urlencode('ບໍ່ສາມາດລຶບຜູ້ໃຊ້ຂອງຕົນເອງໄດ້'));
            exit;
        }

        $userModel->delete($id);

        header('Location: ' . url('/users') . '?deleted=1');
        exit;
    }
}
