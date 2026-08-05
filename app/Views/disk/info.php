
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
<?php if ($isT64): ?>
N/A
<?php else: ?>
<?= $tracks ?>
<?php endif; ?>

FILES:
<?= $fileCount ?>


<?php if ($isT64): ?>

SIZE:
<?= $fileSize ?> bytes


<?php else: ?>

BLOCKS:
<?= $blocksUsed ?> / <?= $totalBlocks ?>


FREE:
<?= $blocksFree ?>

<?php endif; ?>


</pre>


<p>
    <a href="/disk/directory?id=<?= $release->getId() ?>">
        ← Back to Directory
    </a>
</p>

