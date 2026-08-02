<h1><?= $title ?></h1>


<h2>
    <?= htmlspecialchars($release->getName()) ?>
</h2>


<pre>

DISK:
<?= htmlspecialchars(
    $file->getFilename()
) ?>


FORMAT:
<?= htmlspecialchars(
    $format
) ?>


TYPE:
<?= htmlspecialchars(
    $diskType
) ?>


TRACKS:
<?= $tracks ?>


FILES:
<?= $fileCount ?>


BLOCKS:
<?= $blocksUsed ?> / <?= $totalBlocks ?>


FREE:
<?= $blocksFree ?>


</pre>


<p>
    <a href="/disk/directory?id=<?= $release->getId() ?>">
        ← Back to Directory
    </a>
</p>

