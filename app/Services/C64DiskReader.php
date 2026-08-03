<?php

declare(strict_types=1);

namespace App\Services;


final class C64DiskReader
{

    private string $data;

    private array $tracks = [];


    public function __construct(
        private string $path
    ) {

        $data = file_get_contents($path);

        if ($data === false) {

            throw new \RuntimeException(
                'Unable to read disk image'
            );
        }


        $this->data = $data;

        $this->tracks =
            $this->createD64Geometry();
    }





    /**
     * Read C64 file chain.
     *
     * @return array<string,bool>
     */
    public function readFileChain(
        int $startTrack,
        int $startSector
    ): array {


        $used = [];


        $track = $startTrack;

        $sector = $startSector;


        $guard = 0;



        while (
            $track !== 0
            &&
            $guard < 1000
        ) {


            $key =
                sprintf(
                    '%02d:%02d',
                    $track,
                    $sector
                );



            if (
                isset($used[$key])
            ) {

                break;
            }



            $used[$key] = true;



            $offset =
                $this->sectorOffset(
                    $track,
                    $sector
                );



            if (
                $offset === null
            ) {

                break;
            }



            $nextTrack =
                ord(
                    $this->data[$offset]
                );


            $nextSector =
                ord(
                    $this->data[$offset + 1]
                );



            if (
                $nextTrack === 0
            ) {

                break;
            }



            if (
                $nextTrack < 1
                ||
                $nextTrack > 35
            ) {

                break;
            }



            if (
                !isset(
                    $this->tracks[$nextTrack]
                )
            ) {

                break;
            }



            if (
                $nextSector < 0
                ||
                $nextSector >=
                $this->tracks[$nextTrack]
            ) {

                break;
            }



            $track =
                $nextTrack;


            $sector =
                $nextSector;


            $guard++;
        }



        return $used;
    }

    /**
     * Convert track/sector to byte offset.
     */
    private function sectorOffset(
        int $track,
        int $sector
    ): ?int {


        if (
            !isset(
                $this->tracks[$track]
            )
        ) {

            return null;
        }



        if (
            $sector < 0
            ||
            $sector >=
            $this->tracks[$track]
        ) {

            return null;
        }



        $index = 0;



        for (
            $t = 1;
            $t < $track;
            $t++
        ) {

            $index +=
                $this->tracks[$t];
        }



        $index += $sector;



        return
            $index * 256;
    }





    /**
     * Create D64 geometry.
     */
    private function createD64Geometry(): array
    {

        $tracks = [];



        for (
            $i = 1;
            $i <= 17;
            $i++
        ) {

            $tracks[$i] = 21;
        }



        for (
            $i = 18;
            $i <= 24;
            $i++
        ) {

            $tracks[$i] = 19;
        }



        for (
            $i = 25;
            $i <= 30;
            $i++
        ) {

            $tracks[$i] = 18;
        }



        for (
            $i = 31;
            $i <= 35;
            $i++
        ) {

            $tracks[$i] = 17;
        }



        return $tracks;
    }


    /**
     * Get number of tracks on the current disk.
     */
    public function getTrackCount(): int
    {
        return count($this->tracks);
    }

    /**
     * Get number of sectors for a track.
     */
    public function getSectorsPerTrack(
        int $track
    ): int {
        return $this->tracks[$track] ?? 0;
    }

}
