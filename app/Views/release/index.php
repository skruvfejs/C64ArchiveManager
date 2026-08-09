<?php
/** @var object $release */
/** @var object|null $entry */
/** @var array $files */
?>


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
                ?>:

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


</div>


<?php endforeach; ?>
