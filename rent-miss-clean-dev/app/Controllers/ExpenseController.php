<?php

namespace App\Controllers;

use App\Models\Expense;

class ExpenseController extends BaseController {
    public function index() {
        $expenseModel = new Expense();
        $month = $_GET['month'] ?? date('Y-m');
        
        $expenses = $expenseModel->getByMonth($month);
        $categories = $expenseModel->getCategories();
        $totalAmount = $expenseModel->getTotalByMonth($month);

        return view('pages.expenses', [
            'title' => 'ບັນທຶກລາຍຈ່າຍ - Expenses',
            'expenses' => $expenses,
            'categories' => $categories,
            'currentMonth' => $month,
            'totalAmount' => $totalAmount
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $expenseModel = new Expense();
            $data = [
                'expense_date' => $_POST['expense_date'],
                'category_id' => $_POST['category_id'],
                'amount' => $_POST['amount'],
                'description' => $_POST['description'],
                'created_by' => $_SESSION['user']['id'] ?? 1
            ];
            
            if ($expenseModel->create($data)) {
                header('Location: ' . url('/expenses?success=1&month=' . substr($data['expense_date'], 0, 7)));
                exit;
            }
        }
        header('Location: ' . url('/expenses?error=1'));
        exit;
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $expenseModel = new Expense();
            $id = $_POST['id'];
            $data = [
                'expense_date' => $_POST['expense_date'],
                'category_id' => $_POST['category_id'],
                'amount' => $_POST['amount'],
                'description' => $_POST['description']
            ];
            
            if ($expenseModel->update($id, $data)) {
                header('Location: ' . url('/expenses?updated=1&month=' . substr($data['expense_date'], 0, 7)));
                exit;
            }
        }
        header('Location: ' . url('/expenses?error=1'));
        exit;
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $expenseModel = new Expense();
            $id = $_POST['id'];
            if ($expenseModel->delete($id)) {
                // If it's AJAX, we return JSON, but for consistency we should also support normal form delete if needed
                if ($this->isAjax()) {
                    echo json_encode(['success' => true]);
                    exit;
                }
                header('Location: ' . url('/expenses?deleted=1'));
                exit;
            }
        }
        if ($this->isAjax()) {
            echo json_encode(['success' => false]);
            exit;
        }
        header('Location: ' . url('/expenses?error=1'));
        exit;
    }

    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $expenseModel = new Expense();
            $name = $_POST['name'] ?? '';
            if (!empty($name)) {
                $expenseModel->addCategory($name);
            }
        }
        header('Location: ' . url('/expenses'));
        exit;
    }

    public function editCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $expenseModel = new Expense();
            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'] ?? '';
            if (!empty($id) && !empty($name)) {
                $expenseModel->updateCategory($id, $name);
            }
        }
        header('Location: ' . url('/expenses'));
        exit;
    }

    public function deleteCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $expenseModel = new Expense();
            $id = $_POST['id'] ?? 0;
            if (!empty($id)) {
                $expenseModel->deleteCategory($id);
            }
        }
        header('Location: ' . url('/expenses'));
        exit;
    }

    private function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
}
 