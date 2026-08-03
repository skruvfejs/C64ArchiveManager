<?php

declare(strict_types=1);

namespace App\Services;

final class C64DiskGeometry
{
    private string $format;


    public function __construct(
        string $format
    ) {

        $this->format =
            strtoupper($format);
    }



    /**
     * Return sectors per track.
     *
     * @return array<int,int>
     */
    public function getTracks(): array
    {
        return match ($this->format) {


            'D64' =>
                $this->d64Tracks(),


            'D71' =>
                $this->d71Tracks(),


            'D81' =>
                $this->d81Tracks(),


            default =>
                []

        };
    }



    /**
     * Move to next sector.
     *
     * @return array{track:int,sector:int}|null
     */
    public function nextSector(
        int $track,
        int $sector
    ): ?array {


        $tracks =
            $this->getTracks();



        if (!isset($tracks[$track])) {

            return null;
        }



        $sector++;



        if ($sector < $tracks[$track]) {

            return [

                'track' =>
                    $track,

                'sector' =>
                    $sector
            ];
        }



        $track++;


        if (!isset($tracks[$track])) {

            return null;
        }


        return [

            'track' =>
                $track,

            'sector' =>
                0
        ];
    }



    private function d64Tracks(): array
    {
        $tracks = [];


        for ($i = 1; $i <= 17; $i++) {

            $tracks[$i] = 21;
        }


        for ($i = 18; $i <= 24; $i++) {

            $tracks[$i] = 19;
        }


        for ($i = 25; $i <= 30; $i++) {

            $tracks[$i] = 18;
        }


        for ($i = 31; $i <= 35; $i++) {

            $tracks[$i] = 17;
        }


        return $tracks;
    }



    private function d71Tracks(): array
    {
        $tracks =
            $this->d64Tracks();


        for ($i = 36; $i <= 70; $i++) {

            $tracks[$i] =
                $tracks[$i - 35];
        }


        return $tracks;
    }



    private function d81Tracks(): array
    {
        $tracks = [];


        for ($i = 1; $i <= 80; $i++) {

            $tracks[$i] = 40;
        }


        return $tracks;
    }
}


