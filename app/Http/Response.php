<?php

declare(strict_types=1);

namespace App\Http;

final class Response
{
    public function status(int $code): void
    {
        http_response_code($code);
    }

    public function redirect(string $url): never
    {
        header("Location: {$url}");
        exit;
    }

    public function json(array $data, int $status = 200): never
    {
        http_response_code($status);

        header('Content-Type: application/json');

        echo json_encode(
            $data,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        exit;
    }
}

