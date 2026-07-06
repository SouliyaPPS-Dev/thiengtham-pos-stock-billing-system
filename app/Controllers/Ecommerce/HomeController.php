<?php

namespace App\Controllers\Ecommerce;

use App\Core\Database;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;

class HomeController
{
    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    protected function json($data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($path, $params = [])
    {
        $query = http_build_query($params);
        $url = url($path);
        if ($query) $url .= '?' . $query;
        header('Location: ' . $url);
        exit;
    }

    public function index()
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $featured = $productModel->getFeatured(8);
        $categories = $categoryModel->all();

        $banners = [];
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT * FROM banners WHERE status = 'Active' ORDER BY sort_order ASC LIMIT 5");
            $banners = $stmt->fetchAll();
        } catch (\Exception $e) {}

        $newArrivals = [];
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT p.*, c.name as category_name
                                   FROM products p
                                   LEFT JOIN categories c ON c.id = p.category_id
                                   WHERE p.status = 'Active'
                                   ORDER BY p.created_at DESC
                                   LIMIT 4");
            $stmt->execute();
            $newArrivals = $stmt->fetchAll();
        } catch (\Exception $e) {}

        $promotions = [];
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT * FROM promotions WHERE status = 'Active' ORDER BY sort_order ASC LIMIT 5");
            $promotions = $stmt->fetchAll();
        } catch (\Exception $e) {}

        $cartCount = $this->getCartCount();

        return view('pages.ecommerce.home', [
            'layout' => 'ecommerce',
            'title' => 'ໜ້າຫຼັກ',
            'featured' => $featured,
            'categories' => $categories,
            'banners' => $banners,
            'newArrivals' => $newArrivals,
            'promotions' => $promotions,
            'cartCount' => $cartCount,
            'hero' => true,
        ]);
    }

    public function login()
    {
        if (isset($_SESSION['customer'])) {
            $this->redirect('/');
        }

        $cartCount = $this->getCartCount();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $phone = trim(($_POST['phone_prefix'] ?? '+856') . ' ' . ($_POST['phone'] ?? ''));
            $password = $_POST['password'] ?? '';

            if ((empty($email) && empty($phone)) || empty($password)) {
                return view('pages.ecommerce.login', [
                    'layout' => 'ecommerce',
                    'title' => 'ເຂົ້າສູ່ລະບົບ',
                    'error' => 'ກະລຸນາປ້ອນອີເມວ/ເບີໂທ ແລະ ລະຫັດຜ່ານ',
                    'cartCount' => $cartCount,
                ]);
            }

            $customerModel = new Customer();
            $customer = null;

            if (!empty($email)) {
                $customer = $customerModel->findByEmail($email);
            }
            if (!$customer && !empty($phone)) {
                $customer = $customerModel->findByPhone($phone);
            }

            if ($customer && password_verify($password, $customer['password'])) {
                $_SESSION['customer'] = [
                    'id' => $customer['id'],
                    'fullname' => $customer['fullname'],
                    'phone' => $customer['phone'] ?? '',
                    'email' => $customer['email'] ?? '',
                    'address' => $customer['address'] ?? '',
                    'province' => $customer['province'] ?? '',
                    'district' => $customer['district'] ?? '',
                    'village' => $customer['village'] ?? '',
                ];

                $redirect = $_SESSION['checkout_redirect'] ?? '/';
                unset($_SESSION['checkout_redirect']);
                $this->redirect($redirect);
            }

            return view('pages.ecommerce.login', [
                'layout' => 'ecommerce',
                'title' => 'ເຂົ້າສູ່ລະບົບ',
                'error' => 'ອີເມວ/ເບີໂທ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ',
                'cartCount' => $cartCount,
            ]);
        }

        return view('pages.ecommerce.login', [
            'layout' => 'ecommerce',
            'title' => 'ເຂົ້າສູ່ລະບົບ',
            'cartCount' => $cartCount,
        ]);
    }

    public function register()
    {
        if (isset($_SESSION['customer'])) {
            $this->redirect('/');
        }

        $cartCount = $this->getCartCount();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $phone = trim(($_POST['phone_prefix'] ?? '+856') . ' ' . ($_POST['phone'] ?? ''));
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            $address = trim($_POST['address'] ?? '');
            $province = trim($_POST['province'] ?? '');
            $district = trim($_POST['district'] ?? '');
            $village = trim($_POST['village'] ?? '');
            $latitude = !empty($_POST['latitude']) ? $_POST['latitude'] : null;
            $longitude = !empty($_POST['longitude']) ? $_POST['longitude'] : null;

            $errors = [];
            if (empty($fullname)) $errors[] = 'ກະລຸນາປ້ອນຊື່ ແລະ ນາມສະກຸນ';
            if (empty($phone)) $errors[] = 'ກະລຸນາປ້ອນເບີໂທລະສັບ';
            if (empty($password)) $errors[] = 'ກະລຸນາປ້ອນລະຫັດຜ່ານ';
            if ($password !== $confirm) $errors[] = 'ລະຫັດຜ່ານບໍ່ກົງກັນ';
            if (strlen($password) < 6) $errors[] = 'ລະຫັດຜ່ານຕ້ອງມີຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ';

            $customerModel = new Customer();

            if (!empty($email) && $customerModel->findByEmail($email)) {
                $errors[] = 'ອີເມວນີ້ຖືກນຳໃຊ້ແລ້ວ';
            }
            if ($customerModel->findByPhone($phone)) {
                $errors[] = 'ເບີໂທນີ້ຖືກນຳໃຊ້ແລ້ວ';
            }

            if (!empty($errors)) {
                return view('pages.ecommerce.register', [
                    'layout' => 'ecommerce',
                    'title' => 'ສະໝັກສະມາຊິກ',
                    'errors' => $errors,
                    'old' => $_POST,
                    'cartCount' => $cartCount,
                ]);
            }

            $customerId = $customerModel->create([
                'fullname' => $fullname,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'notes' => '',
            ]);

            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE customers SET password = ?, province = ?, district = ?, village = ?, latitude = ?, longitude = ? WHERE id = ?");
            $stmt->execute([$hashed, $province, $district, $village, $latitude, $longitude, $customerId]);

            $customer = $customerModel->find($customerId);
            $_SESSION['customer'] = [
                'id' => $customer['id'],
                'fullname' => $customer['fullname'],
                'phone' => $customer['phone'] ?? '',
                'email' => $customer['email'] ?? '',
                'address' => $customer['address'] ?? '',
                'province' => $customer['province'] ?? '',
                'district' => $customer['district'] ?? '',
                'village' => $customer['village'] ?? '',
                'latitude' => $customer['latitude'] ?? '',
                'longitude' => $customer['longitude'] ?? '',
            ];

            $this->redirect('/', ['registered' => 1]);
        }

        return view('pages.ecommerce.register', [
            'layout' => 'ecommerce',
            'title' => 'ສະໝັກສະມາຊິກ',
            'cartCount' => $cartCount,
        ]);
    }

    public function logout()
    {
        unset($_SESSION['customer']);
        $this->redirect('/');
    }

    private function getCartCount()
    {
        $count = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += (int)($item['quantity'] ?? 0);
            }
        }
        return $count;
    }
}
