<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Flash;
use App\Repositories\DiskTagRepository;
use App\Repositories\TagRepository;
use App\Repositories\ReleaseFileRepository;

final class DiskTagController extends Controller
{
    public function __construct(
        private readonly Auth $auth,
        private readonly Flash $flash,
        private readonly ReleaseFileRepository $disks,
        private readonly DiskTagRepository $diskTags,
        private readonly TagRepository $tags
    ) {
    }

    public function add(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $diskId = (int) (
            $_POST['disk_id'] ?? 0
        );

        $tagId = (int) (
            $_POST['tag_id'] ?? 0
        );

        $disk = $this->disks->findById(
            $diskId
        );

        if ($disk === null) {
            $this->flash->error(
                'Disk not found.'
            );

            header(
                'Location: /'
            );

            exit;
        }

        $tag = $this->tags->findById(
            $tagId
        );

        if ($tag === null) {
            $this->flash->error(
                'Tag not found.'
            );

            header(
                'Location: /disk?id=' . $diskId
            );

            exit;
        }

        if (
            $this->diskTags->find(
                $diskId,
                $tagId
            ) !== null
        ) {
            $this->flash->error(
                'Tag is already assigned.'
            );

            header(
                'Location: /disk?id=' . $diskId
            );

            exit;
        }

        $diskTag = (new \App\Models\DiskTag())
            ->setDiskId($diskId)
            ->setTagId($tagId);

        $this->diskTags->create(
            $diskTag
        );

        $this->flash->success(
            'Tag added.'
        );

        header(
            'Location: /disk?id=' . $diskId
        );

        exit;
    }

    public function remove(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $diskId = (int) (
            $_POST['disk_id'] ?? 0
        );

        $tagId = (int) (
            $_POST['tag_id'] ?? 0
        );

        $disk = $this->disks->findById(
            $diskId
        );

        if ($disk === null) {
            $this->flash->error(
                'Disk not found.'
            );

            header(
                'Location: /'
            );

            exit;
        }

        $tag = $this->tags->findById(
            $tagId
        );

        if ($tag === null) {
            $this->flash->error(
                'Tag not found.'
            );

            header(
                'Location: /disk?id=' . $diskId
            );

            exit;
        }

        $this->diskTags->delete(
            $diskId,
            $tagId
        );

        $this->flash->success(
            'Tag removed.'
        );

        header(
            'Location: /disk?id=' . $diskId
        );

        exit;
    }
}
