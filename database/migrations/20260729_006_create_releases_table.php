<?php

declare(strict_types=1);

return [

    'up' => function (PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE releases (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                entry_id INT UNSIGNED NOT NULL,

                name VARCHAR(255) NOT NULL,
                version VARCHAR(100) DEFAULT NULL,
                notes TEXT DEFAULT NULL,

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                CONSTRAINT fk_releases_entry
                    FOREIGN KEY (entry_id)
                    REFERENCES entries(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,

                UNIQUE KEY uk_releases_entry_name_version
                    (entry_id, name, version),

                INDEX idx_releases_entry (entry_id),
                INDEX idx_releases_name (name),
                INDEX idx_releases_version (version)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci;
        ");

    },

    'down' => function (PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS releases;
        ");

    }

];

