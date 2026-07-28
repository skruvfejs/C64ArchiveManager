<?php

declare(strict_types=1);

namespace App\Core;

class Application
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run(): void
    {
        $this->router->get('/', function () {

            echo "<!DOCTYPE html>";

            echo "<html>";

            echo "<head>";

            echo "<meta charset='utf-8'>";

            echo "<title>C64 Archive Manager</title>";

            echo "</head>";

            echo "<body>";

            echo "<h1>C64 Archive Manager</h1>";

            echo "<p>Router fungerar.</p>";

            echo "</body>";

            echo "</html>";

        });

        $this->router->dispatch();
    }
}
