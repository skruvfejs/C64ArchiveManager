<?php

declare(strict_types=1);

return [

    'up' => function (\PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users
            (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                role_id TINYINT UNSIGNED NOT NULL,

                username VARCHAR(50) NOT NULL,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,

                first_name VARCHAR(100) NULL,
                last_name VARCHAR(100) NULL,

                active TINYINT(1) NOT NULL DEFAULT 1,

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

                updated_at TIMESTAMP NOT NULL
                    DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                CONSTRAINT fk_users_role
                    FOREIGN KEY (role_id)
                    REFERENCES roles(id)
                    ON UPDATE CASCADE
                    ON DELETE RESTRICT,

                UNIQUE KEY uk_users_username (username),
                UNIQUE KEY uk_users_email (email),

                INDEX idx_users_role (role_id),
                INDEX idx_users_active (active)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
        ");

    },

    'down' => function (\PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS users
        ");

    }

];

