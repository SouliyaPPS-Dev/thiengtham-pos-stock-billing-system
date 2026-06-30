<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\ImageKit;

class StaffController extends BaseController {
    public function index() {
        $userModel = new User();
        $staff = $userModel->getAll();

        return view('pages.staff', [
            'title' => 'ຈັດການພະນັກງານ - Staff Management',
            'staff' => $staff
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $data = [
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'full_name' => $_POST['full_name'],
                'phone' => $_POST['phone'],
                'role' => $_POST['role'],
                'status' => $_POST['status'] ?? 'Active'
            ];

            if (!empty($_FILES['avatar']['name'])) {
                $avatarUrl = ImageKit::upload('avatar', '/rent_miss_clean/staff');
                if ($avatarUrl) {
                    $data['avatar'] = $avatarUrl;
                }
            }

            if ($userModel->create($data)) {
                header('Location: ' . url('/staff?success=1'));
                exit;
            }
        }
        header('Location: ' . url('/staff?error=1'));
        exit;
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $userModel = new User();
            $data = [
                'username' => $_POST['username'],
                'full_name' => $_POST['full_name'],
                'phone' => $_POST['phone'],
                'role' => $_POST['role'],
                'status' => $_POST['status']
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }

            if (!empty($_FILES['avatar']['name'])) {
                $avatarUrl = ImageKit::upload('avatar', '/rent_miss_clean/staff');
                if ($avatarUrl) {
                    $data['avatar'] = $avatarUrl;
                }
            }

            if ($userModel->update($id, $data)) {
                header('Location: ' . url('/staff?updated=1'));
                exit;
            }
        }
        header('Location: ' . url('/staff?error=1'));
        exit;
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $userModel = new User();
            if ($userModel->delete($id)) {
                header('Location: ' . url('/staff?deleted=1'));
                exit;
            }
        }
        header('Location: ' . url('/staff?error=1'));
        exit;
    }
}
