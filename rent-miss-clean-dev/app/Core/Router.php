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
            $route = $this->routes[$uri];
            $controllerName = "App\\Controllers\\" . $route[0];
            $method = $route[1];

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $method)) {
                    return $controller->$method();
                }
            }
        }
        
        // Support parameterized routes: /customers/{id}/edit, /customers/{id}, /customers/{id}/delete
        foreach ($this->routes as $routePath => $route) {
            if (strpos($routePath, '{') !== false) {
                $pattern = preg_replace('/\{([^\}]+)\}/', '([^/]+)', $routePath);
                $pattern = '#^' . $pattern . '$#';
                
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    $controllerName = "App\\Controllers\\" . $route[0];
                    $method = $route[1];

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
