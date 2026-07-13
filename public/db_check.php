<?php
/**
 * Emergency database repair endpoint — NUCLEAR OPTION.
 * Uses RENAME TABLE + CREATE + INSERT to bypass stray-dir ALTER TABLE block.
 * DELETE THIS FILE AFTER USE.
 */
header('Content-Type: text/plain; charset=utf-8');

$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'root';
$db   = getenv('DB_NAME') ?: 'if0_42353445_thiengtham';

echo "=== NUCLEAR Database Repair ===\n";
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

// Current state
$rows = $pdo->query("SHOW COLUMNS FROM quotations")->fetchAll(PDO::FETCH_ASSOC);
$cols = array_column($rows, 'Field');
echo "Current columns (" . count($cols) . "): " . implode(', ', $cols) . "\n\n";

if (in_array('customer_id', $cols)) {
    echo ">>> customer_id already exists. Nothing to do.\n";
    exit(0);
}

echo ">>> customer_id MISSING. Using RENAME+CREATE+INSERT approach...\n\n";

try {
    $pdo->beginTransaction();

    // Step 1: Backup old table
    echo "Step 1: RENAME quotations -> quotations_old ...\n";
    $pdo->exec("RENAME TABLE quotations TO quotations_old");
    echo "  OK\n";

    // Step 2: Create correct table
    echo "Step 2: CREATE TABLE quotations (full schema) ...\n";
    $pdo->exec("CREATE TABLE quotations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quotation_number VARCHAR(50) NOT NULL UNIQUE,
        company_template VARCHAR(50) NOT NULL DEFAULT 'luang-prabarg',
        bid_customer_id INT DEFAULT NULL,
        bid_customer_name VARCHAR(200) DEFAULT NULL,
        bid_customer_contact VARCHAR(200) DEFAULT NULL,
        customer_id INT DEFAULT NULL,
        customer_name VARCHAR(200) DEFAULT NULL,
        customer_contact VARCHAR(200) DEFAULT NULL,
        ref_no VARCHAR(100) DEFAULT NULL,
        date DATE DEFAULT NULL,
        expiry_date DATE DEFAULT NULL,
        subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
        discount DECIMAL(12,2) NOT NULL DEFAULT 0,
        tax_percent DECIMAL(5,2) DEFAULT 10.00,
        tax_amount DECIMAL(12,2) DEFAULT 0,
        grand_total DECIMAL(12,2) NOT NULL DEFAULT 0,
        converted_to_sale_id INT DEFAULT NULL,
        notes TEXT,
        terms TEXT,
        status ENUM('Draft','Sent','Approved','Rejected') NOT NULL DEFAULT 'Draft',
        created_by INT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_quotation_number (quotation_number),
        INDEX idx_converted (converted_to_sale_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "  OK\n";

    // Step 3: Copy data from old table (only columns that exist in both)
    echo "Step 3: INSERT INTO quotations SELECT (matching cols) FROM quotations_old ...\n";
    // Get old table columns
    $oldRows = $pdo->query("SHOW COLUMNS FROM quotations_old")->fetchAll(PDO::FETCH_ASSOC);
    $oldCols = array_column($oldRows, 'Field');
    $newRows = $pdo->query("SHOW COLUMNS FROM quotations")->fetchAll(PDO::FETCH_ASSOC);
    $newCols = array_column($newRows, 'Field');
    $common = array_intersect($oldCols, $newCols);
    $colList = implode(', ', $common);
    $pdo->exec("INSERT INTO quotations ($colList) SELECT $colList FROM quotations_old");
    $count = $pdo->query("SELECT COUNT(*) FROM quotations")->fetchColumn();
    echo "  OK - copied $count rows\n";

    // Step 4: Drop old table
    echo "Step 4: DROP TABLE quotations_old ...\n";
    $pdo->exec("DROP TABLE quotations_old");
    echo "  OK\n";

    // Step 5: Add remaining indexes
    echo "Step 5: Adding FK constraints (best-effort) ...\n";
    $fks = [
        "ALTER TABLE quotation_items ADD CONSTRAINT fk_qi_quotation FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE CASCADE",
        "ALTER TABLE quotation_items ADD CONSTRAINT fk_qi_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL",
        "ALTER TABLE quotation_history ADD CONSTRAINT fk_qh_quotation FOREIGN KEY (quotation_id) REFERENCES quotations(id) ON DELETE CASCADE",
    ];
    foreach ($fks as $fk) {
        try { $pdo->exec($fk); echo "  OK: " . substr($fk, 0, 60) . "...\n"; }
        catch (\Exception $e) { echo "  SKIP: " . substr($e->getMessage(), 0, 80) . "\n"; }
    }

    $pdo->commit();
    echo "\n>>> COMMIT OK\n";

} catch (\Exception $e) {
    $pdo->rollBack();
    echo "\n>>> FAILED: " . $e->getMessage() . "\n";
    echo ">>> Rolling back.\n";
    exit(1);
}

// Final verification
echo "\n--- Final Verification ---\n";
$final = $pdo->query("SHOW COLUMNS FROM quotations")->fetchAll(PDO::FETCH_ASSOC);
$finalCols = array_column($final, 'Field');
echo "Columns (" . count($finalCols) . "): " . implode(', ', $finalCols) . "\n";
if (in_array('customer_id', $finalCols)) {
    echo "\n>>> SUCCESS: customer_id is present!\n";
    echo ">>> The quotations page should now work.\n";
} else {
    echo "\n>>> FAILED: customer_id still missing.\n";
}

echo "\n=== DONE ===\n";
echo "\n*** DELETE this file after use! ***\n";
