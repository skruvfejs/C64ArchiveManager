<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Entity\ReleaseFile;

final class ReleaseFileRepository extends Repository
{
    public function create(
        ReleaseFile $file
    ): int {

        $stmt = $this->prepare(
            '
            INSERT INTO release_files
            (
                release_id,
                filename,
                format,
                disk_name,
                disk_id,
                path,
                size,
                crc32,
                md5,
                sha1
            )
            VALUES
            (
                :release_id,
                :filename,
                :format,
                :disk_name,
                :disk_id,
                :path,
                :size,
                :crc32,
                :md5,
                :sha1
            )
            '
        );


        $stmt->execute([

            'release_id' =>
                $file->getReleaseId(),

            'filename' =>
                $file->getFilename(),

            'format' =>
                $file->getFormat(),

            'disk_name' =>
                $file->getDiskName(),

            'disk_id' =>
                $file->getDiskId(),

            'path' =>
                $file->getPath(),

            'size' =>
                $file->getSize(),

            'crc32' =>
                $file->getCrc32(),

            'md5' =>
                $file->getMd5(),

            'sha1' =>
                $file->getSha1()

        ]);


        return $this->lastInsertId();
    }


    public function findById(
        int $id
    ): ?ReleaseFile {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM release_files
            WHERE id = :id
            LIMIT 1
            '
        );


        $stmt->execute([

            'id' => $id

        ]);


        $row =
            $this->fetchOne($stmt);


        if ($row === null) {

            return null;
        }


        return $this->hydrate($row);
    }


    /**
     * @return ReleaseFile[]
     */
    public function findByRelease(
        int $releaseId
    ): array {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM release_files
            WHERE release_id = :release_id
            ORDER BY filename
            '
        );


        $stmt->execute([

            'release_id' =>
                $releaseId

        ]);


        return array_map(

            fn(array $row): ReleaseFile =>
                $this->hydrate($row),

            $this->fetchAll($stmt)

        );
    }


    public function delete(
        int $id
    ): bool {

        $stmt = $this->prepare(
            '
            DELETE FROM release_files
            WHERE id = :id
            '
        );


        return $stmt->execute([

            'id' => $id

        ]);
    }


    private function hydrate(
        array $row
    ): ReleaseFile {

        return (new ReleaseFile())

            ->setId(
                (int) $row['id']
            )

            ->setReleaseId(
                (int) $row['release_id']
            )

            ->setFilename(
                $row['filename']
            )

            ->setFormat(
                $row['format']
            )

            ->setDiskName(
                $row['disk_name'] ?? null
            )

            ->setDiskId(
                $row['disk_id'] ?? null
            )

            ->setPath(
                $row['path']
            )

            ->setSize(
                (int) $row['size']
            )

            ->setCrc32(
                $row['crc32']
            )

            ->setMd5(
                $row['md5']
            )

            ->setSha1(
                $row['sha1']
            );
    }
}

