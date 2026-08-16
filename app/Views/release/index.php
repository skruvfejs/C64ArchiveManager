<?php
/** @var object $release */
/** @var object|null $entry */
/** @var array $files */
?>


<?php if ($entry !== null): ?>

<p>
    <a href="/entry?id=<?= $entry->getId() ?>">
        ← <?= htmlspecialchars(
            $language->get('back_to_entry')
        ) ?>:
        <?= htmlspecialchars($entry->getTitle()) ?>
    </a>
</p>

<?php endif; ?>


<h1>
    <?= htmlspecialchars(
        $language->get('release')
    ) ?>
</h1>


<h2>
    <?= htmlspecialchars($release->getName()) ?>
</h2>


<p>
    <strong>
        <?= htmlspecialchars(
            $language->get('release_id')
        ) ?>:
    </strong>

    <?= $release->getId() ?>
</p>


<h2 style="margin-bottom: 5px;">
    <?= htmlspecialchars($language->get('tags')) ?>
</h2>

<?php if (!empty($releaseTags)): ?>

<div>
    <?php foreach ($releaseTags as $index => $tag): ?>
        <span style="white-space: nowrap;">
            <?= htmlspecialchars($tag->getName()) ?>
            <form
                method="post"
                action="/release/tags/remove"
                style="display:inline-block !important; margin:0 !important; padding:0 !important;"
            >
                <input
                    type="hidden"
                    name="release_id"
                    value="<?= (int) $release->getId() ?>"
                >
                <input
                    type="hidden"
                    name="tag_id"
                    value="<?= (int) $tag->getId() ?>"
                >
                <button
                    type="submit"
                    style="display:inline !important; background:none; border:0; padding:0; margin:0; color:inherit; font:inherit; cursor:pointer;"
                ><span style="color:#ff0000;">[x]</span></button>
            </form><?php if ($index < count($releaseTags) - 1): ?>, <?php endif; ?>
        </span>
    <?php endforeach; ?>
</div>

<?php else: ?>

<p>
    <?= htmlspecialchars($language->get('no_tags')) ?>
</p>

<?php endif; ?>


<?php
$releaseTagIds = [];

foreach ($releaseTags as $releaseTag) {
    $releaseTagIds[] = $releaseTag->getId();
}
?>


<?php if (!empty($allTags)): ?>

<form
    method="post"
    action="/release/tags/add"
>

    <input
        type="hidden"
        name="release_id"
        value="<?= (int) $release->getId() ?>"
    >

    <select name="tag_id" required>

        <option value="">
            <?= htmlspecialchars(
                $language->get('select_tag')
            ) ?>
        </option>

        <?php foreach ($allTags as $tag): ?>

            <?php if (!in_array(
                $tag->getId(),
                $releaseTagIds,
                true
            )): ?>

                <option
                    value="<?= (int) $tag->getId() ?>"
                >
                    <?= htmlspecialchars(
                        $tag->getName()
                    ) ?>
                </option>

            <?php endif; ?>

        <?php endforeach; ?>

    </select>

    <button type="submit">
        <?= htmlspecialchars(
            $language->get('add_tag')
        ) ?>
    </button>

</form>

<?php endif; ?>





<h2>
    <?= htmlspecialchars(
        $language->get('disk_images')
    ) ?>
</h2>


<?php foreach ($files as $fileItem): ?>

<?php
$file = $fileItem['file'];
$duplicates = $fileItem['md5Duplicates'] ?? [];
?>


<div class="card">


<h3>
    <?= htmlspecialchars($file->getFilename()) ?>
</h3>


<table border="1" cellpadding="5">


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('filename')
        ) ?>
    </th>

    <td>
        <?= htmlspecialchars($file->getFilename()) ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('format')
        ) ?>
    </th>

    <td>
        <?= htmlspecialchars($file->getFormat()) ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('disk_name')
        ) ?>
    </th>

    <td>
        <?= htmlspecialchars(
            $file->getDiskName() ?? ''
        ) ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('disk_id')
        ) ?>
    </th>

    <td>
        <?= htmlspecialchars(
            $file->getDiskId() ?? ''
        ) ?>
    </td>
</tr>


<tr>
    <th>
        MD5
    </th>

    <td>
        <?= htmlspecialchars(
            $file->getMd5() ?? ''
        ) ?>


        <?php if (!empty($duplicates)): ?>

        <br><br>

        <details>

            <summary>
                ⚠ <?= htmlspecialchars(
                    $language->get('same_disk_image_also_exists')
                ) ?>

                <?= count($duplicates) ?>

                <?= htmlspecialchars(
                    $language->get('other_locations')
                ) ?>
            </summary>


            <ul>

            <?php foreach ($duplicates as $duplicate): ?>

                <li>

                <?php if ($duplicate['entry'] !== null): ?>

                    <?= htmlspecialchars(
                        $language->get('entry')
                    ) ?>:

                    <a href="/entry?id=<?= $duplicate['entry']->getId() ?>">
                        <?= htmlspecialchars(
                            $duplicate['entry']->getTitle()
                        ) ?>
                    </a>

                    <br>

                <?php endif; ?>


                <?= htmlspecialchars(
                    $language->get('release_id')
                ) ?>:

                <a href="/release?id=<?= $duplicate['release']->getId() ?>">
                    <?= $duplicate['release']->getId() ?>
                </a>

                <br>


                <?= htmlspecialchars(
                    $language->get('file')
                ) ?>:

                <?= htmlspecialchars(
                    $duplicate['file']->getFilename()
                ) ?>

                </li>

            <?php endforeach; ?>

            </ul>


        </details>

        <?php endif; ?>

    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('size')
        ) ?>
    </th>

    <td>
        <?= number_format(
            $file->getSize()
        ) ?>

        <?= htmlspecialchars(
            $language->get('bytes')
        ) ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('directory_entries')
        ) ?>
    </th>

    <td>
        <?= $fileItem['directoryCount'] ?>
    </td>
</tr>


</table>


<p>
    <a href="/disk?id=<?= $release->getId() ?>">
        <?= htmlspecialchars(
            $language->get('open_disk')
        ) ?>
    </a>
</p>

<p>
    <a href="/file/download-disk?id=<?= $file->getId() ?>">
        <?= htmlspecialchars(
            $language->get('download_disk_image')
        ) ?>
    </a>
</p>


</div>


<?php endforeach; ?>
