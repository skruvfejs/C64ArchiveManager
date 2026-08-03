<?php

declare(strict_types=1);

namespace App\Services;


final class C64BamBuilder
{

    private array $tracks = [];

    private array $usedSectors = [];

    private array $sectorOwners = [];





    public function __construct(
        string $format
    ) {

        $geometry =
            new C64DiskGeometry($format);



        foreach (
            $geometry->getTracks()
            as $track => $sectors
        ) {

            $this->tracks[$track] =
                [

                    'total' =>
                        $sectors,

                    'used' =>
                        0
                ];
        }
    }





    /**
     * Add used sectors from file chains.
     *
     * @param array<string,bool> $sectors
     */
    public function addSectors(
        array $sectors
    ): void {


        foreach (
            $sectors as $sector => $unused
        ) {


            $this->addSector(
                $sector
            );
        }
    }





    /**
     * Add one sector.
     */
    private function addSector(
        string $sector
    ): void {


        if (
            isset(
                $this->usedSectors[$sector]
            )
        ) {

            return;
        }



        [
            $track,
            $sectorNumber
        ]
        =
        explode(
            ':',
            $sector
        );



        $track =
            (int)$track;


        $sectorNumber =
            (int)$sectorNumber;



        if (
            !isset(
                $this->tracks[$track]
            )
        ) {

            return;
        }



        if (
            $sectorNumber < 0
            ||
            $sectorNumber >=
            $this->tracks[$track]['total']
        ) {

            return;
        }



        $this->usedSectors[$sector] =
            true;



        $this->sectorOwners[$track][] =
            $sector;



        $this->tracks[$track]['used']++;
    }





    public function countUsedSectors(): int
    {

        return count(
            $this->usedSectors
        );
    }





    /**
     * Debug track allocation.
     *
     * @return array<int,array>
     */
    public function debugTracks(): array
    {

        return $this->sectorOwners;
    }





    /**
     * Reserve D64 directory and BAM sectors.
     *
     * Track 18:
     *
     * Sector 0 = BAM
     * Sector 1-? = directory chain
     */
    public function reserveD64SystemTracks(): void
    {

        $this->addSector(
            '18:00'
        );


        $this->addSector(
            '18:01'
        );


        $this->addSector(
            '18:02'
        );


        $this->addSector(
            '18:03'
        );


        $this->addSector(
            '18:04'
        );
    }





    /**
     * Return complete layout.
     *
     * @return array<int,array>
     */
    public function getLayout(): array
    {

        foreach (
            $this->tracks as &$data
        ) {


            $data['free'] =
                $data['total']
                -
                $data['used'];
        }



        return $this->tracks;
    }
}
