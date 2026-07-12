<?php
// TEMPORARY diagnostic script - remove after verification.
$env = @file_get_contents('/var/www/html/.env') ?: '';
$vals = [];
foreach (explode("\n", $env) as $line) {
    $line = trim($line);
    if (!$line || strpos($line, '#') === 0) continue;
    if (preg_match('/^([A-Za-z0-9_]+)=(.*)$/', $line, $m)) {
        $v = trim($m[2]);
        $v = trim($v, '"\'');
        $vals[$m[1]] = $v;
    }
}
$host = $vals['DB_HOST'] ?: '127.0.0.1';
$user = $vals['DB_USERNAME'] ?: 'root';
$pass = $vals['DB_PASSWORD'] ?: 'root';
$db   = $vals['DB_DATABASE'] ?: 'if0_42353445_thiengtham';
header('Content-Type: text/plain; charset=utf-8');
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $r = $pdo->query("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=".$pdo->quote($db)." AND TABLE_NAME='quotations' AND COLUMN_NAME='customer_id'")->fetch();
    $cols = $pdo->query("SHOW COLUMNS FROM quotations")->fetchAll(PDO::FETCH_COLUMN);
    echo "CONNECTED host=$host db=$db\n";
    echo "customer_id exists: " . ($r ? 'YES' : 'NO') . "\n";
    echo "quotations columns: " . implode(', ', $cols) . "\n";
} catch (\Exception $e) {
    echo "DB ERROR: " . $e->getMessage() . "\n";
}
