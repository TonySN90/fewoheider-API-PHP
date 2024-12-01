<?php

namespace app\routes;

use app\connections\Database;

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $path, string $controllerAction): void
    {
        $this->routes[$method][$path] = $controllerAction;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        $path = strtok($path, '?');

        // Singleton-DB-Connection
        $db = Database::getInstance();

        foreach ($this->routes[$method] ?? [] as $route => $controllerAction) {
            if (preg_match($this->formatRoute($route), $path, $matches)) {
                array_shift($matches);

                list($class, $action) = explode('@', $controllerAction);
                $class = "app\\controller\\$class";

                if (class_exists($class) && method_exists($class, $action)) {
                    $controller = new $class($db);
                    call_user_func_array([$controller, $action], array_values($matches));
                    return;
                }
            }
        }

        http_response_code(404);
        echo "404 Page not found";
    }

    private function formatRoute(string $route): string
    {
        $route = preg_replace('/\{([^\/]+)\}/', '(?P<$1>[^/]+)', $route);
        return "#^$route/?$#";
    }

    public function addApiRoutes(string $prefix, string $controller): void
    {
        $this->addRoute('GET', "$prefix", "$controller@viewAll");
        $this->addRoute('GET', "$prefix/{id}", "$controller@viewById");
        $this->addRoute('POST', "$prefix", "$controller@create");
        $this->addRoute('PUT', "$prefix/{id}", "$controller@update");
        $this->addRoute('DELETE', "$prefix/{id}", "$controller@delete");
    }
}
