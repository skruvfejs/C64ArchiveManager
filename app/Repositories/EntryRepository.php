<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Models\Entry;
use PDO;

final class EntryRepository extends Repository
{
    public function findById(int $id): ?Entry
    {
        $stmt = $this->prepare(
            'SELECT *
             FROM entries
             WHERE id = :id
             LIMIT 1'
        );

        $stmt->execute([
            'id' => $id,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false
            ? null
            : $this->hydrate($row);
    }

    /**
     * @return Entry[]
     */
    public function findAll(): array
    {
        $stmt = $this->prepare(
            'SELECT *
             FROM entries
             ORDER BY title'
        );

        $stmt->execute();

        $entries = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $entries[] = $this->hydrate($row);
        }

        return $entries;
    }

    /**
     * @return Entry[]
     */
    public function search(string $search): array
    {
        $stmt = $this->prepare(
            'SELECT *
             FROM entries
             WHERE title LIKE :search
             ORDER BY title'
        );

        $stmt->execute([
            'search' => '%' . $search . '%',
        ]);

        $entries = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $entries[] = $this->hydrate($row);
        }

        return $entries;
    }

    public function create(Entry $entry): int
    {
        $stmt = $this->prepare(
            'INSERT INTO entries
            (
                entry_type_id,
                title,
                year,
                publisher,
                developer,
                notes
            )
            VALUES
            (
                :entry_type_id,
                :title,
                :year,
                :publisher,
                :developer,
                :notes
            )'
        );

        $stmt->execute([
            'entry_type_id' => $entry->getEntryTypeId(),
            'title'         => $entry->getTitle(),
            'year'          => $entry->getYear(),
            'publisher'     => $entry->getPublisher(),
            'developer'     => $entry->getDeveloper(),
            'notes'         => $entry->getNotes(),
        ]);

        return $this->lastInsertId();
    }

    public function update(Entry $entry): bool
    {
        $stmt = $this->prepare(
            'UPDATE entries
             SET
                entry_type_id = :entry_type_id,
                title = :title,
                year = :year,
                publisher = :publisher,
                developer = :developer,
                notes = :notes,
                updated_at = NOW()
             WHERE id = :id'
        );

        return $stmt->execute([
            'id'            => $entry->getId(),
            'entry_type_id' => $entry->getEntryTypeId(),
            'title'         => $entry->getTitle(),
            'year'          => $entry->getYear(),
            'publisher'     => $entry->getPublisher(),
            'developer'     => $entry->getDeveloper(),
            'notes'         => $entry->getNotes(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->prepare(
            'DELETE
             FROM entries
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
        ]);
    }

    public function exists(int $id): bool
    {
        $stmt = $this->prepare(
            'SELECT 1
             FROM entries
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
        ]);

        return $stmt->fetchColumn() !== false;
    }

    private function hydrate(array $row): Entry
    {
        return (new Entry())
            ->setId((int) $row['id'])
            ->setEntryTypeId((int) $row['entry_type_id'])
            ->setTitle($row['title'])
            ->setYear($row['year'] !== null ? (int) $row['year'] : null)
            ->setPublisher($row['publisher'])
            ->setDeveloper($row['developer'])
            ->setNotes($row['notes'])
            ->setCreatedAt($row['created_at'])
            ->setUpdatedAt($row['updated_at']);
    }
}
