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



    /**
     * Hämta alla diskfiler
     *
     * @return ReleaseFile[]
     */
    public function findAllDisks(): array
    {
        $stmt = $this->prepare(
            '
            SELECT
                rf.*,
                e.title AS entry_title,
                r.notes AS release_notes

            FROM release_files rf

            LEFT JOIN releases r
                ON r.id = rf.release_id

            LEFT JOIN entries e
                ON e.id = r.entry_id

            ORDER BY rf.id DESC
            '
        );


        $stmt->execute();


        return array_map(

            fn(array $row): ReleaseFile =>
                $this->hydrate($row),

            $this->fetchAll($stmt)

        );
    }



    /**
     * Sök bland diskfiler
     *
     * @return ReleaseFile[]
     */
    public function searchDisks(
        string $query
    ): array {

        $stmt = $this->prepare(
            '
            SELECT
                rf.*,
                e.title AS entry_title,
                r.notes AS release_notes

            FROM release_files rf

            LEFT JOIN releases r
                ON r.id = rf.release_id

            LEFT JOIN entries e
                ON e.id = r.entry_id

            WHERE
                rf.filename LIKE :query1
                OR rf.disk_name LIKE :query2
                OR rf.format LIKE :query3
                OR rf.md5 LIKE :query4
                OR r.notes LIKE :query5
                OR e.title LIKE :query6

            ORDER BY rf.id DESC
            '
        );
        $stmt->execute([

            'query1' =>
                '%' . $query . '%',

            'query2' =>
                '%' . $query . '%',

            'query3' =>
                '%' . $query . '%',

            'query4' =>
                '%' . $query . '%',

            'query5' =>
                '%' . $query . '%',

            'query6' =>
                '%' . $query . '%'

        ]);


        return array_map(

            fn(array $row): ReleaseFile =>
                $this->hydrate($row),

            $this->fetchAll($stmt)

        );
    }



    public function findByMd5(
        string $md5
    ): ?ReleaseFile {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM release_files
            WHERE md5 = :md5
            LIMIT 1
            '
        );


        $stmt->execute([

            'md5' =>
                $md5

        ]);


        $row =
            $this->fetchOne($stmt);


        if ($row === null) {

            return null;
        }


        return $this->hydrate($row);
    }



    /**
     * Hitta fil med samma MD5 inom samma Entry.
     */
    public function findByMd5AndEntry(
        string $md5,
        int $entryId
    ): ?ReleaseFile {

        $stmt = $this->prepare(
            '
            SELECT rf.*
            FROM release_files rf

            INNER JOIN releases r
                ON r.id = rf.release_id

            WHERE rf.md5 = :md5
            AND r.entry_id = :entry_id

            LIMIT 1
            '
        );


        $stmt->execute([

            'md5' =>
                $md5,

            'entry_id' =>
                $entryId

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
    public function findAllByMd5(
        string $md5
    ): array {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM release_files
            WHERE md5 = :md5
            ORDER BY release_id
            '
        );


        $stmt->execute([

            'md5' =>
                $md5

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

            'id' =>
                $id

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

            ->setEntryTitle(
                $row['entry_title'] ?? null
            )

            ->setReleaseNotes(
                $row['release_notes'] ?? null
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
