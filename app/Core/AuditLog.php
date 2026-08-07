<?php

declare(strict_types=1);

namespace App\Core;

final class AuditLog
{
    public function __construct(

        private readonly ?int $id,

        private readonly ?int $userId,

        private readonly string $action,

        private readonly string $targetType,

        private readonly ?int $targetId,

        private readonly string $description,

        private readonly string $createdAt

    ) {
    }



    public function id(): ?int
    {
        return $this->id;
    }



    public function userId(): ?int
    {
        return $this->userId;
    }



    public function action(): string
    {
        return $this->action;
    }



    public function targetType(): string
    {
        return $this->targetType;
    }



    public function targetId(): ?int
    {
        return $this->targetId;
    }



    public function description(): string
    {
        return $this->description;
    }



    public function createdAt(): string
    {
        return $this->createdAt;
    }
}
