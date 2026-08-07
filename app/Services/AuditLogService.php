<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\AuditLog;
use App\Core\Auth;
use App\Repositories\AuditLogRepository;

final class AuditLogService
{
    public function __construct(
        private readonly AuditLogRepository $repository,
        private readonly Auth $auth
    ) {
    }



    public function log(
        string $action,
        string $targetType,
        ?int $targetId,
        string $description,
        ?int $userId = null
    ): void {

        $logUserId =
            $userId ?? $this->auth->id();



        $log = new AuditLog(

            null,

            $logUserId,

            $action,

            $targetType,

            $targetId,

            $description,

            date('Y-m-d H:i:s')

        );


        $this->repository->save(
            $log
        );
    }
}
