<?php

declare(strict_types=1);

namespace App\Services;


final class C64BamComparator
{


    /**
     * Compare real BAM against calculated BAM.
     *
     * Real BAM contains all sectors used on disk.
     *
     * Calculated BAM contains sectors found through
     * directory/file chains plus reserved system sectors.
     *
     * @return array<int,array>
     */
    public function compare(
        array $bam,
        array $calculated
    ): array {

        $result = [];


        $bamUsedTotal = 0;
        $calculatedUsedTotal = 0;
        $extraUsedTotal = 0;
        $missingTotal = 0;
        $tracksWithDifferences = 0;



        foreach (
            $bam as $track => $info
        ) {


            $bamUsed =
                $info['used'] ?? 0;



            $calcUsed = 0;



            if (
                isset(
                    $calculated[$track]
                )
            ) {


                if (
                    isset(
                        $calculated[$track]['used']
                    )
                ) {

                    $calcUsed =
                        $calculated[$track]['used'];

                }


                elseif (
                    is_array(
                        $calculated[$track]
                    )
                ) {

                    $calcUsed =
                        count(
                            $calculated[$track]
                        );

                }
            }



            $difference =
                $bamUsed - $calcUsed;



            if (
                $difference === 0
            ) {

                $status =
                    'OK';

            }
            elseif (
                $difference > 0
            ) {

                $status =
                    'ORPHAN_SECTORS';

            }
            else {

                $status =
                    'MISSING_SECTORS';

            }

            $result[$track] =
                [

                    'bam_used' =>
                        $bamUsed,


                    'calculated_used' =>
                        $calcUsed,


                    'extra_used' =>
                        max(
                            0,
                            $difference
                        ),


                    'missing' =>
                        max(
                            0,
                            -$difference
                        ),


                    'difference' =>
                        $difference,


                    'status' =>
                        $status,


                    'match' =>
                        $difference === 0
                ];

            $bamUsedTotal += $bamUsed;
            $calculatedUsedTotal += $calcUsed;
            $extraUsedTotal += max(0, $difference);
            $missingTotal += max(0, -$difference);

            if ($difference !== 0) {
                $tracksWithDifferences++;
            }
        }

        $result['_summary'] = [
            'bam_used' => $bamUsedTotal,
            'calculated_used' => $calculatedUsedTotal,
            'extra_used' => $extraUsedTotal,
            'missing' => $missingTotal,
            'difference' => $bamUsedTotal - $calculatedUsedTotal,
            'tracks_with_differences' => $tracksWithDifferences,
        ];

        return $result;
    }

}
