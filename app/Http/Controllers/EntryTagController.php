<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Flash;
use App\Models\EntryTag;
use App\Repositories\EntryRepository;
use App\Repositories\EntryTagRepository;
use App\Repositories\TagRepository;

final class EntryTagController extends Controller
{
    public function __construct(
        private readonly EntryRepository $entries,
        private readonly EntryTagRepository $entryTags,
        private readonly TagRepository $tags,
        private readonly Flash $flash
    ) {
    }


    public function add(): void
    {
        $entryId =
            (int) (
                $_POST['entry_id'] ?? 0
            );


        $tagId =
            (int) (
                $_POST['tag_id'] ?? 0
            );


        if (
            $entryId <= 0
            ||
            $tagId <= 0
        ) {

            $this->flash->error(
                'Invalid entry or tag.'
            );


            header(
                'Location: /entry?id='
                . $entryId
            );

            exit;
        }


        $entry =
            $this->entries->findById(
                $entryId
            );


        if ($entry === null) {

            $this->flash->error(
                'Entry not found.'
            );


            header(
                'Location: /entry'
            );

            exit;
        }


        $tag =
            $this->tags->findById(
                $tagId
            );


        if ($tag === null) {

            $this->flash->error(
                'Tag not found.'
            );


            header(
                'Location: /entry?id='
                . $entryId
            );

            exit;
        }


        /*
         * Förhindra dubbla kopplingar.
         */
        if (
            $this->entryTags->find(
                $entryId,
                $tagId
            ) !== null
        ) {

            header(
                'Location: /entry?id='
                . $entryId
            );

            exit;
        }


        $entryTag =
            (new EntryTag())
                ->setEntryId(
                    $entryId
                )
                ->setTagId(
                    $tagId
                );


        $this->entryTags->create(
            $entryTag
        );


        $this->flash->success(
            'Tagg tillagd.'
        );


        header(
            'Location: /entry?id='
            . $entryId
        );

        exit;
    }


    public function remove(): void
    {
        $entryId =
            (int) (
                $_POST['entry_id'] ?? 0
            );


        $tagId =
            (int) (
                $_POST['tag_id'] ?? 0
            );


        if (
            $entryId <= 0
            ||
            $tagId <= 0
        ) {

            $this->flash->error(
                'Invalid entry or tag.'
            );


            header(
                'Location: /entry?id='
                . $entryId
            );

            exit;
        }


        $this->entryTags->delete(
            $entryId,
            $tagId
        );


        $this->flash->success(
            'Tagg borttagen.'
        );


        header(
            'Location: /entry?id='
            . $entryId
        );

        exit;
    }
}
