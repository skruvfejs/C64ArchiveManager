<?php

declare(strict_types=1);

namespace App\Core;

final class Config
{
    private array $config = [];

    public function __construct(string $configPath)
    {
        foreach (glob($configPath . '/*.php') as $file) {
            $name = basename($file, '.php');
            $this->config[$name] = require $file;
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $parts = explode('.', $key);

        $value = $this->config;

        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return $default;
            }

            $value = $value[$part];
        }

        return $value;
    }
}
