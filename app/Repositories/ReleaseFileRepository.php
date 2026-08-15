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
    public function findAllDisks(
        string $sort = 'id',
        int $limit = 50,
        int $offset = 0
    ): array {


        $orderBy = [
            'id' => [
                'column' => 'rf.id',
                'empty' => null
            ],

            'title' => [
                'column' => 'e.title',
                'empty' => "CASE
                    WHEN e.title IS NULL
                         OR TRIM(e.title) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'disk_name' => [
                'column' => 'rf.disk_name',
                'empty' => "CASE
                    WHEN rf.disk_name IS NULL
                         OR TRIM(rf.disk_name) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'disk_id' => [
                'column' => 'rf.disk_id',
                'empty' => "CASE
                    WHEN rf.disk_id IS NULL
                         OR TRIM(rf.disk_id) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'format' => [
                'column' => 'rf.format',
                'empty' => "CASE
                    WHEN rf.format IS NULL
                         OR TRIM(rf.format) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'size' => [
                'column' => 'rf.size',
                'empty' => "CASE
                    WHEN rf.size IS NULL
                    THEN 1
                    ELSE 0
                END"
            ],

            'filename' => [
                'column' => 'rf.filename',
                'empty' => "CASE
                    WHEN rf.filename IS NULL
                         OR TRIM(rf.filename) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'tags' => [
                'column' => '(SELECT MIN(t.name)
                    FROM disk_tags dt_sort
                    INNER JOIN tags t
                        ON t.id = dt_sort.tag_id
                    WHERE dt_sort.release_file_id = rf.id)',
                'empty' => "CASE
                    WHEN (
                        SELECT MIN(t_empty.name)
                        FROM disk_tags dt_empty
                        INNER JOIN tags t_empty
                            ON t_empty.id = dt_empty.tag_id
                        WHERE dt_empty.release_file_id = rf.id
                    ) IS NULL
                    THEN 1
                    ELSE 0
                END"
            ]
        ];


        $sortDefinition =
            $orderBy[$sort]
            ?? $orderBy['id'];

        $orderColumn =
            $sortDefinition['column'];

        $orderEmpty =
            $sortDefinition['empty'];




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

            ORDER BY
                ' . (
                    $orderEmpty !== null
                        ? $orderEmpty . ' ASC,'
                        : ''
                ) . '
                ' . $orderColumn . ' ASC

            LIMIT :limit
            OFFSET :offset
            '
        );


        $stmt->bindValue(
            ':limit',
            $limit,
            \PDO::PARAM_INT
        );


        $stmt->bindValue(
            ':offset',
            $offset,
            \PDO::PARAM_INT
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
        string $query,
        string $sort = 'id',
        int $limit = 50,
        int $offset = 0
    ): array {

        $orderBy = [
            'id' => [
                'column' => 'rf.id',
                'empty' => null
            ],

            'title' => [
                'column' => 'e.title',
                'empty' => "CASE
                    WHEN e.title IS NULL
                         OR TRIM(e.title) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'disk_name' => [
                'column' => 'rf.disk_name',
                'empty' => "CASE
                    WHEN rf.disk_name IS NULL
                         OR TRIM(rf.disk_name) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'disk_id' => [
                'column' => 'rf.disk_id',
                'empty' => "CASE
                    WHEN rf.disk_id IS NULL
                         OR TRIM(rf.disk_id) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'format' => [
                'column' => 'rf.format',
                'empty' => "CASE
                    WHEN rf.format IS NULL
                         OR TRIM(rf.format) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'size' => [
                'column' => 'rf.size',
                'empty' => "CASE
                    WHEN rf.size IS NULL
                    THEN 1
                    ELSE 0
                END"
            ],

            'filename' => [
                'column' => 'rf.filename',
                'empty' => "CASE
                    WHEN rf.filename IS NULL
                         OR TRIM(rf.filename) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'tags' => [
                'column' => '(SELECT MIN(t.name)
                    FROM disk_tags dt_sort
                    INNER JOIN tags t
                        ON t.id = dt_sort.tag_id
                    WHERE dt_sort.release_file_id = rf.id)',
                'empty' => "CASE
                    WHEN (
                        SELECT MIN(t_empty.name)
                        FROM disk_tags dt_empty
                        INNER JOIN tags t_empty
                            ON t_empty.id = dt_empty.tag_id
                        WHERE dt_empty.release_file_id = rf.id
                    ) IS NULL
                    THEN 1
                    ELSE 0
                END"
            ]
        ];


        $sortDefinition =
            $orderBy[$sort]
            ?? $orderBy['id'];

        $orderColumn =
            $sortDefinition['column'];

        $orderEmpty =
            $sortDefinition['empty'];




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

            ORDER BY
                ' . (
                    $orderEmpty !== null
                        ? $orderEmpty . ' ASC,'
                        : ''
                ) . '
                ' . $orderColumn . ' ASC

            LIMIT :limit
            OFFSET :offset
            '
        );


        $stmt->bindValue(
            ':query1',
            '%' . $query . '%'
        );

        $stmt->bindValue(
            ':query2',
            '%' . $query . '%'
        );

        $stmt->bindValue(
            ':query3',
            '%' . $query . '%'
        );

        $stmt->bindValue(
            ':query4',
            '%' . $query . '%'
        );

        $stmt->bindValue(
            ':query5',
            '%' . $query . '%'
        );

        $stmt->bindValue(
            ':query6',
            '%' . $query . '%'
        );


        $stmt->bindValue(
            ':limit',
            $limit,
            \PDO::PARAM_INT
        );


        $stmt->bindValue(
            ':offset',
            $offset,
            \PDO::PARAM_INT
        );


        $stmt->execute();



        return array_map(

            fn(array $row): ReleaseFile =>
                $this->hydrate($row),

            $this->fetchAll($stmt)

        );
    }



    /**
     * Hämta diskfiler som har en viss tagg.
     *
     * @return ReleaseFile[]
     */
    public function findAllDisksByTag(
        int $tagId,
        string $sort = 'id',
        int $limit = 50,
        int $offset = 0
    ): array {
        $orderBy = [
            'id' => [
                'column' => 'rf.id',
                'empty' => null
            ],

            'title' => [
                'column' => 'e.title',
                'empty' => "CASE
                    WHEN e.title IS NULL
                         OR TRIM(e.title) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'disk_name' => [
                'column' => 'rf.disk_name',
                'empty' => "CASE
                    WHEN rf.disk_name IS NULL
                         OR TRIM(rf.disk_name) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'disk_id' => [
                'column' => 'rf.disk_id',
                'empty' => "CASE
                    WHEN rf.disk_id IS NULL
                         OR TRIM(rf.disk_id) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'format' => [
                'column' => 'rf.format',
                'empty' => "CASE
                    WHEN rf.format IS NULL
                         OR TRIM(rf.format) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'size' => [
                'column' => 'rf.size',
                'empty' => "CASE
                    WHEN rf.size IS NULL
                    THEN 1
                    ELSE 0
                END"
            ],

            'filename' => [
                'column' => 'rf.filename',
                'empty' => "CASE
                    WHEN rf.filename IS NULL
                         OR TRIM(rf.filename) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'tags' => [
                'column' => '(SELECT MIN(t.name)
                    FROM disk_tags dt_sort
                    INNER JOIN tags t
                        ON t.id = dt_sort.tag_id
                    WHERE dt_sort.release_file_id = rf.id)',
                'empty' => "CASE
                    WHEN (
                        SELECT MIN(t_empty.name)
                        FROM disk_tags dt_empty
                        INNER JOIN tags t_empty
                            ON t_empty.id = dt_empty.tag_id
                        WHERE dt_empty.release_file_id = rf.id
                    ) IS NULL
                    THEN 1
                    ELSE 0
                END"
            ]
        ];


        $sortDefinition =
            $orderBy[$sort]
            ?? $orderBy['id'];

        $orderColumn =
            $sortDefinition['column'];

        $orderEmpty =
            $sortDefinition['empty'];


        $stmt = $this->prepare(
            '
            SELECT
                rf.*,
                e.title AS entry_title,
                r.notes AS release_notes

            FROM release_files rf

            INNER JOIN disk_tags dt
                ON dt.release_file_id = rf.id

            LEFT JOIN releases r
                ON r.id = rf.release_id

            LEFT JOIN entries e
                ON e.id = r.entry_id

            WHERE dt.tag_id = :tag_id

            ORDER BY
                ' . (
                    $orderEmpty !== null
                        ? $orderEmpty . ' ASC,'
                        : ''
                ) . '
                ' . $orderColumn . ' ASC

            LIMIT :limit
            OFFSET :offset
            '
        );

        $stmt->bindValue(
            ':tag_id',
            $tagId,
            \PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':limit',
            $limit,
            \PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':offset',
            $offset,
            \PDO::PARAM_INT
        );

        $stmt->execute();

        return array_map(
            fn(array $row): ReleaseFile =>
                $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }


    /**
     * Sök diskfiler inom en viss tagg.
     *
     * @return ReleaseFile[]
     */
    public function searchDisksByTag(
        int $tagId,
        string $query,
        string $sort = 'id',
        int $limit = 50,
        int $offset = 0
    ): array {
        $orderBy = [
            'id' => [
                'column' => 'rf.id',
                'empty' => null
            ],

            'title' => [
                'column' => 'e.title',
                'empty' => "CASE
                    WHEN e.title IS NULL
                         OR TRIM(e.title) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'disk_name' => [
                'column' => 'rf.disk_name',
                'empty' => "CASE
                    WHEN rf.disk_name IS NULL
                         OR TRIM(rf.disk_name) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'disk_id' => [
                'column' => 'rf.disk_id',
                'empty' => "CASE
                    WHEN rf.disk_id IS NULL
                         OR TRIM(rf.disk_id) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'format' => [
                'column' => 'rf.format',
                'empty' => "CASE
                    WHEN rf.format IS NULL
                         OR TRIM(rf.format) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'size' => [
                'column' => 'rf.size',
                'empty' => "CASE
                    WHEN rf.size IS NULL
                    THEN 1
                    ELSE 0
                END"
            ],

            'filename' => [
                'column' => 'rf.filename',
                'empty' => "CASE
                    WHEN rf.filename IS NULL
                         OR TRIM(rf.filename) = ''
                    THEN 1
                    ELSE 0
                END"
            ],

            'tags' => [
                'column' => '(SELECT MIN(t.name)
                    FROM disk_tags dt_sort
                    INNER JOIN tags t
                        ON t.id = dt_sort.tag_id
                    WHERE dt_sort.release_file_id = rf.id)',
                'empty' => "CASE
                    WHEN (
                        SELECT MIN(t_empty.name)
                        FROM disk_tags dt_empty
                        INNER JOIN tags t_empty
                            ON t_empty.id = dt_empty.tag_id
                        WHERE dt_empty.release_file_id = rf.id
                    ) IS NULL
                    THEN 1
                    ELSE 0
                END"
            ]
        ];


        $sortDefinition =
            $orderBy[$sort]
            ?? $orderBy['id'];

        $orderColumn =
            $sortDefinition['column'];

        $orderEmpty =
            $sortDefinition['empty'];


        $stmt = $this->prepare(
            '
            SELECT
                rf.*,
                e.title AS entry_title,
                r.notes AS release_notes

            FROM release_files rf

            INNER JOIN disk_tags dt
                ON dt.release_file_id = rf.id

            LEFT JOIN releases r
                ON r.id = rf.release_id

            LEFT JOIN entries e
                ON e.id = r.entry_id

            WHERE dt.tag_id = :tag_id
              AND (
                    rf.filename LIKE :query1
                    OR rf.disk_name LIKE :query2
                    OR rf.format LIKE :query3
                    OR rf.md5 LIKE :query4
                    OR r.notes LIKE :query5
                    OR e.title LIKE :query6
              )

            ORDER BY
                ' . (
                    $orderEmpty !== null
                        ? $orderEmpty . ' ASC,'
                        : ''
                ) . '
                ' . $orderColumn . ' ASC

            LIMIT :limit
            OFFSET :offset
            '
        );

        $stmt->bindValue(
            ':tag_id',
            $tagId,
            \PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':query1',
            '%' . $query . '%'
        );

        $stmt->bindValue(
            ':query2',
            '%' . $query . '%'
        );

        $stmt->bindValue(
            ':query3',
            '%' . $query . '%'
        );

        $stmt->bindValue(
            ':query4',
            '%' . $query . '%'
        );

        $stmt->bindValue(
            ':query5',
            '%' . $query . '%'
        );

        $stmt->bindValue(
            ':query6',
            '%' . $query . '%'
        );

        $stmt->bindValue(
            ':limit',
            $limit,
            \PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':offset',
            $offset,
            \PDO::PARAM_INT
        );

        $stmt->execute();

        return array_map(
            fn(array $row): ReleaseFile =>
                $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }


    /**
     * Räkna diskfiler med en viss tagg.
     */
    public function countDisksByTag(
        int $tagId,
        string $query = ''
    ): int {
        $stmt = $this->prepare(
            '
            SELECT COUNT(*)

            FROM release_files rf

            INNER JOIN disk_tags dt
                ON dt.release_file_id = rf.id

            LEFT JOIN releases r
                ON r.id = rf.release_id

            LEFT JOIN entries e
                ON e.id = r.entry_id

            WHERE dt.tag_id = :tag_id
            '
            . (
                $query !== ''
                    ? '
            AND (
                rf.filename LIKE :query1
                OR rf.disk_name LIKE :query2
                OR rf.format LIKE :query3
                OR rf.md5 LIKE :query4
                OR r.notes LIKE :query5
                OR e.title LIKE :query6
            )
            '
                    : ''
            )
        );

        $stmt->bindValue(
            ':tag_id',
            $tagId,
            \PDO::PARAM_INT
        );

        if ($query !== '') {
            $stmt->bindValue(
                ':query1',
                '%' . $query . '%'
            );

            $stmt->bindValue(
                ':query2',
                '%' . $query . '%'
            );

            $stmt->bindValue(
                ':query3',
                '%' . $query . '%'
            );

            $stmt->bindValue(
                ':query4',
                '%' . $query . '%'
            );

            $stmt->bindValue(
                ':query5',
                '%' . $query . '%'
            );

            $stmt->bindValue(
                ':query6',
                '%' . $query . '%'
            );
        }

        $stmt->execute();

        return (int)
            $stmt->fetchColumn();
    }


    /**
     * Räkna antal diskfiler
     */
    public function countDisks(
        string $query = ''
    ): int {

        if ($query === '') {

            $stmt = $this->prepare(
                '
                SELECT COUNT(*)
                FROM release_files
                '
            );


            $stmt->execute();


            return (int)
                $stmt->fetchColumn();
        }



        $stmt = $this->prepare(
            '
            SELECT COUNT(*)

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


        return (int)
            $stmt->fetchColumn();
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



    /**
     * Hämta MD5-värden för alla release-filer
     * som tillhör en Entry.
     *
     * Returnerar:
     * [
     *     'md5' => release_id
     * ]
     *
     * Den första förekomsten av varje MD5 används
     * som referens för duplicate-kontrollen.
     */
    public function findMd5ByEntry(
        int $entryId
    ): array {
        $stmt = $this->prepare(
            '
            SELECT
                rf.md5,
                r.id AS release_id
            FROM release_files rf
            INNER JOIN releases r
                ON r.id = rf.release_id
            WHERE r.entry_id = :entry_id
              AND rf.md5 IS NOT NULL
              AND rf.md5 <> \'\'
            ORDER BY r.created_at DESC, r.id DESC, rf.id ASC
            '
        );

        $stmt->execute([
            'entry_id' =>
                $entryId
        ]);

        $result = [];

        foreach ($this->fetchAll($stmt) as $row) {
            $md5 =
                $row['md5'];

            if (!isset($result[$md5])) {
                $result[$md5] =
                    (int) $row['release_id'];
            }
        }

        return $result;
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

