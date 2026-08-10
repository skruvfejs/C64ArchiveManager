<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ReleaseFileRepository;

final class MaintenanceService
{
    public function __construct(
        private readonly StorageService $storage,
        private readonly ReleaseFileRepository $files
    ) {
    }


    /**
     * Returnera aktuell status för importlagringen.
     *
     * @return array<string,mixed>
     */
    public function getStorageStatus(): array
    {
        $directory =
            $this->storage->getImportDirectory();


        $exists =
            is_dir($directory);


        return [
            'directory' => $directory,

            'exists' =>
                $exists,

            'readable' =>
                $exists &&
                is_readable($directory),

            'writable' =>
                $exists &&
                is_writable($directory),

            'fileCount' =>
                $this->storage->getFileCount(),

            'usedSpace' =>
                $this->storage->formatBytes(
                    $this->storage->getUsedSpace()
                ),

            'totalSpace' =>
                $this->storage->formatBytes(
                    $this->storage->getTotalSpace()
                ),

            'freeSpace' =>
                $this->storage->formatBytes(
                    $this->storage->getFreeSpace()
                ),
        ];
    }


    /**
     * Returnera grundläggande databasstatus.
     *
     * @return array<string,mixed>
     */
    public function getDatabaseStatus(): array
    {
        return [
            'fileCount' =>
                $this->files->countDisks(),
        ];
    }


    /**
     * Kontrollera att databasen och importlagringen
     * innehåller samma fysiska filer.
     *
     * @return array<string,mixed>
     */
    public function getFileIntegrity(): array
    {
        $directory =
            $this->storage->getImportDirectory();


        $physicalFiles = [];


        if (is_dir($directory)) {

            $files =
                scandir($directory);


            if ($files !== false) {

                foreach ($files as $file) {

                    if (
                        $file === '.' ||
                        $file === '..'
                    ) {
                        continue;
                    }


                    $path =
                        $directory
                        . '/'
                        . $file;


                    if (is_file($path)) {
                        $physicalFiles[] =
                            $file;
                    }
                }
            }
        }


        sort($physicalFiles);


        $databaseFiles =
            $this->files->findAllDisks(
                'id',
                PHP_INT_MAX,
                0
            );


        $databasePaths = [];


        foreach ($databaseFiles as $databaseFile) {

            $path =
                $databaseFile->getPath();


            if (
                $path === null ||
                $path === ''
            ) {
                continue;
            }


            $filename =
                basename($path);


            if ($filename !== '') {
                $databasePaths[$filename] = true;
            }
        }


        $missingFiles = [];


        foreach ($databasePaths as $filename => $_) {

            if (
                !in_array(
                    $filename,
                    $physicalFiles,
                    true
                )
            ) {
                $missingFiles[] =
                    $filename;
            }
        }


        $orphanFiles = [];


        foreach ($physicalFiles as $filename) {

            if (
                !isset(
                    $databasePaths[$filename]
                )
            ) {
                $orphanFiles[] =
                    $filename;
            }
        }


        sort($missingFiles);
        sort($orphanFiles);


        return [
            'registeredCount' =>
                count($databaseFiles),

            'registeredUniqueCount' =>
                count($databasePaths),

            'physicalCount' =>
                count($physicalFiles),

            'missingCount' =>
                count($missingFiles),

            'orphanCount' =>
                count($orphanFiles),

            'missingFiles' =>
                $missingFiles,

            'orphanFiles' =>
                $orphanFiles,
        ];
    }
}
