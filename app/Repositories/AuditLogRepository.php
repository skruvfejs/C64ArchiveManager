<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\AuditLog;
use App\Core\Database;
use PDO;

final class AuditLogRepository
{
    private PDO $pdo;


    public function __construct(
        Database $database
    ) {
        $this->pdo =
            $database->pdo();
    }



    public function save(
        AuditLog $log
    ): void {

        $stmt = $this->pdo->prepare(
            "
            INSERT INTO audit_logs
            (
                user_id,
                action,
                target_type,
                target_id,
                description
            )
            VALUES
            (
                :user_id,
                :action,
                :target_type,
                :target_id,
                :description
            )
            "
        );


        $stmt->execute(
            [
                'user_id' =>
                    $log->userId(),

                'action' =>
                    $log->action(),

                'target_type' =>
                    $log->targetType(),

                'target_id' =>
                    $log->targetId(),

                'description' =>
                    $log->description(),
            ]
        );
    }



    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            "
            SELECT *
            FROM audit_logs
            ORDER BY created_at DESC
            "
        );


        $logs = [];


        foreach (
            $stmt->fetchAll(PDO::FETCH_ASSOC)
            as $row
        ) {

            $logs[] =
                $this->map($row);
        }


        return $logs;
    }



    public function findAllWithUsers(
        int $limit = 50,
        int $offset = 0
    ): array
    {
        $stmt = $this->pdo->prepare(
            "
            SELECT
                audit_logs.*,
                users.username
            FROM audit_logs
            LEFT JOIN users
                ON users.id = audit_logs.user_id
            ORDER BY audit_logs.created_at DESC

            LIMIT :limit
            OFFSET :offset
            "
        );


        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );


        $stmt->bindValue(
            ':offset',
            $offset,
            PDO::PARAM_INT
        );


        $stmt->execute();


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }



    public function countAll(): int
    {
        $stmt =
            $this->pdo->query(
                "
                    SELECT COUNT(*)
                    FROM audit_logs
                "
            );


        return (int) $stmt->fetchColumn();
    }



    public function findByUserId(
        int $userId
    ): array {

        $stmt = $this->pdo->prepare(
            "
            SELECT *
            FROM audit_logs
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            "
        );


        $stmt->execute(
            [
                'user_id' => $userId
            ]
        );


        $logs = [];


        foreach (
            $stmt->fetchAll(PDO::FETCH_ASSOC)
            as $row
        ) {

            $logs[] =
                $this->map($row);
        }


        return $logs;
    }



    private function map(
        array $row
    ): AuditLog {

        return new AuditLog(

            (int) $row['id'],

            $row['user_id'] !== null
                ? (int) $row['user_id']
                : null,

            $row['action'],

            $row['target_type'],

            $row['target_id'] !== null
                ? (int) $row['target_id']
                : null,

            $row['description'],

            $row['created_at']

        );
    }
}
