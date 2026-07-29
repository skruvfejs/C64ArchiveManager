<?php

declare(strict_types=1);

return [

    'up' => function (PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE release_files (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                release_id INT UNSIGNED NOT NULL,

                filename VARCHAR(255) NOT NULL,
                format VARCHAR(20) NOT NULL,
                path VARCHAR(1024) NOT NULL,

                size BIGINT UNSIGNED NOT NULL,

                crc32 CHAR(8) DEFAULT NULL,
                md5 CHAR(32) DEFAULT NULL,
                sha1 CHAR(40) DEFAULT NULL,

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                CONSTRAINT fk_release_files_release
                    FOREIGN KEY (release_id)
                    REFERENCES releases(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,

                UNIQUE KEY uk_release_file (release_id, filename),

                INDEX idx_release_files_release (release_id),
                INDEX idx_release_files_format (format),
                INDEX idx_release_files_crc32 (crc32),
                INDEX idx_release_files_md5 (md5),
                INDEX idx_release_files_sha1 (sha1)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci;
        ");

    },

    'down' => function (PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS release_files;
        ");

    }

];
