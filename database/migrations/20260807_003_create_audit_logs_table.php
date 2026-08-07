<?php

declare(strict_types=1);

return [

    'up' => function (\PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE audit_logs
            (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                user_id INT UNSIGNED NULL,

                action VARCHAR(50) NOT NULL,

                target_type VARCHAR(100) NOT NULL,

                target_id INT UNSIGNED NULL,

                description TEXT NOT NULL,

                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                INDEX idx_audit_user_id (user_id),

                INDEX idx_audit_target (
                    target_type,
                    target_id
                ),

                INDEX idx_audit_created (
                    created_at
                ),

                CONSTRAINT fk_audit_user
                    FOREIGN KEY (user_id)
                    REFERENCES users(id)
                    ON DELETE SET NULL
            )
        ");
    },


    'down' => function (\PDO $pdo): void {

        $pdo->exec("
            DROP TABLE audit_logs
        ");
    }

];
