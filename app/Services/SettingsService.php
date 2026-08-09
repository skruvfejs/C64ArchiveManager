<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use RuntimeException;

final class SettingsService
{
    public function __construct(
        private readonly Database $database
    ) {
    }

    private function pdo(): PDO
    {
        return $this->database->pdo();
    }

    public function get(
        string $key,
        ?string $default = null
    ): ?string {
        $statement = $this->pdo()->prepare("
            SELECT setting_value
            FROM settings
            WHERE setting_key = :key
            LIMIT 1
        ");

        $statement->execute([
            ':key' => $key,
        ]);

        $value = $statement->fetchColumn();

        if ($value === false) {
            return $default;
        }

        return (string) $value;
    }

    public function getAll(): array
    {
        $statement = $this->pdo()->query("
            SELECT
                setting_key,
                setting_value
            FROM settings
            ORDER BY setting_key
        ");

        $settings = [];

        foreach ($statement->fetchAll() as $row) {
            $settings[$row['setting_key']] =
                $row['setting_value'];
        }

        return $settings;
    }

    public function set(
        string $key,
        string $value
    ): void {
        $statement = $this->pdo()->prepare("
            INSERT INTO settings
                (setting_key, setting_value)
            VALUES
                (:key, :value)
            ON DUPLICATE KEY UPDATE
                setting_value = VALUES(setting_value)
        ");

        $statement->execute([
            ':key' => $key,
            ':value' => $value,
        ]);
    }

    public function update(array $settings): void
    {
        $allowed = [
            'site_name',
            'default_language',
            'date_format',
            'items_per_page',
            'maintenance_mode',
            'registration_enabled',
        ];

        $pdo = $this->pdo();

        $pdo->beginTransaction();

        try {

            foreach ($settings as $key => $value) {

                if (!in_array($key, $allowed, true)) {
                    continue;
                }

                $this->set(
                    $key,
                    (string) $value
                );
            }

            $pdo->commit();

        } catch (\Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw new RuntimeException(
                'Unable to save settings.',
                previous: $e
            );
        }
    }
}
