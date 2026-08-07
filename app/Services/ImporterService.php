<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\DatabaseTransaction;
use App\Entity\ImportResult;
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
        private P00Parser $p00Parser,

        private EntryResolverService $entryResolver,
        private DiskHeaderService $diskHeaderService
    ) {
    }



    public function import(
        string $filename,
        ?int $entryId,
        bool $forceDuplicate = false,
        ?int $userId = null
    ): ImportResult {


        $format =
            $this->formatDetector
                 ->detect($filename);



        if (
            in_array(
                $format,
                [
                    'D64',
                    'D71',
                    'D81'
                ],
                true
            )
        ) {

            $entryTitle =
                $this->diskHeaderService
                     ->getName(
                         $filename
                     );

        } elseif ($format === 'P00') {

            $info =
                $this->p00Parser
                     ->parse($filename);

            $entryTitle =
                $info['name'];

        } else {

            $entryTitle =
                pathinfo(
                    $filename,
                    PATHINFO_FILENAME
                );
        }



        if ($entryId === null) {

            $entryId =
                $this->entryResolver
                     ->resolve(
                         null,
                         $entryTitle
                     );
        }



        $logId =
            $this->importLogService
                 ->start(
                     basename($filename),
                     $format,
                     $userId
                 );



        try {

            $result =
                $this->transaction->run(
                    function () use (
                        $filename,
                        $entryId,
                        $format,
                        $forceDuplicate
                    ): ImportResult {

                        return $this->doImport(
                            $filename,
                            $entryId,
                            $format,
                            $forceDuplicate
                        );
                    }
                );



            if (!$result->isDuplicate()) {

                $this->importLogService
                     ->success(
                         $logId,
                         $result->getReleaseId(),
                         $result->getFilesImported()
                     );
            }



            return $result;



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
    ): ImportResult {


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
                    $entryId,
                    $forceDuplicate
                ),



            default =>

                throw new RuntimeException(
                    'Unsupported format: '
                    . $format
                )
        };
    }



    private function importP00(
        string $filename,
        int $entryId,
        bool $forceDuplicate = false
    ): ImportResult {


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
                        $filename,
                        $entryId,
                        $forceDuplicate
                    );
    }
}
