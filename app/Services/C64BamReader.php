<?php

declare(strict_types=1);

namespace App\Services;


final class C64BamReader
{

    private string $data;

    private array $tracks = [];



    public function __construct(
        string $imagePath
    ) {

        $data =
            file_get_contents($imagePath);


        if ($data === false) {

            throw new \RuntimeException(
                'Unable to read D64 image'
            );
        }


        $this->data = $data;


        $this->tracks =
            $this->createGeometry();
    }





    /**
     * Read BAM from track 18 sector 0.
     *
     * @return array<int,array{
     *     total:int,
     *     free:int,
     *     used:int
     * }>
     */
    public function read(): array
    {

        $offset =
            $this->sectorOffset(
                18,
                0
            );


        $result = [];



        for (
            $track = 1;
            $track <= 35;
            $track++
        ) {


            /*
             * BAM entry:
             *
             * +0 free sector count
             * +1 bitmap byte 0
             * +2 bitmap byte 1
             * +3 bitmap byte 2
             */

            $pos =
                $offset
                + 4
                + (($track - 1) * 4);



            $freeCount =
                ord(
                    $this->data[$pos]
                );



            $bitmap =
                [
                    ord($this->data[$pos + 1]),
                    ord($this->data[$pos + 2]),
                    ord($this->data[$pos + 3])
                ];



            $total =
                $this->tracks[$track];



            $used = 0;



            for (
                $sector = 0;
                $sector < $total;
                $sector++
            ) {


                $byte =
                    intdiv(
                        $sector,
                        8
                    );


                $bit =
                    $sector & 7;



                /*
                 * BAM:
                 *
                 * 1 = free
                 * 0 = used
                 */

                if (
                    (
                        $bitmap[$byte]
                        &
                        (1 << $bit)
                    )
                    === 0
                ) {

                    $used++;
                }
            }



            $result[$track] =
                [
                    'total' =>
                        $total,

                    'free' =>
                        $freeCount,

                    'used' =>
                        $used
                ];
        }



        return $result;
    }





    public function debug(): void
    {

        $bam =
            $this->read();



        echo "\nC64 BAM DEBUG\n";
        echo "=============\n\n";


        foreach (
            $bam as $track => $info
        ) {


            echo sprintf(
                "Track %02d  Used:%d  Free:%d  Total:%d\n",

                $track,

                $info['used'],

                $info['free'],

                $info['total']
            );
        }
    }





    private function sectorOffset(
        int $track,
        int $sector
    ): int {

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





    private function createGeometry(): array
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
}
