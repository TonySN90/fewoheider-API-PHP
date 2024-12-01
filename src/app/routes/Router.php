<?php

namespace app\routes;

use app\connections\Database;

class Router
{
    private array $routes = [];

    public function add($method, $path, $controllerAction): void
    {
        $this->routes[$method][$path] = $controllerAction;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        // Singleton-DB-Connection
        $db = Database::getInstance();

        foreach ($this->routes[$method] ?? [] as $route => $controllerAction) {
            if (preg_match($this->formatRoute($route), $path, $matches)) {
                array_shift($matches);

                list($class, $action) = explode('@', $controllerAction);
                $class = "app\\controller\\$class";

                if (class_exists($class) && method_exists($class, $action)) {
                    $controller = new $class($db);
                    return call_user_func_array([$controller, $action], array_slice($matches, 1));
                }
            }
        }

        http_response_code(404);
        echo "404 Page not found";
    }

    private function formatRoute($route): string
    {
        $route = preg_replace('/\{([^\/]+)\}/', '(?P<$1>[^/]+)', $route);
        return "#^$route$#";
    }
}
