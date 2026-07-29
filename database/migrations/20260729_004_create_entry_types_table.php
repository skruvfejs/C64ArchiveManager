<?php

declare(strict_types=1);

return [

    'up' => function (\PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS entry_types
            (
                id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                name VARCHAR(50) NOT NULL,
                slug VARCHAR(50) NOT NULL,

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

                updated_at TIMESTAMP NOT NULL
                    DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                UNIQUE KEY uk_entry_types_name (name),
                UNIQUE KEY uk_entry_types_slug (slug)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
        ");

        $pdo->exec("
            INSERT IGNORE INTO entry_types (name, slug)
            VALUES
                ('Game', 'game'),
                ('Demo', 'demo'),
                ('Intro', 'intro'),
                ('Cracktro', 'cracktro'),
                ('Utility', 'utility'),
                ('Application', 'application'),
                ('Programming', 'programming'),
                ('Music', 'music'),
                ('Graphics', 'graphics'),
                ('Slideshow', 'slideshow'),
                ('Magazine', 'magazine'),
                ('Operating System', 'operating_system'),
                ('Cartridge', 'cartridge'),
                ('SID', 'sid'),
                ('ROM', 'rom'),
                ('Document', 'document'),
                ('Collection', 'collection'),
                ('Other', 'other')
        ");

    },

    'down' => function (\PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS entry_types
        ");

    }

];

