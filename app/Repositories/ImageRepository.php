<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Models\Image;

final class ImageRepository extends Repository
{
    public function findById(int $id): ?Image
    {
        $stmt = $this->prepare(
            'SELECT *
               FROM images
              WHERE id = :id
              LIMIT 1'
        );

        $stmt->execute([
            'id' => $id,
        ]);

        $row = $this->fetchOne($stmt);

        if ($row === null) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * @return Image[]
     */
    public function findByEntryId(int $entryId): array
    {
        $stmt = $this->prepare(
            'SELECT *
               FROM images
              WHERE entry_id = :entry_id
           ORDER BY type, filename'
        );

        $stmt->execute([
            'entry_id' => $entryId,
        ]);

        return array_map(
            fn(array $row): Image => $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }

    /**
     * @return Image[]
     */
    public function findAll(): array
    {
        $stmt = $this->prepare(
            'SELECT *
               FROM images
           ORDER BY type, filename'
        );

        $stmt->execute();

        return array_map(
            fn(array $row): Image => $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }

    public function create(Image $image): int
    {
        $stmt = $this->prepare(
            'INSERT INTO images
            (
                entry_id,
                type,
                filename,
                path,
                width,
                height
            )
            VALUES
            (
                :entry_id,
                :type,
                :filename,
                :path,
                :width,
                :height
            )'
        );

        $stmt->execute([
            'entry_id' => $image->getEntryId(),
            'type'     => $image->getType(),
            'filename' => $image->getFilename(),
            'path'     => $image->getPath(),
            'width'    => $image->getWidth(),
            'height'   => $image->getHeight(),
        ]);

        return $this->lastInsertId();
    }

    public function update(Image $image): bool
    {
        $stmt = $this->prepare(
            'UPDATE images
                SET
                    entry_id = :entry_id,
                    type = :type,
                    filename = :filename,
                    path = :path,
                    width = :width,
                    height = :height,
                    updated_at = NOW()
              WHERE id = :id'
        );

        return $stmt->execute([
            'id'       => $image->getId(),
            'entry_id' => $image->getEntryId(),
            'type'     => $image->getType(),
            'filename' => $image->getFilename(),
            'path'     => $image->getPath(),
            'width'    => $image->getWidth(),
            'height'   => $image->getHeight(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->prepare(
            'DELETE
               FROM images
              WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
        ]);
    }

    private function hydrate(array $row): Image
    {
        return (new Image())
            ->setId((int) $row['id'])
            ->setEntryId((int) $row['entry_id'])
            ->setType($row['type'])
            ->setFilename($row['filename'])
            ->setPath($row['path'])
            ->setWidth(
                $row['width'] !== null
                    ? (int) $row['width']
                    : null
            )
            ->setHeight(
                $row['height'] !== null
                    ? (int) $row['height']
                    : null
            )
            ->setCreatedAt($row['created_at'])
            ->setUpdatedAt($row['updated_at']);
    }
}


