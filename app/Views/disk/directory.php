<h1><?= $title ?></h1>


<h2>
    <?= htmlspecialchars($release->getName()) ?>
</h2>


<?php foreach ($files as $file): ?>

<pre>
0 "<?= htmlspecialchars(
    $file->getDiskName() ?? ''
) ?>" <?= htmlspecialchars(
    $file->getDiskId() ?? ''
) ?>


<?= htmlspecialchars(
    $language->get('disk')
) ?>:
<?= htmlspecialchars($file->getFilename()) ?>


<?= htmlspecialchars(
    $language->get('format')
) ?>:
<?= htmlspecialchars($file->getFormat()) ?>


<?= htmlspecialchars(
    $language->get('type')
) ?>:
<?= htmlspecialchars(
    $diskTypes[$file->getId()] ?? ''
) ?>


<?= htmlspecialchars(
    $language->get('tracks')
) ?>:
<?= $tracks[$file->getId()] ?? 0 ?>


<?= htmlspecialchars(
    $language->get('total_blocks')
) ?>:
<?= $totalBlocks[$file->getId()] ?? 0 ?>

</pre>


<table>
    <thead>
        <tr>
            <th>
                <?= htmlspecialchars(
                    $language->get('blocks')
                ) ?>
            </th>

            <th>
                <?= htmlspecialchars(
                    $language->get('size')
                ) ?>
            </th>

            <th>
                <?= htmlspecialchars(
                    $language->get('file')
                ) ?>
            </th>

            <th>
                <?= htmlspecialchars(
                    $language->get('type')
                ) ?>
            </th>
        </tr>
    </thead>


    <tbody>


<?php foreach ($directories[$file->getId()] ?? [] as $entry): ?>

        <tr>

            <td>
                <?= $entry->getBlocks() ?>
            </td>


            <td>
                <?php

                if ($entry->getFileSize() !== null) {

                    echo $entry->getFileSize() . ' ' .
                        $language->get('bytes');

                } else {

                    echo (
                        $entry->getBlocks() * 254
                    ) . ' ' .
                        $language->get('bytes');

                }

                ?>
            </td>


            <td>
                <a href="/file?id=<?= $entry->getId() ?>">
                    <?= htmlspecialchars(
                        $entry->getFilename()
                    ) ?>
                </a>
            </td>


            <td>
                <?= htmlspecialchars(
                    $entry->getFiletype()
                ) ?>
            </td>

        </tr>


<?php endforeach; ?>


    </tbody>
</table>

<p>
    <?= count(
        $directories[$file->getId()] ?? []
    ) ?>

    <?= htmlspecialchars(
        $language->get('files')
    ) ?>
</p>



<p>
    <?= htmlspecialchars(
        $language->get('blocks_used')
    ) ?>:

    <?= $blocksUsed[$file->getId()] ?? 0 ?>
</p>



<p>
    <?= htmlspecialchars(
        $language->get('blocks_free')
    ) ?>:

    <?= $blocksFree[$file->getId()] ?? 0 ?>
</p>



<?php endforeach; ?>



<p>
    <a href="/disk/info?id=<?= $release->getId() ?>">
        → <?= htmlspecialchars(
            $language->get('disk_information')
        ) ?>
    </a>
</p>



<p>
    <a href="/disk?id=<?= $release->getId() ?>">
        ← <?= htmlspecialchars(
            $language->get('back_to_disk_explorer')
        ) ?>
    </a>
</p>
