<?php

declare(strict_types=1);

return [

    'up' => function (\PDO $pdo): void {

        $pdo->exec("
            ALTER TABLE import_logs
            ADD COLUMN user_id INT UNSIGNED NULL
            AFTER id
        ");


        $pdo->exec("
            ALTER TABLE import_logs
            ADD CONSTRAINT fk_import_logs_user
            FOREIGN KEY (user_id)
            REFERENCES users(id)
            ON DELETE SET NULL
        ");

    },


    'down' => function (\PDO $pdo): void {

        $pdo->exec("
            ALTER TABLE import_logs
            DROP FOREIGN KEY fk_import_logs_user
        ");


        $pdo->exec("
            ALTER TABLE import_logs
            DROP COLUMN user_id
        ");

    }

];
