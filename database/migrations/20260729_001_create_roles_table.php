<?php

declare(strict_types=1);

return [

    'up' => function (\PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS roles
            (
                id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                name VARCHAR(50) NOT NULL,
                description VARCHAR(255) DEFAULT NULL,

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL
                    DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                UNIQUE KEY uk_roles_name (name)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
        ");

        $stmt = $pdo->prepare("
            INSERT IGNORE INTO roles
                (id, name, description)
            VALUES
                (1, 'Super Admin', 'Full system owner'),
                (2, 'Admin', 'System administrator'),
                (3, 'Power User', 'Advanced contributor'),
                (4, 'User', 'Standard contributor'),
                (5, 'ReadOnly', 'Read only access')
        ");

        $stmt->execute();
    },

    'down' => function (\PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS roles
        ");

    }

];

