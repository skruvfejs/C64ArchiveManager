<?php

declare(strict_types=1);

return [

    'up' => function (PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE release_tags (
                release_id INT UNSIGNED NOT NULL,
                tag_id INT UNSIGNED NOT NULL,

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

                PRIMARY KEY (release_id, tag_id),

                CONSTRAINT fk_release_tags_release
                    FOREIGN KEY (release_id)
                    REFERENCES releases(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,

                CONSTRAINT fk_release_tags_tag
                    FOREIGN KEY (tag_id)
                    REFERENCES tags(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,

                INDEX idx_release_tags_tag (tag_id)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci;
        ");

    },

    'down' => function (PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS release_tags;
        ");

    }

];
