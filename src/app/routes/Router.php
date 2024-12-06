<?php

namespace app\routes;

use app\connections\Database;

/**
 * Class Router
 *
 * A lightweight routing system for managing API endpoints and dispatching requests
 * to appropriate controllers and their methods.
 */
class Router
{
    /**
     * @var array An associative array storing routes mapped to their methods and actions.
     *            Example: ['GET' => ['/example' => 'Controller@method']]
     */
    private array $routes = [];

    /**
     * Registers a new route with the specified HTTP method, path, and controller action.
     *
     * @param string $method The HTTP method (e.g., GET, POST, PATCH, DELETE).
     * @param string $path The route path, e.g., '/users' or '/users/{id}'.
     * @param string $controllerAction The controller and method to handle the route, formatted as 'Controller@method'.
     */
    public function addRoute(string $method, string $path, string $controllerAction): void
    {
        $this->routes[$method][$path] = $controllerAction;
    }

    /**
     * Dispatches the incoming request to the appropriate controller and action.
     *
     * - Matches the current request method and path against the registered routes.
     * - Instantiates the appropriate controller and calls the action method with any parameters from the path.
     * - Returns a 404 response if no route matches the request.
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD']; // The HTTP method of the request.
        $path = $_SERVER['REQUEST_URI'];     // The full request URI.

        // Remove query parameters from the URI to get the route path.
        $path = strtok($path, '?');

        // Get the database connection via a singleton pattern.
        $db = Database::getInstance();

        // Iterate through routes of the current HTTP method.
        foreach ($this->routes[$method] ?? [] as $route => $controllerAction) {
            // Check if the route matches the current path.
            if (preg_match($this->formatRoute($route), $path, $matches)) {
                // Remove the full match from the matches array.
                array_shift($matches);

                // Extract the controller and action from the route definition.
                list($class, $action) = explode('@', $controllerAction);
                $class = "app\\controller\\$class";

                // Ensure the class and method exist before invoking.
                if (class_exists($class) && method_exists($class, $action)) {
                    $controller = new $class($db); // Instantiate the controller with the database.
                    call_user_func_array([$controller, $action], array_values($matches)); // Invoke the method with parameters.
                    return;
                }
            }
        }

        // If no route matches, return a 404 error response.
        http_response_code(404);
        echo "404 Page not found";
    }

    /**
     * Formats a route string with dynamic placeholders for matching against URLs.
     *
     * - Converts placeholders like `{id}` into named capture groups for regex.
     *
     * @param string $route The route string with optional placeholders, e.g., '/users/{id}'.
     * @return string The formatted regex pattern for route matching.
     */
    private function formatRoute(string $route): string
    {
        $route = preg_replace('/\{([^\/]+)\}/', '(?P<$1>[^/]+)', $route); // Replace `{param}` with regex groups.
        return "#^$route/?$#"; // Add start/end anchors and optional trailing slash.
    }

    /**
     * Automatically adds a standard set of CRUD API routes for a given resource.
     *
     * - Registers the following routes for a given prefix and controller:
     *   - GET /prefix -> viewAll
     *   - GET /prefix/{id} -> viewById
     *   - POST /prefix -> create
     *   - PATCH /prefix/{id} -> update
     *   - DELETE /prefix/{id} -> delete
     *
     * @param string $prefix The API route prefix, e.g., '/users'.
     * @param string $controller The name of the controller handling the routes.
     */
    public function addApiRoutes(string $prefix, string $controller): void
    {
        $this->addRoute('GET', "$prefix", "$controller@viewAll");
        $this->addRoute('GET', "$prefix/{id}", "$controller@viewById");
        $this->addRoute('POST', "$prefix", "$controller@create");
        $this->addRoute('PATCH', "$prefix/{id}", "$controller@update");
        $this->addRoute('DELETE', "$prefix/{id}", "$controller@delete");
    }
}
