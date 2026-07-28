<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $viewFile = dirname(__DIR__) . '/Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View '{$view}' not found.");
        }

        require $viewFile;
    }
}

