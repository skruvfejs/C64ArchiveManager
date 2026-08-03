<?php

declare(strict_types=1);

namespace App\Services;


final class C64DiskIntegrityChecker
{

    private array $errors = [];

    private array $warnings = [];

    private array $orphanSectors = [];





    /**
     * Run complete disk integrity check.
     *
     * @param array $bamCompare
     *
     * @return array
     */
    public function check(
        array $bamCompare
    ): array {


        $this->errors = [];

        $this->warnings = [];

        $this->orphanSectors = [];



        foreach (
            $bamCompare as $track => $info
        ) {


            if (
                ($info['status'] ?? '')
                === 'ORPHAN_SECTORS'
            ) {


                $this->orphanSectors[$track] =
                    $info['extra_used'];



                $this->warnings[] =
                    sprintf(
                        'Track %02d contains %d orphan sectors',
                        $track,
                        $info['extra_used']
                    );
            }



            if (
                ($info['status'] ?? '')
                === 'MISSING_SECTORS'
            ) {


                $this->errors[] =
                    sprintf(
                        'Track %02d has missing sectors',
                        $track
                    );
            }
        }



        return [

            'valid' =>
                empty($this->errors),


            'errors' =>
                $this->errors,


            'warnings' =>
                $this->warnings,


            'orphan_sectors' =>
                $this->orphanSectors,


            'total_orphan_sectors' =>
                array_sum(
                    $this->orphanSectors
                )

        ];
    }





    /**
     * Print human readable result.
     */
    public function debug(
        array $result
    ): void {


        echo "\n";
        echo "C64 DISK INTEGRITY CHECK\n";
        echo "========================\n\n";



        if (
            $result['valid']
        ) {

            echo "Status: VALID\n";

        }
        else {

            echo "Status: INVALID\n";

        }



        echo "\n";



        if (
            !empty(
                $result['errors']
            )
        ) {


            echo "ERRORS:\n";


            foreach (
                $result['errors']
                as $error
            ) {

                echo "- ";
                echo $error;
                echo "\n";
            }


            echo "\n";
        }



        if (
            !empty(
                $result['warnings']
            )
        ) {


            echo "WARNINGS:\n";


            foreach (
                $result['warnings']
                as $warning
            ) {

                echo "- ";
                echo $warning;
                echo "\n";
            }


            echo "\n";
        }



        echo "Orphan sectors: ";

        echo $result['total_orphan_sectors'];

        echo "\n";
    }

}
