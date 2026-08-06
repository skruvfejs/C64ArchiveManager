<?php

declare(strict_types=1);

return [

    'up' => function (\PDO $pdo): void {

        $pdo->exec("
            INSERT INTO roles
            (
                id,
                name
            )
            VALUES
            (
                0,
                'Pending'
            )
        ");
    },

    'down' => function (\PDO $pdo): void {

        $pdo->exec("
            DELETE FROM roles
            WHERE id = 0
        ");
    }

];

