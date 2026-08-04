<?php

declare(strict_types=1);

use App\Core\Router;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\DiskController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\DiskInfoController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ImportLogController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\ReleaseController;


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
        '/logout',
        [
            LogoutController::class,
            'index'
        ]
    );


    $router->get(
        '/disk',
        [
            DiskController::class,
            'index'
        ]
    );


    $router->get(
        '/disk/directory',
        [
            DirectoryController::class,
            'index'
        ]
    );


    $router->get(
        '/disk/info',
        [
            DiskInfoController::class,
            'index'
        ]
    );


    $router->get(
        '/file',
        [
            FileController::class,
            'index'
        ]
    );


    $router->get(
        '/import',
        [
            ImportController::class,
            'index'
        ]
    );


    $router->post(
        '/import',
        [
            ImportController::class,
            'upload'
        ]
    );


    $router->post(
        '/import/force',
        [
            ImportController::class,
            'force'
        ]
    );


    $router->get(
        '/import/logs',
        [
            ImportLogController::class,
            'index'
        ]
    );


$router->get(
    '/entry',
    [
        EntryController::class,
        'index'
    ]
);

$router->get(
    '/release',
    [
        ReleaseController::class,
        'index'
    ]
);

};

