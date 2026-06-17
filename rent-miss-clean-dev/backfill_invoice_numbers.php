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

    $stmt = $db->query("SELECT id, created_at FROM rentals WHERE invoice_number IS NULL OR invoice_number = '' ORDER BY id ASC");
    $rows = $stmt->fetchAll();

    if (empty($rows)) {
        echo "All records already have invoice numbers.\n";
        exit;
    }

    $updateStmt = $db->prepare("UPDATE rentals SET invoice_number = ? WHERE id = ?");

    foreach ($rows as $row) {
        $date = date('dm', strtotime($row['created_at']));
        $invNum = 'INV-' . $date . '-S1-' . str_pad($row['id'], 4, '0', STR_PAD_LEFT);
        $updateStmt->execute([$invNum, $row['id']]);
        echo "Updated ID {$row['id']} -> {$invNum}\n";
    }

    echo "\nDone. " . count($rows) . " records updated.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
