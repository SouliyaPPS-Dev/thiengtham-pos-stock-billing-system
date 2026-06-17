# Project Starter Blueprint: Custom PHP MVC + Tailwind CSS + Alpine.js

A comprehensive blueprint for creating a new full-stack PHP SSR web application. Use this prompt to scaffold a project with the same architecture as the `Rental` POS system — a custom PHP MVC framework optimized for shared hosting (InfinityFree) and local (XAMPP) deployment.

---

## 1. Project Overview & Tech Stack

- **Architecture:** Custom PHP MVC (Model-View-Controller) with front controller pattern
- **Frontend:** Tailwind CSS 3 (compiled), Alpine.js 3.x, FontAwesome 6.4, SweetAlert2 11, Google Fonts
- **Backend:** PHP 8.x+ with PDO (singleton pattern) for MySQL/MariaDB
- **Routing:** Custom array-based router with `{param}` regex matching
- **Auth:** Session-based with `BaseController` guard; hardcoded or database-backed login
- **UI Language:** Lao (UTF-8) using Noto Sans Lao font (configurable)
- **Deployment:** Auto-detects subdirectory paths for local (XAMPP) vs production (shared hosting)
- **PWA:** Service Worker + manifest for offline support and installable app
- **Client State:** Alpine.js for reactive UI components, polling, live notifications
- **Notifications:** Browser Push Notification API + polling every 30s

---

## 2. Directory Structure

```
/
├── .env                    # Environment variables (DB, App, ImageKit)
├── .htaccess               # Root redirect to public/ + security rules
├── index.php               # Root fallback (requires public/index.php)
├── tailwind.config.js      # Tailwind config with theme colors + fonts
├── app/
│   ├── Controllers/        # Business logic (extend BaseController)
│   │   ├── BaseController.php   # Auth guard (session check)
│   │   ├── LoginController.php  # NO extends BaseController (public)
│   │   ├── HomeController.php   # Dashboard
│   │   ├── [Resource]Controller.php  # CRUD pattern per entity
│   │   └── ...
│   ├── Core/               # Framework core
│   │   ├── Database.php    # PDO Singleton wrapper
│   │   └── Router.php      # Route handler (static + param matching)
│   ├── Helpers/            # Global helper functions
│   │   └── view.php        # view(), url(), component(), is_menu_active()
│   ├── Models/             # Data access (raw SQL via PDO)
│   └── Services/           # External integrations (e.g., ImageKit)
├── assets/
│   └── css/
│       └── input.css       # Tailwind source + CSS variables
├── public/                 # Document root
│   ├── .htaccess           # URL rewriting for clean URLs
│   ├── index.php           # Front Controller (autoloader, env, router dispatch)
│   ├── css/app.css         # Compiled Tailwind
│   ├── sw.js               # Service Worker (PWA)
│   ├── manifest.json       # PWA manifest
│   └── icon-*.png / logo.jpg
├── routes/
│   └── web.php             # Route definitions (returns PHP array)
├── views/
│   ├── components/         # Reusable UI fragments (navbar, footer)
│   ├── layouts/            # Master template (main.php — sidebar/navbar modes)
│   └── pages/              # Page-specific views (organized by resource)
└── database.sql            # Full schema + seed data
```

---

## 3. Core Implementation Blueprints

### A. Front Controller & Autoloader (`public/index.php`)

```php
<?php
date_default_timezone_set('Asia/Vientiane');
session_start();

// PSR-4-like Autoloader
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

// Parse .env (manual, no library)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
}

// Environment detection
$isLocalhost = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false);
$_ENV['APP_ENV'] = $isLocalhost ? 'development' : 'production';

// Auto-select DB config for dev vs prod
if ($_ENV['APP_ENV'] === 'production') {
    $_ENV['DB_HOST'] = $_ENV['PROD_DB_HOSTNAME'];
    $_ENV['DB_USERNAME'] = $_ENV['PROD_DB_USERNAME'];
    $_ENV['DB_PASSWORD'] = $_ENV['PROD_DB_PASSWORD'];
    $_ENV['DB_DATABASE'] = $_ENV['PROD_DB_DATABASE'];
}

// Strip base path from REQUEST_URI (handles subdirectory deployment)
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = str_replace('/public/index.php', '', $scriptName);
$requestUri = $_SERVER['REQUEST_URI'];
if (!empty($basePath) && strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}
if (strpos($requestUri, '/public/') === 0) {
    $requestUri = substr($requestUri, 7);
}
$_SERVER['REQUEST_URI'] = $requestUri;

// Normalize URI
$uri = $_SERVER['REQUEST_URI'];
if (empty($uri) || $uri[0] !== '/') $uri = '/' . $uri;
if ($uri !== '/' && substr($uri, -1) === '/') $uri = substr($uri, 0, -1);
$uri = strtok($uri, '?');

try {
    $router = new App\Core\Router();
    $router->handle($uri);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### B. Router (`app/Core/Router.php`)

```php
<?php
namespace App\Core;

class Router {
    protected $routes = [];

    public function __construct() {
        $this->routes = require __DIR__ . '/../../routes/web.php';
    }

    public function handle($uri) {
        $uri = strtok($uri, '?');

        // Exact match for static routes
        if (array_key_exists($uri, $this->routes)) {
            [$controllerName, $method] = $this->routes[$uri];
            $controllerName = "App\\Controllers\\" . $controllerName;
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $method)) {
                    return $controller->$method();
                }
            }
        }

        // Regex match for parameterized routes: /customers/{id}/edit
        foreach ($this->routes as $routePath => $route) {
            if (strpos($routePath, '{') !== false) {
                $pattern = preg_replace('/\{([^\}]+)\}/', '([^/]+)', $routePath);
                $pattern = '#^' . $pattern . '$#';
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    [$controllerName, $method] = $route;
                    $controllerName = "App\\Controllers\\" . $controllerName;
                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        if (method_exists($controller, $method)) {
                            return $controller->$method(...$matches);
                        }
                    }
                }
            }
        }

        http_response_code(404);
        require __DIR__ . '/../../views/pages/404.php';
    }
}
```

### C. Database Singleton (`app/Core/Database.php`)

```php
<?php
namespace App\Core;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $db   = $_ENV['DB_DATABASE'] ?? '';
        $user = $_ENV['DB_USERNAME'] ?? 'root';
        $pass = $_ENV['DB_PASSWORD'] ?? '';

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->connection = new \PDO($dsn, $user, $pass, $options);
        $this->connection->exec("SET time_zone = '+07:00'");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
```

### D. View Engine (`app/Helpers/view.php`)

```php
<?php

function view($path, $data = []) {
    extract($data);
    $path = str_replace('.', '/', $path);
    $viewFile = __DIR__ . "/../../views/$path.php";
    if (!file_exists($viewFile)) die("View file not found: $path");

    $layout_pref = get_layout_preference();
    ob_start();
    require $viewFile;
    $content = ob_get_clean();

    if (isset($data['layout']) && $data['layout'] === false) {
        echo $content; // raw output (no layout wrapper)
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
    $env = $_ENV['APP_ENV'] ?? 'development';
    if ($env === 'production' && !empty($_ENV['PROD_APP_URL'])) {
        return rtrim($_ENV['PROD_APP_URL'], '/') . '/' . ltrim($path, '/');
    }
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $basePath = str_replace('/public/index.php', '', $scriptName);
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $protocol = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    return rtrim($protocol . '://' . $host . $basePath . '/' . ltrim($path, '/'), '/');
}

function is_menu_active($routePath) {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $currentPath = rtrim(strtok($requestUri, '?'), '/');
    if (empty($currentPath)) $currentPath = '/';
    $routePath = '/' . ltrim($routePath, '/');
    return $currentPath === $routePath;
}

function get_layout_preference() {
    $isForcedNavbar = (strpos($_SERVER['REQUEST_URI'] ?? '', '/pos') !== false
                       || strpos($_SERVER['REQUEST_URI'] ?? '', '/rentals') !== false);
    if (isset($_GET['layout'])) {
        if (!($isForcedNavbar && $_GET['layout'] === 'navbar')) {
            $_SESSION['layout_pref'] = $_GET['layout'];
        }
        return $isForcedNavbar ? 'navbar' : $_GET['layout'];
    }
    return $isForcedNavbar ? 'navbar' : ($_SESSION['layout_pref'] ?? 'sidebar');
}
```

### E. Base Controller & Auth Guard (`app/Controllers/BaseController.php`)

```php
<?php
namespace App\Controllers;

class BaseController {
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . url('/login'));
            exit;
        }
    }
}
```

### F. Login Controller (Public — No Auth) (`app/Controllers/LoginController.php`)

```php
<?php
namespace App\Controllers;

class LoginController {
    public function index() {
        if (isset($_SESSION['user'])) {
            header('Location: ' . url('/'));
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->login();
        }
        return view('pages.login', ['title' => 'Login', 'no_nav' => true]);
    }

    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Authenticate against DB or hardcoded (dev mode)
        if ($username === 'admin' && $password === '123456') {
            $_SESSION['user'] = ['username' => 'admin', 'name' => 'Admin User'];
            header('Location: ' . url('/'));
            exit;
        }

        return view('pages.login', [
            'title' => 'Login',
            'error' => 'Invalid credentials',
            'no_nav' => true
        ]);
    }

    public function logout() {
        session_destroy();
        header('Location: ' . url('/login'));
        exit;
    }
}
```

### G. Example CRUD Controller Pattern (`app/Controllers/CustomerController.php`)

```php
<?php
namespace App\Controllers;

use App\Models\Customer as CustomerModel;
use App\Models\CustomerType as CustomerTypeModel;

class CustomerController extends BaseController {
    // LIST
    public function index() {
        $model = new CustomerModel();
        $customers = $model->getCustomers("WHERE 1=1", [], 20, 0);
        return view('pages.customers.index', [
            'title' => 'Customers',
            'customers' => $customers
        ]);
    }

    // CREATE (GET form + POST submit)
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new CustomerModel();
            $data = [
                'fullname' => $_POST['fullname'] ?? '',
                'phone'    => $_POST['phone'] ?? '',
                // ... map all fields
            ];
            $errors = $model->validateCustomer($data);
            if (!empty($errors)) {
                return view('pages.customers.create', [
                    'title' => 'Add Customer',
                    'errors' => $errors,
                    'data' => $data
                ]);
            }
            $model->createCustomer($data);
            header('Location: ' . url('/customers') . '?success=1');
            exit;
        }
        return view('pages.customers.create', ['title' => 'Add Customer']);
    }

    // EDIT (GET + POST)
    public function edit($id) {
        $model = new CustomerModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // validate + update + redirect
            $model->updateCustomer($id, $_POST);
            header('Location: ' . url('/customers') . '?updated=1');
            exit;
        }
        $customer = $model->getCustomerById($id);
        return view('pages.customers.edit', [
            'title' => 'Edit Customer',
            'customer' => $customer
        ]);
    }

    // SHOW detail
    public function show($id) {
        $model = new CustomerModel();
        $customer = $model->getCustomerById($id);
        $history = $model->getCustomerRentalHistory($id);
        return view('pages.customers.show', [
            'title' => 'Customer Detail',
            'customer' => $customer,
            'history' => $history
        ]);
    }

    // DELETE
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new CustomerModel();
            $model->deleteCustomer($id);
            header('Location: ' . url('/customers') . '?deleted=1');
            exit;
        }
        return view('pages.customers.delete', ['id' => $id]);
    }

    // JSON endpoint (AJAX)
    public function view($id) {
        $model = new CustomerModel();
        $customer = $model->getCustomerById($id);
        header('Content-Type: application/json');
        echo json_encode($customer);
    }

    // Check for AJAX requests
    private function isAjax() {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }
}
```

### H. Example Model Pattern (`app/Models/Customer.php`)

```php
<?php
namespace App\Models;

use App\Core\Database;

class Customer {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($where = "WHERE 1=1", $params = [], $limit = 20, $offset = 0) {
        $sql = "SELECT * FROM customers $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO customers (fullname, phone, email, status, created_by)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['fullname'], $data['phone'], $data['email'],
            $data['status'] ?? 'Active', $data['created_by'] ?? 1
        ]);
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;
        $sql = "UPDATE customers SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
```

### I. Example View Pattern (`views/pages/customers/create.php`)

```php
<div class="p-4 md:p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Add Customer</h1>
        <a href="<?= url('/customers') ?>" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <?php if (!empty($errors ?? [])): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
        <ul class="list-disc list-inside text-sm">
            <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" class="bg-white rounded-2xl border p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                <input type="text" name="fullname"
                       value="<?= htmlspecialchars($data['fullname'] ?? $_POST['fullname'] ?? '') ?>"
                       required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                <input type="text" name="phone"
                       value="<?= htmlspecialchars($data['phone'] ?? $_POST['phone'] ?? '') ?>"
                       required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-sky-500 outline-none">
            </div>
        </div>
        <div class="flex items-center justify-end gap-4 mt-6 pt-6 border-t">
            <a href="/customers" class="px-6 py-2.5 border text-gray-600 rounded-xl font-medium hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-sky-500 text-white rounded-xl font-medium hover:bg-sky-600">
                <i class="fas fa-save mr-2"></i> Save
            </button>
        </div>
    </form>
</div>
```

---

## 4. Routing Conventions (`routes/web.php`)

All routes defined in `routes/web.php` returning a PHP array:

```php
<?php
return [
    '/'                     => ['HomeController', 'index'],
    '/login'                => ['LoginController', 'index'],
    '/logout'               => ['LoginController', 'logout'],

    // CRUD resource pattern
    '/customers'            => ['CustomerController', 'index'],
    '/customers/create'     => ['CustomerController', 'create'],
    '/customers/store'      => ['CustomerController', 'store'],
    '/customers/{id}/edit'  => ['CustomerController', 'edit'],
    '/customers/{id}/update'=> ['CustomerController', 'update'],
    '/customers/{id}'       => ['CustomerController', 'show'],
    '/customers/{id}/delete'=> ['CustomerController', 'delete'],

    // JSON endpoints
    '/customers/{id}/view'  => ['CustomerController', 'view'],

    // Settings
    '/settings'             => ['SettingsController', 'index'],
    '/settings/update'      => ['SettingsController', 'update'],
];
```

---

## 5. Master Layout (`views/layouts/main.php`)

Key patterns in the layout:

- **Two layout modes:** Sidebar (default for desktop) and Navbar (top nav, forced for POS/rentals)
- **Layout switched** via `?layout=sidebar|navbar`, stored in `$_SESSION['layout_pref']`
- **CDN assets:** Tailwind compiled CSS, Alpine.js 3, FontAwesome 6, SweetAlert2 11, Google Fonts
- **Global Alpine.js component** `notifications()` for polling-based notification dropdown
- **Flash messages:** URL query params (`?success=1`, `?error=1`, `?updated=1`, `?deleted=1`) parsed by SweetAlert2 on DOMContentLoaded
- **PWA support:** Service Worker registration, iOS meta tags, app manifest
- **`x-cloak`** to hide Alpine.js elements until initialized
- **Nav items** use `get_menu_active_class('/route')` for active state highlighting

---

## 6. Frontend Patterns

### Alpine.js Components

Alpine.js handles all client-side interactivity. Each component is defined inline with `x-data`:

```html
<div x-data="{ open: false }">
  <button @click="open = !open">Toggle</button>
  <div x-show="open">Content</div>
</div>
```

Common Alpine patterns in this architecture:

- **Notifications polling:** `fetch()` every 30s to `/rentals/notifications`, Browser Notification API for push alerts
- **Image preview modal:** Custom event `@open-image-preview.window` with zoom controls
- **Fullscreen toggle:** `document.documentElement.requestFullscreen()`
- **SweetAlert2 confirmations:** `confirmLogout()`, delete confirmations
- **Layout switcher:** URL parameter toggle for sidebar/navbar modes

### CSS Theme Variables (`assets/css/input.css`)

```css
:root {
  --background: 0 0% 100%;
  --foreground: 222.2 84% 4.9%;
  --primary: 199 89% 48%; /* #0ea5e9 (sky blue) */
  --primary-foreground: 0 0% 100%;
  --border: 214.3 31.8% 91.4%;
  --ring: 199 89% 48%;
  --radius: 0.5rem;
}
```

### Flash Messages from URL Params

Every page automatically shows toast notifications via SweetAlert2 (defined in layout):

| Query Param  | Icon    | Message                                           |
| ------------ | ------- | ------------------------------------------------- |
| `?success=1` | success | Record saved                                      |
| `?updated=1` | success | Record updated                                    |
| `?deleted=1` | success | Record deleted                                    |
| `?error=1`   | error   | Error message (use `&error_msg=` for custom text) |

---

## 7. JSON / AJAX Endpoint Pattern

Controllers return JSON for XHR requests:

```php
public function getData($id) {
    $model = new SomeModel();
    $data = $model->getById($id);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $data]);
}
```

AJAX detection (if needed): `$_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'`

---

## 8. PWA Setup

### `public/manifest.json`

Complete PWA manifest for installable app with add-to-home-screen support:

```json
{
  "name": "App Name",
  "short_name": "App",
  "description": "App description",
  "id": "app-id-v1",
  "start_url": "../",
  "scope": "../",
  "display": "standalone",
  "display_override": ["window-controls-overlay", "standalone", "browser"],
  "orientation": "portrait",
  "background_color": "#ffffff",
  "theme_color": "#0ea5e9",
  "categories": ["business", "productivity"],
  "icons": [
    {
      "src": "./icon-192.png",
      "sizes": "192x192",
      "type": "image/png",
      "purpose": "any"
    },
    {
      "src": "./icon-512.png",
      "sizes": "512x512",
      "type": "image/png",
      "purpose": "any"
    },
    {
      "src": "./icon-512.png",
      "sizes": "512x512",
      "type": "image/png",
      "purpose": "maskable"
    }
  ],
  "prefer_related_applications": false,
  "shortcuts": [
    {
      "name": "POS",
      "url": "../pos?layout=navbar",
      "icons": [
        { "src": "./icon-192.png", "sizes": "192x192", "type": "image/png" }
      ]
    },
    {
      "name": "History",
      "url": "../rentals?layout=navbar",
      "icons": [
        { "src": "./icon-192.png", "sizes": "192x192", "type": "image/png" }
      ]
    }
  ]
}
```

### `public/sw.js` (Service Worker)

Complete service worker with cache-first for static assets and network-first for dynamic routes:

```javascript
const CACHE_NAME = "app-v1";

const STATIC_ASSETS = [
  "./public/css/app.css",
  "./public/logo.jpg",
  "./public/icon-192.png",
  "./public/icon-512.png",
  "./public/manifest.json",
  "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css",
  "https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js",
  "https://cdn.jsdelivr.net/npm/sweetalert2@11",
];

const DYNAMIC_ROUTES = [
  "/inventory",
  "/rentals",
  "/pos",
  "/customers",
  "/customer-types",
  "/staff",
  "/settings",
  "/expenses",
  "/category",
  "/print-invoice",
  "/login",
  "/logout",
  "/api",
];

self.addEventListener("install", (e) => {
  self.skipWaiting();
  e.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(STATIC_ASSETS);
    }),
  );
});

self.addEventListener("activate", (e) => {
  e.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)),
      );
    }),
  );
  e.waitUntil(clients.claim());
});

self.addEventListener("fetch", (e) => {
  if (e.request.method !== "GET") return;

  const url = new URL(e.request.url);

  if (
    DYNAMIC_ROUTES.some((route) => {
      const parts = url.pathname.split("/");
      return parts.includes(route.replace("/", ""));
    }) ||
    e.request.mode === "navigate"
  ) {
    e.respondWith(
      fetch(e.request).catch(() => {
        return caches.match(e.request).then((cached) => {
          return cached || caches.match("./offline.html");
        });
      }),
    );
    return;
  }

  e.respondWith(
    caches.match(e.request).then((cached) => {
      return (
        cached ||
        fetch(e.request)
          .then((res) => {
            if (res.ok) {
              const clone = res.clone();
              caches
                .open(CACHE_NAME)
                .then((cache) => cache.put(e.request, clone));
            }
            return res;
          })
          .catch(() => caches.match("./offline.html"))
      );
    }),
  );
});
```

### PWA Integration in Layout (`views/layouts/main.php`)

Add to `<head>` section:

```html
<!-- PWA Manifest -->
<link rel="manifest" href="<?= url('/public/manifest.json') ?>" />
<meta name="theme-color" content="#0ea5e9" />

<!-- iOS PWA Support -->
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="default" />
<meta name="apple-mobile-web-app-title" content="App Name" />
<link rel="apple-touch-icon" href="<?= url('/public/icon-192.png') ?>" />

<!-- Service Worker Registration -->
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('<?= url('/public/sw.js') ?>')
        .then(reg => console.log('SW registered:', reg))
        .catch(err => console.log('SW registration failed:', err));
    });
  }
</script>
```

### Add to Home Screen Prompt (Optional)

Use `beforeinstallprompt` event to show custom install button:

```javascript
let deferredPrompt;

window.addEventListener("beforeinstallprompt", (e) => {
  e.preventDefault();
  deferredPrompt = e;
  // Show install button in UI
});

// On install button click:
async function installApp() {
  if (deferredPrompt) {
    deferredPrompt.prompt();
    const { outcome } = await deferredPrompt.userChoice;
    deferredPrompt = null;
  }
}
```

---

## 9. Deployment Configuration

### Root `.htaccess`

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(manifest\.json|sw\.js|offline\.html)$ public/$1 [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/index.php [L,QSA]
</IfModule>

<FilesMatch "^\.env|database\.sql$">
    Require all denied
</FilesMatch>
```

### Environment Variables (`.env`)

```env
APP_NAME="Your App Name"
APP_ENV=development
APP_DEBUG=true

# Production URL (for production env)
PROD_APP_URL=https://your-domain.com/subdir

# Development DB
DB_HOST=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_DATABASE=your_db

# Production DB
PROD_DB_HOSTNAME=sqlXXX.infinityfree.com
PROD_DB_USERNAME=if0_XXX
PROD_DB_PASSWORD=XXX
PROD_DB_DATABASE=if0_XXX_db
```

---

## 10. Tailwind CSS Configuration (`tailwind.config.js`)

```js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/**/*.php",
    "./views/**/*.php",
    "./public/**/*.php",
    "./index.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: { DEFAULT: "#0ea5e9", foreground: "#ffffff" },
        // ... add custom colors as needed
      },
      fontFamily: {
        sans: ['"Noto Sans Lao"', "sans-serif"],
      },
    },
  },
  plugins: [],
};
```

---

## 11. Project Initialization Steps

1. Create full directory structure (`app/Controllers/`, `app/Core/`, `app/Helpers/`, `app/Models/`, `public/`, `routes/`, `views/layouts/`, `views/pages/`, `views/components/`, `assets/css/`)
2. Setup `public/index.php` with autoloader, env parser, base path detection, and router dispatch
3. Implement `app/Core/Database.php` (PDO singleton)
4. Implement `app/Core/Router.php` (static + regex param matching)
5. Create `app/Helpers/view.php` with `view()`, `url()`, `component()`, `is_menu_active()`, `get_layout_preference()`
6. Create `app/Controllers/BaseController.php` (auth guard)
7. Create `app/Controllers/LoginController.php` (public — no extends)
8. Create `views/layouts/main.php` (master template with sidebar/navbar, Alpine.js, SweetAlert2 flash messages)
9. Create `views/components/navbar.php` and `views/components/footer.php`
10. Create `views/pages/login.php` and `views/pages/home.php`
11. Create `views/pages/404.php`
12. Define initial routes in `routes/web.php`
13. Create `.env` with placeholders
14. Create root `.htaccess` and `public/.htaccess`
15. Setup `tailwind.config.js` and `assets/css/input.css`
16. Add PWA support: `public/manifest.json`, `public/sw.js`, `offline.html`
17. Create `public/css/app.css` (empty or Tailwind build output)
18. Initialize first model + controller for your primary domain entity
19. Create `database.sql` with full schema

---

## 12. Design & UI Standards

- **Font:** "Noto Sans Lao" (Google Fonts) — supports Lao, Thai, and Latin scripts
- **Primary color:** `#0ea5e9` (sky blue) — consistent throughout
- **Border radius:** `rounded-xl` (12px) for cards/inputs/buttons, `rounded-2xl` (16px) for modals
- **Navigation sidebar:** Fixed on desktop, slide-over on mobile (Alpine.js `sidebarOpen`)
- **Navigation navbar:** Sticky top bar with horizontal nav links
- **Charts:** SVG inline (no chart library dependency)
- **Animations:** Tailwind transitions + Alpine.js x-transition for dropdowns/modals
- **Buttons:** Rounded, with hover states and shadow on primary actions
