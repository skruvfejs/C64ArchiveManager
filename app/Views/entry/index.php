<?php

/** @var object $entry */
/** @var array $entryTags */
/** @var array $allTags */
/** @var array $releases */
/** @var int $totalReleases */
/** @var int $uniqueImages */

$assignedTagIds = [];

foreach ($entryTags as $entryTag) {

    $assignedTagIds[] =
        $entryTag->getTagId();
}

?>

<h1>
    <?= htmlspecialchars(
        $entry->getTitle()
    ) ?>
</h1>


<p>
    <strong><?= htmlspecialchars(
        $language->get('entry_id')
    ) ?>:</strong>
    <?= $entry->getId() ?>
</p>


<p>
    <strong><?= htmlspecialchars(
        $language->get('release_count')
    ) ?>:</strong>
    <?= $totalReleases ?>
</p>


<p>
    <strong><?= htmlspecialchars(
        $language->get('unique_disk_images')
    ) ?>:</strong>
    <?= $uniqueImages ?>
</p>


<h2 style="margin-bottom: 5px;">
    <?= htmlspecialchars(
        $language->get('tags')
    ) ?>
</h2>


<div
    style="
        white-space: nowrap;
    "
>

<?php

$firstTag = true;

foreach ($allTags as $tag):

    if (
        !in_array(
            $tag->getId(),
            $assignedTagIds,
            true
        )
    ) {
        continue;
    }

?>

<?php if (!$firstTag): ?>

    ,

<?php endif; ?>


<span
    style="
        white-space: nowrap;
    "
>

    <?= htmlspecialchars(
        $tag->getName()
    ) ?>

    <form
        method="post"
        action="/entry/tags/remove"
        style="
            display: inline !important;
            white-space: nowrap;
            margin: 0;
            padding: 0;
        "
    >

        <input
            type="hidden"
            name="entry_id"
            value="<?= $entry->getId() ?>"
        >

        <input
            type="hidden"
            name="tag_id"
            value="<?= $tag->getId() ?>"
        >

        <button
            type="submit"
            style="
                display: inline !important;
                color: red;
                background: none;
                border: none;
                padding: 0;
                margin: 0;
                cursor: pointer;
                white-space: nowrap;
            "
        >
            [x]
        </button>

    </form>

</span>

<?php

$firstTag = false;

endforeach;

?>

</div>


<?php

$availableTags = array_filter(
    $allTags,
    function ($tag) use ($assignedTagIds): bool {

        return !in_array(
            $tag->getId(),
            $assignedTagIds,
            true
        );
    }
);

?>


<?php if (!empty($availableTags)): ?>

<form
    method="post"
    action="/entry/tags/add"
>

    <input
        type="hidden"
        name="entry_id"
        value="<?= $entry->getId() ?>"
    >


    <select name="tag_id">

        <option value="">
            <?= htmlspecialchars(
                $language->get('select_tag')
            ) ?>
        </option>

        <?php foreach (
            $availableTags as $tag
        ): ?>

            <option
                value="<?= $tag->getId() ?>"
            >
                <?= htmlspecialchars(
                    $tag->getName()
                ) ?>
            </option>

        <?php endforeach; ?>

    </select>


    <button type="submit">
        <?= htmlspecialchars(
            $language->get('add_tag')
        ) ?>
    </button>

</form>

<?php endif; ?>


<h2 style="margin-top: 2em; margin-bottom: 0;">
    <?= htmlspecialchars(
        $language->get('releases')
    ) ?>
</h2>


<?php foreach ($releases as $releaseIndex => $item): ?>

<?php if ($releaseIndex > 0): ?>
    <hr>
<?php endif; ?>

<?php

$release =
    $item['release'];

$files =
    $item['files'];

?>


<div class="card">

    <h3 style="margin-top: 0;">

        <a
            href="/release?id=<?= $release->getId() ?>"
        >

            <?= htmlspecialchars(
                $language->get('release_id')
            ) ?>:
            <?= $release->getId() ?>

        </a>

    </h3>


    <?php foreach ($files as $fileItem): ?>

    <?php

    $file =
        $fileItem['file'];

    ?>


    <div class="disk-file">

        <p>

            <strong><?= htmlspecialchars(
                $language->get('file')
            ) ?>:</strong>

            <?= htmlspecialchars(
                $file->getFilename()
            ) ?>

        </p>


        <p>

            <strong><?= htmlspecialchars(
                $language->get('format')
            ) ?>:</strong>

            <?= htmlspecialchars(
                $file->getFormat()
            ) ?>

        </p>


        <p>

            <strong>MD5:</strong>

            <?= htmlspecialchars(
                $file->getMd5()
            ) ?>

        </p>


        <?php if (
            $fileItem['duplicate']
        ): ?>

        <p>

            ⚠ <?= htmlspecialchars(
                $language->get('same_disk_image_also_exists')
            ) ?>
            Release ID:

            <?= $fileItem['duplicateOf'] ?>

        </p>

        <?php endif; ?>


        <p>

            <strong><?= htmlspecialchars(
                $language->get('size')
            ) ?>:</strong>

            <?= number_format(
                $file->getSize()
            ) ?>

            <?= htmlspecialchars(
                $language->get('bytes')
            ) ?>

        </p>


        <p>

            <strong><?= htmlspecialchars(
                $language->get('directory_entries')
            ) ?>:</strong>

            <?= $fileItem['directoryCount'] ?>

        </p>


        <p>

            <a
                href="/disk?id=<?= $release->getId() ?>"
            >
                <?= htmlspecialchars(
                    $language->get('open_disk')
                ) ?>
            </a>

        </p>

    </div>


    <?php endforeach; ?>


</div>


<?php endforeach; ?>


<?php if (($pages ?? 1) > 1): ?>

<div style="margin-top: 1em;">

    <?php if (($page ?? 1) > 1): ?>

        <a href="/entry?id=<?= $entry->getId() ?>&page=<?= $page - 1 ?>">
            <?= htmlspecialchars(
                $language->get('previous')
            ) ?>
        </a>

    <?php endif; ?>


    <?php for (
        $paginationPage = 1;
        $paginationPage <= $pages;
        $paginationPage++
    ): ?>

        <?php if (
            $paginationPage === ($page ?? 1)
        ): ?>

            <strong>
                <?= $paginationPage ?>
            </strong>

        <?php else: ?>

            <a href="/entry?id=<?= $entry->getId() ?>&page=<?= $paginationPage ?>">
                <?= $paginationPage ?>
            </a>

        <?php endif; ?>

    <?php endfor; ?>


    <?php if (($page ?? 1) < $pages): ?>

        <a href="/entry?id=<?= $entry->getId() ?>&page=<?= $page + 1 ?>">
            <?= htmlspecialchars(
                $language->get('next')
            ) ?>
        </a>

    <?php endif; ?>

</div>

<?php endif; ?>
