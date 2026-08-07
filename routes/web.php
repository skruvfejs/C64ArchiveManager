<?php

declare(strict_types=1);

use App\Core\Router;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PasswordController;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\UserEditController;
use App\Http\Controllers\UserDeleteController;
use App\Http\Controllers\UserCreateController;
use App\Http\Controllers\DeletedUsersController;
use App\Http\Controllers\AuditLogController;

use App\Http\Controllers\DiskController;
use App\Http\Controllers\DisksController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\DiskInfoController;

use App\Http\Controllers\ImportController;
use App\Http\Controllers\ImportLogController;

use App\Http\Controllers\EntryController;
use App\Http\Controllers\ReleaseController;

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\ManageUsersMiddleware;
use App\Http\Middleware\ImportMiddleware;


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
        '/register',
        [
            RegisterController::class,
            'index'
        ]
    );


    $router->post(
        '/register',
        [
            RegisterController::class,
            'register'
        ]
    );



    $router->get(
        '/logout',
        [
            LogoutController::class,
            'index'
        ],
        [
            AuthMiddleware::class,
        ]
    );



    /*
     * Account
     */


    $router->get(
        '/account/password',
        [
            PasswordController::class,
            'index'
        ],
        [
            AuthMiddleware::class,
        ]
    );


    $router->post(
        '/account/password',
        [
            PasswordController::class,
            'update'
        ],
        [
            AuthMiddleware::class,
        ]
    );



    /*
     * User administration
     */


    $router->get(
        '/users',
        [
            UsersController::class,
            'index'
        ],
        [
            ManageUsersMiddleware::class,
        ]
    );


    $router->get(
        '/users/create',
        [
            UserCreateController::class,
            'index'
        ],
        [
            ManageUsersMiddleware::class,
        ]
    );


    $router->post(
        '/users/create',
        [
            UserCreateController::class,
            'create'
        ],
        [
            ManageUsersMiddleware::class,
        ]
    );



    $router->get(
        '/users/edit',
        [
            UserEditController::class,
            'index'
        ],
        [
            ManageUsersMiddleware::class,
        ]
    );


    $router->post(
        '/users/edit',
        [
            UserEditController::class,
            'update'
        ],
        [
            ManageUsersMiddleware::class,
        ]
    );



    $router->post(
        '/users/delete',
        [
            UserDeleteController::class,
            'delete'
        ],
        [
            ManageUsersMiddleware::class,
        ]
    );



    /*
     * Deleted users
     */


    $router->get(
        '/users/deleted',
        [
            DeletedUsersController::class,
            'index'
        ],
        [
            ManageUsersMiddleware::class,
        ]
    );


    $router->post(
        '/users/restore',
        [
            DeletedUsersController::class,
            'restore'
        ],
        [
            ManageUsersMiddleware::class,
        ]
    );



    /*
     * Audit log
     */


    $router->get(
        '/users/logs',
        [
            AuditLogController::class,
            'index'
        ],
        [
            ManageUsersMiddleware::class,
        ]
    );



    /*
     * Disk / archive
     */


    $router->get(
        '/disk',
        [
            DisksController::class,
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
        '/file/download',
        [
            FileController::class,
            'download'
        ]
    );



    /*
     * Import
     */


    $router->get(
        '/import',
        [
            ImportController::class,
            'index'
        ],
        [
            AuthMiddleware::class,
        ]
    );


    $router->post(
        '/import',
        [
            ImportController::class,
            'upload'
        ],
        [
            AuthMiddleware::class,
            ImportMiddleware::class,
        ]
    );


    $router->post(
        '/import/force',
        [
            ImportController::class,
            'force'
        ],
        [
            AuthMiddleware::class,
            ImportMiddleware::class,
        ]
    );


    $router->get(
        '/import/logs',
        [
            ImportLogController::class,
            'index'
        ],
        [
            AuthMiddleware::class,
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
