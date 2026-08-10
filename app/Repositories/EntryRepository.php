<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Models\Entry;

final class EntryRepository extends Repository
{
    public function findById(
        int $id
    ): ?Entry {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM entries
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



    public function findByTitle(
        string $title
    ): ?Entry {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM entries
            WHERE title = :title
            LIMIT 1
            '
        );


        $stmt->execute([

            'title' => $title

        ]);


        $row =
            $this->fetchOne($stmt);


        if ($row === null) {

            return null;
        }


        return $this->hydrate($row);
    }



    public function findBySortTitle(
        string $sortTitle
    ): ?Entry {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM entries
            WHERE sort_title = :sort_title
            LIMIT 1
            '
        );


        $stmt->execute([

            'sort_title' =>
                $sortTitle

        ]);


        $row =
            $this->fetchOne($stmt);


        if ($row === null) {

            return null;
        }


        return $this->hydrate($row);
    }



    /**
     * Count entries for the archive list.
     */
    public function countEntries(
        string $search = ''
    ): int {

        if ($search === '') {

            $stmt = $this->prepare(
                '
                SELECT COUNT(*)
                FROM entries
                '
            );

            $stmt->execute();

            return (int) $stmt->fetchColumn();
        }


        $stmt = $this->prepare(
            '
            SELECT COUNT(*)
            FROM entries e
            WHERE e.title LIKE :search
               OR EXISTS (
                    SELECT 1
                    FROM entry_tags et
                    INNER JOIN tags t
                        ON t.id = et.tag_id
                    WHERE et.entry_id = e.id
                      AND t.name LIKE :tag_search
               )
            '
        );

        $value = '%' . $search . '%';

        $stmt->execute([
            'search' => $value,
            'tag_search' => $value
        ]);

        return (int) $stmt->fetchColumn();
    }



    /**
     * @return array<int, array{
     *     entry: Entry,
     *     releaseCount: int,
     *     tags: string
     * }>
     */
    public function findAllEntries(
        string $sort = 'id',
        int $limit = 50,
        int $offset = 0
    ): array {

        return $this->findEntries(
            '',
            $sort,
            $limit,
            $offset
        );
    }



    /**
     * @return array<int, array{
     *     entry: Entry,
     *     releaseCount: int,
     *     tags: string
     * }>
     */
    public function searchEntries(
        string $search,
        string $sort = 'id',
        int $limit = 50,
        int $offset = 0
    ): array {

        return $this->findEntries(
            $search,
            $sort,
            $limit,
            $offset
        );
    }



    /**
     * @return array<int, array{
     *     entry: Entry,
     *     releaseCount: int,
     *     tags: string
     * }>
     */
    private function findEntries(
        string $search,
        string $sort,
        int $limit,
        int $offset
    ): array {

        $orderBy = [
            'id' => [
                'column' => 'e.id',
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

            'year' => [
                'column' => 'e.year',
                'empty' => "CASE
                    WHEN e.year IS NULL
                    THEN 1
                    ELSE 0
                END"
            ],

            'releases' => [
                'column' => 'release_count',
                'empty' => null
            ],

            'tags' => [
                'column' => 'tag_names',
                'empty' => "CASE
                    WHEN tag_names IS NULL
                         OR TRIM(tag_names) = ''
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


        $where = '';

        $params = [];


        if ($search !== '') {

            $where = '
                WHERE e.title LIKE :search
                   OR EXISTS (
                        SELECT 1
                        FROM entry_tags et_search
                        INNER JOIN tags t_search
                            ON t_search.id = et_search.tag_id
                        WHERE et_search.entry_id = e.id
                          AND t_search.name LIKE :tag_search
                   )
            ';


            $value =
                '%' . $search . '%';


            $params['search'] =
                $value;

            $params['tag_search'] =
                $value;
        }


        $stmt = $this->prepare(
            '
            SELECT
                e.*,

                (
                    SELECT COUNT(*)
                    FROM releases r
                    WHERE r.entry_id = e.id
                ) AS release_count,

                (
                    SELECT GROUP_CONCAT(
                        DISTINCT t.name
                        ORDER BY t.name
                        SEPARATOR \', \'
                    )
                    FROM entry_tags et
                    INNER JOIN tags t
                        ON t.id = et.tag_id
                    WHERE et.entry_id = e.id
                ) AS tag_names

            FROM entries e

            ' . $where . '

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


        foreach ($params as $name => $value) {

            $stmt->bindValue(
                ':' . $name,
                $value
            );
        }


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


        $rows =
            $this->fetchAll($stmt);


        return array_map(

            function (array $row): array {

                return [

                    'entry' =>
                        $this->hydrate($row),

                    'releaseCount' =>
                        (int) $row['release_count'],

                    'tags' =>
                        $row['tag_names'] ?? ''

                ];
            },

            $rows

        );
    }



    /**
     * @return Entry[]
     */
    public function findAll(): array
    {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM entries
            ORDER BY title
            '
        );


        $stmt->execute();


        return array_map(

            fn(array $row): Entry =>
                $this->hydrate($row),

            $this->fetchAll($stmt)

        );
    }



    /**
     * @return Entry[]
     */
    public function search(
        string $search
    ): array {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM entries
            WHERE title LIKE :search
            ORDER BY title
            '
        );


        $stmt->execute([

            'search' =>
                '%' . $search . '%'

        ]);


        return array_map(

            fn(array $row): Entry =>
                $this->hydrate($row),

            $this->fetchAll($stmt)

        );
    }



    public function exists(
        int $id
    ): bool {

        $stmt = $this->prepare(
            '
            SELECT 1
            FROM entries
            WHERE id = :id
            LIMIT 1
            '
        );


        $stmt->execute([

            'id' =>
                $id

        ]);


        return
            $this->fetchOne($stmt)
            !== null;
    }



    public function create(
        Entry $entry
    ): int {

        $stmt = $this->prepare(
            '
            INSERT INTO entries
            (
                entry_type_id,
                title,
                sort_title,
                year,
                description,
                status
            )
            VALUES
            (
                :entry_type_id,
                :title,
                :sort_title,
                :year,
                :description,
                :status
            )
            '
        );


        $stmt->execute([

            'entry_type_id' =>
                $entry->getEntryTypeId(),

            'title' =>
                $entry->getTitle(),

            'sort_title' =>
                $entry->getSortTitle(),

            'year' =>
                $entry->getYear(),

            'description' =>
                $entry->getDescription(),

            'status' =>
                $entry->getStatus()

        ]);


        return $this->lastInsertId();
    }



    public function update(
        Entry $entry
    ): bool {

        $stmt = $this->prepare(
            '
            UPDATE entries
            SET
                entry_type_id = :entry_type_id,
                title = :title,
                sort_title = :sort_title,
                year = :year,
                description = :description,
                status = :status,
                updated_at = NOW()
            WHERE id = :id
            '
        );


        return $stmt->execute([

            'id' =>
                $entry->getId(),

            'entry_type_id' =>
                $entry->getEntryTypeId(),

            'title' =>
                $entry->getTitle(),

            'sort_title' =>
                $entry->getSortTitle(),

            'year' =>
                $entry->getYear(),

            'description' =>
                $entry->getDescription(),

            'status' =>
                $entry->getStatus()

        ]);
    }



    public function delete(
        int $id
    ): bool {

        $stmt = $this->prepare(
            '
            DELETE FROM entries
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
    ): Entry {

        return (new Entry())

            ->setId(
                (int) $row['id']
            )

            ->setEntryTypeId(
                (int) $row['entry_type_id']
            )

            ->setTitle(
                $row['title']
            )

            ->setSortTitle(
                $row['sort_title']
            )

            ->setYear(
                $row['year'] !== null
                    ? (int) $row['year']
                    : null
            )

            ->setDescription(
                $row['description']
            )

            ->setStatus(
                (int) $row['status']
            )

            ->setCreatedAt(
                $row['created_at']
            )

            ->setUpdatedAt(
                $row['updated_at']
            );
    }
}
