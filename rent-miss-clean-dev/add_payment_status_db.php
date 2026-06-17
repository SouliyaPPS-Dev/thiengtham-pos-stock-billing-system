<?php
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

// Load environment variables
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
    
    // Check if column exists
    $result = $db->query("SHOW COLUMNS FROM rentals LIKE 'payment_status'");
    $exists = $result->fetch();
    
    if (!$exists) {
        $db->exec("ALTER TABLE rentals ADD COLUMN payment_status VARCHAR(20) DEFAULT 'Paid' AFTER paid_amount");
        echo "Successfully added 'payment_status' column to 'rentals' table.\n";
    } else {
        echo "'payment_status' column already exists in 'rentals' table.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
