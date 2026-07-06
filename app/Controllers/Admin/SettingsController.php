<?php

namespace App\Controllers\Admin;

use App\Models\Settings;
use App\Models\PaymentMethod;
use App\Helpers\ImageKit;

class SettingsController extends \App\Controllers\BaseController
{
    public function index()
    {
        $settings = (new Settings())->getAll();
        $paymentMethods = (new PaymentMethod())->getAll();

        return view('pages.admin.settings.index', [
            'title' => 'ຕັ້ງຄ່າລະບົບ',
            'settings' => $settings,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/settings');
        }

        $settingsModel = new Settings();

        $keys = [
            'store_name', 'store_phone', 'store_whatsapp', 'store_address', 'store_email',
            'currency', 'tax_percent', 'paper_size', 'receipt_footer',
            'invoice_terms',
            'bill_logo_width', 'bill_logo_height', 'bill_logo_position',
            'bill_signature_width', 'bill_signature_height', 'bill_signature_position',
            'bill_terms',
        ];

        foreach ($keys as $key) {
            if (isset($_POST[$key])) {
                $settingsModel->set($key, $_POST[$key]);
            }
        }

        if (!empty($_FILES['store_logo']['name'])) {
            $logoUrl = ImageKit::upload('store_logo', '/pos-stock');
            if ($logoUrl) {
                $settingsModel->set('store_logo', $logoUrl);
            }
        }

        if (!empty($_FILES['bill_logo']['name'])) {
            $billLogoUrl = ImageKit::upload('bill_logo', '/pos-stock/bill');
            if ($billLogoUrl) {
                $settingsModel->set('bill_logo', $billLogoUrl);
            }
        }

        if (!empty($_FILES['bill_signature']['name'])) {
            $signatureUrl = ImageKit::upload('bill_signature', '/pos-stock/bill');
            if ($signatureUrl) {
                $settingsModel->set('bill_signature', $signatureUrl);
            }
        }

        $this->redirect('/admin/settings', ['updated' => 1]);
    }

    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/settings');
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->redirect('/admin/settings', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ',
            ]);
        }

        if ($newPassword !== $confirmPassword) {
            $this->redirect('/admin/settings', [
                'error' => 1,
                'error_msg' => 'ລະຫັດຜ່ານໃໝ່ບໍ່ກົງກັນ',
            ]);
        }

        $userId = $_SESSION['user']['id'] ?? 0;
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $this->redirect('/admin/settings', [
                'error' => 1,
                'error_msg' => 'ລະຫັດຜ່ານປັດຈຸບັນບໍ່ຖືກຕ້ອງ',
            ]);
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmtUpdate = $db->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
        $stmtUpdate->execute([$newHash, $userId]);

        $this->redirect('/admin/settings', ['success' => 1]);
    }
}
