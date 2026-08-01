<?php

declare(strict_types=1);

use App\Core\Router;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DiskController;


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


    $router->get(
        '/disk',
        [
            DiskController::class,
            'index'
        ]
    );

};

