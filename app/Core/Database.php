<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private PDO $pdo;

    public function __construct(Config $config)
    {
        $host    = $config->get('database.host');
        $port    = $config->get('database.port');
        $db      = $config->get('database.database');
        $user    = $config->get('database.username');
        $pass    = $config->get('database.password');
        $charset = $config->get('database.charset');

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $host,
            $port,
            $db,
            $charset
        );

        try {
            $this->pdo = new PDO(
                $dsn,
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            throw new RuntimeException(
                'Unable to connect to the database.',
                previous: $e
            );
        }
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}

