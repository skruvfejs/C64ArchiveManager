<?php

declare(strict_types=1);

return [

    'up' => function (PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE entry_tags (
                entry_id INT UNSIGNED NOT NULL,
                tag_id INT UNSIGNED NOT NULL,

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

                PRIMARY KEY (entry_id, tag_id),

                CONSTRAINT fk_entry_tags_entry
                    FOREIGN KEY (entry_id)
                    REFERENCES entries(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,

                CONSTRAINT fk_entry_tags_tag
                    FOREIGN KEY (tag_id)
                    REFERENCES tags(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,

                INDEX idx_entry_tags_tag (tag_id)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci;
        ");

    },

    'down' => function (PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS entry_tags;
        ");

    }

];

