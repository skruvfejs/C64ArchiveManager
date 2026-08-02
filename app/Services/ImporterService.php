<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\DatabaseTransaction;
use RuntimeException;
use Throwable;

final class ImporterService
{
    public function __construct(
        private FormatDetectorService $formatDetector,
        private DatabaseTransaction $transaction,
        private ImportLogService $importLogService,

        private D64ImporterService $d64Importer,
        private T64ImporterService $t64Importer,
        private D71ImporterService $d71Importer,
        private D81ImporterService $d81Importer,
        private PrgImporterService $prgImporter,
        private P00Parser $p00Parser
    ) {
    }


    public function import(
        string $filename,
        int $entryId,
        bool $forceDuplicate = false
    ): int {

        $format =
            $this->formatDetector
                 ->detect($filename);


        $logId =
            $this->importLogService
                 ->start(
                     basename($filename),
                     $format
                 );


        try {

            $releaseId =
                $this->transaction->run(
                    function () use (
                        $filename,
                        $entryId,
                        $format,
                        $forceDuplicate
                    ): int {

                        return $this->doImport(
                            $filename,
                            $entryId,
                            $format,
                            $forceDuplicate
                        );
                    }
                );


            $this->importLogService
                 ->success(
                     $logId,
                     $releaseId
                 );


            return $releaseId;


        } catch (Throwable $e) {


            $this->importLogService
                 ->failed(
                     $logId,
                     $e->getMessage()
                 );


            throw $e;
        }
    }


    private function doImport(
        string $filename,
        int $entryId,
        string $format,
        bool $forceDuplicate = false
    ): int {

        return match ($format) {


            'D64' =>
                $this->d64Importer
                     ->import(
                         $filename,
                         $entryId,
                         $forceDuplicate
                     ),


            'T64' =>
                $this->t64Importer
                     ->import(
                         $filename,
                         $entryId,
                         $forceDuplicate
                     ),


            'D71' =>
                $this->d71Importer
                     ->import(
                         $filename,
                         $entryId,
                         $forceDuplicate
                     ),


            'D81' =>
                $this->d81Importer
                     ->import(
                         $filename,
                         $entryId,
                         $forceDuplicate
                     ),


            'PRG' =>
                $this->prgImporter
                     ->import(
                         $filename,
                         $entryId,
                         $forceDuplicate
                     ),


            'P00' =>
                $this->importP00(
                    $filename,
                    $entryId
                ),


            default =>
                throw new RuntimeException(
                    "Unsupported format: $format"
                )
        };
    }


    private function importP00(
        string $filename,
        int $entryId
    ): int {

        $info =
            $this->p00Parser
                 ->parse($filename);


        $data =
            $this->p00Parser
                 ->extractPrg($filename);


        return $this->prgImporter
                    ->importData(
                        $data,
                        $info['name'] . '.prg',
                        $entryId
                    );
    }
}

