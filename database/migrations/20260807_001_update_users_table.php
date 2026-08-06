<?php

declare(strict_types=1);

return [

    'up' => function (\PDO $pdo): void {

        $pdo->exec("
            ALTER TABLE users

                DROP COLUMN active,

                ADD COLUMN deleted_at DATETIME NULL
                    AFTER last_login_at,

                ADD COLUMN deleted_by INT UNSIGNED NULL
                    AFTER deleted_at
        ");

        $pdo->exec("
            ALTER TABLE users

                ADD CONSTRAINT fk_users_deleted_by
                    FOREIGN KEY (deleted_by)
                    REFERENCES users(id)
                    ON UPDATE CASCADE
                    ON DELETE SET NULL
        ");
    },

    'down' => function (\PDO $pdo): void {

        $pdo->exec("
            ALTER TABLE users

                DROP FOREIGN KEY fk_users_deleted_by
        ");

        $pdo->exec("
            ALTER TABLE users

                DROP COLUMN deleted_by,

                DROP COLUMN deleted_at,

                ADD COLUMN active TINYINT(1)
                    NOT NULL
                    DEFAULT 1
                    AFTER last_name
        ");
    }

];

