<?php

declare(strict_types=1);

namespace App\Models;

final class ReleaseTag
{
    private int $releaseId;

    private int $tagId;

    private ?string $createdAt = null;

    public function getReleaseId(): int
    {
        return $this->releaseId;
    }

    public function setReleaseId(int $releaseId): self
    {
        $this->releaseId = $releaseId;

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
