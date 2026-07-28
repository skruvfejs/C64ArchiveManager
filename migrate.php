<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Core\Config;
use App\Core\Database;
use App\Core\Migration;

$config = new Config(__DIR__ . '/config');

$database = new Database($config);

$migration = new Migration($database);

$command = $argv[1] ?? 'migrate';

switch ($command) {

    case 'migrate':
        $migration->migrate(__DIR__ . '/database/migrations');
        break;

    case 'status':
        $migration->status(__DIR__ . '/database/migrations');
        break;

    case 'rollback':
        $migration->rollback(__DIR__ . '/database/migrations');
        break;

    case 'fresh':

        echo "WARNING!\n";
        echo "This will rollback ALL migrations and recreate the database.\n";
        echo "Continue? (y/N): ";

        $answer = trim(fgets(STDIN));

        if (strtolower($answer) === 'y') {

            $migration->fresh(__DIR__ . '/database/migrations');

        } else {

            echo "Cancelled.\n";
        }

        break;

    case 'help':
    default:

        echo PHP_EOL;
        echo "C64 Archive Manager Migration Tool" . PHP_EOL;
        echo "=================================" . PHP_EOL;
        echo PHP_EOL;
        echo "Usage:" . PHP_EOL;
        echo "  php migrate.php" . PHP_EOL;
        echo "      Run all pending migrations." . PHP_EOL;
        echo PHP_EOL;
        echo "  php migrate.php status" . PHP_EOL;
        echo "      Show migration status." . PHP_EOL;
        echo PHP_EOL;
        echo "  php migrate.php rollback" . PHP_EOL;
        echo "      Roll back the latest migration." . PHP_EOL;
        echo PHP_EOL;
        echo "  php migrate.php fresh" . PHP_EOL;
        echo "      Roll back all migrations and run them again." . PHP_EOL;
        echo PHP_EOL;
        break;
}

