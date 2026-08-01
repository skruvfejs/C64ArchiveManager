<?php

declare(strict_types=1);

use App\Core\Application;
use App\Services\ImporterService;


require __DIR__ . '/vendor/autoload.php';


if ($argc < 3) {

    echo "Usage:\n";
    echo "  php import.php <file> <entry_id>\n";

    exit(1);
}


$filename =
    $argv[1];


$entryId =
    (int) $argv[2];


$app =
    new Application();


$importer =
    $app->container()
        ->get(ImporterService::class);


try {

    $releaseId =
        $importer->import(
            $filename,
            $entryId
        );


    echo "Import OK\n";
    echo "Release ID: {$releaseId}\n";


} catch (Throwable $e) {


    echo "Import FAILED\n";
    echo $e->getMessage() . "\n";


    exit(1);
}

