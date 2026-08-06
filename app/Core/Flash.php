<?php

declare(strict_types=1);

namespace App\Core;

final class Flash
{
    public function __construct(
        private readonly Session $session
    ) {
    }



    public function success(
        string $message
    ): void {

        $this->session->set(
            'flash',
            [
                'type' =>
                    'success',

                'message' =>
                    $message,
            ]
        );
    }



    public function error(
        string $message
    ): void {

        $this->session->set(
            'flash',
            [
                'type' =>
                    'error',

                'message' =>
                    $message,
            ]
        );
    }



    public function get(): ?array
    {
        if (
            !$this->session->has('flash')
        ) {

            return null;
        }



        $flash =
            $this->session->get(
                'flash'
            );



        $this->session->remove(
            'flash'
        );



        return $flash;
    }
}
