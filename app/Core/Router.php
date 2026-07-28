<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Request;

final class Router
{
    /**
     * @var array<string, array<string, callable|array>>
     */
    private array $routes = [];

    public function get(string $uri, callable|array $callback): void
    {
        $this->routes['GET'][$uri] = $callback;
    }

    public function post(string $uri, callable|array $callback): void
    {
        $this->routes['POST'][$uri] = $callback;
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri = $request->uri();

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        $callback = $this->routes[$method][$uri];

        // Vanlig callback (closure)
        if (is_callable($callback)) {
            $callback();
            return;
        }

        // [Controller::class, 'method']
        if (is_array($callback) && count($callback) === 2) {
            [$class, $method] = $callback;

            $controller = new $class();

            $controller->$method();

            return;
        }

        throw new \RuntimeException('Invalid route callback.');
    }
}

