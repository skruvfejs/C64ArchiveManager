<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Models\EntryTag;

final class EntryTagRepository extends Repository
{
    public function find(int $entryId, int $tagId): ?EntryTag
    {
        $stmt = $this->prepare(
            'SELECT *
               FROM entry_tags
              WHERE entry_id = :entry_id
                AND tag_id = :tag_id
              LIMIT 1'
        );

        $stmt->execute([
            'entry_id' => $entryId,
            'tag_id'   => $tagId,
        ]);

        $row = $this->fetchOne($stmt);

        if ($row === null) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * @return EntryTag[]
     */
    public function findByEntryId(int $entryId): array
    {
        $stmt = $this->prepare(
            'SELECT *
               FROM entry_tags
              WHERE entry_id = :entry_id
           ORDER BY tag_id'
        );

        $stmt->execute([
            'entry_id' => $entryId,
        ]);

        return array_map(
            fn(array $row): EntryTag => $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }

    /**
     * @return EntryTag[]
     */
    public function findByTagId(int $tagId): array
    {
        $stmt = $this->prepare(
            'SELECT *
               FROM entry_tags
              WHERE tag_id = :tag_id
           ORDER BY entry_id'
        );

        $stmt->execute([
            'tag_id' => $tagId,
        ]);

        return array_map(
            fn(array $row): EntryTag => $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }

    public function create(EntryTag $entryTag): bool
    {
        $stmt = $this->prepare(
            'INSERT INTO entry_tags
            (
                entry_id,
                tag_id
            )
            VALUES
            (
                :entry_id,
                :tag_id
            )'
        );

        return $stmt->execute([
            'entry_id' => $entryTag->getEntryId(),
            'tag_id'   => $entryTag->getTagId(),
        ]);
    }

    public function delete(int $entryId, int $tagId): bool
    {
        $stmt = $this->prepare(
            'DELETE
               FROM entry_tags
              WHERE entry_id = :entry_id
                AND tag_id = :tag_id'
        );

        return $stmt->execute([
            'entry_id' => $entryId,
            'tag_id'   => $tagId,
        ]);
    }

    public function deleteByEntryId(int $entryId): bool
    {
        $stmt = $this->prepare(
            'DELETE
               FROM entry_tags
              WHERE entry_id = :entry_id'
        );

        return $stmt->execute([
            'entry_id' => $entryId,
        ]);
    }

    public function deleteByTagId(int $tagId): bool
    {
        $stmt = $this->prepare(
            'DELETE
               FROM entry_tags
              WHERE tag_id = :tag_id'
        );

        return $stmt->execute([
            'tag_id' => $tagId,
        ]);
    }

    private function hydrate(array $row): EntryTag
    {
        return (new EntryTag())
            ->setEntryId((int) $row['entry_id'])
            ->setTagId((int) $row['tag_id'])
            ->setCreatedAt($row['created_at']);
    }
}

