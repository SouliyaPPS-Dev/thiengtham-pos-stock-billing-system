<?php
require_once __DIR__ . '/app/Helpers/view.php';

$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $value = trim($value);
            if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1];
            }
            $_ENV[$name] = $value;
        }
    }
}

$host = $_ENV['DB_HOST'] ?? 'localhost';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? 'Admin123';
$database = $_ENV['DB_DATABASE'] ?? 'if0_41710498_rent';

$db = new mysqli($host, $username, $password, $database);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error . "\n");
}

$result = $db->query("SHOW COLUMNS FROM customers LIKE 'avatar'");
if ($result && $result->num_rows > 0) {
    echo "Column 'avatar' already exists in customers table.\n";
} else {
    if ($db->query("ALTER TABLE customers ADD COLUMN avatar VARCHAR(500) DEFAULT NULL AFTER id")) {
        echo "Column 'avatar' added to customers table.\n";
    } else {
        echo "Error adding column: " . $db->error . "\n";
    }
}

$db->close();
