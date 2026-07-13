<?php

namespace App\Controllers\Admin;

use App\Core\Database;

class ExpenseController extends \App\Controllers\BaseController
{
    protected function db()
    {
        return Database::getInstance()->getConnection();
    }

    public function index()
    {
        $month = $_GET['month'] ?? date('Y-m');
        $db = $this->db();

        $stmt = $db->prepare("SELECT e.*, ec.name as category_name
                               FROM expenses e
                               LEFT JOIN expense_categories ec ON ec.id = e.category_id
                               WHERE DATE_FORMAT(e.expense_date, '%Y-%m') = ?
                               ORDER BY e.expense_date DESC, e.id DESC");
        $stmt->execute([$month]);
        $expenses = $stmt->fetchAll();

        $stmtCat = $db->query("SELECT * FROM expense_categories ORDER BY name ASC");
        $categories = $stmtCat->fetchAll();

        $stmtTotal = $db->prepare("SELECT COALESCE(SUM(amount), 0) as total
                                    FROM expenses
                                    WHERE DATE_FORMAT(expense_date, '%Y-%m') = ?");
        $stmtTotal->execute([$month]);
        $totalAmount = (float)$stmtTotal->fetch()['total'];

        return view('pages.admin.expenses.index', [
            'title' => 'ລາຍຈ່າຍ',
            'expenses' => $expenses,
            'categories' => $categories,
            'expenseCategories' => $categories,
            'currentMonth' => $month,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/expenses');
        }

        $db = $this->db();
        $stmt = $db->prepare("INSERT INTO expenses (expense_date, category_id, amount, description, created_by, created_at)
                               VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $_POST['expense_date'] ?? date('Y-m-d'),
            $_POST['category_id'] ?? null,
            $_POST['amount'] ?? 0,
            $_POST['description'] ?? '',
            $_SESSION['user']['id'] ?? null,
        ]);

        $month = substr($_POST['expense_date'] ?? date('Y-m'), 0, 7);
        $this->redirect('/admin/expenses', ['success' => 1, 'month' => $month]);
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/expenses');
        }

        $id = $_POST['id'] ?? 0;
        if (!$id) {
            $this->redirect('/admin/expenses', ['error' => 1]);
        }

        $db = $this->db();
        $stmt = $db->prepare("UPDATE expenses SET expense_date = ?, category_id = ?, amount = ?, description = ? WHERE id = ?");
        $stmt->execute([
            $_POST['expense_date'] ?? date('Y-m-d'),
            $_POST['category_id'] ?? null,
            $_POST['amount'] ?? 0,
            $_POST['description'] ?? '',
            $id,
        ]);

        $month = substr($_POST['expense_date'] ?? date('Y-m'), 0, 7);
        $this->redirect('/admin/expenses', ['updated' => 1, 'month' => $month]);
    }

    public function delete()
    {
        $id = $_POST['id'] ?? 0;

        if (!$id) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Invalid ID']);
            }
            $this->redirect('/admin/expenses', ['error' => 1]);
        }

        $db = $this->db();
        $stmt = $db->prepare("DELETE FROM expenses WHERE id = ?");
        $stmt->execute([$id]);

        if ($this->isAjax()) {
            $this->json(['success' => true]);
        }

        $this->redirect('/admin/expenses', ['deleted' => 1]);
    }

    public function addCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/expenses');
        }

        $name = $_POST['name'] ?? '';
        if (!empty($name)) {
            $db = $this->db();
            $stmt = $db->prepare("INSERT INTO expense_categories (name, created_at) VALUES (?, NOW())");
            $stmt->execute([$name]);
        }

        $this->redirect('/admin/expenses', ['success' => 1]);
    }

    public function editCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/expenses');
        }

        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';

        if (!empty($id) && !empty($name)) {
            $db = $this->db();
            $stmt = $db->prepare("UPDATE expense_categories SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
        }

        $this->redirect('/admin/expenses', ['updated' => 1]);
    }

    public function deleteCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/expenses');
        }

        $id = $_POST['id'] ?? 0;

        if (!empty($id)) {
            $db = $this->db();
            $stmt = $db->prepare("DELETE FROM expense_categories WHERE id = ?");
            $stmt->execute([$id]);
        }

        $this->redirect('/admin/expenses', ['deleted' => 1]);
    }

    public function bulkDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/expenses');
        }

        $ids = $_POST['ids'] ?? [];
        if (empty($ids) || !is_array($ids)) {
            $this->redirect('/admin/expenses', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາເລືອກລາຍການທີ່ຕ້ອງການລົບ',
            ]);
        }

        $ids = array_map('intval', $ids);
        $db = $this->db();

        try {
            $db->beginTransaction();
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $db->prepare("DELETE FROM expenses WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $db->commit();

            $this->redirect('/admin/expenses', [
                'success' => 1,
                'success_msg' => 'ລົບ ' . count($ids) . ' ລາຍການສຳເລັດ',
            ]);
        } catch (\Exception $e) {
            $db->rollBack();
            $this->redirect('/admin/expenses', [
                'error' => 1,
                'error_msg' => 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage(),
            ]);
        }
    }
}
