<h1><?= $title ?></h1>


<h2>
    <?= htmlspecialchars($release->getName()) ?>
</h2>


<?php foreach ($files as $file): ?>

    <h3>
        <?= htmlspecialchars($file->getFilename()) ?>
        (<?= htmlspecialchars($file->getFormat()) ?>)
    </h3>


    <pre>

0 "<?= htmlspecialchars(
    $file->getDiskName() ?? ''
) ?>" <?= htmlspecialchars(
    $file->getDiskId() ?? ''
) ?>


<?php foreach ($directories[$file->getId()] ?? [] as $entry): ?>

<?= str_pad(
    (string) $entry->getBlocks(),
    4,
    ' ',
    STR_PAD_LEFT
) ?>
 "<?= htmlspecialchars(
    $entry->getFilename()
) ?>"
 <?= $entry->getFiletype() ?>


<?php endforeach; ?>


<?= count(
    $directories[$file->getId()] ?? []
) ?>
 FILES


BLOCKS USED:
<?= $blocksUsed[$file->getId()] ?? 0 ?>


BLOCKS FREE:
<?= $blocksFree[$file->getId()] ?? 0 ?>


    </pre>


<?php endforeach; ?>


<p>
    <a href="/disk?id=<?= $release->getId() ?>">
        ← Back to Disk Explorer
    </a>
</p>

