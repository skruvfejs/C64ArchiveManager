<?php

declare(strict_types=1);

namespace App\Core;

final class Application
{
    private Config $config;

    public function __construct()
    {
        $this->config = new Config(
            dirname(__DIR__, 2) . '/config'
        );

        date_default_timezone_set(
            $this->config->get('app.timezone', 'UTC')
        );
    }

    public function run(): void
    {
        echo '<h1>'
            . htmlspecialchars(
                $this->config->get('app.name')
            )
            . '</h1>';

        echo '<p>Framework initialized successfully.</p>';

        echo '<p>Version '
            . htmlspecialchars(
                $this->config->get('app.version')
            )
            . '</p>';
    }
}

