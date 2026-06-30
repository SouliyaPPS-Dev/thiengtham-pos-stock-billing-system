<?php
$host = $_ENV['DB_HOST'] ?? 'localhost';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? 'Admin123';
$database = $_ENV['DB_DATABASE'] ?? 'if0_41710498_rent';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $check = $pdo->query("SHOW COLUMNS FROM users LIKE 'avatar'");
    if (!$check->fetch()) {
        $pdo->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(500) DEFAULT NULL AFTER phone");
        echo "Column 'avatar' added to users table.\n";
    } else {
        echo "Column 'avatar' already exists.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
