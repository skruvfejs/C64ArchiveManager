<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Flash;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseTagRepository;
use App\Repositories\TagRepository;

final class ReleaseTagController extends Controller
{
    public function __construct(
        private readonly Auth $auth,
        private readonly Flash $flash,
        private readonly ReleaseRepository $releases,
        private readonly ReleaseTagRepository $releaseTags,
        private readonly TagRepository $tags
    ) {
    }

    public function add(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $releaseId = (int) (
            $_POST['release_id'] ?? 0
        );

        $tagId = (int) (
            $_POST['tag_id'] ?? 0
        );

        $release = $this->releases->findById(
            $releaseId
        );

        if ($release === null) {
            $this->flash->error(
                'Release not found.'
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
                'Location: /release?id=' . $releaseId
            );

            exit;
        }

        if (
            $this->releaseTags->find(
                $releaseId,
                $tagId
            ) !== null
        ) {
            $this->flash->error(
                'Tag is already assigned.'
            );

            header(
                'Location: /release?id=' . $releaseId
            );

            exit;
        }

        $releaseTag = (new \App\Models\ReleaseTag())
            ->setReleaseId($releaseId)
            ->setTagId($tagId);

        $this->releaseTags->create(
            $releaseTag
        );

        $this->flash->success(
            'Tag added.'
        );

        header(
            'Location: /release?id=' . $releaseId
        );

        exit;
    }

    public function remove(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $releaseId = (int) (
            $_POST['release_id'] ?? 0
        );

        $tagId = (int) (
            $_POST['tag_id'] ?? 0
        );

        $release = $this->releases->findById(
            $releaseId
        );

        if ($release === null) {
            $this->flash->error(
                'Release not found.'
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
                'Location: /release?id=' . $releaseId
            );

            exit;
        }

        $this->releaseTags->delete(
            $releaseId,
            $tagId
        );

        $this->flash->success(
            'Tag removed.'
        );

        header(
            'Location: /release?id=' . $releaseId
        );

        exit;
    }
}
