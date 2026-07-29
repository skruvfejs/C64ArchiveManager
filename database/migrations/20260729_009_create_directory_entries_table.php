<?php

declare(strict_types=1);

return [

    'up' => function (PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE directory_entries (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                release_file_id INT UNSIGNED NOT NULL,

                filename VARCHAR(255) NOT NULL,
                filetype CHAR(3) NOT NULL,

                blocks SMALLINT UNSIGNED NOT NULL,

                locked BOOLEAN NOT NULL DEFAULT FALSE,
                closed BOOLEAN NOT NULL DEFAULT TRUE,

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                CONSTRAINT fk_directory_entries_release_file
                    FOREIGN KEY (release_file_id)
                    REFERENCES release_files(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,

                INDEX idx_directory_entries_release_file (release_file_id),
                INDEX idx_directory_entries_filename (filename),
                INDEX idx_directory_entries_filetype (filetype),
                INDEX idx_directory_entries_blocks (blocks),
                INDEX idx_directory_entries_locked (locked),
                INDEX idx_directory_entries_closed (closed)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci;
        ");

    },

    'down' => function (PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS directory_entries;
        ");

    }

];

