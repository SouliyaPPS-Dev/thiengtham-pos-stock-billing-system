<?php
/**
 * Emergency database diagnostic & repair endpoint.
 * DELETE THIS FILE AFTER USE.
 */
header('Content-Type: text/plain; charset=utf-8');

$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'root';
$db   = getenv('DB_NAME') ?: 'if0_42353445_thiengtham';

echo "=== Database Diagnostic ===\n";
echo "Host: $host, DB: $db\n\n";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "Connection: OK\n\n";
} catch (\Exception $e) {
    echo "Connection FAILED: " . $e->getMessage() . "\n";
    exit(1);
}

// Check if customer_id exists on quotations
echo "--- quotations columns ---\n";
$rows = $pdo->query("SHOW COLUMNS FROM quotations")->fetchAll(PDO::FETCH_ASSOC);
$cols = array_column($rows, 'Field');
echo "Total columns: " . count($cols) . "\n";
echo "Columns: " . implode(', ', $cols) . "\n\n";

if (in_array('customer_id', $cols)) {
    echo ">>> customer_id EXISTS - column is present.\n\n";
} else {
    echo ">>> customer_id MISSING - attempting to add it NOW...\n\n";
    
    // Try to add the column
    $sqls = [
        "ALTER TABLE quotations ADD COLUMN customer_id INT DEFAULT NULL",
        "ALTER TABLE quotations ADD COLUMN customer_name VARCHAR(200) DEFAULT NULL",
        "ALTER TABLE quotations ADD COLUMN customer_contact VARCHAR(200) DEFAULT NULL",
        "ALTER TABLE quotations ADD COLUMN company_template VARCHAR(50) NOT NULL DEFAULT 'luang-prabarg'",
        "ALTER TABLE quotations ADD COLUMN bid_customer_id INT DEFAULT NULL",
        "ALTER TABLE quotations ADD COLUMN bid_customer_name VARCHAR(200) DEFAULT NULL",
        "ALTER TABLE quotations ADD COLUMN bid_customer_contact VARCHAR(200) DEFAULT NULL",
        "ALTER TABLE quotations ADD COLUMN ref_no VARCHAR(100) DEFAULT NULL",
        "ALTER TABLE quotations ADD COLUMN expiry_date DATE DEFAULT NULL",
        "ALTER TABLE quotations ADD COLUMN converted_to_sale_id INT DEFAULT NULL",
    ];
    
    foreach ($sqls as $sql) {
        try {
            $pdo->exec($sql);
            echo "OK: $sql\n";
        } catch (\Exception $e) {
            echo "SKIP: " . $e->getMessage() . " -- $sql\n";
        }
    }
    
    // Check stray dirs
    echo "\n--- Checking for stray data-dir directories ---\n";
    try {
        $datadir = $pdo->query("SELECT @@datadir AS d")->fetchColumn();
        $dbDir = rtrim($datadir, '/') . '/' . $db;
        echo "Data dir: $dbDir\n";
        if (is_dir($dbDir)) {
            $entries = @scandir($dbDir);
            foreach ($entries as $e) {
                if ($e === '.' || $e === '..' || $e === 'db.opt') continue;
                $full = $dbDir . '/' . $e;
                if (is_dir($full)) {
                    echo "  STRAY DIR: $full\n";
                    // Try to remove it
                    $it = new RecursiveDirectoryIterator($full, RecursiveDirectoryIterator::SKIP_DOTS);
                    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                    foreach ($files as $file) {
                        if ($file->isDir()) {
                            @rmdir($file->getRealPath());
                        } else {
                            @unlink($file->getRealPath());
                        }
                    }
                    @rmdir($full);
                    echo "  -> Removed: " . (!is_dir($full) ? 'YES' : 'NO (still exists)') . "\n";
                }
            }
        }
    } catch (\Exception $e) {
        echo "Stray dir check error: " . $e->getMessage() . "\n";
    }
    
    // Re-verify
    echo "\n--- Re-verification ---\n";
    $rows2 = $pdo->query("SHOW COLUMNS FROM quotations")->fetchAll(PDO::FETCH_ASSOC);
    $cols2 = array_column($rows2, 'Field');
    echo "Columns after fix: " . implode(', ', $cols2) . "\n";
    if (in_array('customer_id', $cols2)) {
        echo ">>> SUCCESS: customer_id now exists!\n";
    } else {
        echo ">>> STILL MISSING: customer_id - something else is wrong\n";
    }
}

echo "\n=== DONE ===\n";
echo "\n*** DELETE this file after use! ***\n";
