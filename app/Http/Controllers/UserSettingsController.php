<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\View;
use App\Services\UserService;

final class UserSettingsController extends Controller
{
    public function __construct(
        private readonly Auth $auth,
        private readonly UserService $users,
        private readonly View $view,
        private readonly Flash $flash
    ) {
    }


    public function index(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');

            exit;
        }


        $userId =
            $this->auth->id();


        $user =
            $this->users->findById(
                $userId
            );


        if ($user === null) {
            $this->flash->error(
                'Användaren hittades inte.'
            );

            header(
                'Location: /'
            );

            exit;
        }


        $this->view->render(
            'account/settings',
            [
                'title' =>
                    'Användarinställningar',

                'user' =>
                    $user
            ]
        );
    }


    public function update(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');

            exit;
        }


        $userId =
            $this->auth->id();


        $user =
            $this->users->findById(
                $userId
            );


        if ($user === null) {
            $this->flash->error(
                'Användaren hittades inte.'
            );

            header(
                'Location: /account/settings'
            );

            exit;
        }


        $language =
            $_POST['language'] ?? '';


        if (!in_array(
            $language,
            ['sv', 'en'],
            true
        )) {
            $this->flash->error(
                'Ogiltigt språkval.'
            );

            header(
                'Location: /account/settings'
            );

            exit;
        }


        $user->setLanguage(
            $language
        );


        if (!$this->users->update($user)) {
            $this->flash->error(
                'Inställningarna kunde inte sparas.'
            );

            header(
                'Location: /account/settings'
            );

            exit;
        }


        $this->flash->success(
            'Inställningarna har sparats.'
        );


        header(
            'Location: /account/settings'
        );

        exit;
    }
}
