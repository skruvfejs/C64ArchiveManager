<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Entry;
use App\Repositories\EntryRepository;

final class EntryResolverService
{
    public function __construct(
        private EntryRepository $entryRepository
    ) {
    }



    public function resolve(
        ?int $entryId,
        string $title
    ): int {


        /*
         * Använd befintlig Entry om vald
         */
        if (
            $entryId !== null &&
            $entryId > 0
        ) {

            if (
                $this->entryRepository
                     ->exists($entryId)
            ) {

                return $entryId;
            }
        }



        /*
         * Skapa normaliserat söknamn
         */
        $sortTitle =
            $this->createSortTitle(
                $title
            );



        /*
         * Försök hitta befintlig Entry
         */
        $existing =
            $this->entryRepository
                 ->findBySortTitle(
                     $sortTitle
                 );



        if ($existing !== null) {

            return $existing->getId();
        }



        /*
         * Skapa ny Entry
         *
         * 18 = Other
         */
        $entry =
            new Entry();



        $entry
            ->setEntryTypeId(18)
            ->setTitle(
                trim($title)
            )
            ->setSortTitle(
                $sortTitle
            )
            ->setYear(null)
            ->setDescription(null)
            ->setStatus(1);



        return $this->entryRepository
                    ->create($entry);
    }



    private function createSortTitle(
        string $title
    ): string {


        /*
         * Trimma bara
         */
        $title =
            trim($title);



        /*
         * C64-namn till normal form
         */
        $title =
            strtoupper($title);



        /*
         * Ersätt flera mellanslag
         * med ett enda
         *
         * DISK   08
         * blir
         * DISK 08
         */
        $title =
            preg_replace(
                '/\s+/',
                ' ',
                $title
            );



        /*
         * Ta bort vanliga prefix
         */
        foreach (
            [
                'THE ',
                'A ',
                'AN '
            ]
            as $prefix
        ) {

            if (
                str_starts_with(
                    $title,
                    $prefix
                )
            ) {

                return trim(
                    substr(
                        $title,
                        strlen($prefix)
                    )
                );
            }
        }



        return $title;
    }
}
