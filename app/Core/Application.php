<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Request;

final class Application
{
    private Config $config;
    private Router $router;
    private Request $request;

    public function __construct()
    {
        $this->config = new Config(
            dirname(__DIR__, 2) . '/config'
        );

        date_default_timezone_set(
            $this->config->get('app.timezone', 'UTC')
        );

        $this->router = new Router();
        $this->request = new Request();
    }

    public function run(): void
    {
        $routes = require dirname(__DIR__, 2) . '/routes/web.php';

        $routes($this->router);

        $this->router->dispatch($this->request);
    }
}

