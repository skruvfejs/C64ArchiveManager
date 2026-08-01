<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Models\Tag;

final class TagRepository extends Repository
{
    public function findById(int $id): ?Tag
    {
        $stmt = $this->prepare(
            'SELECT *
               FROM tags
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

    public function findByName(string $name): ?Tag
    {
        $stmt = $this->prepare(
            'SELECT *
               FROM tags
              WHERE name = :name
              LIMIT 1'
        );

        $stmt->execute([
            'name' => $name,
        ]);

        $row = $this->fetchOne($stmt);

        if ($row === null) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * @return Tag[]
     */
    public function findAll(): array
    {
        $stmt = $this->prepare(
            'SELECT *
               FROM tags
           ORDER BY name'
        );

        $stmt->execute();

        return array_map(
            fn(array $row): Tag => $this->hydrate($row),
            $this->fetchAll($stmt)
        );
    }

    public function create(Tag $tag): int
    {
        $stmt = $this->prepare(
            'INSERT INTO tags
            (
                name,
                description
            )
            VALUES
            (
                :name,
                :description
            )'
        );

        $stmt->execute([
            'name'        => $tag->getName(),
            'description' => $tag->getDescription(),
        ]);

        return $this->lastInsertId();
    }

    public function update(Tag $tag): bool
    {
        $stmt = $this->prepare(
            'UPDATE tags
                SET
                    name = :name,
                    description = :description,
                    updated_at = NOW()
              WHERE id = :id'
        );

        return $stmt->execute([
            'id'          => $tag->getId(),
            'name'        => $tag->getName(),
            'description' => $tag->getDescription(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->prepare(
            'DELETE
               FROM tags
              WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
        ]);
    }

    private function hydrate(array $row): Tag
    {
        return (new Tag())
            ->setId((int) $row['id'])
            ->setName($row['name'])
            ->setDescription($row['description'])
            ->setCreatedAt($row['created_at'])
            ->setUpdatedAt($row['updated_at']);
    }
}

