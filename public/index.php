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
    // Development: use existing .env with local MySQL
    $_ENV['APP_ENV'] = 'development';
} elseif (strpos($host, 'infinityfree') !== false || file_exists(__DIR__ . '/../.env.infinityfree')) {
    // InfinityFree: load .env.infinityfree overrides
    $_ENV['APP_ENV'] = 'infinityfree';
    $ifreeFile = __DIR__ . '/../.env.infinityfree';
    if (file_exists($ifreeFile)) {
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
    }
} else {
    // Production (HF Spaces): use PROD_DB_* env vars (set by start.sh or Space Secrets)
    $_ENV['APP_ENV'] = 'production';
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
