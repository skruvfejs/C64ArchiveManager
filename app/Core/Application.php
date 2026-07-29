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

        // Gör databasen tillgänglig för controllers
        $this->router->setDatabase($this->database);
    }

    public function run(): void
    {
        // Ladda routes
        $routes = require dirname(__DIR__, 2) . '/routes/web.php';

        $routes($this->router);

        // Kör applikationen
        $this->router->dispatch($this->request);
    }
}
