<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Models\ReleaseTag;

final class ReleaseTagRepository extends Repository
{
    public function find(
        int $releaseId,
        int $tagId
    ): ?ReleaseTag {
        $stmt = $this->prepare(
            'SELECT *
               FROM release_tags
              WHERE release_id = :release_id
                AND tag_id = :tag_id
              LIMIT 1'
        );

        $stmt->execute([
            'release_id' => $releaseId,
            'tag_id'     => $tagId,
        ]);

        $row = $this->fetchOne($stmt);

        if ($row === null) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * @return ReleaseTag[]
     */
    public function findByReleaseId(
        int $releaseId
    ): array {
        $stmt = $this->prepare(
            'SELECT *
               FROM release_tags
              WHERE release_id = :release_id
           ORDER BY tag_id'
        );

        $stmt->execute([
            'release_id' => $releaseId,
        ]);

        return array_map(
            fn(array $row): ReleaseTag => $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }

    /**
     * @return ReleaseTag[]
     */
    public function findByTagId(
        int $tagId
    ): array {
        $stmt = $this->prepare(
            'SELECT *
               FROM release_tags
              WHERE tag_id = :tag_id
           ORDER BY release_id'
        );

        $stmt->execute([
            'tag_id' => $tagId,
        ]);

        return array_map(
            fn(array $row): ReleaseTag => $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }

    public function create(
        ReleaseTag $releaseTag
    ): bool {
        $stmt = $this->prepare(
            'INSERT INTO release_tags
            (
                release_id,
                tag_id
            )
            VALUES
            (
                :release_id,
                :tag_id
            )'
        );

        return $stmt->execute([
            'release_id' => $releaseTag->getReleaseId(),
            'tag_id'     => $releaseTag->getTagId(),
        ]);
    }

    public function delete(
        int $releaseId,
        int $tagId
    ): bool {
        $stmt = $this->prepare(
            'DELETE
               FROM release_tags
              WHERE release_id = :release_id
                AND tag_id = :tag_id'
        );

        return $stmt->execute([
            'release_id' => $releaseId,
            'tag_id'     => $tagId,
        ]);
    }

    public function deleteByReleaseId(
        int $releaseId
    ): bool {
        $stmt = $this->prepare(
            'DELETE
               FROM release_tags
              WHERE release_id = :release_id'
        );

        return $stmt->execute([
            'release_id' => $releaseId,
        ]);
    }

    public function deleteByTagId(
        int $tagId
    ): bool {
        $stmt = $this->prepare(
            'DELETE
               FROM release_tags
              WHERE tag_id = :tag_id'
        );

        return $stmt->execute([
            'tag_id' => $tagId,
        ]);
    }

    private function hydrate(
        array $row
    ): ReleaseTag {
        return (new ReleaseTag())
            ->setReleaseId((int) $row['release_id'])
            ->setTagId((int) $row['tag_id'])
            ->setCreatedAt($row['created_at']);
    }
}
