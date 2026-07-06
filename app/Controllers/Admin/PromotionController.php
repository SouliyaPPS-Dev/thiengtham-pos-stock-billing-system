<?php

namespace App\Controllers\Admin;

class PromotionController extends \App\Controllers\BaseController
{
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        $db = \App\Core\Database::getInstance()->getConnection();

        if ($search) {
            $stmt = $db->prepare("SELECT * FROM promotions WHERE title LIKE ? ORDER BY sort_order ASC LIMIT ? OFFSET ?");
            $like = "%{$search}%";
            $offset = ($page - 1) * $perPage;
            $stmt->execute([$like, $perPage, $offset]);
            $promotions = $stmt->fetchAll();

            $countStmt = $db->prepare("SELECT COUNT(*) FROM promotions WHERE title LIKE ?");
            $countStmt->execute([$like]);
            $total = (int)$countStmt->fetchColumn();
        } else {
            $stmt = $db->prepare("SELECT * FROM promotions ORDER BY sort_order ASC LIMIT ? OFFSET ?");
            $offset = ($page - 1) * $perPage;
            $stmt->execute([$perPage, $offset]);
            $promotions = $stmt->fetchAll();

            $countStmt = $db->query("SELECT COUNT(*) FROM promotions");
            $total = (int)$countStmt->fetchColumn();
        }

        $totalPages = max(1, ceil($total / $perPage));

        return view('pages.admin.promotions.index', [
            'title' => 'ໂປຣໂມຊັ້ນ',
            'promotions' => $promotions,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function create()
    {
        return view('pages.admin.promotions.create', [
            'title' => 'ເພີ່ມໂປຣໂມຊັ້ນໃໝ່',
        ]);
    }

    public function store()
    {
        $title = trim($_POST['title'] ?? '');
        $image = trim($_POST['image'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $status = $_POST['status'] ?? 'Active';

        if (empty($image)) {
            $this->redirect('/admin/promotions/create', [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນລິ້ງຮູບພາບ',
            ]);
        }

        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO promotions (title, image, link, sort_order, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $image, $link, $sortOrder, $status]);

        $this->redirect('/admin/promotions', ['success' => 1]);
    }

    public function edit($id)
    {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM promotions WHERE id = ?");
        $stmt->execute([$id]);
        $promotion = $stmt->fetch();

        if (!$promotion) {
            $this->redirect('/admin/promotions', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບໂປຣໂມຊັ້ນ',
            ]);
        }

        return view('pages.admin.promotions.edit', [
            'title' => 'ແກ້ໄຂໂປຣໂມຊັ້ນ',
            'promotion' => $promotion,
        ]);
    }

    public function update($id)
    {
        $db = \App\Core\Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT * FROM promotions WHERE id = ?");
        $stmt->execute([$id]);
        $promotion = $stmt->fetch();

        if (!$promotion) {
            $this->redirect('/admin/promotions', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບໂປຣໂມຊັ້ນ',
            ]);
        }

        $title = trim($_POST['title'] ?? '');
        $image = trim($_POST['image'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $status = $_POST['status'] ?? 'Active';

        if (empty($image)) {
            $this->redirect("/admin/promotions/{$id}/edit", [
                'error' => 1,
                'error_msg' => 'ກະລຸນາປ້ອນລິ້ງຮູບພາບ',
            ]);
        }

        $stmt = $db->prepare("UPDATE promotions SET title = ?, image = ?, link = ?, sort_order = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $image, $link, $sortOrder, $status, $id]);

        $this->redirect('/admin/promotions', ['updated' => 1]);
    }

    public function delete($id)
    {
        $db = \App\Core\Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT * FROM promotions WHERE id = ?");
        $stmt->execute([$id]);
        $promotion = $stmt->fetch();

        if (!$promotion) {
            $this->redirect('/admin/promotions', [
                'error' => 1,
                'error_msg' => 'ບໍ່ພົບໂປຣໂມຊັ້ນ',
            ]);
        }

        $stmt = $db->prepare("DELETE FROM promotions WHERE id = ?");
        $stmt->execute([$id]);

        $this->redirect('/admin/promotions', ['deleted' => 1]);
    }
}
