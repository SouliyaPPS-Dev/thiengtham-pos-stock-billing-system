<?php

function get_layout_preference() {
    $currentPath = $_SERVER['REQUEST_URI'] ?? '';
    $isForcedNavbar = (strpos($currentPath, '/pos') !== false || strpos($currentPath, '/rentals') !== false);

    if (isset($_GET['layout'])) {
        $layout = $_GET['layout'];
        
        // If it's a forced page and we are setting it to 'navbar', 
        // don't update the session so the manual choice for other pages is preserved.
        if (!($isForcedNavbar && $layout === 'navbar')) {
            $_SESSION['layout_pref'] = $layout;
        }
        
        return $isForcedNavbar ? 'navbar' : $layout;
    }
    
    if ($isForcedNavbar) {
        return 'navbar';
    }
     
    return $_SESSION['layout_pref'] ?? 'sidebar';
}

function view($path, $data = []) {
    extract($data);
    
    // Convert dot notation to path
    $path = str_replace('.', '/', $path);
    $viewFile = __DIR__ . "/../../views/$path.php";
    
    if (!file_exists($viewFile)) {
        die("View file not found: $path");
    }

    // Set layout preference
    $layout_pref = get_layout_preference();

    ob_start();
    require $viewFile;
    $content = ob_get_clean();

    if (isset($data['layout']) && $data['layout'] === false) {
        echo $content;
    } else {
        require __DIR__ . "/../../views/layouts/main.php";
    }
}


function component($name, $data = []) {
    extract($data);
    $path = str_replace('.', '/', $name);
    require __DIR__ . "/../../views/components/$path.php";
}
 
function url($path = '/') {
    // Check if we are in production and have a production URL configured
    $env = $_ENV['APP_ENV'] ?? 'development';
    if ($env === 'production' && !empty($_ENV['PROD_APP_URL'])) {
        $baseUrl = rtrim($_ENV['PROD_APP_URL'], '/');
        $fullUrl = $baseUrl . '/' . ltrim($path, '/');
        return rtrim($fullUrl, '/');
    }

    // Detect base path dynamically for development
    $scriptName = $_SERVER['SCRIPT_NAME']; // e.g., /subdir/public/index.php or /index.php
    $basePath = str_replace('/public/index.php', '', $scriptName);
    $basePath = str_replace('/index.php', '', $basePath);
    
    // Build the full URL with auto-detect protocol
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $protocol = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1') ? 'https' : 'http';
    
    // Check for X-Forwarded-Proto (used by load balancers/proxies)
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        $protocol = 'https';
    }
    
    $fullUrl = $protocol . '://' . $host . $basePath . '/' . ltrim($path, '/');
    
    return rtrim($fullUrl, '/');
}

function current_url() {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $protocol = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1') ? 'https' : 'http';
    
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        $protocol = 'https';
    }
    
    $fullUrl = $protocol . '://' . $host . strtok($path, '?');
    return rtrim($fullUrl, '/');
}

function is_menu_active($routePath) {
    // Get the current request URI path (without query parameters)
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $currentPath = strtok($requestUri, '?');
    
    // Get the base path dynamically
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $basePath = str_replace('/public/index.php', '', $scriptName);
    $basePath = str_replace('/index.php', '', $basePath);
    
    // Remove base path from current path to get the route
    if (!empty($basePath) && strpos($currentPath, $basePath) === 0) {
        $currentPath = substr($currentPath, strlen($basePath));
    }
    
    $currentPath = rtrim($currentPath, '/');
    if (empty($currentPath)) {
        $currentPath = '/';
    }
    
    // Normalize the route path
    $routePath = '/' . ltrim($routePath, '/');
    
    // Exact match for home
    if ($routePath === '/' || $routePath === '') {
        return $currentPath === '/' || $currentPath === '';
    }
    
    // For other routes, check if current path matches or starts with route
    return $currentPath === $routePath || strpos($currentPath, $routePath) === 0;
}

function get_menu_active_class($routePath, $activeClass = 'menu-active-box', $defaultClass = 'text-gray-600 hover:bg-gray-100') {
    return is_menu_active($routePath) ? $activeClass : $defaultClass;
}

function get_store_name() {
    try {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT store_name FROM settings LIMIT 1");
        if ($stmt && $row = $stmt->fetch()) {
            return htmlspecialchars($row['store_name'] ?: 'Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ');
        }
    } catch (\Exception $e) {}
    return 'Miss Clean ຊຸດໄໝໃຫ້ເຊົ່າ';
}
 
function get_active_rentals_count() {
    try {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(*) as count FROM rentals WHERE status = 'Active'");
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch (\Exception $e) {
        return 0;
    }
}

function get_logo_url($logoPath) {
    if (empty($logoPath)) {
        return '';
    }

    // Check if it's already a full URL (e.g., from ImageKit)
    if (strpos($logoPath, 'http') === 0) {
        return $logoPath;
    }

    $env = $_ENV['APP_ENV'] ?? 'development';
    $host = $_SERVER['HTTP_HOST'] ?? '';

    // Development environment
    if ($env === 'development' || strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        return url($logoPath);
    }

    // Production environment - use full URL
    $prodUrl = $_ENV['PROD_APP_URL'] ?? 'https://pos-miss-clean.page.gd/rent-miss-clean';
    return $prodUrl . '/' . ltrim($logoPath, '/');
}
  