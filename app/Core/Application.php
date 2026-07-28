<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Request;

final class Application
{
    private Config $config;
    private Router $router;
    private Request $request;
    private Database $database;

    public function __construct()
    {
        // Ladda konfiguration
        $this->config = new Config(
            dirname(__DIR__, 2) . '/config'
        );

        // Sätt tidszon
        date_default_timezone_set(
            $this->config->get('app.timezone', 'UTC')
        );

        // Initiera kärnobjekt
        $this->router = new Router();
        $this->request = new Request();
        $this->database = new Database($this->config);
    }

    public function run(): void
    {
        // Ladda alla webbroutes
        $routes = require dirname(__DIR__, 2) . '/routes/web.php';

        $routes($this->router);

        // Kör rätt route
        $this->router->dispatch($this->request);
    }
}

