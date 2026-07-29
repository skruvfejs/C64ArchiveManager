<?php

declare(strict_types=1);

return [

    'up' => function (\PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS entries
            (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                entry_type_id TINYINT UNSIGNED NOT NULL,

                title VARCHAR(255) NOT NULL,
                sort_title VARCHAR(255) NULL,

                year SMALLINT UNSIGNED NULL,

                description TEXT NULL,

                status TINYINT UNSIGNED NOT NULL DEFAULT 1,

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

                updated_at TIMESTAMP NOT NULL
                    DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                CONSTRAINT fk_entries_entry_type
                    FOREIGN KEY (entry_type_id)
                    REFERENCES entry_types(id)
                    ON UPDATE CASCADE
                    ON DELETE RESTRICT,

                INDEX idx_entries_type (entry_type_id),
                INDEX idx_entries_title (title),
                INDEX idx_entries_sort_title (sort_title),
                INDEX idx_entries_year (year),
                INDEX idx_entries_status (status)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
        ");

    },

    'down' => function (\PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS entries
        ");

    }

];
