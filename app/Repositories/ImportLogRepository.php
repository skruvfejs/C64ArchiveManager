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

        $stmt = $this->prepare(
            '
            INSERT INTO import_logs
            (
                filename,
                format,
                status,
                release_id,
                files_imported,
                message
            )
            VALUES
            (
                :filename,
                :format,
                :status,
                :release_id,
                :files_imported,
                :message
            )
            '
        );


        $stmt->execute([

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

        ]);


        return $this->lastInsertId();
    }


    public function markSuccess(
        int $id,
        ?int $releaseId = null,
        int $filesImported = 0
    ): bool {

        $stmt = $this->prepare(
            '
            UPDATE import_logs
            SET
                status = :status,
                release_id = :release_id,
                files_imported = :files_imported,
                finished_at = NOW()
            WHERE id = :id
            '
        );


        return $stmt->execute([

            'id' =>
                $id,

            'status' =>
                'SUCCESS',

            'release_id' =>
                $releaseId,

            'files_imported' =>
                $filesImported

        ]);
    }


    public function markFailed(
        int $id,
        string $message
    ): bool {

        $stmt = $this->prepare(
            '
            UPDATE import_logs
            SET
                status = :status,
                message = :message,
                finished_at = NOW()
            WHERE id = :id
            '
        );


        return $stmt->execute([

            'id' =>
                $id,

            'status' =>
                'FAILED',

            'message' =>
                $message

        ]);
    }


    public function findById(
        int $id
    ): ?ImportLog {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM import_logs
            WHERE id = :id
            LIMIT 1
            '
        );


        $stmt->execute([

            'id' =>
                $id

        ]);


        $row =
            $this->fetchOne($stmt);


        if ($row === null) {

            return null;
        }


        return $this->hydrate($row);
    }


    /**
     * @return ImportLog[]
     */
    public function findLatest(
        int $limit = 50
    ): array {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM import_logs
            ORDER BY started_at DESC
            LIMIT :limit
            '
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


    private function hydrate(
        array $row
    ): ImportLog {

        return (new ImportLog())

            ->setId(
                (int) $row['id']
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
            );
    }
}
