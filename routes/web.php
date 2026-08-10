<?php

declare(strict_types=1);

use App\Core\Router;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\LanguageController;

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
use App\Http\Controllers\EntryTagController;
use App\Http\Controllers\ReleaseController;
use App\Http\Controllers\ReleaseTagController;
use App\Http\Controllers\DiskTagController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SystemController;

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\ManageUsersMiddleware;
use App\Http\Middleware\ImportMiddleware;
use App\Http\Middleware\ViewLogsMiddleware;
use App\Http\Middleware\ManageSystemMiddleware;
use App\Http\Middleware\ManageTagsMiddleware;
use App\Http\Middleware\EditMiddleware;
use App\Http\Middleware\MaintenanceMiddleware;


return function (Router $router): void {


    $router->get(
        '/',
        [
            HomeController::class,
            'index'
        ],
        [
            MaintenanceMiddleware::class,
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
        ],
        [
            MaintenanceMiddleware::class,
        ]
    );


    $router->post(
        '/register',
        [
            RegisterController::class,
            'register'
        ],
        [
            MaintenanceMiddleware::class,
        ]
    );


    $router->get(
        '/language/en',
        [
            LanguageController::class,
            'english'
        ]
    );


    $router->get(
        '/language/sv',
        [
            LanguageController::class,
            'swedish'
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
        ]
    );


    $router->get(
        '/account/settings',
        [
            UserSettingsController::class,
            'index'
        ],
        [
            AuthMiddleware::class,
            MaintenanceMiddleware::class,
        ]
    );


    $router->post(
        '/account/settings',
        [
            UserSettingsController::class,
            'update'
        ],
        [
            AuthMiddleware::class,
            MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
    ]
);


/*
 * Tag administration
 */

$router->get(
    '/administration/tags',
    [
        TagController::class,
        'index'
    ],
    [
        ManageTagsMiddleware::class,
        MaintenanceMiddleware::class,
    ]
);


/*
 * System administration
 */

$router->get(
    '/administration/tags/edit',
    [
        TagController::class,
        'editForm'
    ],
    [
        ManageTagsMiddleware::class,
        MaintenanceMiddleware::class,
    ]
);


$router->post(
    '/administration/tags/edit',
    [
        TagController::class,
        'update'
    ],
    [
        ManageTagsMiddleware::class,
        MaintenanceMiddleware::class,
    ]
);


$router->post(
    '/administration/tags/delete',
    [
        TagController::class,
        'delete'
    ],
    [
        ManageTagsMiddleware::class,
        MaintenanceMiddleware::class,
    ]
);


$router->get(
    '/administration/tags/create',
    [
        TagController::class,
        'createForm'
    ],
    [
        ManageTagsMiddleware::class,
        MaintenanceMiddleware::class,
    ]
);


$router->post(
    '/administration/tags/create',
    [
        TagController::class,
        'create'
    ],
    [
        ManageTagsMiddleware::class,
        MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
    ]
);

$router->post(
    '/administration/system/maintenance/delete',
    [
        SystemController::class,
        'deleteUnregisteredFile'
    ],
    [
        ManageSystemMiddleware::class,
        MaintenanceMiddleware::class,
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
        MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
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
            MaintenanceMiddleware::class,
        ]
    );



    $router->get(
        '/disk/comment/edit',
        [
            DiskController::class,
            'editComment'
        ],
        [
            EditMiddleware::class,
            MaintenanceMiddleware::class,
        ]
    );


    $router->post(
        '/disk/comment/delete',
        [
            DiskController::class,
            'deleteComment'
        ],
        [
            EditMiddleware::class,
            MaintenanceMiddleware::class,
        ]
    );


    $router->post(
        '/disk/comment/edit',
        [
            DiskController::class,
            'updateComment'
        ],
        [
            EditMiddleware::class,
            MaintenanceMiddleware::class,
        ]
    );


    $router->post(
        '/disk/tags/add',
        [
            DiskTagController::class,
            'add'
        ],
        [
            EditMiddleware::class,
            MaintenanceMiddleware::class,
        ]
    );

    $router->post(
        '/disk/tags/remove',
        [
            DiskTagController::class,
            'remove'
        ],
        [
            EditMiddleware::class,
            MaintenanceMiddleware::class,
        ]
    );

    $router->post(
        '/release/tags/add',
        [
            ReleaseTagController::class,
            'add'
        ],
        [
            EditMiddleware::class,
            MaintenanceMiddleware::class,
        ]
    );


    $router->post(
        '/release/tags/remove',
        [
            ReleaseTagController::class,
            'remove'
        ],
        [
            EditMiddleware::class,
            MaintenanceMiddleware::class,
        ]
    );


    $router->post(
        '/entry/tags/add',
        [
            EntryTagController::class,
            'add'
        ],
        [
            ManageTagsMiddleware::class,
            MaintenanceMiddleware::class,
        ]
    );


    $router->post(
        '/entry/tags/remove',
        [
            EntryTagController::class,
            'remove'
        ],
        [
            ManageTagsMiddleware::class,
            MaintenanceMiddleware::class,
        ]
    );
};
