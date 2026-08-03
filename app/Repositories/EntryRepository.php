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

            'id' => $id

        ]);


        return $this->fetchOne($stmt) !== null;
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

            'id' => $id

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
