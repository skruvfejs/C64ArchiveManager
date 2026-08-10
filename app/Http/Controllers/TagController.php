<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\View;
use App\Models\Tag;
use App\Repositories\EntryTagRepository;
use App\Repositories\TagRepository;

final class TagController extends Controller
{
    public function __construct(
        private readonly Auth $auth,
        private readonly TagRepository $tags,
        private readonly EntryTagRepository $entryTags,
        private readonly View $view,
        private readonly Flash $flash
    ) {
    }

    public function index(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $this->view->render(
            'tags/index',
            [
                'title' => 'Tags',
                'tags' => $this->tags->findAll(),
            ]
        );
    }

    public function createForm(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $this->view->render(
            'tags/create',
            [
                'title' => 'Create tag',
            ]
        );
    }

    public function create(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $name = trim(
            $_POST['name'] ?? ''
        );

        $description = trim(
            $_POST['description'] ?? ''
        );

        if ($name === '') {
            $this->flash->error(
                'Tag name is required.'
            );

            header(
                'Location: /administration/tags/create'
            );

            exit;
        }

        if (
            $this->tags->findByName($name) !== null
        ) {
            $this->flash->error(
                'A tag with that name already exists.'
            );

            header(
                'Location: /administration/tags/create'
            );

            exit;
        }

        $tag = (new Tag())
            ->setName($name)
            ->setDescription(
                $description !== ''
                    ? $description
                    : null
            );

        $this->tags->create($tag);

        $this->flash->success(
            'Tag created.'
        );

        header(
            'Location: /administration/tags'
        );

        exit;
    }

    public function editForm(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $id = (int) (
            $_GET['id'] ?? 0
        );

        $tag = $this->tags->findById($id);

        if ($tag === null) {
            $this->flash->error(
                'Tag not found.'
            );

            header(
                'Location: /administration/tags'
            );

            exit;
        }

        $this->view->render(
            'tags/edit',
            [
                'title' => 'Edit tag',
                'tag' => $tag,
            ]
        );
    }

    public function delete(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $id = (int) (
            $_POST['id'] ?? 0
        );

        $tag = $this->tags->findById($id);

        if ($tag === null) {
            $this->flash->error(
                'Tag not found.'
            );

            header(
                'Location: /administration/tags'
            );

            exit;
        }

        $this->entryTags->deleteByTagId($id);

        $this->tags->delete($id);

        $this->flash->success(
            'Tag deleted.'
        );

        header(
            'Location: /administration/tags'
        );

        exit;
    }


    public function update(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $id = (int) (
            $_POST['id'] ?? 0
        );

        $tag = $this->tags->findById($id);

        if ($tag === null) {
            $this->flash->error(
                'Tag not found.'
            );

            header(
                'Location: /administration/tags'
            );

            exit;
        }

        $name = trim(
            $_POST['name'] ?? ''
        );

        $description = trim(
            $_POST['description'] ?? ''
        );

        if ($name === '') {
            $this->flash->error(
                'Tag name is required.'
            );

            header(
                'Location: /administration/tags/edit?id=' . $id
            );

            exit;
        }

        $existing = $this->tags->findByName($name);

        if (
            $existing !== null
            && $existing->getId() !== $tag->getId()
        ) {
            $this->flash->error(
                'A tag with that name already exists.'
            );

            header(
                'Location: /administration/tags/edit?id=' . $id
            );

            exit;
        }

        $tag
            ->setName($name)
            ->setDescription(
                $description !== ''
                    ? $description
                    : null
            );

        $this->tags->update($tag);

        $this->flash->success(
            'Tag updated.'
        );

        header(
            'Location: /administration/tags'
        );

        exit;
    }
}
