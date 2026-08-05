<?php

declare(strict_types=1);

return [

    'up' => function (PDO $pdo): void {

        $pdo->exec("
            ALTER TABLE directory_entries

            ADD COLUMN file_offset BIGINT UNSIGNED NULL
                AFTER blocks,

            ADD COLUMN file_size INT UNSIGNED NULL
                AFTER file_offset
        ");

    },


    'down' => function (PDO $pdo): void {

        $pdo->exec("
            ALTER TABLE directory_entries

            DROP COLUMN file_size,

            DROP COLUMN file_offset
        ");

    }

];
