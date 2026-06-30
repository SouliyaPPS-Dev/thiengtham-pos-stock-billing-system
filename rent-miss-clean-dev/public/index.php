<?php
 
// Set timezone to Vientiane (UTC+7)
date_default_timezone_set('Asia/Vientiane');

// CORS
header('Access-Control-Allow-Origin: *');

session_start(); 

// PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load helper functions
require_once __DIR__ . '/../app/Helpers/view.php';

// Load environment variables
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Remove quotes if present
            if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1];
            }
            
            $_ENV[$name] = $value;
        }
    }
}

// Enable error reporting only in debug mode
$isDebug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
if ($isDebug) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Auto-detect environment based on host (only if not explicitly set in .env)
$host = $_SERVER['HTTP_HOST'] ?? '';
$isLocalhost = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);

$envFromFile = $_ENV['APP_ENV'] ?? '';
if ($envFromFile !== 'production' && $envFromFile !== 'offline') {
    $_ENV['APP_ENV'] = $isLocalhost ? 'development' : 'production';
}

// Detect environment and set database config
$env = $_ENV['APP_ENV'] ?? 'development';
if ($env === 'production') {
    $_ENV['DB_HOST'] = $_ENV['PROD_DB_HOSTNAME'] ?? 'localhost';
    $_ENV['DB_USERNAME'] = $_ENV['PROD_DB_USERNAME'] ?? 'root';
    $_ENV['DB_PASSWORD'] = $_ENV['PROD_DB_PASSWORD'] ?? '';
    $_ENV['DB_DATABASE'] = $_ENV['PROD_DB_DATABASE'] ?? '';
    $_ENV['DB_PORT'] = $_ENV['PROD_DB_PORT'] ?? '3306';
}

// Adjust REQUEST_URI for subdirectories
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    
    // Dynamically detect base path
    $basePath = str_replace('/public/index.php', '', $scriptName);
    $basePath = str_replace('/index.php', '', $basePath);
    
    // Strip the base path from REQUEST_URI if present
    if (!empty($basePath) && strpos($requestUri, $basePath) === 0) {
        $requestUri = substr($requestUri, strlen($basePath));
    }
    
    // Also strip /public if present (from root .htaccess redirect)
    if (strpos($requestUri, '/public/') === 0) {
        $requestUri = substr($requestUri, 7); // Remove '/public/'
    }
    
    $_SERVER['REQUEST_URI'] = $requestUri;
}

// Ensure URI starts with / and handle empty URI
$uri = $_SERVER['REQUEST_URI'];
if (empty($uri) || $uri[0] !== '/') {
    $uri = '/' . $uri;
}

// Remove trailing slash for matching consistency, except for root
if ($uri !== '/' && substr($uri, -1) === '/') {
    $uri = substr($uri, 0, -1);
}

// Remove query string from URI for routing
$uri = strtok($uri, '?');

try {
    $router = new App\Core\Router();
    $router->handle($uri);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    echo "<br>";
    echo "File: " . $e->getFile();
    echo "<br>";
    echo "Line: " . $e->getLine();
}
