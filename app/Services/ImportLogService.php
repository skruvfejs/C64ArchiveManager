<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\ImportLog;
use App\Repositories\ImportLogRepository;

final class ImportLogService
{
    public function __construct(
        private ImportLogRepository $repository
    ) {
    }


    /**
     * Startar en importlogg.
     */
    public function start(
        string $filename,
        string $format
    ): int {

        $log = new ImportLog();

        $log
            ->setFilename($filename)
            ->setFormat($format)
            ->setStatus('RUNNING');


        return $this->repository
                    ->create($log);
    }


    /**
     * Markerar import som lyckad.
     */
    public function success(
        int $id,
        ?int $releaseId = null,
        int $filesImported = 0
    ): bool {

        return $this->repository
                    ->markSuccess(
                        $id,
                        $releaseId,
                        $filesImported
                    );
    }


    /**
     * Markerar import som misslyckad.
     */
    public function failed(
        int $id,
        string $message
    ): bool {

        return $this->repository
                    ->markFailed(
                        $id,
                        $message
                    );
    }


    /**
     * Hämtar senaste importer.
     *
     * @return ImportLog[]
     */
    public function latest(
        int $limit = 50
    ): array {

        return $this->repository
                    ->findLatest($limit);
    }


    /**
     * Hämtar en specifik import.
     */
    public function find(
        int $id
    ): ?ImportLog {

        return $this->repository
                    ->findById($id);
    }
}
