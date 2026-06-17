<?php
// Load .env file
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

$host = $_ENV['DB_HOST'] ?? 'localhost';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? 'Admin123';
$database = $_ENV['DB_DATABASE'] ?? 'if0_41710498_rent';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "Starting Database Fix...\n";

// 1. Check if expense_categories exists
$result = $conn->query("SHOW TABLES LIKE 'expense_categories'");
if ($result->num_rows == 0) {
    echo "Creating expense_categories table...\n";
    $sql = "CREATE TABLE `expense_categories` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    if ($conn->query($sql)) {
        echo "Successfully created expense_categories.\n";
    } else {
        echo "Error creating expense_categories: " . $conn->error . "\n";
    }
}

// 2. Check expenses table
$result = $conn->query("SHOW TABLES LIKE 'expenses'");
if ($result->num_rows == 0) {
    echo "Creating expenses table...\n";
    $sql = "CREATE TABLE `expenses` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `expense_date` date NOT NULL,
      `category_id` int(11) DEFAULT NULL,
      `amount` decimal(15,2) NOT NULL,
      `description` text DEFAULT NULL,
      `created_by` int(11) DEFAULT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    if ($conn->query($sql)) {
        echo "Successfully created expenses.\n";
    } else {
        echo "Error creating expenses: " . $conn->error . "\n";
    }
} else {
    echo "expenses table already exists. Checking columns...\n";
    $columns = [
        'category_id' => "INT(11) DEFAULT NULL",
        'amount' => "DECIMAL(15,2) NOT NULL",
        'description' => "TEXT DEFAULT NULL",
        'created_by' => "INT(11) DEFAULT NULL"
    ];

    $after = "expense_date";
    foreach ($columns as $col => $def) {
        $check = $conn->query("SHOW COLUMNS FROM `expenses` LIKE '$col'");
        if ($check->num_rows == 0) {
            echo "Adding column '$col' to expenses...\n";
            $alter_sql = "ALTER TABLE `expenses` ADD `$col` $def AFTER `$after` ";
            if ($conn->query($alter_sql)) {
                echo "Successfully added '$col'.\n";
            } else {
                echo "Error adding '$col': " . $conn->error . "\n";
            }
        } else {
            echo "Column '$col' already exists.\n";
        }
        $after = $col;
    }
}

// 3. Populate categories
$checkCat = $conn->query("SELECT COUNT(*) as count FROM expense_categories");
$row = $checkCat->fetch_assoc();
if ($row['count'] == 0) {
    echo "Populating default categories...\n";
    $sql = "INSERT IGNORE INTO `expense_categories` (`id`, `name`) VALUES
    (1, 'ຄ່າເຊົ່າສະຖານທີ່'),
    (2, 'ຄ່າໄຟຟ້າ'),
    (3, 'ຄ່ານ້ຳ'),
    (4, 'ຄ່າອິນເຕີເນັດ'),
    (5, 'ຄ່າແຮງງານ/ເງິນເດືອນ'),
    (6, 'ຄ່າອຸປະກອນ'),
    (7, 'ອື່ນໆ')";
    $conn->query($sql);
}

echo "Database Fix Completed.\n";
$conn->close();
