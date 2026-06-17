<?php

namespace App\Controllers;

use App\Models\Setting;
use App\Models\PaymentMethod;
use App\Helpers\ImageKit;

class SettingsController extends BaseController {
    public function index() {
        $settingModel = new Setting();
        $paymentModel = new PaymentMethod();
        
        $settings = $settingModel->get();
        $payment_methods = $paymentModel->getAll();

        return view('pages.settings', [
            'title' => 'ຕັ້ງຄ່າລະບົບ - Settings',
            'settings' => $settings,
            'payment_methods' => $payment_methods
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = new Setting();
            
            $data = [
                'store_name' => $_POST['store_name'] ?? '',
                'store_phone' => $_POST['store_phone'] ?? '',
                'store_address' => $_POST['store_address'] ?? '',
                'store_email' => $_POST['store_email'] ?? '',
                'currency' => $_POST['currency'] ?? '₭',
                'tax_percent' => $_POST['tax_percent'] ?? 0,
                'paper_size' => $_POST['paper_size'] ?? '80mm',
                'rental_terms' => $_POST['rental_terms'] ?? '',
                'receipt_footer' => $_POST['receipt_footer'] ?? ''
            ];

            // Handle file upload for logo via ImageKit helper
            if (!empty($_FILES['store_logo']['name'])) {
                $logoUrl = ImageKit::upload('store_logo');
                if ($logoUrl) {
                    $data['store_logo'] = $logoUrl;
                }
            }

            $success = $settingModel->update($data);
            
            if ($success) {
                header('Location: ' . url('/settings?updated=1'));
                exit;
            } else {
                header('Location: ' . url('/settings?error=1&error_msg=' . urlencode('ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກການຕັ້ງຄ່າ')));
                exit;
            }
        }
        
        header('Location: ' . url('/settings'));
        exit;
    }

    public function addPaymentMethod() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paymentModel = new PaymentMethod();
            $name = $_POST['name'] ?? '';
            $details = $_POST['details'] ?? '';
            
            if (!empty($name)) {
                if ($paymentModel->create($name, $details)) {
                    header('Location: ' . url('/settings?success=1'));
                    exit;
                } else {
                    header('Location: ' . url('/settings?error=1'));
                    exit;
                }
            }
        }
        header('Location: ' . url('/settings'));
        exit;
    }

    public function editPaymentMethod() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paymentModel = new PaymentMethod();
            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'] ?? '';
            $details = $_POST['details'] ?? '';
            
            if (!empty($id) && !empty($name)) {
                if ($paymentModel->update($id, $name, $details)) {
                    header('Location: ' . url('/settings?updated=1'));
                    exit;
                } else {
                    header('Location: ' . url('/settings?error=1'));
                    exit;
                }
            }
        }
        header('Location: ' . url('/settings'));
        exit;
    }

    public function deletePaymentMethod() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paymentModel = new PaymentMethod();
            $id = $_POST['id'] ?? 0;
            if ($paymentModel->delete($id)) {
                header('Location: ' . url('/settings?deleted=1'));
                exit;
            }
        }
        header('Location: ' . url('/settings'));
        exit;
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if (empty($current) || empty($new) || empty($confirm)) {
                header('Location: ' . url('/settings?error=1&error_msg=' . urlencode('ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ')));
                exit;
            } elseif ($new !== $confirm) {
                header('Location: ' . url('/settings?error=1&error_msg=' . urlencode('ລະຫັດຜ່ານໃໝ່ບໍ່ກົງກັນ')));
                exit;
            } else {
                // Since we don't have a complex User model yet, we'll do it directly
                $host = $_ENV['DB_HOST'] ?? 'localhost';
                $db_user = $_ENV['DB_USERNAME'] ?? 'root';
                $db_pass = $_ENV['DB_PASSWORD'] ?? 'Admin123';
                $db_name = $_ENV['DB_DATABASE'] ?? 'if0_41710498_rent';
                
                $conn = new \mysqli($host, $db_user, $db_pass, $db_name);
                $conn->query("SET time_zone = '+07:00'");
                
                // For simplicity as requested, checking 'admin' user
                $res = $conn->query("SELECT password FROM users WHERE username = 'admin' LIMIT 1");
                $user = $res->fetch_assoc();

                // Check if current password matches (assuming plain text as in database.sql for now, but should ideally be hashed)
                if ($user && $user['password'] === $current) {
                    $new_esc = $conn->real_escape_string($new);
                    $conn->query("UPDATE users SET password = '$new_esc' WHERE username = 'admin'");
                    header('Location: ' . url('/settings?success=1'));
                    exit;
                } else {
                    header('Location: ' . url('/settings?error=1&error_msg=' . urlencode('ລະຫັດຜ່ານປັດຈຸບັນບໍ່ຖືກຕ້ອງ')));
                    exit;
                }
                $conn->close();
            }
        }
        header('Location: ' . url('/settings'));
        exit;
    }
}
