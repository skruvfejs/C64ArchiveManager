<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Request;
use ReflectionClass;
use RuntimeException;

final class Router
{
    private array $routes = [];
    private ?Database $database = null;

    public function setDatabase(Database $database): void
    {
        $this->database = $database;
    }

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
        $httpMethod = $request->method();
        $uri = $request->uri();

        if (!isset($this->routes[$httpMethod][$uri])) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        $callback = $this->routes[$httpMethod][$uri];

        if (is_callable($callback)) {
            $callback();
            return;
        }

        if (is_array($callback) && count($callback) === 2) {

            [$class, $method] = $callback;

            $reflection = new ReflectionClass($class);

            if (
                $reflection->hasMethod('__construct') &&
                $reflection->getConstructor()?->getNumberOfParameters() > 0
            ) {

                if ($this->database === null) {
                    throw new RuntimeException('Database has not been configured in Router.');
                }

                $controller = $reflection->newInstance($this->database);

            } else {

                $controller = new $class();

            }

            $controller->$method();

            return;
        }

        throw new RuntimeException('Invalid route callback.');
    }
}
