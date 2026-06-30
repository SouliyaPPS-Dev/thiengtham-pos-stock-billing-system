<?php

namespace App\Controllers;

class PublicController
{
    public function showInvoice($id)
    {
        $db = \App\Core\Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT r.*, c.fullname as customer_name, c.phone as customer_phone,
                   c.address as customer_address, c.id_card_no as customer_id_card,
                   u.full_name as created_by_name, pm.name as payment_method_name,
                   s.store_name, s.store_phone, s.store_address, s.store_email, s.currency,
                   s.store_logo, s.receipt_footer, s.paper_size, s.rental_terms
            FROM rentals r
            LEFT JOIN customers c ON r.customer_id = c.id
            LEFT JOIN users u ON r.user_id = u.id
            LEFT JOIN payment_methods pm ON r.payment_method_id = pm.id
            LEFT JOIN settings s ON s.id = 1
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        $rental = $stmt->fetch();

        if (!$rental) {
            http_response_code(404);
            require __DIR__ . '/../../views/pages/404.php';
            return;
        }

        $stmt = $db->prepare("
            SELECT ri.*, p.name as product_name, p.code as product_code, p.size
            FROM rental_items ri
            LEFT JOIN products p ON ri.product_id = p.id
            WHERE ri.rental_id = ?
        ");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll();

        return view('pages.rentals.print_invoice', [
            'title' => 'ບິນເລກທີ: ' . $rental['invoice_number'],
            'rental' => $rental,
            'items' => $items,
            'layout' => false
        ]);
    }
}
