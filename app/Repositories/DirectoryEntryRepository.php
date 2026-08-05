<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Entity\DirectoryEntry;

final class DirectoryEntryRepository extends Repository
{
    public function create(
        DirectoryEntry $entry
    ): int {

        $stmt = $this->prepare(
            '
            INSERT INTO directory_entries
            (
                release_file_id,
                filename,
                directory_position,
                filetype,
                start_track,
                start_sector,
                blocks,
                file_offset,
                file_size,
                locked,
                closed
            )
            VALUES
            (
                :release_file_id,
                :filename,
                :directory_position,
                :filetype,
                :start_track,
                :start_sector,
                :blocks,
                :file_offset,
                :file_size,
                :locked,
                :closed
            )
            '
        );


        $stmt->execute([

            'release_file_id' =>
                $entry->getReleaseFileId(),

            'filename' =>
                $entry->getFilename(),

            'directory_position' =>
                $entry->getDirectoryPosition(),

            'filetype' =>
                $entry->getFiletype(),

            'start_track' =>
                $entry->getStartTrack(),

            'start_sector' =>
                $entry->getStartSector(),

            'blocks' =>
                $entry->getBlocks(),

            'file_offset' =>
                $entry->getFileOffset(),

            'file_size' =>
                $entry->getFileSize(),

            'locked' =>
                $entry->isLocked()
                    ? 1
                    : 0,

            'closed' =>
                $entry->isClosed()
                    ? 1
                    : 0

        ]);


        return $this->lastInsertId();
    }


    public function findById(
        int $id
    ): ?DirectoryEntry {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM directory_entries
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
     * @return DirectoryEntry[]
     */
    public function findByReleaseFile(
        int $releaseFileId
    ): array {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM directory_entries
            WHERE release_file_id = :release_file_id
            ORDER BY directory_position
            '
        );


        $stmt->execute([

            'release_file_id' =>
                $releaseFileId

        ]);


        return array_map(

            fn(array $row): DirectoryEntry =>
                $this->hydrate($row),

            $this->fetchAll($stmt)

        );
    }

    public function findExisting(
        int $releaseFileId,
        string $filename,
        ?int $directoryPosition
    ): ?DirectoryEntry {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM directory_entries
            WHERE release_file_id = :release_file_id
            AND filename = :filename
            AND directory_position = :directory_position
            LIMIT 1
            '
        );


        $stmt->execute([

            'release_file_id' =>
                $releaseFileId,

            'filename' =>
                $filename,

            'directory_position' =>
                $directoryPosition

        ]);


        $row =
            $this->fetchOne($stmt);


        if ($row === null) {

            return null;
        }


        return $this->hydrate($row);
    }



    public function update(
        DirectoryEntry $entry
    ): bool {

        $stmt = $this->prepare(
            '
            UPDATE directory_entries
            SET
                filename = :filename,
                filetype = :filetype,
                start_track = :start_track,
                start_sector = :start_sector,
                blocks = :blocks,
                file_offset = :file_offset,
                file_size = :file_size,
                locked = :locked,
                closed = :closed
            WHERE id = :id
            '
        );


        return $stmt->execute([

            'id' =>
                $entry->getId(),

            'filename' =>
                $entry->getFilename(),

            'filetype' =>
                $entry->getFiletype(),

            'start_track' =>
                $entry->getStartTrack(),

            'start_sector' =>
                $entry->getStartSector(),

            'blocks' =>
                $entry->getBlocks(),

            'file_offset' =>
                $entry->getFileOffset(),

            'file_size' =>
                $entry->getFileSize(),

            'locked' =>
                $entry->isLocked()
                    ? 1
                    : 0,

            'closed' =>
                $entry->isClosed()
                    ? 1
                    : 0

        ]);
    }



    public function delete(
        int $id
    ): bool {

        $stmt = $this->prepare(
            '
            DELETE FROM directory_entries
            WHERE id = :id
            '
        );


        return $stmt->execute([

            'id' => $id

        ]);
    }



    private function hydrate(
        array $row
    ): DirectoryEntry {

        return (new DirectoryEntry())

            ->setId(
                (int) $row['id']
            )

            ->setReleaseFileId(
                (int) $row['release_file_id']
            )

            ->setFilename(
                $row['filename']
            )

            ->setDirectoryPosition(
                $row['directory_position'] !== null
                    ? (int) $row['directory_position']
                    : null
            )

            ->setFiletype(
                $row['filetype']
            )

            ->setStartTrack(
                $row['start_track'] !== null
                    ? (int) $row['start_track']
                    : null
            )

            ->setStartSector(
                $row['start_sector'] !== null
                    ? (int) $row['start_sector']
                    : null
            )

            ->setBlocks(
                (int) $row['blocks']
            )

            ->setFileOffset(
                $row['file_offset'] !== null
                    ? (int) $row['file_offset']
                    : null
            )

            ->setFileSize(
                $row['file_size'] !== null
                    ? (int) $row['file_size']
                    : null
            )

            ->setLocked(
                (bool) $row['locked']
            )

            ->setClosed(
                (bool) $row['closed']
            );
    }
}
