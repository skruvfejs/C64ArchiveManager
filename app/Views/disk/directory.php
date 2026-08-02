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


<?php foreach ($directories[$file->getId()] ?? [] as $entry): ?><?php

    $line =
        str_pad(
            (string) $entry->getBlocks(),
            4,
            ' ',
            STR_PAD_LEFT
        )
        . ' "'
        . $entry->getFilename()
        . '"';


    echo str_pad(
        $line,
        28,
        ' '
    );


    echo $entry->getFiletype();

    echo PHP_EOL;

?><?php endforeach; ?>


<?= count(
    $directories[$file->getId()] ?? []
) ?> FILES


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

