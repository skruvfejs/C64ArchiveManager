<?php

declare(strict_types=1);

return [

    'up' => function (PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE import_logs (

                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                filename VARCHAR(255) NOT NULL,

                format VARCHAR(20) NOT NULL,

                status VARCHAR(20) NOT NULL,

                release_id INT UNSIGNED DEFAULT NULL,

                files_imported INT UNSIGNED NOT NULL DEFAULT 0,

                message TEXT DEFAULT NULL,

                started_at TIMESTAMP NOT NULL
                    DEFAULT CURRENT_TIMESTAMP,

                finished_at TIMESTAMP NULL DEFAULT NULL,


                CONSTRAINT fk_import_logs_release
                    FOREIGN KEY (release_id)
                    REFERENCES releases(id)
                    ON DELETE SET NULL
                    ON UPDATE CASCADE,


                INDEX idx_import_logs_status (status),

                INDEX idx_import_logs_release (release_id),

                INDEX idx_import_logs_started (started_at)

            )

            ENGINE=InnoDB

            DEFAULT CHARSET=utf8mb4

            COLLATE=utf8mb4_unicode_ci;
        ");

    },


    'down' => function (PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS import_logs;
        ");

    }

];

