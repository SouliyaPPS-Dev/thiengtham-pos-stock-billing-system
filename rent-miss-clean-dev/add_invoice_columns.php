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

    $result = $db->query("SHOW COLUMNS FROM rentals LIKE 'invoice_number'");
    $exists = $result->fetch();
    if (!$exists) {
        $db->exec("ALTER TABLE rentals ADD COLUMN invoice_number VARCHAR(50) DEFAULT NULL AFTER id");
        echo "Added 'invoice_number' column.\n";
    } else {
        echo "'invoice_number' column already exists.\n";
    }

    $result = $db->query("SHOW COLUMNS FROM rentals LIKE 'change_amount'");
    $exists = $result->fetch();
    if (!$exists) {
        $db->exec("ALTER TABLE rentals ADD COLUMN change_amount decimal(15,2) NOT NULL DEFAULT 0.00 AFTER paid_amount");
        echo "Added 'change_amount' column.\n";
    } else {
        echo "'change_amount' column already exists.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
