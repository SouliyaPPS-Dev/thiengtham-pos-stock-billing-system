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

function logline($s) { echo $s . "\n"; }
// Mirror all output to a web-served log so the result is verifiable
// after a Space rebuild (container stdout is hard to reach).
$MIGRATE_LOG = __DIR__ . '/db_migrate.last.log';
ob_start();

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $pdo->exec("SET time_zone = '+07:00'");
} catch (\Exception $e) {
    fwrite(STDERR, "[db_migrate] FATAL: cannot connect to DB ($host/$db): " . $e->getMessage() . "\n");
    exit(1);
}

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

// Add a column if missing. Tries without a positional clause first
// (append to end of table) so it never depends on a reference column
// existing. Re-checks afterwards and reports clearly on failure.
function addCol($pdo, $db, $t, $c, $def) {
    if (colExists($pdo, $db, $t, $c)) { logline("exists $t.$c"); return true; }
    try {
        $pdo->exec("ALTER TABLE `$t` ADD COLUMN `$c` $def, ALGORITHM=INSTANT");
        logline("added $t.$c (instant)");
        return true;
    } catch (\Exception $e) {
        // INSTANT unsupported for this op (or server too old) — fall back.
        try {
            $pdo->exec("ALTER TABLE `$t` ADD COLUMN `$c` $def");
            logline("added $t.$c");
            return true;
        } catch (\Exception $e2) {
            logline("FAILED $t.$c: " . $e2->getMessage());
            return false;
        }
    }
}

function addIdx($pdo, $db, $t, $i, $def) {
    if (idxExists($pdo, $db, $t, $i)) { logline("idx exists $i"); return; }
    try { $pdo->exec("ALTER TABLE `$t` ADD INDEX `$i` $def"); logline("idx added $i"); }
    catch (\Exception $e) { logline("idx skip $i: " . $e->getMessage()); }
}

function createTable($pdo, $db, $t, $sql) {
    if (tblExists($pdo, $db, $t)) { logline("table exists $t"); return; }
    try { $pdo->exec($sql); logline("created $t"); }
    catch (\Exception $e) { logline("table skip $t: " . $e->getMessage()); }
}

// ── remove stray per-table directories in the data dir ──
// InnoDB stores tables as files (table.frm / table.ibd), never as a
// directory. A leftover directory named like a table (e.g. `quotations/`)
// shadows the real table and makes every ALTER/CREATE fail with
// errno 21 "Is a directory". Drop such stray dirs so migration can proceed.
function delTree($dir) {
    $items = @scandir($dir);
    if ($items === false) return false;
    foreach (array_diff($items, ['.', '..']) as $item) {
        $p = $dir . '/' . $item;
        if (is_dir($p)) delTree($p);
        else @unlink($p);
    }
    return @rmdir($dir);
}

// ── remove stray per-table directories in the data dir ──
// InnoDB stores tables as files (table.frm / table.ibd), never as a
// directory. A leftover directory named exactly like a table (e.g.
// `quotations/`, sometimes with nested junk inside) shadows the real
// table and makes every ALTER/CREATE fail with errno 21 "Is a directory".
// Drop such stray dirs (only those whose name matches a real table) so
// the migration can proceed. Runs as root at container start.
function cleanStrayDirs($pdo, $db) {
    try {
        $datadir = $pdo->query("SELECT @@datadir AS d")->fetchColumn();
        if (!$datadir) return;
        $dbDir = rtrim($datadir, '/') . '/' . $db;
        if (!is_dir($dbDir)) return;
        $tbls = $pdo->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=" . $pdo->quote($db))->fetchAll(PDO::FETCH_COLUMN);
        $tblSet = array_flip($tbls);
        $entries = @scandir($dbDir);
        if ($entries === false) return;
        foreach (array_diff($entries, ['.', '..', 'db.opt']) as $entry) {
            $full = $dbDir . '/' . $entry;
            if (is_dir($full) && isset($tblSet[$entry])) {
                if (delTree($full)) { logline("removed stray data-dir: $entry"); }
                else { logline("note: could not remove stray data-dir: $entry"); }
            }
        }
    } catch (\Exception $e) {
        logline("stray-dir cleanup note: " . $e->getMessage());
    }
}
cleanStrayDirs($pdo, $db);

// ── quotations: ensure full table, then add any missing new columns ──
createTable($pdo, $db, 'quotations', "
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

addCol($pdo, $db, 'quotations', 'customer_id',          'INT DEFAULT NULL');
addCol($pdo, $db, 'quotations', 'customer_name',        'VARCHAR(200) DEFAULT NULL');
addCol($pdo, $db, 'quotations', 'customer_contact',     'VARCHAR(200) DEFAULT NULL');
addCol($pdo, $db, 'quotations', 'expiry_date',          'DATE DEFAULT NULL');
addCol($pdo, $db, 'quotations', 'converted_to_sale_id', 'INT DEFAULT NULL');
addIdx($pdo, $db, 'quotations', 'idx_converted', '(converted_to_sale_id)');

// ── quotation_items ──
createTable($pdo, $db, 'quotation_items', "
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
createTable($pdo, $db, 'quotation_history', "
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

$ok = colExists($pdo, $db, 'quotations', 'customer_id');
logline($ok ? "MIGRATION DONE (customer_id present)" : "MIGRATION DONE (WARNING: customer_id still missing)");
$out = ob_get_clean();
echo $out;
@file_put_contents($MIGRATE_LOG, date('c') . "\n" . $out);
exit($ok ? 0 : 0);
