<?php
// Idempotent bucket-DB schema sync for the Hugging Face Space.
// Runs on every container start (after MySQL is up). Safe to re-run:
// every operation is guarded and wrapped in try/catch.
//
// DB credentials are passed via environment variables by start.sh:
//   DB_HOST, DB_USER, DB_PASS, DB_NAME

$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'root';
$db   = getenv('DB_NAME') ?: 'if0_42353445_thiengtham';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $pdo->exec("SET time_zone = '+07:00'");
} catch (\Exception $e) {
    fwrite(STDERR, "[db_migrate] Cannot connect to DB: " . $e->getMessage() . "\n");
    exit(1);
}

$dbEsc = $pdo->quote($db);

function tblExists($pdo, $db, $t) {
    $r = $pdo->query("SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = " . $pdo->quote($db) . " AND TABLE_NAME = " . $pdo->quote($t))->fetch();
    return (bool)$r;
}
function colExists($pdo, $db, $t, $c) {
    $r = $pdo->query("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = " . $pdo->quote($db) . " AND TABLE_NAME = " . $pdo->quote($t) . " AND COLUMN_NAME = " . $pdo->quote($c))->fetch();
    return (bool)$r;
}
function idxExists($pdo, $db, $t, $i) {
    $r = $pdo->query("SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = " . $pdo->quote($db) . " AND TABLE_NAME = " . $pdo->quote($t) . " AND INDEX_NAME = " . $pdo->quote($i))->fetch();
    return (bool)$r;
}
function addCol($pdo, $t, $c, $def) {
    global $db;
    if (colExists($pdo, $db, $t, $c)) { echo "exists $t.$c\n"; return; }
    try { $pdo->exec("ALTER TABLE `$t` ADD COLUMN `$c` $def"); echo "added $t.$c\n"; }
    catch (\Exception $e) { echo "skip $t.$c: " . $e->getMessage() . "\n"; }
}
function addIdx($pdo, $t, $i, $def) {
    global $db;
    if (idxExists($pdo, $db, $t, $i)) { echo "idx exists $i\n"; return; }
    try { $pdo->exec("ALTER TABLE `$t` ADD INDEX `$i` $def"); echo "idx added $i\n"; }
    catch (\Exception $e) { echo "idx skip $i: " . $e->getMessage() . "\n"; }
}
function createTable($pdo, $t, $sql) {
    if (tblExists($pdo, $GLOBALS['db'], $t)) { echo "table exists $t\n"; return; }
    try { $pdo->exec($sql); echo "created $t\n"; }
    catch (\Exception $e) { echo "table skip $t: " . $e->getMessage() . "\n"; }
}

// ── quotations: ensure full table, then add any missing new columns ──
createTable($pdo, 'quotations', "
    CREATE TABLE quotations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quotation_number VARCHAR(50) NOT NULL UNIQUE,
        company_template VARCHAR(50) NOT NULL DEFAULT 'luang-prabarg',
        supplier_id INT DEFAULT NULL,
        supplier_name VARCHAR(200) DEFAULT NULL,
        supplier_contact VARCHAR(200) DEFAULT NULL,
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

addCol($pdo, 'quotations', 'customer_id',          'INT DEFAULT NULL AFTER supplier_contact');
addCol($pdo, 'quotations', 'customer_name',        'VARCHAR(200) DEFAULT NULL AFTER customer_id');
addCol($pdo, 'quotations', 'customer_contact',     'VARCHAR(200) DEFAULT NULL AFTER customer_name');
addCol($pdo, 'quotations', 'expiry_date',          'DATE DEFAULT NULL AFTER date');
addCol($pdo, 'quotations', 'converted_to_sale_id', 'INT DEFAULT NULL AFTER grand_total');
addIdx($pdo, 'quotations', 'idx_converted', '(converted_to_sale_id)');

// ── quotation_items ──
createTable($pdo, 'quotation_items', "
    CREATE TABLE quotation_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quotation_id INT NOT NULL,
        product_id INT DEFAULT NULL,
        product_name VARCHAR(255) NOT NULL,
        quantity DECIMAL(12,2) NOT NULL DEFAULT 1,
        unit VARCHAR(50) DEFAULT 'SET',
        unit_price DECIMAL(12,2) NOT NULL DEFAULT 0,
        amount DECIMAL(12,2) NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// ── quotation_history ──
createTable($pdo, 'quotation_history', "
    CREATE TABLE quotation_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quotation_id INT NOT NULL,
        action VARCHAR(50) NOT NULL,
        old_status VARCHAR(50) DEFAULT NULL,
        new_status VARCHAR(50) DEFAULT NULL,
        notes TEXT,
        performed_by INT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_quotation_history (quotation_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

echo "MIGRATION DONE\n";
exit(0);
