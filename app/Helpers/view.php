<?php

function get_layout_preference() {
    if (isset($_GET['layout'])) {
        $_SESSION['layout_pref'] = $_GET['layout'];
        return $_GET['layout'];
    }

    if (!isset($_SESSION['layout_pref'])) {
        $_SESSION['layout_pref'] = 'sidebar';
    }

    return $_SESSION['layout_pref'];
}

function is_admin_route() {
    $currentPath = $_SERVER['REQUEST_URI'] ?? '/';
    return strpos($currentPath, '/admin') === 0;
}

function view($path, $data = []) {
    extract($data);
    $path = str_replace('.', '/', $path);
    $viewFile = __DIR__ . "/../../views/$path.php";

    if (!file_exists($viewFile)) {
        $viewFile = __DIR__ . "/../../views/$path/index.php";
    }

    if (!file_exists($viewFile)) {
        die("View file not found: $path");
    }

    $layout_pref = get_layout_preference();

    ob_start();
    require $viewFile;
    $content = ob_get_clean();

    if (isset($data['layout']) && $data['layout'] === false) {
        echo $content;
    } else {
        $layoutFile = isset($data['layout']) && is_string($data['layout'])
            ? __DIR__ . "/../../views/layouts/{$data['layout']}.php"
            : __DIR__ . "/../../views/layouts/main.php";

        if (!file_exists($layoutFile)) {
            $layoutFile = __DIR__ . "/../../views/layouts/main.php";
        }

        require $layoutFile;
    }
}

function component($name, $data = []) {
    extract($data);
    $path = str_replace('.', '/', $name);
    require __DIR__ . "/../../views/components/$path.php";
}

function url($path = '/') {
    $env = $_ENV['APP_ENV'] ?? 'development';
    $prodUrl = $_ENV['PROD_APP_URL'] ?? '';

    if ($env === 'production' && !empty($prodUrl)) {
        $baseUrl = rtrim($prodUrl, '/');
    } else {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace('/public/index.php', '', $scriptName);
        $basePath = str_replace('/index.php', '', $basePath);

        $host = $_SERVER['HTTP_HOST'] ?? '';
        $protocol = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1') ? 'https' : 'http';
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $protocol = 'https';
        }

        $baseUrl = $protocol . '://' . $host . $basePath;
    }

    $docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
    $publicDir = realpath(__DIR__ . '/../../public');
    if ($docRoot && $publicDir && $docRoot === $publicDir) {
        $path = preg_replace('#^/public/#', '/', $path);
    }

    $fullUrl = $baseUrl . '/' . ltrim($path, '/');
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
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $currentPath = strtok($requestUri, '?');
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = str_replace('/public/index.php', '', $scriptName);
    $basePath = str_replace('/index.php', '', $basePath);
    if (!empty($basePath) && strpos($currentPath, $basePath) === 0) {
        $currentPath = substr($currentPath, strlen($basePath));
    }
    $currentPath = rtrim($currentPath, '/');
    if (empty($currentPath)) $currentPath = '/';
    $routePath = '/' . ltrim($routePath, '/');
    if ($routePath === '/' || $routePath === '') {
        return $currentPath === '/' || $currentPath === '';
    }
    if ($routePath === '/admin') {
        return $currentPath === '/admin' || $currentPath === '/admin/';
    }
    return $currentPath === $routePath || strpos($currentPath, $routePath . '/') === 0 || strpos($currentPath, $routePath) === 0;
}

function get_menu_active_class($routePath, $activeClass = 'menu-active-box', $defaultClass = 'text-gray-600 hover:bg-gray-100') {
    return is_menu_active($routePath) ? $activeClass : $defaultClass;
}

function get_store_logo() {
    try {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'store_logo' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ? ($row['setting_value'] ?: '') : '';
    } catch (\Exception $e) {}
    return '';
}

function get_store_name() {
    try {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'store_name' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ? htmlspecialchars($row['setting_value'] ?: 'My Store') : 'My Store';
    } catch (\Exception $e) {}
    return 'My Store';
}

function get_store_setting($key, $default = '') {
    try {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? htmlspecialchars($row['setting_value'] ?: $default) : $default;
    } catch (\Exception $e) {}
    return $default;
}

function get_store_phone() {
    return get_store_setting('store_phone', '');
}

function get_store_whatsapp() {
    $wa = get_store_setting('store_whatsapp', '');
    $wa = $wa ?: get_store_phone();
    return $wa ?: '+8562078287509';
}

function get_logo_url($logoPath) {
    if (empty($logoPath)) return '';
    if (strpos($logoPath, 'http') === 0) return $logoPath;
    $env = $_ENV['APP_ENV'] ?? 'development';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($env === 'development' || strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        return url($logoPath);
    }
    $prodUrl = $_ENV['PROD_APP_URL'] ?? 'https://your-app.hf.space';
    return $prodUrl . '/' . ltrim($logoPath, '/');
}
