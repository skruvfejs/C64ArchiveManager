<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class LanguageService
{
    private string $language;

    private array $translations = [];

    public function __construct(
        private readonly SettingsService $settings
    ) {
        $language =
            $this->settings->get(
                'default_language',
                'sv'
            );

        if (!in_array($language, ['sv', 'en'], true)) {
            $language = 'sv';
        }

        $this->language = $language;

        $this->load();
    }


    private function load(): void
    {
        $file =
            dirname(__DIR__, 2)
            . '/lang/'
            . $this->language
            . '.php';

        if (!is_file($file)) {
            throw new RuntimeException(
                'Language file not found: '
                . $this->language
            );
        }

        $translations = require $file;

        if (!is_array($translations)) {
            throw new RuntimeException(
                'Language file must return an array: '
                . $this->language
            );
        }

        $this->translations = $translations;
    }


    public function get(
        string $key,
        ?string $default = null
    ): string {
        if (array_key_exists($key, $this->translations)) {
            return (string) $this->translations[$key];
        }

        return $default ?? $key;
    }


    public function language(): string
    {
        return $this->language;
    }
}
