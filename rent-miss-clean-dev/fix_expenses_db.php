<?php
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

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Ensure expense_categories exists
$conn->query("CREATE TABLE IF NOT EXISTS `expense_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// 2. Add category_id to expenses if it doesn't exist
$result = $conn->query("SHOW COLUMNS FROM `expenses` LIKE 'category_id'");
if ($result->num_rows == 0) {
    if ($conn->query("ALTER TABLE `expenses` ADD `category_id` INT(11) AFTER `expense_date`")) {
        echo "Successfully added 'category_id' column to 'expenses' table.\n";
    } else {
        echo "Error adding column: " . $conn->error . "\n";
    }
} else {
    echo "'category_id' column already exists in 'expenses' table.\n";
}

// 3. Ensure other columns exist (amount, description, created_by)
$cols = [
    'amount' => "DECIMAL(15,2) NOT NULL",
    'description' => "TEXT DEFAULT NULL",
    'created_by' => "INT(11) DEFAULT NULL"
];

$after = "category_id";
foreach ($cols as $col => $def) {
    $res = $conn->query("SHOW COLUMNS FROM `expenses` LIKE '$col'");
    if ($res->num_rows == 0) {
        if ($conn->query("ALTER TABLE `expenses` ADD `$col` $def AFTER `$after`")) {
            echo "Successfully added '$col' column to 'expenses' table.\n";
        } else {
            echo "Error adding '$col' column: " . $conn->error . "\n";
        }
    }
    $after = $col;
}

// 4. Populate default categories if empty
$checkCat = $conn->query("SELECT COUNT(*) as count FROM expense_categories");
$row = $checkCat->fetch_assoc();
if ($row['count'] == 0) {
    $conn->query("INSERT IGNORE INTO `expense_categories` (`id`, `name`) VALUES
    (1, 'ຄ່າເຊົ່າສະຖານທີ່'),
    (2, 'ຄ່າໄຟຟ້າ'),
    (3, 'ຄ່ານ້ຳ'),
    (4, 'ຄ່າອິນເຕີເນັດ'),
    (5, 'ຄ່າແຮງງານ/ເງິນເດືອນ'),
    (6, 'ຄ່າອຸປະກອນ'),
    (7, 'ອື່ນໆ')");
    echo "Default categories populated.\n";
}

$conn->close();
echo "Database fix completed.\n";
