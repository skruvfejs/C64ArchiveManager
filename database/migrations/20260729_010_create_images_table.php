<?php

declare(strict_types=1);

return [

    'up' => function (PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE images (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                entry_id INT UNSIGNED NOT NULL,

                type VARCHAR(50) NOT NULL,

                filename VARCHAR(255) NOT NULL,
                path VARCHAR(1024) NOT NULL,

                width SMALLINT UNSIGNED DEFAULT NULL,
                height SMALLINT UNSIGNED DEFAULT NULL,

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                CONSTRAINT fk_images_entry
                    FOREIGN KEY (entry_id)
                    REFERENCES entries(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,

                INDEX idx_images_entry (entry_id),
                INDEX idx_images_type (type)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci;
        ");

    },

    'down' => function (PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS images;
        ");

    }

];

