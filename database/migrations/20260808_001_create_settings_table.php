<?php

declare(strict_types=1);

return [

    'up' => function (\PDO $pdo): void {

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS settings
            (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                setting_key VARCHAR(100) NOT NULL,

                setting_value TEXT DEFAULT NULL,

                created_at TIMESTAMP NOT NULL
                    DEFAULT CURRENT_TIMESTAMP,

                updated_at TIMESTAMP NOT NULL
                    DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                UNIQUE KEY uk_settings_key (setting_key)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
        ");

        $stmt = $pdo->prepare("
            INSERT IGNORE INTO settings
                (setting_key, setting_value)
            VALUES
                (:key, :value)
        ");

        $settings = [
            'site_name' => 'C64 Archive Manager',
            'default_language' => 'sv',
            'date_format' => 'Y-m-d',
            'items_per_page' => '25',
            'maintenance_mode' => '0',
            'registration_enabled' => '0',
        ];

        foreach ($settings as $key => $value) {
            $stmt->execute([
                ':key' => $key,
                ':value' => $value,
            ]);
        }
    },

    'down' => function (\PDO $pdo): void {

        $pdo->exec("
            DROP TABLE IF EXISTS settings
        ");
    },

];
