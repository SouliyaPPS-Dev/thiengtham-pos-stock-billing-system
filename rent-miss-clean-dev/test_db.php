<?php
// Database connection test script
// Access this file at: http://localhost/pos-miss-clean/rent-miss-clean-dev/test_db.php

// Load .env file
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

// Get database configuration
$host = $_ENV['DB_HOST'] ?? 'localhost';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? 'Admin123';
$database = $_ENV['DB_DATABASE'] ?? 'if0_41710498_rent';

echo "<h2>Database Connection Test</h2>";
echo "<p><strong>Host:</strong> $host</p>";
echo "<p><strong>Username:</strong> $username</p>";
echo "<p><strong>Database:</strong> $database</p>";
echo "<hr>";

// Test connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo "<p style='color: red;'><strong>Connection FAILED:</strong> " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'><strong>Connection SUCCESSFUL!</strong></p>";
    echo "<p><strong>MySQL Version:</strong> " . $conn->server_info . "</p>";
    
    // Test query
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        echo "<p><strong>Tables in database:</strong></p>";
        echo "<ul>";
        while ($row = $result->fetch_array()) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    }
    
    $conn->close();
}
