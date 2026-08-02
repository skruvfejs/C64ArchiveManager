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


DISK:
<?= htmlspecialchars($file->getFilename()) ?>


FORMAT:
<?= htmlspecialchars($file->getFormat()) ?>


TYPE:
<?= htmlspecialchars(
    $diskTypes[$file->getId()] ?? ''
) ?>


TRACKS:
<?= $tracks[$file->getId()] ?? 0 ?>


TOTAL BLOCKS:
<?= $totalBlocks[$file->getId()] ?? 0 ?>

</pre>


<table>
    <thead>
        <tr>
            <th>BLOCKS</th>
            <th>FILE</th>
            <th>TYPE</th>
        </tr>
    </thead>

    <tbody>

<?php foreach ($directories[$file->getId()] ?? [] as $entry): ?>

        <tr>
            <td>
                <?= $entry->getBlocks() ?>
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
) ?> FILES
</p>


<p>
BLOCKS USED:
<?= $blocksUsed[$file->getId()] ?? 0 ?>
</p>


<p>
BLOCKS FREE:
<?= $blocksFree[$file->getId()] ?? 0 ?>
</p>


<?php endforeach; ?>


<p>
    <a href="/disk/info?id=<?= $release->getId() ?>">
        → Disk Information
    </a>
</p>


<p>
    <a href="/disk?id=<?= $release->getId() ?>">
        ← Back to Disk Explorer
    </a>
</p>

