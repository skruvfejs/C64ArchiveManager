<?php

declare(strict_types=1);

use App\Core\Router;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;

return function (Router $router): void {

    $router->get(
        '/',
        [
            HomeController::class,
            'index'
        ]
    );

    $router->get(
        '/login',
        [
            LoginController::class,
            'index'
        ]
    );

    $router->post(
        '/login',
        [
            LoginController::class,
            'login'
        ]
    );

};

