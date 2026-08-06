<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Request;
use App\Repositories\UserRepository;
use App\Services\RegistrationService;
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


        $this->container->singleton(
            UserRepository::class,
            fn (Container $c) => new UserRepository(
                $c->get(Database::class)
            )
        );


        $this->container->singleton(
            UserService::class,
            fn (Container $c) => new UserService(
                $c->get(UserRepository::class)
            )
        );


        $this->container->singleton(
            RegistrationService::class,
            fn (Container $c) => new RegistrationService(
                $c->get(UserService::class)
            )
        );
        $this->container->singleton(
            View::class,
            fn () => new View()
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
            require dirname(__DIR__, 2) . '/routes/web.php';


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

