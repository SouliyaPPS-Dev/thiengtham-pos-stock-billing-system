<?php
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1];
            }
            $_ENV[$name] = $value;
        }
    }
}
  
try {
    $db = Database::getInstance()->getConnection();

    $columns = [
        'guarantee_id_card' => "ALTER TABLE rentals ADD COLUMN guarantee_id_card tinyint(1) DEFAULT 0 AFTER payment_method_id",
        'guarantee_passport' => "ALTER TABLE rentals ADD COLUMN guarantee_passport tinyint(1) DEFAULT 0 AFTER guarantee_id_card",
        'guarantee_family_book' => "ALTER TABLE rentals ADD COLUMN guarantee_family_book tinyint(1) DEFAULT 0 AFTER guarantee_passport",
        'guarantee_cash' => "ALTER TABLE rentals ADD COLUMN guarantee_cash tinyint(1) DEFAULT 0 AFTER guarantee_family_book",
        'payment_status' => "ALTER TABLE rentals ADD COLUMN payment_status varchar(20) DEFAULT 'Paid' AFTER paid_amount",
        'invoice_number' => "ALTER TABLE rentals ADD COLUMN invoice_number varchar(50) DEFAULT NULL AFTER id",
        'change_amount' => "ALTER TABLE rentals ADD COLUMN change_amount decimal(15,2) NOT NULL DEFAULT 0.00 AFTER paid_amount",
    ];

    foreach ($columns as $name => $sql) {
        $stmt = $db->query("SHOW COLUMNS FROM rentals LIKE '$name'");
        $exists = $stmt->fetch();
        if (!$exists) {
            $db->exec($sql);
            echo "Added '$name' column.\n";
        } else {
            echo "'$name' column already exists.\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
