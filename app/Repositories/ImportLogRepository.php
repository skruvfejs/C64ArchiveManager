<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Entity\ImportLog;

final class ImportLogRepository extends Repository
{
    public function create(
        ImportLog $log
    ): int {

        $sql = "
            INSERT INTO import_logs
            (
                user_id,
                filename,
                format,
                status,
                release_id,
                files_imported,
                message
            )
            VALUES
            (
                :user_id,
                :filename,
                :format,
                :status,
                :release_id,
                :files_imported,
                :message
            )
        ";


        $stmt =
            $this->prepare($sql);


        $stmt->execute(
            [
                'user_id' =>
                    $log->getUserId(),

                'filename' =>
                    $log->getFilename(),

                'format' =>
                    $log->getFormat(),

                'status' =>
                    $log->getStatus(),

                'release_id' =>
                    $log->getReleaseId(),

                'files_imported' =>
                    $log->getFilesImported(),

                'message' =>
                    $log->getMessage()
            ]
        );


        return $this->lastInsertId();
    }



    public function markSuccess(
        int $id,
        ?int $releaseId,
        int $filesImported
    ): bool {

        $stmt =
            $this->prepare(
                "
                UPDATE import_logs
                SET
                    status = 'SUCCESS',
                    release_id = :release_id,
                    files_imported = :files_imported,
                    finished_at = NOW()
                WHERE id = :id
                "
            );


        return $stmt->execute(
            [
                'id' =>
                    $id,

                'release_id' =>
                    $releaseId,

                'files_imported' =>
                    $filesImported
            ]
        );
    }



    public function markFailed(
        int $id,
        string $message
    ): bool {

        $stmt =
            $this->prepare(
                "
                UPDATE import_logs
                SET
                    status = 'FAILED',
                    message = :message,
                    finished_at = NOW()
                WHERE id = :id
                "
            );


        return $stmt->execute(
            [
                'id' =>
                    $id,

                'message' =>
                    $message
            ]
        );
    }



    /**
     * @return ImportLog[]
     */
    public function findLatest(
        int $limit = 50
    ): array {

        $stmt =
            $this->prepare(
                "
                SELECT
                    import_logs.*,
                    users.username AS username

                FROM import_logs

                LEFT JOIN users
                    ON users.id = import_logs.user_id

                ORDER BY import_logs.id DESC

                LIMIT :limit
                "
            );


        $stmt->bindValue(
            ':limit',
            $limit,
            \PDO::PARAM_INT
        );


        $stmt->execute();


        return array_map(
            fn(array $row): ImportLog =>
                $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }



    public function findById(
        int $id
    ): ?ImportLog {

        $stmt =
            $this->prepare(
                "
                SELECT
                    import_logs.*,
                    users.username AS username

                FROM import_logs

                LEFT JOIN users
                    ON users.id = import_logs.user_id

                WHERE import_logs.id = :id
                "
            );


        $stmt->execute(
            [
                'id' =>
                    $id
            ]
        );


        $row =
            $this->fetchOne($stmt);


        if ($row === null) {
            return null;
        }


        return $this->hydrate($row);
    }



    private function hydrate(
        array $row
    ): ImportLog {

        return (new ImportLog())

            ->setId(
                (int) $row['id']
            )

            ->setUserId(
                isset($row['user_id'])
                    ? (int) $row['user_id']
                    : null
            )

            ->setUsername(
                $row['username'] ?? null
            )

            ->setFilename(
                $row['filename']
            )

            ->setFormat(
                $row['format']
            )

            ->setStatus(
                $row['status']
            )

            ->setReleaseId(
                $row['release_id'] !== null
                    ? (int) $row['release_id']
                    : null
            )

            ->setFilesImported(
                (int) $row['files_imported']
            )

            ->setMessage(
                $row['message']
            )

            ->setStartedAt(
                $row['started_at']
            )

            ->setFinishedAt(
                $row['finished_at']
            );
    }
}
