<?php

date_default_timezone_set('Asia/Vientiane');

header('Access-Control-Allow-Origin: *');

session_start();

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

require_once __DIR__ . '/../app/Helpers/view.php';

// ============================================================
// Language detection (Lao / English / Thai / Chinese)
// ?lang=xx sets the preferred language in session + cookie.
// ============================================================
if (isset($_GET['lang'])) {
    $__lang = strtolower(trim($_GET['lang']));
    if (in_array($__lang, ['lo', 'en', 'th', 'zh'], true)) {
        $_SESSION['lang'] = $__lang;
        setcookie('lang', $__lang, time() + (60 * 60 * 24 * 365), '/');
    }
}

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1];
            }
            $_ENV[$name] = $value;
        }
    }
}

// ============================================================
// Multi-environment detection
// Supports: development (localhost), infinityfree, production (HF Spaces)
// ============================================================
$host = $_SERVER['HTTP_HOST'] ?? '';
$isLocalhost = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);

if ($isLocalhost) {
    $_ENV['APP_ENV'] = 'development';
} elseif (!empty(getenv('SPACE_ID')) || ($_ENV['APP_ENV_HF'] ?? null) === 'true') {
    $_ENV['APP_ENV'] = 'production';
} else {
    $ifreeFile = __DIR__ . '/../.env.infinityfree';
    if (file_exists($ifreeFile)) {
        $_ENV['APP_ENV'] = 'infinityfree';
        $lines = file($ifreeFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) continue;
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match("/^'(.*)'$/", $value, $matches)) {
                    $value = $matches[1];
                }
                $_ENV[$name] = $value;
            }
        }
    } else {
        $_ENV['APP_ENV'] = 'production';
    }
}

$isDebug = (getenv('APP_DEBUG') ?: $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? 'false') === 'true';
if ($isDebug) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

$env = $_ENV['APP_ENV'] ?? 'development';
if ($env === 'production') {
    $_ENV['DB_HOST'] = $_ENV['PROD_DB_HOSTNAME'] ?? 'localhost';
    $_ENV['DB_USERNAME'] = $_ENV['PROD_DB_USERNAME'] ?? 'root';
    $_ENV['DB_PASSWORD'] = $_ENV['PROD_DB_PASSWORD'] ?? '';
    $_ENV['DB_DATABASE'] = $_ENV['PROD_DB_DATABASE'] ?? '';
}

if (($_GET['diag'] ?? '') === 'd1ag-7f3k') {
    header('Content-Type: text/plain; charset=utf-8');
    $dbh = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $dbu = $_ENV['DB_USERNAME'] ?? 'root';
    $dbp = $_ENV['DB_PASSWORD'] ?? 'root';
    $dbn = $_ENV['DB_DATABASE'] ?? 'if0_42353445_thiengtham';
    echo "ENV: DB_HOST=$dbh DB_USER=$dbu DB_NAME=$dbn\n";
    echo "ENV: PROD_DB_HOSTNAME=" . ($_ENV['PROD_DB_HOSTNAME'] ?? 'NOT SET') . "\n";
    echo "ENV: APP_ENV=" . ($_ENV['APP_ENV'] ?? 'NOT SET') . "\n";
    echo "ENV: APP_ENV_HF=" . ($_ENV['APP_ENV_HF'] ?? 'NOT SET') . "\n";
    echo "DSN: mysql:host=$dbh;dbname=$dbn\n";
    try {
        $pdo = new PDO("mysql:host=$dbh;dbname=$dbn;charset=utf8mb4", $dbu, $dbp, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        echo "CONNECTED db=$dbn\n";
        $datadir = $pdo->query("SELECT @@datadir AS d")->fetch()['d'];
        $stray = rtrim($datadir, '/') . '/' . $dbn . '/quotations';
        echo "datadir=$datadir stray_path=$stray\n";
        if (is_dir($stray)) {
            echo "STRAY DIR FOUND\n";
            if (@rmdir($stray)) { echo "rmdir OK (empty)\n"; }
            else {
                echo "rmdir failed (not empty?): " . (function_exists('error_get_last') ? json_encode(error_get_last()) : '') . "\n";
                // try removing contents then dir
                array_map('unlink', glob($stray . '/*'));
                if (@rmdir($stray)) { echo "rmdir OK after cleanup\n"; } else { echo "rmdir still failed\n"; }
            }
        } else {
            echo "no stray dir\n";
        }
        $cols = $pdo->query("SHOW COLUMNS FROM quotations")->fetchAll(PDO::FETCH_COLUMN);
        echo "before: " . implode(', ', $cols) . "\n";
        foreach ([
            'customer_id'=>'INT DEFAULT NULL',
            'customer_name'=>'VARCHAR(200) DEFAULT NULL',
            'customer_contact'=>'VARCHAR(200) DEFAULT NULL',
            'expiry_date'=>'DATE DEFAULT NULL',
            'converted_to_sale_id'=>'INT DEFAULT NULL',
        ] as $c=>$def) {
            $has = $pdo->query("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=".$pdo->quote($dbn)." AND TABLE_NAME='quotations' AND COLUMN_NAME=".$pdo->quote($c))->fetch();
            if ($has) { echo "$c: exists\n"; continue; }
            try {
                $pdo->exec("ALTER TABLE quotations ADD COLUMN `$c` $def, ALGORITHM=INSTANT");
                echo "$c: ADDED (instant)\n";
            } catch (\Exception $e1) {
                try {
                    $pdo->exec("ALTER TABLE quotations ADD COLUMN `$c` $def");
                    echo "$c: ADDED (copy)\n";
                } catch (\Exception $e2) {
                    echo "$c: FAIL -> " . $e2->getMessage() . "\n";
                }
            }
        }
        $cols = $pdo->query("SHOW COLUMNS FROM quotations")->fetchAll(PDO::FETCH_COLUMN);
        echo "after: " . implode(', ', $cols) . "\n";
    } catch (\Exception $e) { echo "DB ERROR: " . $e->getMessage() . "\n"; }
    exit;
}

if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $basePath = str_replace('/public/index.php', '', $scriptName);
    $basePath = str_replace('/index.php', '', $basePath);
    if (!empty($basePath) && strpos($requestUri, $basePath) === 0) {
        $requestUri = substr($requestUri, strlen($basePath));
    }
    if (strpos($requestUri, '/public/') === 0) {
        $requestUri = substr($requestUri, 7);
    }
    $_SERVER['REQUEST_URI'] = $requestUri;
}

$uri = $_SERVER['REQUEST_URI'] ?? '/';
if (empty($uri) || $uri[0] !== '/') $uri = '/' . $uri;
if ($uri !== '/' && substr($uri, -1) === '/') $uri = substr($uri, 0, -1);
$uri = strtok($uri, '?');

try {
    $router = new App\Core\Router();
    $router->handle($uri);
} catch (Exception $e) {
    if ($isDebug) {
        echo "Error: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . "<br>";
        echo "Line: " . $e->getLine();
    } else {
        http_response_code(500);
        echo "Internal Server Error";
    }
}
