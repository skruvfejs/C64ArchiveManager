<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Entity\Release;

final class ReleaseRepository extends Repository
{
    public function create(
        Release $release
    ): int {

        $stmt = $this->prepare(
            '
            INSERT INTO releases
            (
                entry_id,
                name,
                version,
                notes
            )
            VALUES
            (
                :entry_id,
                :name,
                :version,
                :notes
            )
            '
        );


        $stmt->execute([

            'entry_id' =>
                $release->getEntryId(),

            'name' =>
                $release->getName(),

            'version' =>
                $release->getVersion(),

            'notes' =>
                $release->getNotes()

        ]);


        return $this->lastInsertId();
    }



    public function existsByEntryNameVersion(
        int $entryId,
        string $name,
        ?string $version
    ): bool {

        if ($version === null) {

            $stmt = $this->prepare(
                '
                SELECT COUNT(*)
                FROM releases
                WHERE entry_id = :entry_id
                AND name = :name
                AND version IS NULL
                LIMIT 1
                '
            );


            $stmt->execute([

                'entry_id' =>
                    $entryId,

                'name' =>
                    $name

            ]);

        } else {

            $stmt = $this->prepare(
                '
                SELECT COUNT(*)
                FROM releases
                WHERE entry_id = :entry_id
                AND name = :name
                AND version = :version
                LIMIT 1
                '
            );


            $stmt->execute([

                'entry_id' =>
                    $entryId,

                'name' =>
                    $name,

                'version' =>
                    $version

            ]);
        }


        return (int) $stmt->fetchColumn() > 0;
    }



    public function findByEntryNameVersion(
        int $entryId,
        string $name,
        ?string $version
    ): ?Release {

        if ($version === null) {

            $stmt = $this->prepare(
                '
                SELECT *
                FROM releases
                WHERE entry_id = :entry_id
                AND name = :name
                AND version IS NULL
                LIMIT 1
                '
            );


            $stmt->execute([

                'entry_id' =>
                    $entryId,

                'name' =>
                    $name

            ]);

        } else {

            $stmt = $this->prepare(
                '
                SELECT *
                FROM releases
                WHERE entry_id = :entry_id
                AND name = :name
                AND version = :version
                LIMIT 1
                '
            );


            $stmt->execute([

                'entry_id' =>
                    $entryId,

                'name' =>
                    $name,

                'version' =>
                    $version

            ]);
        }


        $row =
            $this->fetchOne($stmt);


        if ($row === null) {

            return null;
        }


        return $this->hydrate($row);
    }



    public function findById(
        int $id
    ): ?Release {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM releases
            WHERE id = :id
            LIMIT 1
            '
        );


        $stmt->execute([

            'id' =>
                $id

        ]);


        $row =
            $this->fetchOne($stmt);


        if ($row === null) {

            return null;
        }


        return $this->hydrate($row);
    }



    public function update(
        int $id,
        string $notes
    ): bool {
        $stmt = $this->prepare(
            '
            UPDATE releases
            SET notes = :notes
            WHERE id = :id
            '
        );

        return $stmt->execute([
            'notes' =>
                $notes,
            'id' =>
                $id
        ]);
    }


    public function findByEntry(
        int $entryId
    ): array {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM releases
            WHERE entry_id = :entry_id
            ORDER BY created_at DESC
            '
        );


        $stmt->execute([

            'entry_id' =>
                $entryId

        ]);


        return array_map(

            fn(array $row): Release =>
                $this->hydrate($row),

            $this->fetchAll($stmt)

        );
    }



    public function delete(
        int $id
    ): bool {

        $stmt = $this->prepare(
            '
            DELETE FROM releases
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
    ): Release {

        return (new Release())

            ->setId(
                (int) $row['id']
            )

            ->setEntryId(
                (int) $row['entry_id']
            )

            ->setName(
                $row['name']
            )

            ->setVersion(
                $row['version']
            )

            ->setNotes(
                $row['notes']
            );
    }
}
