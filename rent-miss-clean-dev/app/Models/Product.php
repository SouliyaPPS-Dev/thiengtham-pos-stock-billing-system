<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            ORDER BY p.id DESC
        ");
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        $products = [];
        foreach ($result as $row) {
            $products[] = [
                'id' => $row['id'],
                'code' => $row['code'],
                'name' => $row['name'],
                'category' => $row['category_name'] ?? 'ບໍ່ມີປະເພດ',
                'category_id' => $row['category_id'],
                'size' => $row['size'],
                'bust' => $row['bust'],
                'waist' => $row['waist'],
                'hips' => $row['hips'],
                'color' => $row['color'],
                'price' => $row['rental_price'],
                'rental_price' => $row['rental_price'],
                'deposit_price' => $row['deposit_price'],
                'status' => $row['status'],
                'stock' => $row['stock'] ?? 0,
                'image' => $row['image'] ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=400&fit=crop',
                'created_at' => $row['created_at']
            ];
        }
        return $products;
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getStats($fromDate = null, $toDate = null) {
        $stats = [
            'total_products' => 0,
            'rented_out' => 0,
            'available' => 0,
            'revenue_today' => 0,
            'revenue_month' => 0,
            'expense_month' => 0,
            'recent_rentals' => [],
            'popular_items' => [],
            'income_by_month' => [],
            'expense_by_month' => [],
            'income_by_day' => [],
            'expense_by_day' => []
        ];

        $dateWhere = '';
        $params = [];
        if ($fromDate && $toDate) {
            $dateWhere = ' AND created_at >= ? AND created_at <= ? ';
            $params = [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'];
        }

        // 1. Total products
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM products");
        $stats['total_products'] = $stmt->fetch()['count'];

        // 2. Rented out
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM products WHERE status = 'Rented'");
        $stats['rented_out'] = $stmt->fetch()['count'];

        // 3. Available
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM products WHERE status = 'Available'");
        $stats['available'] = $stmt->fetch()['count'];

        // 4. Today's revenue
        $stmt = $this->db->query("SELECT SUM(grand_total) as total FROM rentals WHERE DATE(created_at) = CURDATE() AND status != 'Cancelled'");
        $revenue = $stmt->fetch()['total'];
        $stats['revenue_today'] = $revenue ?: 0;

        // 5. Income in range (or this month)
        $incomeSql = "SELECT COALESCE(SUM(grand_total), 0) as total FROM rentals WHERE status != 'Cancelled'";
        if ($fromDate && $toDate) {
            $stmt = $this->db->prepare($incomeSql . " AND created_at >= ? AND created_at <= ?");
            $stmt->execute([$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } else {
            $stmt = $this->db->query($incomeSql . " AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')");
        }
        $stats['revenue_month'] = (int)$stmt->fetch()['total'];

        // 6. Expense in range (or this month)
        $expenseSql = "SELECT COALESCE(SUM(amount), 0) as total FROM expenses WHERE 1=1";
        if ($fromDate && $toDate) {
            $stmt = $this->db->prepare($expenseSql . " AND expense_date >= ? AND expense_date <= ?");
            $stmt->execute([$fromDate, $toDate]);
        } else {
            $stmt = $this->db->query($expenseSql . " AND DATE_FORMAT(expense_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')");
        }
        $stats['expense_month'] = (int)$stmt->fetch()['total'];

        // 7. Recent Rentals
        $stmt = $this->db->query("
            SELECT r.*, c.fullname as customer_name
            FROM rentals r
            JOIN customers c ON r.customer_id = c.id
            ORDER BY r.created_at DESC
            LIMIT 5
        ");
        $stats['recent_rentals'] = $stmt->fetchAll();

        // 8. Popular Items
        $stmt = $this->db->query("
            SELECT p.name, p.image, COUNT(ri.id) as rental_count
            FROM products p
            JOIN rental_items ri ON p.id = ri.product_id
            GROUP BY p.id
            ORDER BY rental_count DESC
            LIMIT 3
        ");
        $stats['popular_items'] = $stmt->fetchAll();

        // 9. Income by month (last 6 months) - always for overall context
        $stmt = $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COALESCE(SUM(grand_total), 0) as total
            FROM rentals
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND status != 'Cancelled'
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month ASC
        ");
        $stats['income_by_month'] = $stmt->fetchAll();

        // 10. Expense by month (last 6 months) - always for overall context
        $stmt = $this->db->query("
            SELECT DATE_FORMAT(expense_date, '%Y-%m') as month, COALESCE(SUM(amount), 0) as total
            FROM expenses
            WHERE expense_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(expense_date, '%Y-%m')
            ORDER BY month ASC
        ");
        $stats['expense_by_month'] = $stmt->fetchAll();

        // 11. Income by day (for daily chart)
        $incomeDaySql = "
            SELECT DATE(created_at) as day, COALESCE(SUM(grand_total), 0) as total
            FROM rentals
            WHERE status != 'Cancelled'
        ";
        if ($fromDate && $toDate) {
            $stmt = $this->db->prepare($incomeDaySql . " AND created_at >= ? AND created_at <= ? GROUP BY DATE(created_at) ORDER BY day ASC");
            $stmt->execute([$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } else {
            $stmt = $this->db->query($incomeDaySql . " AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m') GROUP BY DATE(created_at) ORDER BY day ASC");
        }
        $stats['income_by_day'] = $stmt->fetchAll();

        // 12. Expense by day (for daily chart)
        $expenseDaySql = "
            SELECT expense_date as day, COALESCE(SUM(amount), 0) as total
            FROM expenses
            WHERE 1=1
        ";
        if ($fromDate && $toDate) {
            $stmt = $this->db->prepare($expenseDaySql . " AND expense_date >= ? AND expense_date <= ? GROUP BY expense_date ORDER BY day ASC");
            $stmt->execute([$fromDate, $toDate]);
        } else {
            $stmt = $this->db->query($expenseDaySql . " AND DATE_FORMAT(expense_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m') GROUP BY expense_date ORDER BY day ASC");
        }
        $stats['expense_by_day'] = $stmt->fetchAll();

        return $stats;
    }
}
   