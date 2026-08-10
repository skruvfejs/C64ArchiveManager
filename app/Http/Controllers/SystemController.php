<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Config;
use App\Core\View;
use App\Services\BackupService;
use App\Services\DatabaseExportService;
use App\Services\DatabaseImportService;
use App\Services\DatabaseService;
use App\Services\SettingsService;
use App\Services\StorageService;

final class SystemController extends Controller
{
    public function __construct(
        private readonly Auth $auth,
        private readonly View $view,
        private readonly DatabaseService $databaseService,
        private readonly BackupService $backupService,
        private readonly DatabaseExportService $exportService,
        private readonly DatabaseImportService $importService,
        private readonly SettingsService $settingsService,
        private readonly StorageService $storageService
    ) {
    }


    public function index(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }


        $this->view->render(
            'system/index',
            [
                'title' => 'Systemadministration',
            ]
        );
    }


    public function database(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }


        $statistics = $this->backupService->getStatistics();


        $this->view->render(
            'system/database',
            [
                'title'       => 'Databas',
                'database'    => $this->databaseService->getDatabaseName(),
                'version'     => $this->databaseService->getVersion(),
                'tables'      => $this->databaseService->getTableCount(),
                'size'        => $this->databaseService->getDatabaseSize(),
                'lastBackup'  => $this->backupService->getLastBackup(),
                'backups'     => $this->backupService->listBackups(),
                'backupCount' => $statistics['count'],
                'backupSize'  => $statistics['sizeText'],
            ]
        );
    }


    public function createBackup(): void
    {
        $this->exportService->exportFull(
            'Manual backup'
        );


        header('Location: /administration/system/database');
        exit;
    }


    public function export(): void
    {
        $type = $_POST['type'] ?? 'full';
        $description = trim($_POST['description'] ?? '');


        switch ($type) {

            case 'archive':
                $this->exportService->exportArchive($description);
                break;

            case 'system':
                $this->exportService->exportSystem($description);
                break;

            default:
                $this->exportService->exportFull($description);
                break;
        }


        header('Location: /administration/system/database');
        exit;
    }


    public function import(): void
    {
        if (
            !isset($_FILES['backup']) ||
            !is_array($_FILES['backup'])
        ) {
            header('Location: /administration/system/database');
            exit;
        }


        if (
            ($_FILES['backup']['error'] ?? UPLOAD_ERR_NO_FILE) !==
            UPLOAD_ERR_OK
        ) {
            header('Location: /administration/system/database');
            exit;
        }


        $temporaryFile = $_FILES['backup']['tmp_name'] ?? '';


        if (
            $temporaryFile === '' ||
            !is_uploaded_file($temporaryFile)
        ) {
            header('Location: /administration/system/database');
            exit;
        }


        $filename = basename(
            (string) ($_FILES['backup']['name'] ?? '')
        );


        if (
            $filename === '' ||
            !str_ends_with(
                strtolower($filename),
                '.sql'
            )
        ) {
            header('Location: /administration/system/database');
            exit;
        }


        $metadata = $this->backupService->loadMetadata(
            $filename
        );


        if (empty($metadata)) {
            $backupPath = $this->backupService->downloadPath(
                $filename
            );


            if (is_file($backupPath)) {
                $metadata = $this->backupService->loadMetadata(
                    basename($backupPath)
                );
            }
        }


        $type = $metadata['backup_type'] ?? 'full';


        $importFile = $temporaryFile;


        switch ($type) {

            case 'archive':
                $this->importService->importArchive(
                    $importFile,
                    $metadata
                );
                break;

            case 'system':
                $this->importService->importSystem(
                    $importFile,
                    $metadata
                );
                break;

            default:
                $this->importService->importFull(
                    $importFile,
                    $metadata
                );
                break;
        }


        header('Location: /administration/system/database');
        exit;
    }


    public function downloadBackup(): void
    {
        $filename = $_GET['file'] ?? '';


        if ($filename === '') {
            http_response_code(404);
            exit;
        }


        $path = $this->backupService->downloadPath($filename);


        if (!is_file($path)) {
            http_response_code(404);
            exit;
        }


        header('Content-Type: application/sql');
        header(
            'Content-Disposition: attachment; filename="' .
            basename($path) .
            '"'
        );
        header('Content-Length: ' . filesize($path));


        readfile($path);
        exit;
    }


    public function deleteBackup(): void
    {
        $filename = $_POST['file'] ?? '';


        if ($filename !== '') {
            $this->backupService->delete($filename);
        }


        header('Location: /administration/system/database');
        exit;
    }


    public function restoreBackup(): void
    {
        $filename = basename(
            (string) ($_POST['file'] ?? '')
        );


        if ($filename === '') {
            http_response_code(400);
            exit;
        }


        $path = $this->backupService->downloadPath(
            $filename
        );


        if (!is_file($path)) {
            http_response_code(404);
            exit;
        }


        $metadata = $this->backupService->loadMetadata(
            $filename
        );


        if (empty($metadata)) {
            http_response_code(400);
            exit;
        }


        $type = $metadata['backup_type'] ?? 'full';


        switch ($type) {

            case 'archive':
                $this->importService->importArchive(
                    $path,
                    $metadata
                );
                break;

            case 'system':
                $this->importService->importSystem(
                    $path,
                    $metadata
                );
                break;

            case 'full':
                $this->importService->importFull(
                    $path,
                    $metadata
                );
                break;

            default:
                http_response_code(400);
                exit;
        }


        header('Location: /administration/system/database');
        exit;
    }


    public function settings(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }


        $settings = $this->settingsService->getAll();


        $this->view->render(
            'system/settings',
            [
                'title'    => 'Inställningar',
                'settings' => $settings,
            ]
        );
    }


    public function saveSettings(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }


        $siteName = trim(
            (string) ($_POST['site_name'] ?? '')
        );


        $language = (string) (
            $_POST['default_language'] ?? 'sv'
        );


        $dateFormat = (string) (
            $_POST['date_format'] ?? 'Y-m-d'
        );


        $itemsPerPage = (int) (
            $_POST['items_per_page'] ?? 25
        );


        $maintenanceMode = isset(
            $_POST['maintenance_mode']
        ) ? '1' : '0';


        $registrationEnabled = isset(
            $_POST['registration_enabled']
        ) ? '1' : '0';


        if ($siteName === '') {
            $siteName = 'C64 Archive Manager';
        }


        if (!in_array($language, ['sv', 'en'], true)) {
            $language = 'sv';
        }


        if (
            !in_array(
                $dateFormat,
                [
                    'Y-m-d',
                    'd-m-Y',
                    'Y-m-d H:i',
                ],
                true
            )
        ) {
            $dateFormat = 'Y-m-d';
        }


        if (
            !in_array(
                $itemsPerPage,
                [10, 25, 50, 100],
                true
            )
        ) {
            $itemsPerPage = 25;
        }


        $this->settingsService->update([
            'site_name' => $siteName,
            'default_language' => $language,
            'date_format' => $dateFormat,
            'items_per_page' => (string) $itemsPerPage,
            'maintenance_mode' => $maintenanceMode,
            'registration_enabled' => $registrationEnabled,
        ]);


        header('Location: /administration/system/settings');
        exit;
    }


    public function maintenance(): void
    {
        $this->view->render(
            'system/maintenance',
            [
                'title' => 'Underhåll',
            ]
        );
    }


    public function information(): void
    {
        $config = new Config(
            dirname(__DIR__, 3) . '/config'
        );


        $app = $config->get('app');


        $this->view->render(
            'system/information',
            [
                'title' => 'Systeminformation',

                'appName' => $app['name'] ?? 'C64 Archive Manager',
                'appVersion' => $app['version'] ?? '1.0',

                'phpVersion' => PHP_VERSION,
                'operatingSystem' => PHP_OS,
                'serverSoftware' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'phpSapi' => PHP_SAPI,

                'databaseName' =>
                    $this->databaseService->getDatabaseName(),

                'databaseVersion' =>
                    $this->databaseService->getVersion(),

                'tableCount' =>
                    $this->databaseService->getTableCount(),

                'databaseSize' =>
                    $this->databaseService->getDatabaseSize(),

                'importedFiles' =>
                    $this->storageService->getFileCount(),

                'usedSpace' =>
                    $this->storageService->formatBytes(
                        $this->storageService->getUsedSpace()
                    ),

                'totalSpace' =>
                    $this->storageService->formatBytes(
                        $this->storageService->getTotalSpace()
                    ),

                'freeSpace' =>
                    $this->storageService->formatBytes(
                        $this->storageService->getFreeSpace()
                    ),
            ]
        );
    }
}
