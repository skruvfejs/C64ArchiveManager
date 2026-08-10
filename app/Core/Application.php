<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Request;
use App\Http\Middleware\MaintenanceMiddleware;
use App\Repositories\AuditLogRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\AuditLogService;
use App\Services\DateService;
use App\Services\LanguageService;
use App\Services\MaintenanceService;
use App\Services\RegistrationService;
use App\Services\RoleService;
use App\Services\SettingsService;
use App\Services\StorageService;
use App\Services\UserService;

final class Application
{
    private Container $container;

    private Router $router;


    public function __construct()
    {
        $this->container = new Container();


        /*
         * Config
         */

        $this->container->singleton(
            Config::class,
            fn () => new Config(
                dirname(__DIR__, 2) . '/config'
            )
        );


        /*
         * Timezone
         */

        date_default_timezone_set(
            $this->container
                ->get(Config::class)
                ->get('app.timezone', 'UTC')
        );


        /*
         * Core services
         */

        $this->container->singleton(
            Database::class,
            fn (Container $c) => new Database(
                $c->get(Config::class)
            )
        );


        /*
         * Database transaction
         */

        $this->container->singleton(
            DatabaseTransaction::class,
            fn (Container $c) => new DatabaseTransaction(
                $c->get(Database::class)->pdo()
            )
        );


        $this->container->singleton(
            Session::class,
            fn () => new Session()
        );


        $this->container->singleton(
            Auth::class,
            fn (Container $c) => new Auth(
                $c->get(Session::class)
            )
        );


        /*
         * Repositories
         */

        $this->container->singleton(
            RoleRepository::class,
            fn (Container $c) => new RoleRepository(
                $c->get(Database::class)
            )
        );


        $this->container->singleton(
            UserRepository::class,
            fn (Container $c) => new UserRepository(
                $c->get(Database::class)
            )
        );


        $this->container->singleton(
            AuditLogRepository::class,
            fn (Container $c) => new AuditLogRepository(
                $c->get(Database::class)
            )
        );


        $this->container->singleton(
            ReleaseFileRepository::class,
            fn (Container $c) => new ReleaseFileRepository(
                $c->get(Database::class)
            )
        );


        /*
         * Services
         */

        $this->container->singleton(
            RoleService::class,
            fn (Container $c) => new RoleService(
                $c->get(RoleRepository::class)
            )
        );


        $this->container->singleton(
            UserService::class,
            fn (Container $c) => new UserService(
                $c->get(UserRepository::class)
            )
        );


        $this->container->singleton(
            AuditLogService::class,
            fn (Container $c) => new AuditLogService(
                $c->get(AuditLogRepository::class),
                $c->get(Auth::class)
            )
        );


        $this->container->singleton(
            RegistrationService::class,
            fn (Container $c) => new RegistrationService(
                $c->get(UserService::class),
                $c->get(RoleService::class),
                $c->get(AuditLogService::class)
            )
        );


        $this->container->singleton(
            SettingsService::class,
            fn (Container $c) => new SettingsService(
                $c->get(Database::class)
            )
        );


        $this->container->singleton(
            DateService::class,
            fn (Container $c) => new DateService(
                $c->get(SettingsService::class)
            )
        );


        $this->container->singleton(
            StorageService::class,
            fn () => new StorageService()
        );


        $this->container->singleton(
            MaintenanceService::class,
            fn (Container $c) => new MaintenanceService(
                $c->get(StorageService::class),
                $c->get(ReleaseFileRepository::class)
            )
        );


        /*
         * Language
         */

        $this->container->singleton(
            LanguageService::class,
            fn (Container $c) => new LanguageService(
                $c->get(SettingsService::class),
                $c->get(Session::class),
                $c->get(Auth::class),
                $c->get(UserService::class)
            )
        );


        /*
         * View
         */

        $this->container->singleton(
            View::class,
            function (Container $c): View {
                $view = new View();


                $view->share(
                    'siteName',
                    $c->get(SettingsService::class)->get(
                        'site_name',
                        'C64 Archive Manager'
                    )
                );


                $view->share(
                    'language',
                    $c->get(LanguageService::class)
                );


                $view->share(
                    'dateService',
                    $c->get(DateService::class)
                );


                $theme = 'system';


                $auth =
                    $c->get(Auth::class);


                $userId =
                    $auth->id();


                if ($userId !== null) {

                    $user =
                        $c->get(UserService::class)
                            ->findById($userId);


                    if ($user !== null) {
                        $theme =
                            $user->getTheme();
                    }
                }


                $view->share(
                    'theme',
                    $theme
                );


                return $view;
            }
        );


        /*
         * Maintenance middleware
         */

        $this->container->singleton(
            MaintenanceMiddleware::class,
            fn (Container $c) =>
                new MaintenanceMiddleware(
                    $c->get(SettingsService::class),
                    $c->get(Authorization::class),
                    $c->get(View::class)
                )
        );


        $this->container->singleton(
            Request::class,
            fn () => new Request()
        );


        $this->router = new Router(
            $this->container
        );
    }



    public function run(): void
    {
        $routes =
            require dirname(__DIR__, 2)
            . '/routes/web.php';


        $routes(
            $this->router
        );


        $this->router->dispatch(
            $this->container->get(
                Request::class
            )
        );
    }



    public function container(): Container
    {
        return $this->container;
    }
}
