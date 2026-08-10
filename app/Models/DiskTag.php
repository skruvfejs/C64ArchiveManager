<?php

declare(strict_types=1);

namespace App\Models;

final class DiskTag
{
    private int $diskId;

    private int $tagId;

    private ?string $createdAt = null;

    public function getDiskId(): int
    {
        return $this->diskId;
    }

    public function setDiskId(int $diskId): self
    {
        $this->diskId = $diskId;

        return $this;
    }

    public function getTagId(): int
    {
        return $this->tagId;
    }

    public function setTagId(int $tagId): self
    {
        $this->tagId = $tagId;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
