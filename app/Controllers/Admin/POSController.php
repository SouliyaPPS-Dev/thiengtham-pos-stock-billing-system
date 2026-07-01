<?php

namespace App\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Sale;
use App\Core\Database;

class POSController extends \App\Controllers\BaseController
{
    public function index()
    {
        $products = (new Product())->getAll('', [], 0, 0);
        $categories = (new Category())->all();
        $customers = (new Customer())->all();
        $suppliers = (new Supplier())->all();

        $db = Database::getInstance()->getConnection();
        $paymentMethods = $db->query("SELECT * FROM payment_methods WHERE is_active = 1")->fetchAll();

        return view('pages.admin.pos.index', [
            'title' => 'ຂາຍສິນຄ້າ',
            'products' => $products,
            'categories' => $categories,
            'customers' => $customers,
            'suppliers' => $suppliers,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['items'])) {
            $this->json(['success' => false, 'message' => 'ກະລຸນາເລືອກສິນຄ້າ']);
        }

        try {
            $productModel = new Product();

            foreach ($data['items'] as $item) {
                $product = $productModel->find($item['product_id']);
                if (!$product) {
                    $this->json(['success' => false, 'message' => 'ບໍ່ພົບສິນຄ້າ ID: ' . $item['product_id']]);
                }
                if (($product['stock'] ?? 0) < $item['quantity']) {
                    $this->json(['success' => false, 'message' => 'ສິນຄ້າ ' . ($product['name'] ?? '') . ' ມີຈຳນວນບໍ່ພຽງພໍ (ຄົງເຫຼືອ: ' . ($product['stock'] ?? 0) . ')']);
                }
            }

            $sale = (new Sale())->create($data, $data['items']);

            $this->json([
                'success' => true,
                'message' => 'ບັນທຶກການຂາຍສຳເລັດ',
                'sale_id' => $sale['id'],
                'invoice_number' => $sale['invoice_number'],
                'invoice_url' => url('/admin/invoices/' . $sale['id']),
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage()], 500);
        }
    }
}
