<?php

declare(strict_types=1);

namespace App\Core;

class Application
{
    public function run(): void
    {
        echo "<!DOCTYPE html>";
        echo "<html lang='sv'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<title>C64 Archive Manager</title>";
        echo "</head>";
        echo "<body>";
        echo "<h1>C64 Archive Manager v0.1</h1>";
        echo "<p>Application started successfully.</p>";
        echo "</body>";
        echo "</html>";
    }
}

