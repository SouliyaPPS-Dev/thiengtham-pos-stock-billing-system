<?php

namespace App\Core;

class Router {
    protected $routes = [];

    public function __construct() {
        $this->routes = require __DIR__ . '/../../routes/web.php';
    }

    public function handle($uri) {
        $uri = strtok($uri, '?');

        if (array_key_exists($uri, $this->routes)) {
            return $this->dispatch($uri, $this->routes[$uri]);
        }

        foreach ($this->routes as $routePath => $route) {
            if (strpos($routePath, '{') !== false) {
                $pattern = preg_replace('/\{([^\}]+)\}/', '([^/]+)', $routePath);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    return $this->dispatch($uri, $route, $matches);
                }
            }
        }

        http_response_code(404);
        require __DIR__ . '/../../views/pages/404.php';
    }

    protected function dispatch($uri, $route, $params = [])
    {
        $controllerName = "App\\Controllers\\" . $route[0];
        $method = $route[1];

        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo "Controller not found: " . htmlspecialchars($controllerName);
            exit;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo "Method not found: " . htmlspecialchars($method);
            exit;
        }

        return $controller->$method(...$params);
    }
}
