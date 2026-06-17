<?php

namespace App\Controllers;

use App\Models\Settings;
use App\Models\User;

class SettingsController extends BaseController {
    public function index() {
        $settings = (new Settings())->getAll();

        return view('pages.settings.index', [
            'title' => 'ຕັ້ງຄ່າ',
            'settings' => $settings,
        ]);
    }

    public function update() {
        $settingsModel = new Settings();

        $fields = [
            'store_name' => $_POST['store_name'] ?? '',
            'currency' => $_POST['currency'] ?? 'ກີບ',
            'currency_symbol' => $_POST['currency_symbol'] ?? '₭',
            'tax_rate' => $_POST['tax_rate'] ?? 0,
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'invoice_footer' => $_POST['invoice_footer'] ?? '',
        ];

        foreach ($fields as $key => $value) {
            $settingsModel->set($key, $value);
        }

        header('Location: ' . url('/settings') . '?updated=1');
        exit;
    }

    public function changePassword() {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword)) {
            header('Location: ' . url('/settings') . '?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນລະຫັດຜ່ານ'));
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            header('Location: ' . url('/settings') . '?error=1&error_msg=' . urlencode('ລະຫັດຜ່ານໃໝ່ບໍ່ກົງກັນ'));
            exit;
        }

        $userId = $_SESSION['user']['id'] ?? null;
        $userModel = new User();

        if ($userId) {
            $user = $userModel->find($userId);
            if (!$user || !password_verify($currentPassword, $user['password'])) {
                header('Location: ' . url('/settings') . '?error=1&error_msg=' . urlencode('ລະຫັດຜ່ານປັດຈຸບັນບໍ່ຖືກຕ້ອງ'));
                exit;
            }

            $userModel->update($userId, [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            ]);
        } else {
            $userModel->updateByUsername('admin', [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            ]);
        }

        header('Location: ' . url('/settings') . '?updated=1');
        exit;
    }
}
