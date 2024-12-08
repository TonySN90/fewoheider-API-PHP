<?php

namespace app\routes;

use app\connections\Database;

/**
 * Class Router
 *
 * A lightweight routing system for managing API endpoints, supporting middleware.
 */
class Router
{
    private array $routes = [];
    private array $middlewares = [];

    /**
     * Registers a new route with the specified HTTP method, path, and controller action.
     *
     * @param string $method The HTTP method (e.g., GET, POST, PATCH, DELETE).
     * @param string $path The route path, e.g., '/users' or '/users/{id}'.
     * @param string $controllerAction The controller and method to handle the route, formatted as 'Controller@method'.
     * @param callable|null $middleware Optional middleware to be executed before the route action.
     */
    public function addRoute(string $method, string $path, string $controllerAction, ?callable $middleware = null): void
    {
        $this->routes[$method][$path] = $controllerAction;

        if ($middleware) {
            $this->middlewares[$method][$path] = $middleware;
        }
    }

    /**
     * Dispatches the incoming request to the appropriate controller and action.
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = strtok($_SERVER['REQUEST_URI'], '?'); // Remove query parameters

        $db = Database::getInstance();

        foreach ($this->routes[$method] ?? [] as $route => $controllerAction) {
            if (preg_match($this->formatRoute($route), $path, $matches)) {
                array_shift($matches);

                // Middleware check
                if (isset($this->middlewares[$method][$route])) {
                    $middleware = $this->middlewares[$method][$route];
                    if (!$middleware()) {
                        // Middleware denied the request
                        return;
                    }
                }

                list($class, $action) = explode('@', $controllerAction);
                $class = "app\\controller\\$class";

                if (class_exists($class) && method_exists($class, $action)) {
                    $controller = new $class($db);
                    call_user_func_array([$controller, $action], array_values($matches));
                    return;
                }
            }
        }

        // No matching route found
        http_response_code(404);
        echo "404 Page not found";
    }

    /**
     * Formats a route string with dynamic placeholders for matching against URLs.
     */
    private function formatRoute(string $route): string
    {
        $route = preg_replace('/\{([^\/]+)\}/', '(?P<$1>[^/]+)', $route); // Replace `{param}` with regex groups.
        return "#^$route/?$#";
    }

    /**
     * Automatically adds a standard set of CRUD API routes for a given resource.
     *
     * @param string $prefix The API route prefix, e.g., '/users'.
     * @param string $controller The name of the controller handling the routes.
     * @param callable|null $middleware Optional middleware to protect all routes.
     */
    public function addApiRoutes(string $prefix, string $controller, ?callable $middleware = null): void
    {
        $this->addRoute('GET', "$prefix", "$controller@getAll", $middleware);
        $this->addRoute('GET', "$prefix/{id}", "$controller@getById", $middleware);
        $this->addRoute('POST', "$prefix", "$controller@create", $middleware);
        $this->addRoute('PATCH', "$prefix/{id}", "$controller@update", $middleware);
        $this->addRoute('DELETE', "$prefix/{id}", "$controller@delete", $middleware);
    }
}
