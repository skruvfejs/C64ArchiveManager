<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    /**
     * Gemensamma variabler som finns i alla vyer.
     *
     * @var array<string,mixed>
     */
    private array $shared = [];


    /**
     * Dela en variabel med alla vyer.
     */
    public function share(string $key, mixed $value): void
    {
        $this->shared[$key] = $value;
    }


    public function render(
        string $view,
        array $data = [],
        string $layout = 'layouts/main'
    ): void {

        $data = array_merge($this->shared, $data);

        extract($data, EXTR_SKIP);

        $viewFile =
            dirname(__DIR__)
            . '/Views/'
            . $view
            . '.php';


        if (!file_exists($viewFile)) {

            throw new \RuntimeException(
                "View '{$view}' not found."
            );

        }


        ob_start();

        require $viewFile;

        $content =
            ob_get_clean();


        $layoutFile =
            dirname(__DIR__)
            . '/Views/'
            . $layout
            . '.php';


        if (!file_exists($layoutFile)) {

            throw new \RuntimeException(
                "Layout '{$layout}' not found."
            );

        }


        require $layoutFile;
    }
}
