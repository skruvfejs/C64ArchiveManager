<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Auth;
use App\Core\Session;
use RuntimeException;

final class LanguageService
{
    private string $language;

    private array $translations = [];


    public function __construct(
        private readonly SettingsService $settings,
        private readonly Session $session,
        private readonly Auth $auth,
        private readonly UserService $users
    ) {
        $language = null;

        if ($this->auth->check()) {
            $userId = $this->auth->id();

            if ($userId !== null) {
                $user = $this->users->findById($userId);

                if ($user !== null) {
                    $language = $user->getLanguage();
                }
            }
        } else {
            $language = $this->session->get(
                'language'
            );
        }

        if (!in_array($language, ['sv', 'en'], true)) {
            $language =
                $this->settings->get(
                    'default_language',
                    'sv'
                );
        }

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
        if (
            array_key_exists(
                $key,
                $this->translations
            )
        ) {
            return (string) $this->translations[$key];
        }

        return $default ?? $key;
    }


    public function language(): string
    {
        return $this->language;
    }
}
