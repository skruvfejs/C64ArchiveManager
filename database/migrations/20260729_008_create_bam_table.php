<?php

declare(strict_types=1);

return [

    'up' => function (PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE bam (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                release_file_id INT UNSIGNED NOT NULL,

                disk_name VARCHAR(255) NOT NULL,
                disk_id CHAR(2) DEFAULT NULL,
                dos_type VARCHAR(16) DEFAULT NULL,

                blocks_free SMALLINT UNSIGNED NOT NULL,
                blocks_used SMALLINT UNSIGNED NOT NULL,

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                CONSTRAINT fk_bam_release_file
                    FOREIGN KEY (release_file_id)
                    REFERENCES release_files(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,

                UNIQUE KEY uk_bam_release_file (release_file_id),

                INDEX idx_bam_disk_name (disk_name),
                INDEX idx_bam_disk_id (disk_id)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci;
        ");

    },

    'down' => function (PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS bam;
        ");

    }

];

