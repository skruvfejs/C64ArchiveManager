<?php

declare(strict_types=1);

return [

    'up' => function (\PDO $pdo): void {

        $pdo->exec("
            ALTER TABLE users

            ADD COLUMN theme VARCHAR(20) NOT NULL DEFAULT 'light'
                AFTER active,

            ADD COLUMN language VARCHAR(10) NOT NULL DEFAULT 'sv'
                AFTER theme,

            ADD COLUMN last_login_at DATETIME NULL
                AFTER language
        ");

    },

    'down' => function (\PDO $pdo): void {

        $pdo->exec("
            ALTER TABLE users

            DROP COLUMN last_login_at,

            DROP COLUMN language,

            DROP COLUMN theme
        ");

    }

];

