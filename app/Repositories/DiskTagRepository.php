<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Models\DiskTag;

final class DiskTagRepository extends Repository
{
    public function find(
        int $diskId,
        int $tagId
    ): ?DiskTag {
        $stmt = $this->prepare(
            'SELECT *
               FROM disk_tags
              WHERE release_file_id = :release_file_id
                AND tag_id = :tag_id
              LIMIT 1'
        );

        $stmt->execute([
            'release_file_id' => $diskId,
            'tag_id'          => $tagId,
        ]);

        $row = $this->fetchOne($stmt);

        if ($row === null) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * @return DiskTag[]
     */
    public function findByDiskId(
        int $diskId
    ): array {
        $stmt = $this->prepare(
            'SELECT *
               FROM disk_tags
              WHERE release_file_id = :release_file_id
           ORDER BY tag_id'
        );

        $stmt->execute([
            'release_file_id' => $diskId,
        ]);

        return array_map(
            fn(array $row): DiskTag => $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }

    /**
     * @return DiskTag[]
     */
    public function findByTagId(
        int $tagId
    ): array {
        $stmt = $this->prepare(
            'SELECT *
               FROM disk_tags
              WHERE tag_id = :tag_id
           ORDER BY release_file_id'
        );

        $stmt->execute([
            'tag_id' => $tagId,
        ]);

        return array_map(
            fn(array $row): DiskTag => $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }

    public function create(
        DiskTag $diskTag
    ): bool {
        $stmt = $this->prepare(
            'INSERT INTO disk_tags
            (
                release_file_id,
                tag_id
            )
            VALUES
            (
                :release_file_id,
                :tag_id
            )'
        );

        return $stmt->execute([
            'release_file_id' => $diskTag->getDiskId(),
            'tag_id'          => $diskTag->getTagId(),
        ]);
    }

    public function delete(
        int $diskId,
        int $tagId
    ): bool {
        $stmt = $this->prepare(
            'DELETE
               FROM disk_tags
              WHERE release_file_id = :release_file_id
                AND tag_id = :tag_id'
        );

        return $stmt->execute([
            'release_file_id' => $diskId,
            'tag_id'          => $tagId,
        ]);
    }

    public function deleteByDiskId(
        int $diskId
    ): bool {
        $stmt = $this->prepare(
            'DELETE
               FROM disk_tags
              WHERE release_file_id = :release_file_id'
        );

        return $stmt->execute([
            'release_file_id' => $diskId,
        ]);
    }

    public function deleteByTagId(
        int $tagId
    ): bool {
        $stmt = $this->prepare(
            'DELETE
               FROM disk_tags
              WHERE tag_id = :tag_id'
        );

        return $stmt->execute([
            'tag_id' => $tagId,
        ]);
    }

    private function hydrate(
        array $row
    ): DiskTag {
        return (new DiskTag())
            ->setDiskId((int) $row['release_file_id'])
            ->setTagId((int) $row['tag_id'])
            ->setCreatedAt($row['created_at']);
    }
}
