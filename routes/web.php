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
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\SystemController;

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\ManageUsersMiddleware;
use App\Http\Middleware\ImportMiddleware;
use App\Http\Middleware\ViewLogsMiddleware;
use App\Http\Middleware\ManageSystemMiddleware;


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
 * Administration
 */

$router->get(
    '/administration',
    [
        AdministrationController::class,
        'index'
    ],
    [
        ManageUsersMiddleware::class,
    ]
);


/*
 * System administration
 */

$router->get(
    '/administration/system/database',
    [
        SystemController::class,
        'database'
    ],
    [
        ManageSystemMiddleware::class,
    ]
);


$router->post(
    '/administration/system/backup/create',
    [
        SystemController::class,
        'createBackup'
    ],
    [
        ManageSystemMiddleware::class,
    ]
);


$router->post(
    '/administration/system/export',
    [
        SystemController::class,
        'export'
    ],
    [
        ManageSystemMiddleware::class,
    ]
);


$router->post(
    '/administration/system/import',
    [
        SystemController::class,
        'import'
    ],
    [
        ManageSystemMiddleware::class,
    ]
);


$router->get(
    '/administration/system/backup/download',
    [
        SystemController::class,
        'downloadBackup'
    ],
    [
        ManageSystemMiddleware::class,
    ]
);


$router->post(
    '/administration/system/backup/delete',
    [
        SystemController::class,
        'deleteBackup'
    ],
    [
        ManageSystemMiddleware::class,
    ]
);


$router->post(
    '/administration/system/backup/restore',
    [
        SystemController::class,
        'restoreBackup'
    ],
    [
        ManageSystemMiddleware::class,
    ]
);



$router->get(
    '/administration/system/settings',
    [
        SystemController::class,
        'settings'
    ],
    [
        ManageSystemMiddleware::class,
    ]
);


$router->post(
    '/administration/system/settings',
    [
        SystemController::class,
        'saveSettings'
    ],
    [
        ManageSystemMiddleware::class,
    ]
);


$router->get(
    '/administration/system/maintenance',
    [
        SystemController::class,
        'maintenance'
    ],
    [
        ManageSystemMiddleware::class,
    ]
);


$router->get(
    '/administration/system/api',
    [
        SystemController::class,
        'api'
    ],
    [
        ManageSystemMiddleware::class,
    ]
);


$router->get(
    '/administration/system/information',
    [
        SystemController::class,
        'information'
    ],
    [
        ManageSystemMiddleware::class,
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
        ],
        [
            AuthMiddleware::class,
        ]
    );


    $router->get(
        '/disk/directory',
        [
            DirectoryController::class,
            'index'
        ],
        [
            AuthMiddleware::class,
        ]
    );


    $router->get(
        '/disk/info',
        [
            DiskInfoController::class,
            'index'
        ],
        [
            AuthMiddleware::class,
        ]
    );


    $router->get(
        '/file',
        [
            FileController::class,
            'index'
        ],
        [
            AuthMiddleware::class,
        ]
    );


    $router->get(
        '/file/download',
        [
            FileController::class,
            'download'
        ],
        [
            AuthMiddleware::class,
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
            ImportMiddleware::class,
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
            ViewLogsMiddleware::class,
        ]
    );


    $router->get(
        '/entry',
        [
            EntryController::class,
            'index'
        ],
        [
            AuthMiddleware::class,
        ]
    );


    $router->get(
        '/release',
        [
            ReleaseController::class,
            'index'
        ],
        [
            AuthMiddleware::class,
        ]
    );
};
