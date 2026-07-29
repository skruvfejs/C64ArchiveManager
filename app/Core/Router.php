<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Request;
use RuntimeException;

final class Router
{
    private array $routes = [];

    public function __construct(
        private readonly Container $container
    ) {
    }

    public function get(
        string $uri,
        callable|array $callback,
        array $middleware = []
    ): void {
        $this->routes['GET'][$uri] = [
            'callback'   => $callback,
            'middleware' => $middleware,
        ];
    }

    public function post(
        string $uri,
        callable|array $callback,
        array $middleware = []
    ): void {
        $this->routes['POST'][$uri] = [
            'callback'   => $callback,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri    = $request->uri();

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        $route = $this->routes[$method][$uri];

        foreach ($route['middleware'] as $middlewareClass) {

            $middleware = $this->container->make($middlewareClass);

            if (!method_exists($middleware, 'handle')) {
                throw new RuntimeException(
                    sprintf(
                        'Middleware "%s" must implement handle().',
                        $middlewareClass
                    )
                );
            }

            $middleware->handle();
        }

        $callback = $route['callback'];

        if (is_callable($callback)) {
            $callback();
            return;
        }

        if (
            is_array($callback)
            && count($callback) === 2
        ) {

            [$controllerClass, $controllerMethod] = $callback;

            $controller = $this->container->make(
                $controllerClass
            );

            if (!method_exists($controller, $controllerMethod)) {
                throw new RuntimeException(
                    sprintf(
                        'Method "%s::%s" does not exist.',
                        $controllerClass,
                        $controllerMethod
                    )
                );
            }

            $controller->$controllerMethod();

            return;
        }

        throw new RuntimeException('Invalid route callback.');
    }
}

