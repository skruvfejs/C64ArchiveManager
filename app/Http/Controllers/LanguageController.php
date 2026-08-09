<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Session;

final class LanguageController extends Controller
{
    public function __construct(
        private readonly Session $session
    ) {
    }


    public function english(): void
    {
        $this->setLanguage('en');
    }


    public function swedish(): void
    {
        $this->setLanguage('sv');
    }


    private function setLanguage(
        string $language
    ): void {
        if (!in_array(
            $language,
            ['sv', 'en'],
            true
        )) {
            header('Location: /');

            exit;
        }


        $this->session->set(
            'language',
            $language
        );


        $referer =
            $_SERVER['HTTP_REFERER'] ?? '';


        $redirect = '/';


        if ($referer !== '') {
            $refererParts =
                parse_url($referer);


            $host =
                $_SERVER['HTTP_HOST'] ?? '';


            if (
                isset($refererParts['host'])
                && $refererParts['host'] === $host
            ) {
                $redirect =
                    ($refererParts['path'] ?? '/')
                    . (
                        isset($refererParts['query'])
                            ? '?' . $refererParts['query']
                            : ''
                    );
            }
        }


        header(
            'Location: ' . $redirect
        );

        exit;
    }
}
